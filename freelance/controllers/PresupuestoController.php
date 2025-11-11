<?php

namespace app\controllers;

use app\models\Presupuesto;
use app\models\DetallePresupuesto;
use app\models\CuentasPresupuesto;
use app\models\PresupuestoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use Yii;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;

/**
 * PresupuestoController implements the CRUD actions for Presupuesto model.
 */
class PresupuestoController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                        'batch-delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Presupuesto models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PresupuestoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Presupuesto model.
     * @param int $pre_id Presupuesto ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($pre_id)
    {
        $model = $this->findModel($pre_id);

        if ($this->request->get('view') === 'modal') {
            return $this->renderAjax('view', ['model' => $model]);
        }

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new Presupuesto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Presupuesto();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                // Primero construimos los modelos de detalle y los validamos sin persistir
                $detallesData = Yii::$app->request->post('DetallePresupuesto', []);
                $detalleModels = [];
                $detalleRowErrors = [];
                foreach ($detallesData as $idx => $d) {
                    $cantidad = isset($d['dtp_cantidad']) ? floatval($d['dtp_cantidad']) : 0.0;
                    $precio = isset($d['dtp_precio']) ? floatval($d['dtp_precio']) : 0.0;

                    // Si la línea está vacía (cantidad y precio 0), ignorarla
                    if ($cantidad == 0 && $precio == 0) {
                        continue;
                    }

                    $det = new DetallePresupuesto();
                    // No asignamos pre_id aún (se asignará al guardar la presupuesto)
                    $det->cof_id = isset($d['cof_id']) && $d['cof_id'] !== '' ? $d['cof_id'] : null;
                    $det->dtp_descripcion = isset($d['dtp_descripcion']) ? $d['dtp_descripcion'] : '';
                    $det->dtp_cantidad = $cantidad;
                    $det->dtp_precio = $precio;
                    $det->dtp_iva = isset($d['dtp_iva']) ? floatval($d['dtp_iva']) : 0.0;
                    $det->dtp_subtotal = $cantidad * $precio;

                    // Validar sólo los atributos del detalle que provienen del formulario
                    // (no validamos pre_id aquí porque la presupuesto aún no está guardada)
                    if (!$det->validate(['cof_id', 'dtp_descripcion', 'dtp_cantidad', 'dtp_precio', 'dtp_iva'])) {
                        // Guardar errores por fila (por cada atributo)
                        $detalleRowErrors[$idx] = $det->getErrors();
                    }
                    $detalleModels[] = $det;
                }
                if (!empty($detalleRowErrors)) {
                    // Añadir errores agrupados al modelo principal para mostrarlos en errorSummary
                    foreach ($detalleRowErrors as $idx => $errs) {
                        $model->addError('detalles', 'Fila ' . ($idx+1) . ': ' . implode('; ', array_map(function($a){ return is_array($a) ? implode('|', $a) : $a; }, $errs)));
                    }
                    // Renderizar formulario con errores (no guardar) y devolver los datos de detalle y errores por fila
                    $socios = \app\models\Socio::find()->all();
                    $formasDePago = \app\models\FormaDePago::find()->all();
                    // Lista de bancos para seleccionar cuenta de pago
                    $bancos = \app\models\Banco::find()->where(['ban_eliminado' => 0])->all();
                    $bancosMap = \yii\helpers\ArrayHelper::map($bancos, 'ban_id', function($b){ return $b->ban_nombre . ' - ' . $b->ban_numcuenta; });
                    $selectedBanco = isset($detallesData['cuenta_ban_id']) ? $detallesData['cuenta_ban_id'] : (Yii::$app->request->post('CuentasPresupuesto', [])['ban_id'] ?? null);
                    $renderMethod = $this->request->isAjax ? 'renderAjax' : 'render';
                    return $this->$renderMethod('create', [
                        'model' => $model,
                        'clientes' => [],
                        'socios' => \yii\helpers\ArrayHelper::map($socios, 'soc_id', 'soc_nombre'),
                        'formasDePago' => \yii\helpers\ArrayHelper::map($formasDePago, 'fdp_id', 'fdp_nombre'),
                        'detallesData' => $detallesData,
                        'detalleRowErrors' => $detalleRowErrors,
                        'bancos' => $bancosMap,
                        'selectedBanco' => $selectedBanco,
                    ]);
                }

                // Si todo es válido, guardar presupuesto y detalles en transacción
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$model->save()) {
                        throw new \Exception('No se pudo guardar el presupuesto: ' . json_encode($model->getErrors()));
                    }

                    $subtotal = 0.0;
                    $ivaTotal = 0.0;
                    foreach ($detalleModels as $det) {
                        $det->pre_id = $model->pre_id;
                        if (!$det->save(false)) {
                            throw new \Exception('Error al guardar detalle: ' . json_encode($det->getErrors()));
                        }
                        $subtotal += $det->dtp_subtotal;
                        $ivaTotal += $det->dtp_subtotal * ($det->dtp_iva / 100.0);
                    }

                    // Guardar cuenta seleccionada para la transferencia (si se envió)
                    $postedCuenta = Yii::$app->request->post('CuentasPresupuesto', []);
                    $banId = isset($postedCuenta['ban_id']) && $postedCuenta['ban_id'] !== '' ? $postedCuenta['ban_id'] : null;
                    if ($banId) {
                        $cf = new CuentasPresupuesto();
                        $cf->ban_id = $banId;
                        $cf->pre_id = $model->pre_id;
                        if (!$cf->save()) {
                            throw new \Exception('No se pudo guardar la cuenta de presupuesto: ' . json_encode($cf->getErrors()));
                        }
                    }

                    // Recalcular totales y guardar en la presupuesto
                    $gastos = isset($model->pre_gastos_suplidos) ? floatval($model->pre_gastos_suplidos) : 0.0;
                    $model->pre_subtotal = $subtotal;
                    $model->pre_iva = $ivaTotal;
                    $model->pre_total = $subtotal + $ivaTotal + $gastos;
                    $model->save(false, ['pre_subtotal', 'pre_iva', 'pre_total']);

                    $transaction->commit();

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ['success' => true, 'message' => 'Presupuesto creado correctamente.'];
                    }

                    Yii::$app->session->setFlash('success', 'Presupuesto creado.');
                    return $this->redirect(['index']);
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::error($e->getMessage());
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ['success' => false, 'errors' => $e->getMessage()];
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        $socios = \app\models\Socio::find()->all();
        $formasDePago = \app\models\FormaDePago::find()->all();

    // Bancos disponibles para seleccionar cuenta de transferencia
    $bancos = \app\models\Banco::find()->where(['ban_eliminado' => 0])->all();
    $bancosMap = \yii\helpers\ArrayHelper::map($bancos, 'ban_id', function($b){ return $b->ban_nombre . ' - ' . $b->ban_numcuenta; });

        $renderMethod = $this->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('create', [
            'model' => $model,
            'clientes' => [], // Se cargan por AJAX
            'socios' => \yii\helpers\ArrayHelper::map($socios, 'soc_id', 'soc_nombre'),
            'formasDePago' => \yii\helpers\ArrayHelper::map($formasDePago, 'fdp_id', 'fdp_nombre'),
            'bancos' => $bancosMap,
            'selectedBanco' => null,
        ]);
    }

    public function actionListadoClientes($term = null, $type = 'name') {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (!is_null($term)) {
            $query = new \yii\db\Query();
            $query->from('cliente')
                ->where(['cli_eliminado' => 0])
                ->limit(20);

            if ($type === 'name') {
                $query->andWhere(['like', 'cli_nombre', $term]);
                $query->select(['value' => 'cli_id', 'label' => 'cli_nombre']);
            } elseif ($type === 'doc') {
                $query->andWhere(['like', 'cli_numdocide', $term]);
                $query->select(['value' => 'cli_id', 'label' => new \yii\db\Expression("CONCAT(cli_numdocide, ' - ', cli_nombre)")]);
            }

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out = array_values($data);
        }
        return $out;
    }

    public function actionDatosCliente($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $cliente = \app\models\Cliente::findOne($id);
        if ($cliente) {
            return [
                'nif' => $cliente->cli_numdocide,
                'razon_social' => $cliente->cli_nombre,
                'nombre' => $cliente->cli_nombre,
                'tipo_doc' => $cliente->tdo ? $cliente->tdo->tdo_nombre : '',
                'num_identificacion' => $cliente->cli_numdocide,
                'direccion' => $cliente->cli_direccion,
                'cp' => $cliente->cli_codpostal,
                'provincia' => $cliente->prv ? $cliente->prv->prv_nombre : '',
                'poblacion' => $cliente->cli_poblacion,
                'pais' => $cliente->pai ? $cliente->pai->pai_nombre : '',
                'forma_pago' => $cliente->fdp ? $cliente->fdp->fdp_nombre : '',
                'socio' => $cliente->soc ? $cliente->soc->soc_nombre : '',
            ];
        }
        return [];
    }

    /**
     * Updates an existing Presupuesto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $pre_id Presupuesto ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($pre_id)
    {
        $model = $this->findModel($pre_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($this->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($model->save()) {
                    return ['success' => true, 'message' => 'Presupuesto actualizado correctamente.'];
                } else {
                    return ['success' => false, 'errors' => $model->getErrors()];
                }
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        $renderMethod = $this->request->get('view') === 'modal' ? 'renderAjax' : 'render';
        return $this->$renderMethod('update', ['model' => $model]);
    }

    /**
     * Deletes an existing Presupuesto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $pre_id Presupuesto ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($pre_id)
    {
        $model = $this->findModel($pre_id);
        $model->pre_eliminado = 1;
        $model->save(false, ['pre_eliminado']);

        return $this->redirect(['index']);
    }
    
    public function actionBatchDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ids = Yii::$app->request->post('ids');
        if (empty($ids)) {
            return ['success' => false, 'message' => 'No se han seleccionado presupuestos.'];
        }

        try {
            $count = 0;
            foreach ($ids as $id) {
                $model = $this->findModel($id);
                if ($model) {
                    $model->pre_eliminado = 1;
                    if ($model->save(false, ['pre_eliminado'])) {
                        $count++;
                    }
                }
            }
            return ['success' => true, 'message' => $count . ' presupuesto(s) eliminado(s) correctamente.'];
        } catch (\yii\db\Exception $e) {
            return ['success' => false, 'message' => 'Ocurrió un error al eliminar los presupuestos.'];
        }
    }

    /**
     * Finds the Presupuesto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $pre_id Presupuesto ID
     * @return Presupuesto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($pre_id)
    {
        if (($model = Presupuesto::findOne(['pre_id' => $pre_id, 'pre_eliminado' => 0])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExportExcel()
    {
        $presupuestos = Presupuesto::find()->where(['pre_eliminado' => 0])->all();

        $headers = ['Número', 'Fecha', 'Cliente', 'Total'];
        $data = [];

        foreach ($presupuestos as $presupuesto) {
            $data[] = [
                $presupuesto->pre_numero,
                Yii::$app->formatter->asDate($presupuesto->pre_fecha, 'php:d-m-Y'),
                $presupuesto->cli->cli_nombre,
                $presupuesto->pre_total,
            ];
        }

        return ExcelExportHelper::export('Listado_Presupuestos', $headers, $data);
    }

    public function actionExportPdf()
    {
        $presupuestos = Presupuesto::find()->where(['pre_eliminado' => 0])->all();
        $headers = ['Número', 'Fecha', 'Cliente', 'Total'];
        $rows = [];

        foreach ($presupuestos as $presupuesto) {
            $rows[] = [
                $presupuesto->pre_numero,
                Yii::$app->formatter->asDate($presupuesto->pre_fecha, 'php:d-m-Y'),
                $presupuesto->cli->cli_nombre,
                Yii::$app->formatter->asCurrency($presupuesto->pre_total),
            ];
        }

        $html = $this->renderPartial('@app/views/export/_tabla_pdf', [
            'titulo' => 'Listado de Presupuestos',
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return PdfExportHelper::export('Listado_Presupuestos', $html);
    }

    public function actionPrint($pre_id = null)
    {
        if ($pre_id !== null) {
            $model = $this->findModel($pre_id);
            return $this->renderPartial('print', ['model' => $model]);
        }

        $presupuestos = Presupuesto::find()->where(['pre_eliminado' => 0])->all();

        $headers = ['Número', 'Fecha', 'Cliente', 'Total'];
        $rows = [];

        foreach ($presupuestos as $presupuesto) {
            $rows[] = [
                $presupuesto->pre_numero,
                Yii::$app->formatter->asDate($presupuesto->pre_fecha, 'php:d-m-Y'),
                $presupuesto->cli->cli_nombre,
                Yii::$app->formatter->asCurrency($presupuesto->pre_total),
            ];
        }

        return $this->renderPartial('@app/views/export/print_table', [
            'titulo' => 'Listado de Presupuestos',
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }

    public function actionSendEmail($pre_id)
    {
        $model = $this->findModel($pre_id);
        
        if ($this->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $emailTo = Yii::$app->request->post('email_to');
            $subject = Yii::$app->request->post('email_subject');
            $body = Yii::$app->request->post('email_body');

            // Simulate sending email
            try {
                // Here you would implement the actual email sending logic
                // For example, using Yii's mailer component:
                /*
                Yii::$app->mailer->compose()
                    ->setTo($emailTo)
                    ->setFrom(['your-email@example.com' => 'Your Name'])
                    ->setSubject($subject)
                    ->setTextBody($body)
                    ->send();
                */
                
                // For now, we just simulate success
                return ['success' => true, 'message' => 'Correo electrónico enviado a ' . $emailTo];
            } catch (\Exception $e) {
                return ['success' => false, 'message' => 'Error al enviar el correo: ' . $e->getMessage()];
            }
        }

        return $this->renderAjax('_send_email_form', [
            'model' => $model,
        ]);
    }

    public function actionChangeStatus($pre_id)
    {
        $model = $this->findModel($pre_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->save()) {
                return ['success' => true, 'message' => 'Situación del presupuesto actualizada.'];
            } else {
                return ['success' => false, 'errors' => $model->getErrors()];
            }
        }

        return $this->renderAjax('_change_status_form', [
            'model' => $model,
        ]);
    }
}