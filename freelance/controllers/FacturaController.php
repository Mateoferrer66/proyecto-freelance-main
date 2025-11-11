<?php

namespace app\controllers;

use Mpdf\Mpdf;
use app\models\Factura;
use app\models\DetalleFactura;
use app\models\CuentasFactura;
use app\models\FacturaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use Yii;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;

/**
 * FacturaController implements the CRUD actions for Factura model.
 */
class FacturaController extends BaseController
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
     * Lists all Factura models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FacturaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Factura model.
     * @param int $fac_id Factura ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($fac_id)
    {
        $model = $this->findModel($fac_id);

        if ($this->request->get('view') === 'modal') {
            return $this->renderAjax('view', ['model' => $model]);
        }

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new Factura model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Factura();

        // Asegurar que al crear la factura el estado sea siempre 'Sin Pagar'
        $model->fac_estado = Factura::FAC_ESTADO_SIN_PAGAR;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Forzar estado a 'Sin Pagar' incluso si el formulario intentara cambiarlo
                $model->fac_estado = Factura::FAC_ESTADO_SIN_PAGAR;

                // Primero construimos los modelos de detalle y los validamos sin persistir
                $detallesData = Yii::$app->request->post('DetalleFactura', []);
                $detalleModels = [];
                $detalleRowErrors = [];
                foreach ($detallesData as $idx => $d) {
                    $cantidad = isset($d['dtf_cantidad']) ? floatval($d['dtf_cantidad']) : 0.0;
                    $precio = isset($d['dtf_precio']) ? floatval($d['dtf_precio']) : 0.0;

                    // Si la línea está vacía (cantidad y precio 0), ignorarla
                    if ($cantidad == 0 && $precio == 0) {
                        continue;
                    }

                    $det = new DetalleFactura();
                    // No asignamos fac_id aún (se asignará al guardar la factura)
                    $det->cof_id = isset($d['cof_id']) && $d['cof_id'] !== '' ? $d['cof_id'] : null;
                    $det->dtf_descripcion = isset($d['dtf_descripcion']) ? $d['dtf_descripcion'] : '';
                    $det->dtf_cantidad = $cantidad;
                    $det->dtf_precio = $precio;
                    $det->dtf_iva = isset($d['dtf_iva']) ? floatval($d['dtf_iva']) : 0.0;
                    $det->dtf_subtotal = $cantidad * $precio;

                    // Validar sólo los atributos del detalle que provienen del formulario
                    // (no validamos fac_id aquí porque la factura aún no está guardada)
                    if (!$det->validate(['cof_id', 'dtf_descripcion', 'dtf_cantidad', 'dtf_precio', 'dtf_iva'])) {
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
                    $selectedBanco = isset($detallesData['cuenta_ban_id']) ? $detallesData['cuenta_ban_id'] : (Yii::$app->request->post('CuentasFactura', [])['ban_id'] ?? null);
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

                // Si todo es válido, guardar factura y detalles en transacción
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$model->save()) {
                        throw new \Exception('No se pudo guardar la factura: ' . json_encode($model->getErrors()));
                    }

                    $subtotal = 0.0;
                    $ivaTotal = 0.0;
                    foreach ($detalleModels as $det) {
                        $det->fac_id = $model->fac_id;
                        if (!$det->save(false)) {
                            throw new \Exception('Error al guardar detalle: ' . json_encode($det->getErrors()));
                        }
                        $subtotal += $det->dtf_subtotal;
                        $ivaTotal += $det->dtf_subtotal * ($det->dtf_iva / 100.0);
                    }

                    // Guardar cuenta seleccionada para la transferencia (si se envió)
                    $postedCuenta = Yii::$app->request->post('CuentasFactura', []);
                    $banId = isset($postedCuenta['ban_id']) && $postedCuenta['ban_id'] !== '' ? $postedCuenta['ban_id'] : null;
                    if ($banId) {
                        $cf = new CuentasFactura();
                        $cf->ban_id = $banId;
                        $cf->fac_id = $model->fac_id;
                        if (!$cf->save()) {
                            throw new \Exception('No se pudo guardar la cuenta de factura: ' . json_encode($cf->getErrors()));
                        }
                    }

                    // Recalcular totales y guardar en la factura
                    $gastos = isset($model->fac_gastos_suplidos) ? floatval($model->fac_gastos_suplidos) : 0.0;
                    $model->fac_subtotal = $subtotal;
                    $model->fac_iva = $ivaTotal;
                    $model->fac_total = $subtotal + $ivaTotal + $gastos;
                    $model->save(false, ['fac_subtotal', 'fac_iva', 'fac_total']);

                    $transaction->commit();

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ['success' => true, 'message' => 'Factura creada correctamente.'];
                    }

                    Yii::$app->session->setFlash('success', 'Factura creada.');
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
            // Asegurar valor por defecto cuando se muestra el formulario
            $model->fac_estado = Factura::FAC_ESTADO_SIN_PAGAR;
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
     * Updates an existing Factura model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $fac_id Factura ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($fac_id)
    {
        $model = $this->findModel($fac_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($this->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($model->save()) {
                    return ['success' => true, 'message' => 'Factura actualizada correctamente.'];
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
     * Deletes an existing Factura model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $fac_id Factura ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($fac_id)
    {
        $model = $this->findModel($fac_id);
        $model->fac_eliminada = 1;
        $model->save(false, ['fac_eliminada']);

        return $this->redirect(['index']);
    }
    
    public function actionBatchDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ids = Yii::$app->request->post('ids');
        if (empty($ids)) {
            return ['success' => false, 'message' => 'No se han seleccionado facturas.'];
        }

        try {
            $count = 0;
            foreach ($ids as $id) {
                $model = $this->findModel($id);
                if ($model) {
                    $model->fac_eliminada = 1;
                    if ($model->save(false, ['fac_eliminada'])) {
                        $count++;
                    }
                }
            }
            return ['success' => true, 'message' => $count . ' factura(s) eliminada(s) correctamente.'];
        } catch (\yii\db\Exception $e) {
            return ['success' => false, 'message' => 'Ocurrió un error al eliminar las facturas.'];
        }
    }

    /**
     * Finds the Factura model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $fac_id Factura ID
     * @return Factura the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($fac_id)
    {
        if (($model = Factura::findOne(['fac_id' => $fac_id, 'fac_eliminada' => 0])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExportExcel()
    {
        $facturas = Factura::find()->where(['fac_eliminada' => 0])->all();

        $headers = ['Número', 'Fecha', 'Cliente', 'Total', 'Estado'];
        $data = [];

        foreach ($facturas as $factura) {
            $data[] = [
                $factura->fac_numero,
                Yii::$app->formatter->asDate($factura->fac_fecha, 'php:d-m-Y'),
                $factura->cli->cli_nombre,
                $factura->fac_total,
                $factura->fac_estado,
            ];
        }

        return ExcelExportHelper::export('Listado_Facturas', $headers, $data);
    }

    public function actionExportPdf()
    {
        $facturas = Factura::find()->where(['fac_eliminada' => 0])->all();
        $headers = ['Número', 'Fecha', 'Cliente', 'Total', 'Estado'];
        $rows = [];

        foreach ($facturas as $factura) {
            $rows[] = [
                $factura->fac_numero,
                Yii::$app->formatter->asDate($factura->fac_fecha, 'php:d-m-Y'),
                $factura->cli->cli_nombre,
                Yii::$app->formatter->asCurrency($factura->fac_total),
                $factura->fac_estado,
            ];
        }

        $html = $this->renderPartial('@app/views/export/_tabla_pdf', [
            'titulo' => 'Listado de Facturas',
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return PdfExportHelper::export('Listado_Facturas', $html);
    }

    public function actionPrint($fac_id = null)
    {
        if ($fac_id !== null) {
            $model = $this->findModel($fac_id);
            return $this->renderPartial('print', ['model' => $model]);
        }

        $facturas = Factura::find()->where(['fac_eliminada' => 0])->all();

        $headers = ['Número', 'Fecha', 'Cliente', 'Total', 'Estado'];
        $rows = [];

        foreach ($facturas as $factura) {
            $rows[] = [
                $factura->fac_numero,
                Yii::$app->formatter->asDate($factura->fac_fecha, 'php:d-m-Y'),
                $factura->cli->cli_nombre,
                Yii::$app->formatter->asCurrency($factura->fac_total),
                $factura->fac_estado,
            ];
        }

        return $this->renderPartial('@app/views/export/print_table', [
            'titulo' => 'Listado de Facturas',
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }

    public function actionSendEmail($fac_id)
    {
        $model = $this->findModel($fac_id);
        return $this->renderAjax('_send_email_form', [
            'model' => $model,
        ]);
    }

    public function actionDoSendEmail($fac_id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $model = $this->findModel($fac_id);

            if (empty($model->cli->cli_email)) {
                return ['success' => false, 'errors' => ['email' => 'El cliente no tiene un correo electrónico configurado.']];
            }

            // Generate PDF content
            $html = $this->renderPartial('print', ['model' => $model]);
            
            $mpdf = new Mpdf([
                'format' => 'A4',
                'orientation' => 'P',
                'margin_top' => 10,
                'margin_bottom' => 10,
            ]);
            $mpdf->SetTitle('Factura ' . $model->fac_numero);
            $mpdf->WriteHTML($html);
            $pdfContent = $mpdf->Output(null, \Mpdf\Output\Destination::STRING_RETURN);

            // Send email
            $mail = Yii::$app->mailer->compose()
                ->setFrom(['noreply@freelance.com' => 'Freelance App']) // Replace with a valid sender
                ->setTo($model->cli->cli_email)
                ->setSubject('Factura: ' . $model->fac_numero)
                ->setTextBody('Estimado cliente, adjuntamos su factura ' . $model->fac_numero . '.')
                ->attachContent($pdfContent, ['fileName' => 'Factura_' . $model->fac_numero . '.pdf', 'contentType' => 'application/pdf']);
            
            if ($mail->send()) {
                Yii::$app->session->setFlash('success', 'Correo electrónico enviado a ' . $model->cli->cli_email . '.');
                return ['success' => true];
            } else {
                Yii::error("Error sending email for invoice " . $model->fac_id);
                return ['success' => false, 'errors' => ['email' => 'Hubo un error al enviar el correo.']];
            }
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            return ['success' => false, 'errors' => ['exception' => 'Ocurrió una excepción: ' . $e->getMessage()]];
        }
    }

    public function actionMarkAsPaid($fac_id)
    {
        $model = $this->findModel($fac_id);
        $model->fac_estado = Factura::FAC_ESTADO_LIQUIDADA;
        $model->save(false, ['fac_estado']);
        Yii::$app->session->setFlash('success', 'Factura marcada como liquidada.');
        return $this->redirect(['index']);
    }

    public function actionChangeStatus($fac_id)
    {
        $model = $this->findModel($fac_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->save()) {
                return ['success' => true, 'message' => 'Situación de la factura actualizada.'];
            } else {
                return ['success' => false, 'errors' => $model->getErrors()];
            }
        }

        return $this->renderAjax('_change_status_form', [
            'model' => $model,
        ]);
    }
}