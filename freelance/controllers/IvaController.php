<?php

namespace app\controllers;

use app\models\Iva;
use app\models\IvaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;
use Yii;

/**
 * IvaController implements the CRUD actions for Iva model.
 */
class IvaController extends BaseController
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
     * Lists all Iva models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new IvaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Iva model.
     * @param int $id Iva ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($this->request->get('view') === 'modal') {
            return $this->renderAjax('view', ['model' => $model]);
        }

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new Iva model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Iva();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    if ($model->save()) {
                        return ['success' => true, 'message' => 'IVA creado correctamente.'];
                    } else {
                        return ['success' => false, 'errors' => $model->getErrors()];
                    }
                }
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        if ($this->request->get('view') === 'modal') {
            return $this->renderAjax('create', ['model' => $model]);
        }

        return $this->render('create', ['model' => $model]);
    }


    /**
     * Updates an existing Iva model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id Iva ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if ($model->save()) {
                    return ['success' => true, 'message' => 'IVA actualizado correctamente.'];
                } else {
                    return ['success' => false, 'errors' => $model->getErrors()];
                }
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

    /**
     * Deletes an existing Iva model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id Iva ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Iva model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id Iva ID
     * @return Iva the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Iva::findOne(['iva_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

     public function actionExportExcel()
    {
        $ivas = Iva::find()->all();

        $headers = ['Porcentaje', 'Concepto'];
        $data = [];

        foreach ($ivas as $iva) {
            $data[] = [
                $iva->iva_porcentaje,
                $iva->iva_concepto,
            ];
        }

        return ExcelExportHelper::export('Listado_IVA', $headers, $data);
    }

    public function actionExportPdf()
{
    $ivas = Iva::find()->all();
    $headers = ['Porcentaje', 'Concepto'];
    $rows = [];

    foreach ($ivas as $iva) {
        $rows[] = [$iva->iva_porcentaje, $iva->iva_concepto];
    }

    $html = $this->renderPartial('@app/views/export/_tabla_pdf', [
        'titulo' => 'Listado de IVA',
        'headers' => $headers,
        'rows' => $rows,
    ]);

    return PdfExportHelper::export('Listado_IVA', $html);
}
public function actionPrint()
{
    $ivas = \app\models\Iva::find()->all();

    $headers = ['Porcentaje', 'Concepto'];
    $rows = [];

    foreach ($ivas as $iva) {
        $rows[] = [$iva->iva_porcentaje, $iva->iva_concepto];
    }

    return $this->renderPartial('@app/views/export/print_table', [
        'titulo' => 'Listado de IVA',
        'headers' => $headers,
        'rows' => $rows,
    ]);
}
}
