<?php

namespace app\controllers;

use app\models\ConceptoFacturacion;
use app\models\ConceptoFacturacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;
/**
 * ConceptoFacturacionController implements the CRUD actions for ConceptoFacturacion model.
 */
class ConceptoFacturacionController extends Controller
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
     * Lists all ConceptoFacturacion models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ConceptoFacturacionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ConceptoFacturacion model.
     * @param int $cof_id Cof ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($cof_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($cof_id),
        ]);
    }

    /**
     * Creates a new ConceptoFacturacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ConceptoFacturacion();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'cof_id' => $model->cof_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ConceptoFacturacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $cof_id Cof ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($cof_id)
    {
        $model = $this->findModel($cof_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'cof_id' => $model->cof_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ConceptoFacturacion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $cof_id Cof ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($cof_id)
    {
        $this->findModel($cof_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ConceptoFacturacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $cof_id Cof ID
     * @return ConceptoFacturacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cof_id)
    {
        if (($model = ConceptoFacturacion::findOne(['cof_id' => $cof_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExportExcel()
    {
        $conceptos = ConceptoFacturacion::find()->with('iva')->all();

        $headers = ['Código', 'Nombre', 'Clasificación', 'IVA'];
        $data = [];

        foreach ($conceptos as $concepto) {
            $data[] = [
                $concepto->cof_codigo,
                $concepto->cof_nombre,
                $concepto->displayCofClasificacion(),
                $concepto->iva ? $concepto->iva->iva_concepto : 'Sin IVA',
            ];
        }

        return ExcelExportHelper::export('Listado_Conceptos_Facturacion', $headers, $data);
    }

    public function actionExportPdf()
    {
        $conceptos = ConceptoFacturacion::find()->with('iva')->all();
        $headers = ['Código', 'Nombre', 'Clasificación', 'IVA'];
        $rows = [];

        foreach ($conceptos as $concepto) {
            $rows[] = [
                $concepto->cof_codigo,
                $concepto->cof_nombre,
                $concepto->displayCofClasificacion(),
                $concepto->iva ? $concepto->iva->iva_concepto : 'Sin IVA',
            ];
        }

        $html = $this->renderPartial('@app/views/export/_tabla_pdf', [
            'titulo' => 'Listado de Conceptos de Facturación',
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return PdfExportHelper::export('Listado_Conceptos_Facturacion', $html);
    }

    public function actionPrint()
    {
        $conceptos = ConceptoFacturacion::find()->with('iva')->all();

        $headers = ['Código', 'Nombre', 'Clasificación', 'IVA'];
        $rows = [];

        foreach ($conceptos as $concepto) {
            $rows[] = [
                $concepto->cof_codigo,
                $concepto->cof_nombre,
                $concepto->displayCofClasificacion(),
                $concepto->iva ? $concepto->iva->iva_concepto : 'Sin IVA',
            ];
        }

        return $this->renderPartial('@app/views/export/print_table', [
            'titulo' => 'Listado de Conceptos de Facturación',
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }
}
