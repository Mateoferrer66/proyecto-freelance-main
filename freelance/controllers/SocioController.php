<?php

namespace app\controllers;

use Yii;
use app\models\Socio;
use yii\web\Response;
use yii\widgets\ActiveForm;

class SocioController extends BaseController
{
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
}
