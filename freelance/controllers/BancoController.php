<?php

namespace app\controllers;

use app\models\Banco;
use app\models\BancoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BancoController implements the CRUD actions for Banco model.
 */
class BancoController extends Controller
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
     * Lists all Banco models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BancoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Banco model.
     * @param int $ban_id Ban ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($ban_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($ban_id),
        ]);
    }

    /**
     * Creates a new Banco model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Banco();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'ban_id' => $model->ban_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Banco model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $ban_id Ban ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($ban_id)
    {
        $model = $this->findModel($ban_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'ban_id' => $model->ban_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Banco model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $ban_id Ban ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($ban_id)
    {
        $this->findModel($ban_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Banco model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $ban_id Ban ID
     * @return Banco the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($ban_id)
    {
        if (($model = Banco::findOne(['ban_id' => $ban_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
