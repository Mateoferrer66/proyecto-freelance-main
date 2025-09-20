<?php

namespace app\controllers;

use app\models\Banco;
use app\models\BancoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;

/**
 * BancoController implements the CRUD actions for Banco model.
 */
class BancoController extends Controller
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
     * Lists all Banco models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BancoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Banco model.
     * @param int $ban_id Ban ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($ban_id)
    {
        $model = $this->findModel($ban_id);

        if ($this->request->get('view') === 'modal') {
            return $this->renderAjax('view', ['model' => $model]);
        }

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new Banco model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Banco();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'ban_id' => $model->ban_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Banco model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $ban_id Ban ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($ban_id)
    {
        $model = $this->findModel($ban_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($this->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if ($model->save()) {
                    return ['success' => true, 'message' => 'Banco actualizado correctamente.'];
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
     * Deletes an existing Banco model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $ban_id Ban ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($ban_id)
    {
        $this->findModel($ban_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Banco model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $ban_id Ban ID
     * @return Banco the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($ban_id)
    {
        if (($model = Banco::findOne(['ban_id' => $ban_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionExportExcel()
    {
        $bancos = Banco::find()->all();

        $headers = ['Código', 'Nombre', 'Número de Cuenta', 'Eliminado'];
        $data = [];

        foreach ($bancos as $banco) {
            $data[] = [
                $banco->ban_id,
                $banco->ban_nombre,
                $banco->ban_numcuenta,
                $banco->ban_eliminado ? 'Sí' : 'No',
            ];
        }

        return ExcelExportHelper::export('Listado_Bancos', $headers, $data);
    }

    public function actionExportPdf()
    {
        $bancos = Banco::find()->all();
        $headers = ['Código', 'Nombre', 'Número de Cuenta', 'Eliminado'];
        $rows = [];

        foreach ($bancos as $banco) {
            $rows[] = [
                $banco->ban_id,
                $banco->ban_nombre,
                $banco->ban_numcuenta,
                $banco->ban_eliminado ? 'Sí' : 'No',
            ];
        }

        $html = $this->renderPartial('@app/views/export/_tabla_pdf', [
            'titulo' => 'Listado de Bancos',
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return PdfExportHelper::export('Listado_Bancos', $html);
    }

    public function actionPrint()
    {
        $bancos = Banco::find()->all();

        $headers = ['Código', 'Nombre', 'Número de Cuenta', 'Eliminado'];
        $rows = [];

        foreach ($bancos as $banco) {
            $rows[] = [
                $banco->ban_id,
                $banco->ban_nombre,
                $banco->ban_numcuenta,
                $banco->ban_eliminado ? 'Sí' : 'No',
            ];
        }

        return $this->renderPartial('@app/views/export/print_table', [
            'titulo' => 'Listado de Bancos',
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }
}