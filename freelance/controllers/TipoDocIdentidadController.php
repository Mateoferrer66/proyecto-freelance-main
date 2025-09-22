<?php

namespace app\controllers;

use app\models\TipoDocIdentidad;
use app\models\TipoDocIdentidadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;
/**
 * TipoDocIdentidadController implements the CRUD actions for TipoDocIdentidad model.
 */
class TipoDocIdentidadController extends Controller
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
     * Lists all TipoDocIdentidad models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TipoDocIdentidadSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TipoDocIdentidad model.
     * @param int $tdo_id Tdo ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->get('view') === 'modal') {
            return $this->renderAjax('view', ['model' => $model]);
        }

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new TipoDocIdentidad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TipoDocIdentidad();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    if ($model->save()) {
                        return ['success' => true, 'message' => 'Tipo de documento creado correctamente.'];
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

        if (Yii::$app->request->get('view') === 'modal') {
            return $this->renderAjax('create', ['model' => $model]);
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing TipoDocIdentidad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id Tdo ID
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
                    return ['success' => true, 'message' => 'Tipo de documento actualizado correctamente.'];
                } else {
                    return ['success' => false, 'errors' => $model->getErrors()];
                }
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        if (Yii::$app->request->get('view') === 'modal') {
            return $this->renderAjax('update', ['model' => $model]);
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing TipoDocIdentidad model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $tdo_id Tdo ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TipoDocIdentidad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id Tdo ID
     * @return TipoDocIdentidad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TipoDocIdentidad::findOne(['tdo_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionExportExcel()
    {
        $tipos = TipoDocIdentidad::find()->all();

        $headers = ['Código', 'Nombre', 'Eliminado'];
        $data = [];

        foreach ($tipos as $tipo) {
            $data[] = [
                $tipo->tdo_id,
                $tipo->tdo_nombre,
                $tipo->tdo_eliminado ? 'Sí' : 'No',
            ];
        }

        return ExcelExportHelper::export('Listado_Tipos_Doc_Identidad', $headers, $data);
    }

    public function actionExportPdf()
    {
        $tipos = TipoDocIdentidad::find()->all();
        $headers = ['Código', 'Nombre', 'Eliminado'];
        $rows = [];

        foreach ($tipos as $tipo) {
            $rows[] = [
                $tipo->tdo_id,
                $tipo->tdo_nombre,
                $tipo->tdo_eliminado ? 'Sí' : 'No',
            ];
        }

        $html = $this->renderPartial('@app/views/export/_tabla_pdf', [
            'titulo' => 'Listado de Tipos de Documento de Identidad',
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return PdfExportHelper::export('Listado_Tipos_Doc_Identidad', $html);
    }

    public function actionPrint()
    {
        $tipos = TipoDocIdentidad::find()->all();

        $headers = ['Código', 'Nombre', 'Eliminado'];
        $rows = [];

        foreach ($tipos as $tipo) {
            $rows[] = [
                $tipo->tdo_id,
                $tipo->tdo_nombre,
                $tipo->tdo_eliminado ? 'Sí' : 'No',
            ];
        }

        return $this->renderPartial('@app/views/export/print_table', [
            'titulo' => 'Listado de Tipos de Documento de Identidad',
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }
}
