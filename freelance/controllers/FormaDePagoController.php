<?php

namespace app\controllers;

use app\models\FormaDePago;
use app\models\FormaDePagoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;

/**
 * FormaDePagoController implements the CRUD actions for FormaDePago model.
 */
class FormaDePagoController extends Controller
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
     * Lists all FormaDePago models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FormaDePagoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FormaDePago model.
     * @param int $fdp_id Fdp ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($fdp_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($fdp_id),
        ]);
    }

    /**
     * Creates a new FormaDePago model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new FormaDePago();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'fdp_id' => $model->fdp_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FormaDePago model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $fdp_id Fdp ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($fdp_id)
    {
        $model = $this->findModel($fdp_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'fdp_id' => $model->fdp_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing FormaDePago model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $fdp_id Fdp ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($fdp_id)
    {
        $this->findModel($fdp_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the FormaDePago model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $fdp_id Fdp ID
     * @return FormaDePago the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($fdp_id)
    {
        if (($model = FormaDePago::findOne(['fdp_id' => $fdp_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionExportExcel()
    {
        $formas = FormaDePago::find()->all();

        $headers = ['ID', 'Nombre', 'Eliminada'];
        $data = [];

        foreach ($formas as $forma) {
            $data[] = [
                $forma->fdp_id,
                $forma->fdp_nombre,
                $forma->fdp_eliminada ? 'Sí' : 'No',
            ];
        }

        return ExcelExportHelper::export('Listado_Formas_De_Pago', $headers, $data);
    }

    public function actionExportPdf()
    {
        $formas = FormaDePago::find()->all();
        $headers = ['ID', 'Nombre', 'Eliminada'];
        $rows = [];

        foreach ($formas as $forma) {
            $rows[] = [
                $forma->fdp_id,
                $forma->fdp_nombre,
                $forma->fdp_eliminada ? 'Sí' : 'No',
            ];
        }

        $html = $this->renderPartial('@app/views/export/_tabla_pdf', [
            'titulo' => 'Listado de Formas de Pago',
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return PdfExportHelper::export('Listado_Formas_De_Pago', $html);
    }

    public function actionPrint()
    {
        $formas = FormaDePago::find()->all();

        $headers = ['ID', 'Nombre', 'Eliminada'];
        $rows = [];

        foreach ($formas as $forma) {
            $rows[] = [
                $forma->fdp_id,
                $forma->fdp_nombre,
                $forma->fdp_eliminada ? 'Sí' : 'No',
            ];
        }

        return $this->renderPartial('@app/views/export/print_table', [
            'titulo' => 'Listado de Formas de Pago',
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }
}
