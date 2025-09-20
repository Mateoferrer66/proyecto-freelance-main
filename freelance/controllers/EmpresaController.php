<?php

namespace app\controllers;

use app\models\Empresa;
use app\models\Configuracion;
use app\models\EmpresaSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EmpresaController implements the CRUD actions for Empresa model.
 */
class EmpresaController extends Controller
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
     * Lists all Empresa models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EmpresaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Empresa model.
     * @param int $emp_id Emp ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($emp_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($emp_id),
        ]);
    }

    /**
     * Creates a new Empresa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $empresa = new Empresa();
        $configuracion = new Configuracion();

        if ($this->request->isPost) {
            $empresa->load($this->request->post());
            $configuracion->load($this->request->post());

            $valid = $empresa->validate();
            $valid = $configuracion->validate() && $valid;

            if ($valid) {
                $empresa->save(false);
                $configuracion->save(false);
                Yii::$app->session->setFlash('success', 'Datos guardados correctamente.');
                return $this->redirect(['view', 'emp_id' => $empresa->emp_id]);
            }
        } else {
            $empresa->loadDefaultValues();
            $configuracion->loadDefaultValues();
        }

        return $this->render('create', [
            'empresa' => $empresa,
            'configuracion' => $configuracion,
        ]);
    }

    /**
     * Updates an existing Empresa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $emp_id Emp ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($emp_id)
    {
        $empresa = $this->findModel($emp_id);
        $configuracion = Configuracion::find()->one();
        
        if(!$configuracion){
            $configuracion = new Configuracion();
        }

        if ($this->request->isPost && $empresa->load($this->request->post()) && $configuracion->load($this->request->post())) {
            
            $valid = $empresa->validate();
            $valid = $configuracion->validate() && $valid;

            if($valid){
                $empresa->save(false);
                $configuracion->save(false);
                Yii::$app->session->setFlash('success', 'Datos guardados correctamente.');
                return $this->redirect(['view', 'emp_id' => $empresa->emp_id]);
            }
        }

        return $this->render('update', [
            'empresa' => $empresa,
            'configuracion' => $configuracion,
        ]);
    }

    /**
     * Deletes an existing Empresa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $emp_id Emp ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($emp_id)
    {
        $this->findModel($emp_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Empresa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $emp_id Emp ID
     * @return Empresa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($emp_id)
    {
        if (($model = Empresa::findOne(['emp_id' => $emp_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
