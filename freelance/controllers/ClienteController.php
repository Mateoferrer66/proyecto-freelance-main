<?php

namespace app\controllers;

use app\models\Cliente;
use app\models\ClienteSearch;
use app\models\CliAltaBaja;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;
use app\models\Consecutivo;
use app\models\Provincia;
use app\models\TipoDocIdentidad;
use app\models\Pais;
use app\models\FormaDePago;
use app\models\Socio;
use yii\web\Response;
use yii\helpers\Html;
use Yii;

class ClienteController extends BaseController
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                        'toggle-status' => ['POST'],
                        'batch-delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionToggleStatus($cli_id)
    {
        $model = $this->findModel($cli_id);

        $alta_baja = new CliAltaBaja();
        $alta_baja->cli_id = $cli_id;
        $alta_baja->usu_id = Yii::$app->user->id;
        $alta_baja->cab_fecha = date('Y-m-d H:i:s');

        if ($model->cli_estado === Cliente::CLI_ESTADO_ACTIVO) {
            $model->cli_estado = Cliente::CLI_ESTADO_INACTIVO;
            $alta_baja->cab_accion = CliAltaBaja::CAB_ACCION_INACTIVO;
            $alta_baja->cab_observaciones = 'Cliente inactivado';
        } else {
            $model->cli_estado = Cliente::CLI_ESTADO_ACTIVO;
            $alta_baja->cab_accion = CliAltaBaja::CAB_ACCION_ACTIVO;
            $alta_baja->cab_observaciones = 'Cliente activado';
        }

        if ($model->save(['cli_estado'])) {
            $alta_baja->save();
        }

        return $this->redirect(['index']);
    }

    public function actionIndex()
    {
        $searchModel = new ClienteSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($cli_id)
    {
        $model = $this->findModel($cli_id);

        if ($this->request->get('view') === 'modal') {
            return $this->renderAjax('view', ['model' => $model]);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new Cliente();

        // Asegurar un valor por defecto para soc_id si no se envía
        if ($model->soc_id === null) {
            $model->soc_id = 1;
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Comprobaciones referenciales
                if (!TipoDocIdentidad::findOne($model->tdo_id)) {
                    $model->addError('tdo_id', 'El tipo de documento seleccionado no existe.');
                }
                if (!Pais::findOne($model->pai_id)) {
                    $model->addError('pai_id', 'El país seleccionado no existe.');
                }
                if (!FormaDePago::findOne($model->fdp_id)) {
                    $model->addError('fdp_id', 'La forma de pago seleccionada no existe.');
                }
                if (!Socio::findOne($model->soc_id)) {
                    $model->addError('soc_id', 'El socio seleccionado no existe.');
                }

                if ($model->hasErrors()) {
                    Yii::error('Errores de integridad referencial al crear Cliente: ' . json_encode($model->getErrors()));
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ['success' => false, 'errors' => $model->getErrors()];
                    }
                    $renderMethod = $this->request->isAjax ? 'renderAjax' : 'render';
                    Yii::$app->session->setFlash('error', 'No se pudo crear el cliente: ' . implode(' | ', $model->getFirstErrors()));
                    return $this->$renderMethod('create', ['model' => $model]);
                }

                // Comprobar unicidad del número de cliente
                $exists = Cliente::find()->where(['cli_numero' => $model->cli_numero])->andWhere(['cli_eliminado' => 0])->exists();
                if ($exists) {
                    $model->addError('cli_numero', 'El consecutivo seleccionado ya está en uso por otro cliente.');
                    Yii::error('Intento de crear cliente con consecutivo ya usado: ' . $model->cli_numero);
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ['success' => false, 'errors' => $model->getErrors()];
                    }
                    $renderMethod = $this->request->isAjax ? 'renderAjax' : 'render';
                    return $this->$renderMethod('create', ['model' => $model]);
                }

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$model->save()) {
                        $transaction->rollBack();
                        Yii::error('Errores al guardar Cliente: ' . json_encode($model->getErrors()));
                        $firstErrors = $model->getFirstErrors();
                        $userMessage = !empty($firstErrors) ? implode(' | ', $firstErrors) : 'Errores de validación al crear cliente.';
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return ['success' => false, 'errors' => $model->getErrors()];
                        }
                        Yii::$app->session->setFlash('error', 'No se pudo crear el cliente: ' . $userMessage);
                        $renderMethod = $this->request->isAjax ? 'renderAjax' : 'render';
                        return $this->$renderMethod('create', ['model' => $model]);
                    }

                    $alta_baja = new CliAltaBaja();
                    $alta_baja->cli_id = $model->cli_id;
                    $alta_baja->usu_id = Yii::$app->user->id;
                    $alta_baja->cab_accion = CliAltaBaja::CAB_ACCION_ALTA;
                    $alta_baja->cab_fecha = date('Y-m-d H:i:s');
                    $alta_baja->cab_observaciones = 'Cliente creado';
                    if (!$alta_baja->save()) {
                        throw new \Exception('Error al guardar en el historial.');
                    }

                    // SELECT ... FOR UPDATE y UPDATE crudo del consecutivo
                    try {
                        $tbl = Consecutivo::tableName();
                        $sql = "SELECT con_consecutivo FROM {$tbl} WHERE con_serie = :serie FOR UPDATE";
                        $row = Yii::$app->db->createCommand($sql, [':serie' => Consecutivo::CON_SERIE_C])->queryOne();

                        if ($row === false || $row === null) {
                            throw new \yii\db\Exception('No se encontró el registro de consecutivo para clientes (Serie C).');
                        }

                        $used = (int)$model->cli_numero;
                        $current = (int)$row['con_consecutivo'];

                        if ($used >= $current) {
                            $new = $used + 1;
                            $sqlUpdate = "UPDATE {$tbl} SET con_consecutivo = :new WHERE con_serie = :serie AND con_consecutivo = :expected";
                            $affected = Yii::$app->db->createCommand($sqlUpdate, [
                                ':new' => $new,
                                ':serie' => Consecutivo::CON_SERIE_C,
                                ':expected' => $current
                            ])->execute();

                            if ($affected <= 0) {
                                throw new \yii\db\Exception('Error de concurrencia o dato inesperado al actualizar el consecutivo de clientes.');
                            }
                            Yii::info('Consecutivo (serie C) actualizado a ' . $new . ' tras crear cliente con número ' . $used);
                        } else {
                            Yii::info('Consecutivo (serie C) se mantiene en ' . $current . ' (cliente creado con número ' . $used . ' que es menor)');
                        }
                    } catch (\Throwable $e) {
                        // Re-lanza la excepción para que la transacción principal haga rollback
                        throw $e;
                    }

                    $transaction->commit();

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ['success' => true, 'message' => 'Cliente creado correctamente.'];
                    }
                    Yii::$app->session->setFlash('success', 'Cliente creado correctamente.');
                    return $this->redirect(['index']);
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::error('Error al crear cliente: ' . $e->getMessage());
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        } else {
            $model->loadDefaultValues();
            try {
                $con = Consecutivo::findOne(['con_serie' => Consecutivo::CON_SERIE_C]);
                if ($con !== null) {
                    $model->cli_numero = $con->con_consecutivo + 1;
                }
            } catch (\Throwable $e) {
                Yii::error('No se pudo obtener consecutivo por defecto: ' . $e->getMessage());
            }
        }

        $renderMethod = $this->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('create', ['model' => $model]);
    }


    public function actionUpdate($cli_id)
    {
        $model = $this->findModel($cli_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($this->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($model->save()) {
                    return ['success' => true, 'message' => 'Cliente actualizado correctamente.'];
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

    public function actionDelete($cli_id)
    {
        $model = $this->findModel($cli_id);
        $model->cli_eliminado = 1;
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->save(false)) {
                $alta_baja = new CliAltaBaja();
                $alta_baja->cli_id = $cli_id;
                $alta_baja->usu_id = Yii::$app->user->id;
                $alta_baja->cab_accion = CliAltaBaja::CAB_ACCION_BAJA;
                $alta_baja->cab_fecha = date('Y-m-d H:i:s');
                $alta_baja->cab_observaciones = 'Cliente eliminado';
                if (!$alta_baja->save()) {
                    throw new \Exception('Error al guardar en el historial.');
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Cliente eliminado correctamente.');
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'No se pudo eliminar al cliente.');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionBatchDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ids = Yii::$app->request->post('ids');
        if (empty($ids)) {
            return ['success' => false, 'message' => 'No se han seleccionado clientes.'];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $count = Cliente::updateAll(['cli_eliminado' => 1], ['in', 'cli_id', $ids]);

            $alta_baja_records = [];
            foreach ($ids as $id) {
                $alta_baja_records[] = [
                    $id,
                    Yii::$app->user->id,
                    CliAltaBaja::CAB_ACCION_BAJA,
                    date('Y-m-d H:i:s'),
                    'Cliente eliminado en lote'
                ];
            }
            Yii::$app->db->createCommand()->batchInsert(CliAltaBaja::tableName(), ['cli_id', 'usu_id', 'cab_accion', 'cab_fecha', 'cab_observaciones'], $alta_baja_records)->execute();
            
            $transaction->commit();

            return ['success' => true, 'message' => $count . ' cliente(s) eliminado(s) correctamente.'];
        } catch (\yii\db\Exception $e) {
            $transaction->rollBack();
            // Log the error if needed
            return ['success' => false, 'message' => 'Ocurrió un error al eliminar los clientes.'];
        }
    }

    protected function findModel($cli_id)
    {
        if (($model = Cliente::findOne(['cli_id' => $cli_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionExportExcel()
    {
        $clientes = Cliente::find()->all();

        $headers = ['Número de Documento', 'Nombre', 'Activo'];
        $data = [];

        foreach ($clientes as $cliente) {
            $data[] = [
                $cliente->cli_numdocide,
                $cliente->cli_nombre,
                $cliente->cli_estado,
            ];
        }

        return ExcelExportHelper::export('Listado_Clientes', $headers, $data);
    }

    public function actionExportPdf()
    {
        $clientes = Cliente::find()->all();
        $headers = ['Número de Documento', 'Nombre', 'Activo'];
        $rows = [];

        foreach ($clientes as $cliente) {
            $rows[] = [
                $cliente->cli_numdocide,
                $cliente->cli_nombre,
                $cliente->cli_estado,
            ];
        }

        $html = $this->renderPartial('@app/views/export/_tabla_pdf', [
            'titulo' => 'Listado de Clientes',
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return PdfExportHelper::export('Listado_Clientes', $html);
    }

    public function actionPrint()
    {
        $clientes = Cliente::find()->all();

        $headers = ['Número de Documento', 'Nombre', 'Activo'];
        $rows = [];

        foreach ($clientes as $cliente) {
            $rows[] = [
                $cliente->cli_numdocide,
                $cliente->cli_nombre,
                $cliente->cli_estado,
            ];
        }

        return $this->renderPartial('@app/views/export/print_table', [
            'titulo' => 'Listado de Clientes',
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }
    public function actionProvinciasPorPais($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $provincias = Provincia::find()
            ->where(['pai_id' => $id])
            ->orderBy('prv_nombre')
            ->all();

        $data = [];
        foreach ($provincias as $provincia) {
            $data[] = ['id' => $provincia->prv_id, 'name' => $provincia->prv_nombre];
        }
        return $data;
    }
}