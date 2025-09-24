<?php

namespace app\controllers;

use app\models\Cliente;
use app\models\ClienteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;

/**
 * ClienteController implements the CRUD actions for Cliente model.
 */
class ClienteController extends Controller
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
                        'toggle-status' => ['POST'],
                        'batch-delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionToggleStatus($cli_id)
    {
        $model = $this->findModel($cli_id);

        if ($model->cli_estado === Cliente::CLI_ESTADO_ACTIVO) {
            $model->cli_estado = Cliente::CLI_ESTADO_INACTIVO;
        } else {
            $model->cli_estado = Cliente::CLI_ESTADO_ACTIVO;
        }

        $model->save(['cli_estado']);

        return $this->redirect(['index']);
    }

    /**
     * Lists all Cliente models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ClienteSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cliente model.
     * @param int $cli_id Cli ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($cli_id)
    {
        $model = $this->findModel($cli_id);

        if ($this->request->get('view') === 'modal') {
            return $this->renderAjax('view', ['model' => $model]);
        }

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new Cliente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Cliente();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'cli_id' => $model->cli_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        if ($this->request->get('view') === 'modal') {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Cliente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $cli_id Cli ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($cli_id)
    {
        $model = $this->findModel($cli_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($this->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if ($model->save()) {
                    return ['success' => true, 'message' => 'Cliente actualizado correctamente.'];
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

    /**
     * Deletes an existing Cliente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $cli_id Cli ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($cli_id)
    {
        $this->findModel($cli_id)->delete();

        return $this->redirect(['index']);
    }

    public function actionBatchDelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $ids = \Yii::$app->request->post('ids');
        if (empty($ids)) {
            return ['success' => false, 'message' => 'No se han seleccionado clientes.'];
        }

        try {
            $count = Cliente::deleteAll(['in', 'cli_id', $ids]);
            return ['success' => true, 'message' => $count . ' cliente(s) eliminado(s) correctamente.'];
        } catch (\yii\db\Exception $e) {
            // Log the error if needed
            return ['success' => false, 'message' => 'Ocurrió un error al eliminar los clientes.'];
        }
    }

    /**
     * Finds the Cliente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $cli_id Cli ID
     * @return Cliente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cli_id)
    {
        if (($model = Cliente::findOne(['cli_id' => $cli_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionExportExcel()
    {
        $clientes = Cliente::find()->all();

        $headers = ['Número de Documento', 'Nombre', 'Activo'];
        $data = [];

        foreach ($clientes as $cliente) {
            $data[] = [
                $cliente->cli_numdocide,
                $cliente->cli_nombre,
                $cliente->cli_estado,
            ];
        }

        return ExcelExportHelper::export('Listado_Clientes', $headers, $data);
    }

    public function actionExportPdf()
    {
        $clientes = Cliente::find()->all();
        $headers = ['Número de Documento', 'Nombre', 'Activo'];
        $rows = [];

        foreach ($clientes as $cliente) {
            $rows[] = [
                $cliente->cli_numdocide,
                $cliente->cli_nombre,
                $cliente->cli_estado,
            ];
        }

        $html = $this->renderPartial('@app/views/export/_tabla_pdf', [
            'titulo' => 'Listado de Clientes',
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return PdfExportHelper::export('Listado_Clientes', $html);
    }

    public function actionPrint()
    {
        $clientes = Cliente::find()->all();

        $headers = ['Número de Documento', 'Nombre', 'Activo'];
        $rows = [];

        foreach ($clientes as $cliente) {
            $rows[] = [
                $cliente->cli_numdocide,
                $cliente->cli_nombre,
                $cliente->cli_estado,
            ];
        }

        return $this->renderPartial('@app/views/export/print_table', [
            'titulo' => 'Listado de Clientes',
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }
}