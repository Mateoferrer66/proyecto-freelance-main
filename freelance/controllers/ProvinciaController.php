<?php

namespace app\controllers;

use app\models\Provincia;
use app\models\ProvinciaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;

/**
 * ProvinciaController implements the CRUD actions for Provincia model.
 */
class ProvinciaController extends Controller
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
     * Lists all Provincia models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProvinciaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

         $dataProvider->pagination->pageSize = 10; // Configura el tamaño de página a 10

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Provincia model.
     * @param int $prv_id Prv ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prv_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prv_id),
        ]);
    }

    /**
     * Creates a new Provincia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Provincia();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'prv_id' => $model->prv_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Provincia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prv_id Prv ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prv_id)
    {
        $model = $this->findModel($prv_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'prv_id' => $model->prv_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Provincia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prv_id Prv ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prv_id)
    {
        $this->findModel($prv_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Provincia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prv_id Prv ID
     * @return Provincia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prv_id)
    {
        if (($model = Provincia::findOne(['prv_id' => $prv_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionExportExcel()
    {
        $provincias = Provincia::find()->with('pais')->all();

        $headers = ['Código', 'País', 'Nombre', 'Eliminada'];
        $data = [];

        foreach ($provincias as $provincia) {
            $data[] = [
                $provincia->prv_id,
                $provincia->pais ? $provincia->pais->pai_nombre : 'Sin país',
                $provincia->prv_nombre,
                $provincia->prv_eliminada ? 'Sí' : 'No',
            ];
        }

        return ExcelExportHelper::export('Listado_Provincias', $headers, $data);
    }

    public function actionExportPdf()
    {
        $provincias = Provincia::find()->with('pais')->all();
        $headers = ['Código', 'País', 'Nombre', 'Eliminada'];
        $rows = [];

        foreach ($provincias as $provincia) {
            $rows[] = [
                $provincia->prv_id,
                $provincia->pais ? $provincia->pais->pai_nombre : 'Sin país',
                $provincia->prv_nombre,
                $provincia->prv_eliminada ? 'Sí' : 'No',
            ];
        }

        $html = $this->renderPartial('@app/views/export/_tabla_pdf', [
            'titulo' => 'Listado de Provincias',
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return PdfExportHelper::export('Listado_Provincias', $html);
    }

    public function actionPrint()
    {
        $provincias = Provincia::find()->with('pais')->all();

        $headers = ['Código', 'País', 'Nombre', 'Eliminada'];
        $rows = [];

        foreach ($provincias as $provincia) {
            $rows[] = [
                $provincia->prv_id,
                $provincia->pais ? $provincia->pais->pai_nombre : 'Sin país',
                $provincia->prv_nombre,
                $provincia->prv_eliminada ? 'Sí' : 'No',
            ];
        }

        return $this->renderPartial('@app/views/export/print_table', [
            'titulo' => 'Listado de Provincias',
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }
}
