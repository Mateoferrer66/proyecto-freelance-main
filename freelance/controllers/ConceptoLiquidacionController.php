<?php

namespace app\controllers;

use app\models\ConceptoLiquidacion;
use app\models\ConceptoLiquidacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;
use Yii;

/**
 * ConceptoLiquidacionController implements the CRUD actions for ConceptoLiquidacion model.
 */
class ConceptoLiquidacionController extends BaseController
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
     * Lists all ConceptoLiquidacion models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ConceptoLiquidacionSearch();
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
        $model = new ConceptoLiquidacion();

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
            if ($this->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if ($model->save()) {
                    return ['success' => true, 'message' => 'Concepto actualizado correctamente.'];
                } else {
                    return ['success' => false, 'errors' => $model->getErrors()];
                }
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        $renderMethod = $this->request->get('view') === 'modal' ? 'renderAjax' : 'render';
        return $this->$renderMethod('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = ConceptoLiquidacion::findOne(['col_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionExportExcel()
    {
        $conceptos = ConceptoLiquidacion::find()->all();

        $headers = ['Código', 'Nombre', 'Clasificación', 'Tipo', 'Porcentaje', 'Valor'];
        $data = [];

        foreach ($conceptos as $concepto) {
            $data[] = [
                $concepto->col_codigo,
                $concepto->col_nombre,
                $concepto->displayColClasificacion(),
                $concepto->displayColTipo(),
                $concepto->col_porcentaje,
                $concepto->col_valor,
            ];
        }

        return ExcelExportHelper::export('Listado_Conceptos_Liquidacion', $headers, $data);
    }

    public function actionExportPdf()
    {
        $conceptos = ConceptoLiquidacion::find()->all();

        $headers = ['Código', 'Nombre', 'Clasificación', 'Tipo', 'Porcentaje', 'Valor'];
        $rows = [];

        foreach ($conceptos as $concepto) {
            $rows[] = [
                $concepto->col_codigo,
                $concepto->col_nombre,
                $concepto->displayColClasificacion(),
                $concepto->displayColTipo(),
                $concepto->col_porcentaje,
                $concepto->col_valor,
            ];
        }

        $html = $this->renderPartial('@app/views/export/_tabla_pdf', [
            'titulo' => 'Listado de Conceptos de Liquidación',
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return PdfExportHelper::export('Listado_Conceptos_Liquidacion', $html);
    }

    public function actionPrint()
    {
        $conceptos = ConceptoLiquidacion::find()->all();

        $headers = ['Código', 'Nombre', 'Clasificación', 'Tipo', 'Porcentaje', 'Valor'];
        $rows = [];

        foreach ($conceptos as $concepto) {
            $rows[] = [
                $concepto->col_codigo,
                $concepto->col_nombre,
                $concepto->displayColClasificacion(),
                $concepto->displayColTipo(),
                $concepto->col_porcentaje,
                $concepto->col_valor,
            ];
        }

        return $this->renderPartial('@app/views/export/print_table', [
            'titulo' => 'Listado de Conceptos de Liquidación',
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }
}