<?php

namespace app\controllers;

use app\models\Categoria;
use app\models\CategoriaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;

/**
 * CategoriaController implements the CRUD actions for Categoria model.
 */
class CategoriaController extends Controller
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
     * Lists all Categoria models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CategoriaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Categoria model.
     * @param int $cat_id Cat ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($cat_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($cat_id),
        ]);
    }

    /**
     * Creates a new Categoria model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Categoria();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'cat_id' => $model->cat_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Categoria model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $cat_id Cat ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($cat_id)
    {
        $model = $this->findModel($cat_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'cat_id' => $model->cat_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Categoria model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $cat_id Cat ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($cat_id)
    {
        $this->findModel($cat_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Categoria model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $cat_id Cat ID
     * @return Categoria the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cat_id)
    {
        if (($model = Categoria::findOne(['cat_id' => $cat_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionExportExcel()
    {
        $categorias = Categoria::find()->all();

        $headers = ['Código', 'Nombre', 'Eliminada'];
        $data = [];

        foreach ($categorias as $categoria) {
            $data[] = [
                $categoria->cat_id,
                $categoria->cat_nombre,
                $categoria->cat_eliminada ? 'Sí' : 'No',
            ];
        }

        return ExcelExportHelper::export('Listado_Categorias', $headers, $data);
    }

    public function actionExportPdf()
    {
        $categorias = Categoria::find()->all();
        $headers = ['Código', 'Nombre', 'Eliminada'];
        $rows = [];

        foreach ($categorias as $categoria) {
            $rows[] = [
                $categoria->cat_id,
                $categoria->cat_nombre,
                $categoria->cat_eliminada ? 'Sí' : 'No',
            ];
        }

        $html = $this->renderPartial('@app/views/export/_tabla_pdf', [
            'titulo' => 'Listado de Categorías',
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return PdfExportHelper::export('Listado_Categorias', $html);
    }

    public function actionPrint()
    {
        $categorias = Categoria::find()->all();

        $headers = ['Código', 'Nombre', 'Eliminada'];
        $rows = [];

        foreach ($categorias as $categoria) {
            $rows[] = [
                $categoria->cat_id,
                $categoria->cat_nombre,
                $categoria->cat_eliminada ? 'Sí' : 'No',
            ];
        }

        return $this->renderPartial('@app/views/export/print_table', [
            'titulo' => 'Listado de Categorías',
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }
}
