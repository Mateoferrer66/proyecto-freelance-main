<?php

namespace app\controllers;

use app\models\ConceptoFacturacion;
use app\models\ConceptoFacturacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;
use Yii;

/**
 * ConceptoFacturacionController implements the CRUD actions for ConceptoFacturacion model.
 */
class ConceptoFacturacionController extends BaseController
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

    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($this->request->get('view') === 'modal') {
            return $this->renderAjax('view', ['model' => $model]);
        }

        return $this->render('view', ['model' => $model]);
    }

    public function actionCreate()
    {
        $model = new ConceptoFacturacion();

        if ($this->request->isPost && $model->load($this->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $model->save()
                    ? ['success' => true, 'message' => 'Concepto creado correctamente.']
                    : ['success' => false, 'errors' => $model->getErrors()];
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        $model->loadDefaultValues();

        if ($this->request->get('view') === 'modal') {
            return $this->renderAjax('create', ['model' => $model]);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $model->save()
                    ? ['success' => true, 'message' => 'Concepto actualizado correctamente.']
                    : ['success' => false, 'errors' => $model->getErrors()];
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        if ($this->request->get('view') === 'modal') {
            return $this->renderAjax('update', ['model' => $model]);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = ConceptoFacturacion::findOne(['cof_id' => $id])) !== null) {
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
