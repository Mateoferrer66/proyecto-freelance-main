<?php

namespace app\controllers;

use app\models\Usuario;
use app\models\UsuarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use Yii;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;

/**
 * UsuarioController implements the CRUD actions for Usuario model.
 */
class UsuarioController extends Controller
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
                        'toggle-status' => ['POST'],
                        'batch-delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionToggleStatus($usu_id)
    {
        $model = $this->findModel($usu_id);

        if ($model->usu_estado === Usuario::USU_ESTADO_ACTIVO) {
            $model->usu_estado = Usuario::USU_ESTADO_INACTIVO;
        } else {
            $model->usu_estado = Usuario::USU_ESTADO_ACTIVO;
        }

        $model->save(['usu_estado']);

        return $this->redirect(['index']);
    }

    /**
     * Lists all Usuario models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UsuarioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Usuario model.
     * @param int $usu_id Usu ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($usu_id)
    {
        $model = $this->findModel($usu_id);

        if ($this->request->get('view') === 'modal') {
            return $this->renderAjax('view', ['model' => $model]);
        }

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new Usuario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Usuario();

        if ($this->request->isPost) {
            $postData = $this->request->post();

            // Set usu_login from email
            if (isset($postData['Usuario']['usu_email'])) {
                $postData['Usuario']['usu_login'] = $postData['Usuario']['usu_email'];
            }


            if ($model->load($postData) && $model->save()) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['success' => true, 'message' => 'Usuario creado correctamente.'];
                }
                Yii::$app->session->setFlash('success', 'Usuario creado.');
                return $this->redirect(['index']);
            } else {
                Yii::error($model->getErrors());
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['success' => false, 'errors' => $model->getErrors()];
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        $renderMethod = $this->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Usuario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $usu_id Usu ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($usu_id)
    {
        $model = $this->findModel($usu_id);
        $oldPassword = $model->usu_password;

        if ($this->request->isPost && $model->load($this->request->post())) {
            if (empty($model->usu_password)) {
                $model->usu_password = $oldPassword;
            }

            if ($this->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($model->save()) {
                    return ['success' => true, 'message' => 'Usuario actualizado correctamente.'];
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
     * Deletes an existing Usuario model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $usu_id Usu ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($usu_id)
    {
        $this->findModel($usu_id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionBatchDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ids = Yii::$app->request->post('ids');
        if (empty($ids)) {
            return ['success' => false, 'message' => 'No se han seleccionado usuarios.'];
        }

        try {
            $count = Usuario::deleteAll(['in', 'usu_id', $ids]);
            return ['success' => true, 'message' => $count . ' usuario(s) eliminado(s) correctamente.'];
        } catch (\yii\db\Exception $e) {
            // Log the error if needed
            return ['success' => false, 'message' => 'Ocurrió un error al eliminar los usuarios.'];
        }
    }

    /**
     * Finds the Usuario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $usu_id Usu ID
     * @return Usuario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($usu_id)
    {
        if (($model = Usuario::findOne(['usu_id' => $usu_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExportExcel()
    {
        $usuarios = Usuario::find()->all();

        $headers = ['Login', 'Nombre', 'Estado'];
        $data = [];

        foreach ($usuarios as $usuario) {
            $data[] = [
                $usuario->usu_login,
                $usuario->usu_nombre,
                $usuario->usu_estado,
            ];
        }

        return ExcelExportHelper::export('Listado_Usuarios', $headers, $data);
    }

    public function actionExportPdf()
    {
        $usuarios = Usuario::find()->all();
        $headers = ['Login', 'Nombre', 'Estado'];
        $rows = [];

        foreach ($usuarios as $usuario) {
            $rows[] = [
                $usuario->usu_login,
                $usuario->usu_nombre,
                $usuario->usu_estado,
            ];
        }

        $html = $this->renderPartial('@app/views/export/_tabla_pdf', [
            'titulo' => 'Listado de Usuarios',
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return PdfExportHelper::export('Listado_Usuarios', $html);
    }

    public function actionPrint()
    {
        $usuarios = Usuario::find()->all();

        $headers = ['Login', 'Nombre', 'Estado'];
        $rows = [];

        foreach ($usuarios as $usuario) {
            $rows[] = [
                $usuario->usu_login,
                $usuario->usu_nombre,
                $usuario->usu_estado,
            ];
        }

        return $this->renderPartial('@app/views/export/print_table', [
            'titulo' => 'Listado de Usuarios',
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }
}
