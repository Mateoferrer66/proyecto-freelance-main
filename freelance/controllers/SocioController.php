<?php

namespace app\controllers;

use app\models\Socio;
use app\models\SocioSearch;
use app\models\SocAltaBaja;
use app\models\Categoria;
use app\models\Provincia;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use Yii;

class SocioController extends BaseController
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

    public function actionIndex()
    {
        $searchModel = new SocioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Socio();

        // Asegurar un valor por defecto para soc_id si no se envía
        if ($model->soc_id === null) {
            $model->soc_id = 1;
        }

        if ($this->request->isPost) {
            /*
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
            */
        } else {
            $model->loadDefaultValues();
            try {
                $con = Consecutivo::findOne(['con_serie' => Consecutivo::CON_SERIE_S]);
                if ($con !== null) {
                    $model->soc_numero = $con->con_consecutivo + 1;
                }
            } catch (\Throwable $e) {
                Yii::error('No se pudo obtener consecutivo por defecto: ' . $e->getMessage());
            }
        }

        $categories = Categoria::getList();
        $provinces = Provincia::getSpainProvincesList();

        $renderMethod = $this->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('create', 
            [
                'model' => $model,
                'categories' => $categories, 
                'provinces' => $provinces
            ]
        );
    }

    public function actionToggleStatus($soc_id)
    {
        $model = $this->findModel($soc_id);

        $modelSocAltaBaja = new SocAltaBaja();
        $modelSocAltaBaja->soc_id = $soc_id;
        $modelSocAltaBaja->usu_id = Yii::$app->user->id;
        $modelSocAltaBaja->sab_fecha = date('Y-m-d H:i:s');

        if ($model->soc_estado === Socio::SOC_ESTADO_ACTIVO) {
            $model->soc_estado = Socio::SOC_ESTADO_INACTIVO;
            $modelSocAltaBaja->sab_accion = SocAltaBaja::SAB_ACCION_INACTIVO;
            $modelSocAltaBaja->sab_observaciones = 'Socio inactivado';
        } else {
            $model->soc_estado = Socio::SOC_ESTADO_ACTIVO;
            $modelSocAltaBaja->sab_accion = SocAltaBaja::SAB_ACCION_ACTIVO;
            $modelSocAltaBaja->sab_observaciones = 'Socio activado';
        }

        if ($model->save(['soc_estado'])) {
            $modelSocAltaBaja->save();
            Yii::$app->session->setFlash('success', $modelSocAltaBaja->sab_observaciones.' correctamente.');
        } else {
            Yii::$app->session->setFlash('danger', 'No se pudo cambiar el estado del socio.');
        }

        return $this->redirect(['index']);
    }

    public function actionDelete($soc_id)
    {
        $model = $this->findModel($soc_id);
        $model->soc_eliminado = 1;
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->save(false)) {
                $alta_baja = new SocAltaBaja();
                $alta_baja->soc_id = $soc_id;
                $alta_baja->usu_id = Yii::$app->user->id;
                $alta_baja->sab_accion = SocAltaBaja::SAB_ACCION_BAJA;
                $alta_baja->sab_fecha = date('Y-m-d H:i:s');
                $alta_baja->sab_observaciones = 'Eliminación de socio';
                if (!$alta_baja->save()) {
                    throw new \Exception('Error al guardar en el historial.');
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Socio eliminado correctamente.');
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', 'No se pudo eliminar al socio.');
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
            return ['success' => false, 'message' => 'No se han seleccionado socios.'];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $count = Socio::updateAll(['soc_eliminado' => 1], ['in', 'soc_id', $ids]);

            $alta_baja_records = [];
            foreach ($ids as $id) {
                $alta_baja_records[] = [
                    $id,
                    Yii::$app->user->id,
                    SocAltaBaja::SAB_ACCION_BAJA,
                    date('Y-m-d H:i:s'),
                    'Socio eliminado en lote'
                ];
            }
            Yii::$app->db->createCommand()->batchInsert(SocAltaBaja::tableName(), ['soc_id', 'usu_id', 'sab_accion', 'sab_fecha', 'sab_observaciones'], $alta_baja_records)->execute();
            
            $transaction->commit();

            return ['success' => true, 'message' => $count . ' socio(s) eliminado(s) correctamente.'];
        } catch (\yii\db\Exception $e) {
            $transaction->rollBack();
            // Log the error if needed
            return ['success' => false, 'message' => 'Ocurrió un error al eliminar los socios.'];
        }
    }

    /**
     * Creates a new Socio model via AJAX.
     * If creation is successful, returns JSON with success=true and the new ID/Name.
     * If creation fails, returns JSON with success=false and validation errors.
     */
    public function actionCreateAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Socio();

        if ($model->load(Yii::$app->request->post())) {
            // Force some defaults if not present
            if (empty($model->soc_password)) {
                $model->soc_password = Yii::$app->security->generateRandomString(8); // Temporary password if required
            }
            
            if ($model->save()) {
                return [
                    'success' => true,
                    'id' => $model->soc_id,
                    'nombre' => $model->soc_nombre . ' ' . $model->soc_apellido,
                ];
            } else {
                return [
                    'success' => false,
                    'errors' => $model->getErrors(),
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'No data received',
        ];
    }

    protected function findModel($soc_id)
    {
        if (($model = Socio::findOne(['soc_id' => $soc_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
