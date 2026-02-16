<?php

namespace app\controllers;

use app\models\Portafolio;
use app\models\PortafolioSearch;
use app\controllers\BaseController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * PortafolioController implements the CRUD actions for Portafolio model.
 */
class PortafolioController extends BaseController
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
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Portafolio models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PortafolioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Portafolio model.
     * @param int $por_id Portafolio ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($por_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($por_id),
        ]);
    }

    /**
     * Creates a new Portafolio model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Portafolio();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Handle image uploads
                $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
                
                if ($model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        // Upload images before saving
                        $model->uploadImages();
                        
                        if ($model->save(false)) {
                            $transaction->commit();
                            
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = Response::FORMAT_JSON;
                                return ['success' => true, 'message' => 'Proyecto creado correctamente.'];
                            }
                            
                            Yii::$app->session->setFlash('success', 'Proyecto de portafolio creado exitosamente.');
                            return $this->redirect(['index']);
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        Yii::error($e->getMessage());
                        
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return ['success' => false, 'errors' => $e->getMessage()];
                        }
                        
                        Yii::$app->session->setFlash('error', 'Error al crear el proyecto: ' . $e->getMessage());
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        $socios = \app\models\Socio::find()->all();
        
        $renderMethod = $this->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('create', [
            'model' => $model,
            'socios' => ArrayHelper::map($socios, 'soc_id', 'soc_nombre'),
        ]);
    }

    /**
     * Updates an existing Portafolio model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param int $por_id Portafolio ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($por_id)
    {
        $model = $this->findModel($por_id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Handle image uploads
                $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
                
                if ($model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        // Upload new images if any
                        if ($model->imageFiles) {
                            $model->uploadImages();
                        }
                        
                        if ($model->save(false)) {
                            $transaction->commit();
                            
                            if (Yii::$app->request->isAjax) {
                                Yii::$app->response->format = Response::FORMAT_JSON;
                                return ['success' => true, 'message' => 'Proyecto actualizado correctamente.'];
                            }
                            
                            Yii::$app->session->setFlash('success', 'Proyecto actualizado exitosamente.');
                            return $this->redirect(['index']);
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        Yii::error($e->getMessage());
                        
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return ['success' => false, 'errors' => $e->getMessage()];
                        }
                        
                        Yii::$app->session->setFlash('error', 'Error al actualizar el proyecto: ' . $e->getMessage());
                    }
                }
            }
        }

        $socios = \app\models\Socio::find()->all();
        
        $renderMethod = $this->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('update', [
            'model' => $model,
            'socios' => ArrayHelper::map($socios, 'soc_id', 'soc_nombre'),
        ]);
    }

    /**
     * Deletes an existing Portafolio model (soft delete).
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $por_id Portafolio ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($por_id)
    {
        $model = $this->findModel($por_id);
        $model->por_eliminado = 1;
        $model->save(false, ['por_eliminado']);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => true, 'message' => 'Proyecto eliminado correctamente.'];
        }

        Yii::$app->session->setFlash('success', 'Proyecto eliminado exitosamente.');
        return $this->redirect(['index']);
    }

    /**
     * Delete a specific image from a portfolio project
     * @param int $por_id
     * @param string $image
     * @return Response
     */
    public function actionDeleteImage($por_id, $image)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = $this->findModel($por_id);
        
        if ($model->deleteImage($image)) {
            return ['success' => true, 'message' => 'Imagen eliminada correctamente.'];
        }
        
        return ['success' => false, 'message' => 'Error al eliminar la imagen.'];
    }

    /**
     * AJAX endpoint for socio autocomplete
     * @param string|null $term
     * @return array
     */
    public function actionListadoSocios($term = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        
        if (!is_null($term)) {
            $query = new \yii\db\Query();
            $query->from('socio')
                ->where(['like', 'soc_nombre', $term])
                ->orWhere(['like', 'soc_codigo', $term])
                ->limit(20);
            
            $command = $query->createCommand();
            $data = $command->queryAll();
            
            foreach ($data as $soc) {
                $out[] = [
                    'value' => $soc['soc_id'],
                    'label' => $soc['soc_codigo'] . ' - ' . $soc['soc_nombre'],
                ];
            }
        }
        
        return $out;
    }

    /**
     * Finds the Portafolio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $por_id Portafolio ID
     * @return Portafolio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($por_id)
    {
        if (($model = Portafolio::findOne(['por_id' => $por_id, 'por_eliminado' => 0])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
