<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use app\controllers\BaseController;
use app\models\Liquidacion;
use app\models\Consecutivo;

class TransferenciasController extends BaseController
{
    public function actionIndex()
    {
        // Mock data provider to show the grid as in the image
        $dataProvider = new ArrayDataProvider([
            'allModels' => [],
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Liquidacion();
        $model->liq_a_favor = Liquidacion::LIQ_A_FAVOR_SOCIO;
        $model->liq_fecha = date('Y-m-d');

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->usu_id = Yii::$app->user->id ?? 1; // Fallback if not logged in
                
                // Defaults for numeric fields to pass validation
                $model->liq_irpf = $model->liq_irpf ?? 0;
                $model->liq_ret_imp_soc = $model->liq_ret_imp_soc ?? 0;
                $model->liq_total_neto = $model->liq_total_neto ?? 0;
                $model->liq_total_gastos = $model->liq_total_gastos ?? 0;
                $model->liq_total_retenciones = $model->liq_total_retenciones ?? 0;
                $model->liq_ingreso_liquido = $model->liq_ingreso_liquido ?? 0;
                $model->liq_irpf_valor = $model->liq_irpf_valor ?? 0;
                $model->liq_ret_imp_soc_valor = $model->liq_ret_imp_soc_valor ?? 0;
                $model->liq_iva_facturas = $model->liq_iva_facturas ?? 0;

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $modelConsecutivo = Consecutivo::findOne(['con_serie' => Consecutivo::CON_SERIE_L]);
                    if ($modelConsecutivo) {
                        $model->liq_numero = (string)$modelConsecutivo->con_consecutivo;
                        $modelConsecutivo->con_consecutivo++;
                        $modelConsecutivo->save(false);
                    } else {
                        $model->liq_numero = '1';
                    }

                    if ($model->save()) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Transferencia creada exitosamente.');
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Error al guardar la transferencia.');
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        } else {
            $model->loadDefaultValues();
            $modelConsecutivo = Consecutivo::findOne(['con_serie' => Consecutivo::CON_SERIE_L]);
            if ($modelConsecutivo) {
                $model->liq_numero = (string)$modelConsecutivo->con_consecutivo;
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateLiquidacionSocio()
    {
        $model = new Liquidacion();
        $model->liq_a_favor = Liquidacion::LIQ_A_FAVOR_SOCIO;
        $model->liq_fecha = date('Y-m-d');

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->usu_id = Yii::$app->user->id ?? 1; // Fallback if not logged in
                
                // Defaults for numeric fields to pass validation
                $model->liq_irpf = $model->liq_irpf ?: 0;
                $model->liq_ret_imp_soc = $model->liq_ret_imp_soc ?: 0;
                $model->liq_total_neto = $model->liq_total_neto ?: 0;
                $model->liq_total_gastos = $model->liq_total_gastos ?: 0;
                $model->liq_total_retenciones = $model->liq_total_retenciones ?: 0;
                $model->liq_ingreso_liquido = $model->liq_ingreso_liquido ?: 0;
                $model->liq_irpf_valor = $model->liq_irpf_valor ?: 0;
                $model->liq_ret_imp_soc_valor = $model->liq_ret_imp_soc_valor ?: 0;
                $model->liq_iva_facturas = $model->liq_iva_facturas ?: 0;

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $modelConsecutivo = Consecutivo::findOne(['con_serie' => Consecutivo::CON_SERIE_L]);
                    if ($modelConsecutivo) {
                        $model->liq_numero = (string)$modelConsecutivo->con_consecutivo;
                        $modelConsecutivo->con_consecutivo++;
                        $modelConsecutivo->save(false);
                    } else {
                        $model->liq_numero = '1';
                    }

                    if ($model->save()) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Liquidación creada exitosamente.');
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Error al guardar la liquidación.');
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        } else {
            $model->loadDefaultValues();
            $modelConsecutivo = Consecutivo::findOne(['con_serie' => Consecutivo::CON_SERIE_L]);
            if ($modelConsecutivo) {
                $model->liq_numero = (string)$modelConsecutivo->con_consecutivo;
            }
        }

        return $this->render('create-liquidacion-socio', [
            'model' => $model,
        ]);
    }

    public function actionCreateLiquidacionFreelance()
    {
        $model = new Liquidacion();
        $model->liq_a_favor = Liquidacion::LIQ_A_FAVOR_EMPRESA;
        $model->liq_fecha = date('Y-m-d');

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->usu_id = Yii::$app->user->id ?? 1; // Fallback if not logged in
                
                // Defaults for numeric fields to pass validation
                $model->liq_irpf = $model->liq_irpf ?? 0;
                $model->liq_ret_imp_soc = $model->liq_ret_imp_soc ?? 0;
                $model->liq_total_neto = $model->liq_total_neto ?? 0;
                $model->liq_total_gastos = $model->liq_total_gastos ?? 0;
                $model->liq_total_retenciones = $model->liq_total_retenciones ?? 0;
                $model->liq_ingreso_liquido = $model->liq_ingreso_liquido ?? 0;

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $modelConsecutivo = Consecutivo::findOne(['con_serie' => Consecutivo::CON_SERIE_L]);
                    if ($modelConsecutivo) {
                        $model->liq_numero = (string)$modelConsecutivo->con_consecutivo;
                        $modelConsecutivo->con_consecutivo++;
                        $modelConsecutivo->save(false);
                    } else {
                        $model->liq_numero = '1';
                    }

                    if ($model->save()) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Liquidación freelance creada exitosamente.');
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Error al guardar la liquidación.');
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        } else {
            $model->loadDefaultValues();
            $modelConsecutivo = Consecutivo::findOne(['con_serie' => Consecutivo::CON_SERIE_L]);
            if ($modelConsecutivo) {
                $model->liq_numero = (string)$modelConsecutivo->con_consecutivo;
            }
        }

        return $this->render('create-liquidacion-freelance', [
            'model' => $model,
        ]);
    }
}
