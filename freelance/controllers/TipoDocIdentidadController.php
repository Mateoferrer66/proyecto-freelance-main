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
    public function actionView($tdo_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($tdo_id),
        ]);
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
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'tdo_id' => $model->tdo_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TipoDocIdentidad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $tdo_id Tdo ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($tdo_id)
    {
        $model = $this->findModel($tdo_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'tdo_id' => $model->tdo_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TipoDocIdentidad model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $tdo_id Tdo ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($tdo_id)
    {
        $this->findModel($tdo_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TipoDocIdentidad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $tdo_id Tdo ID
     * @return TipoDocIdentidad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($tdo_id)
    {
        if (($model = TipoDocIdentidad::findOne(['tdo_id' => $tdo_id])) !== null) {
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
