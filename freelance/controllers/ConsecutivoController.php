<?php

namespace app\controllers;

use app\models\Consecutivo;
use app\models\ConsecutivoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use Yii;

/**
 * ConsecutivoController implements the CRUD actions for Consecutivo model.
 */
class ConsecutivoController extends Controller
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
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Consecutivo models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ConsecutivoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Consecutivo model.
     * @param string $con_serie Con Serie
     * @param int $con_consecutivo Con Consecutivo
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($con_serie, $con_consecutivo)
    {
        return $this->render('view', [
            'model' => $this->findModel($con_serie, $con_consecutivo),
        ]);
    }

    /**
     * Creates a new Consecutivo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Consecutivo();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'con_serie' => $model->con_serie, 'con_consecutivo' => $model->con_consecutivo]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Consecutivo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $con_serie Con Serie
     * @param int $con_consecutivo Con Consecutivo
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($con_serie, $con_consecutivo)
    {
        $model = $this->findModel($con_serie, $con_consecutivo);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'con_serie' => $model->con_serie, 'con_consecutivo' => $model->con_consecutivo]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Consecutivo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $con_serie Con Serie
     * @param int $con_consecutivo Con Consecutivo
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($con_serie, $con_consecutivo)
    {
        $this->findModel($con_serie, $con_consecutivo)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Consecutivo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $con_serie Con Serie
     * @param int $con_consecutivo Con Consecutivo
     * @return Consecutivo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($con_serie, $con_consecutivo)
    {
        if (($model = Consecutivo::findOne(['con_serie' => $con_serie, 'con_consecutivo' => $con_consecutivo])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /**
     * Sets a new consecutivo.
     * @return array
     */
  public function actionSetConsecutivo()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $serie = Yii::$app->request->post('serie');
        $valor = Yii::$app->request->post('consecutivo');

        if (!$serie || !$valor) {
            return ['success' => false, 'message' => 'La serie y el consecutivo son requeridos.'];
        }

        // Buscar el consecutivo por serie
        $model = Consecutivo::findOne(['con_serie' => $serie]);

        if ($model) {
            // Si existe, actualiza el valor
            $model->con_consecutivo = $valor;
        } else {
            // Si no existe, crea uno nuevo
            $model = new Consecutivo([
                'con_serie' => $serie,
                'con_consecutivo' => $valor,
            ]);
        }

        if ($model->save()) {
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Error al guardar el consecutivo.', 'errors' => $model->getErrors()];
    }
}
