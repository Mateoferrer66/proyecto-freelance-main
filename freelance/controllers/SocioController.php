<?php

namespace app\controllers;

use app\models\Socio;
use app\models\SocioSearch;
use app\models\SocAltaBaja;
use app\models\Categoria;
use app\models\Provincia;
use app\models\Consecutivo;
use app\models\Participacion;
use app\models\Empresa;
use app\models\SubcuentaSocio;
use app\models\SocCuenta;
use app\components\UtilitiesHelper;
use app\components\ExcelExportHelper;
use app\services\SocioService;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Yii;

class SocioController extends BaseController
{
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

    public function actionIndex()
    {
        $searchModel = new SocioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetMember($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Socio::findOne($id);
        if (!$model) {
            return ['success' => false, 'error' => 'Socio no encontrado'];
        }
        return [
            'success' => true,
            'html' => $this->renderPartial('_view', ['model' => $model])
        ];
    }

    /**
     * Creates a new Socio model
     */
    public function actionCreate()
    {
        $model = new Socio();
        //Si se recibe datos del formulario de crear socio
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                //Se concatenan los apellidos
                $model->soc_apellido = $model->soc_apellido1.' '.$model->soc_apellido2;
                $model->soc_password = 'SocioPrueba123!';
                //Se marca como socio no eliminado
                $model->soc_eliminado = 0;

                //Archivo de Foto//////////////////////////////////////////////////////////////////
                $upFilePhoto = UploadedFile::getInstance($model, 'soc_foto');
                if ($upFilePhoto) {
                    // Nombre sin extensión
                    $fileName = pathinfo($upFilePhoto->name, PATHINFO_FILENAME);
                    // Formatear nombre (slug limpio)
                    $safeName = UtilitiesHelper::formatUrl($fileName, '');
                    // Generar nombre único para evitar colisiones
                    $model->soc_foto = $safeName . '_' . time() . '.' . $upFilePhoto->extension;
                }
                //Archivo de Logo//////////////////////////////////////////////////////////////////

                //Archivo de Logo//////////////////////////////////////////////////////////////////
                $upFileLogo = UploadedFile::getInstance($model, 'soc_ficlogo');
                if ($upFileLogo) {
                    // Nombre sin extensión
                    $fileName = pathinfo($upFileLogo->name, PATHINFO_FILENAME);
                    // Formatear nombre (slug limpio)
                    $safeName = UtilitiesHelper::formatUrl($fileName, '');
                    // Generar nombre único para evitar colisiones
                    $model->soc_ficlogo = $safeName . '_' . time() . '.' . $upFileLogo->extension;
                }
                //Archivo de Logo//////////////////////////////////////////////////////////////////

                //Archivo de Contrato//////////////////////////////////////////////////////////////
                $upFileContract = UploadedFile::getInstance($model, 'soc_ficcontrato');
                if ($upFileContract) {
                    // Nombre sin extensión
                    $fileName = pathinfo($upFileContract->name, PATHINFO_FILENAME);
                    // Formatear nombre (slug limpio)
                    $safeName = UtilitiesHelper::formatUrl($fileName, '');
                    // Generar nombre único para evitar colisiones
                    $model->soc_ficcontrato = $safeName . '_' . time() . '.' . $upFileContract->extension;
                }
                //Archivo de Contrato//////////////////////////////////////////////////////////////

                //Archivo de Documento/////////////////////////////////////////////////////////////
                $upFileDocument = UploadedFile::getInstance($model, 'soc_ficdocide');
                if ($upFileDocument) {
                    // Nombre sin extensión
                    $fileName = pathinfo($upFileDocument->name, PATHINFO_FILENAME);
                    // Formatear nombre (slug limpio)
                    $safeName = UtilitiesHelper::formatUrl($fileName, '');
                    // Generar nombre único para evitar colisiones
                    $model->soc_ficdocide = $safeName . '_' . time() . '.' . $upFileDocument->extension;
                }
                //Archivo de Documento/////////////////////////////////////////////////////////////

                //Archivo de Otros/////////////////////////////////////////////////////////////////
                $upFileOthDocument = UploadedFile::getInstance($model, 'soc_ficotros');
                if ($upFileOthDocument) {
                    // Nombre sin extensión
                    $fileName = pathinfo($upFileOthDocument->name, PATHINFO_FILENAME);
                    // Formatear nombre (slug limpio)
                    $safeName = UtilitiesHelper::formatUrl($fileName, '');
                    // Generar nombre único para evitar colisiones
                    $model->soc_ficotros = $safeName . '_' . time() . '.' . $upFileOthDocument->extension;
                }
                //Archivo de Documento/////////////////////////////////////////////////////////////

                //Archivo de PRL///////////////////////////////////////////////////////////////////
                $upFilePrlDocument = UploadedFile::getInstance($model, 'soc_fiprl');
                if ($upFilePrlDocument) {
                    // Nombre sin extensión
                    $fileName = pathinfo($upFilePrlDocument->name, PATHINFO_FILENAME);
                    // Formatear nombre (slug limpio)
                    $safeName = UtilitiesHelper::formatUrl($fileName, '');
                    // Generar nombre único para evitar colisiones
                    $model->soc_fiprl = $safeName . '_' . time() . '.' . $upFilePrlDocument->extension;
                }
                //Archivo de PRL///////////////////////////////////////////////////////////////////

                //Se asignan pariticipaciones y consecutivo de socio y se guarda registro
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    //Bloquear consecutivo socio///////////////////////////////////////////////////
                    $modelConsecutivoSocio = Consecutivo::find()
                                                ->where(['con_serie' => Consecutivo::CON_SERIE_S])
                                                ->one();
                    if (!$modelConsecutivoSocio) {
                        throw new \Exception('No existe consecutivo de socio.');
                    }
                    ///////////////////////////////////////////////////////////////////////////////

                    //Bloquear participaciones/////////////////////////////////////////////////////
                    $modelParticipacion = Participacion::find()->one(); // siempre hay uno
                    if (!$modelParticipacion) {
                        throw new \Exception('No existe registro de participación.');
                    }
                    ///////////////////////////////////////////////////////////////////////////////

                    //Consultar empresa////////////////////////////////////////////////////////////
                    $modelEmpresa = Empresa::find()
                                        ->where(['emp_id' => 1])
                                        ->one();
                    if (!$modelEmpresa) {
                        throw new \Exception('No existe empresa.');
                    }
                    ///////////////////////////////////////////////////////////////////////////////

                    /* Manejo de numero de socio */
                    if ($model->soc_numero == $model->soc_numero_original) {
                        $model->soc_numero = $modelConsecutivoSocio->con_consecutivo;
                        // Actualizar consecutivo de socio
                        $modelConsecutivoSocio->con_consecutivo = $modelConsecutivoSocio->con_consecutivo + 1;
                        if (!$modelConsecutivoSocio->save(false)) {
                            throw new \Exception('Error actualizando consecutivo de socio.');
                        }
                    } else {
                        // Usuario lo modificó → validar duplicado
                        $memberExists = Socio::find()
                                        ->where(['soc_numero' => $model->soc_numero])
                                        ->andWhere(['soc_eliminado' => 0])
                                        ->exists();
                        if ($memberExists) {
                            $model->addError('soc_numero', 'El número ya existe.');
                            throw new \Exception('Número de socio duplicado.');
                        }
                    }

                    /* Manejo participaciones */
                    $model->soc_participacion_desde = $modelParticipacion->par_numero;
                    $newParticTo = $modelParticipacion->par_numero + $modelEmpresa->emp_participaciones;
                    $model->soc_participacion_hasta = $newParticTo - 1;
                    // Actualizar consecutivo de participación
                    $modelParticipacion->par_numero = $newParticTo;
                    if (!$modelParticipacion->save(false)) {
                         throw new \Exception('Error actualizando participación.');
                    }

                    /* Guardar Socio */
                    if (!$model->save()) {
                        throw new \Exception('Error guardando el socio.');
                    }

                    /* Crear registro de alta de socio */
                    $modelSocAB = new SocAltaBaja();
                    $modelSocAB->soc_id = $model->soc_id;
                    $modelSocAB->usu_id = Yii::$app->user->id;
                    $modelSocAB->sab_accion = SocAltaBaja::SAB_ACCION_ALTA;
                    $modelSocAB->sab_fecha = $model->soc_fecha.' '.date('H:i:s');
                    $modelSocAB->sab_observaciones = 'Creación de socio';
                    if (!$modelSocAB->save()) {
                        throw new \Exception('Error al guardar en el historial el alta de socio.');
                    }

                    //Crear cuentas de socio///////////////////////////////////////////////////////
                    $modelsSubacc = SubcuentaSocio::find()
                                        ->select(['scs_numero', 'scs_descripcion'])
                                        ->orderBy(['scs_id' => SORT_ASC])
                                        ->all();
                    if (!empty($modelsSubacc)) {
                        foreach ($modelsSubacc as $modelSubacc) {
                            $formatSocId = substr(
                                UtilitiesHelper::formatCode($model->soc_numero, 4),
                                -4
                            );
                            $modelSocCuenta = new SocCuenta();
                            $modelSocCuenta->soc_id = $model->soc_id;
                            $modelSocCuenta->scu_cuenta = $modelSubacc->scs_numero . $formatSocId;
                            $modelSocCuenta->scu_descripcion = $modelSubacc->scs_descripcion . ' ' . $model->soc_nombre . ' ' . $model->soc_apellido;
                            $modelSocCuenta->save(false); // equivalente a insert()
                        }
                    }
                    ///////////////////////////////////////////////////////////////////////////////

                    //Guardar archivo de foto//////////////////////////////////////////////////
                    if ($upFilePhoto) {
                        // Ruta física real
                        $photoUploadPath = Yii::getAlias('@webroot/uploads/members/photo/') . $model->soc_foto;
                        // Crear carpeta si no existe
                        if (!is_dir(dirname($photoUploadPath))) {
                            mkdir(dirname($photoUploadPath), 0775, true);
                        }
                        // Guardar archivo
                        if (!$upFilePhoto->saveAs($photoUploadPath)) {
                            $model->addError('soc_foto', 'Error al guardar el archivo de foto del socio.');
                            throw new \Exception('Error al guardar el archivo de foto del socio.');
                        }
                    }
                    ///////////////////////////////////////////////////////////////////////////

                    //Guardar archivo de logo//////////////////////////////////////////////////
                    if ($upFileLogo) {
                        // Ruta física real
                        $logoUploadPath = Yii::getAlias('@webroot/uploads/members/logo/') . $model->soc_ficlogo;
                        // Crear carpeta si no existe
                        if (!is_dir(dirname($logoUploadPath))) {
                            mkdir(dirname($logoUploadPath), 0775, true);
                        }
                        // Guardar archivo
                        if (!$upFileLogo->saveAs($logoUploadPath)) {
                            $model->addError('soc_ficlogo', 'Error al guardar el archivo de logo del socio.');
                            throw new \Exception('Error al guardar el archivo de logo del socio.');
                        }
                    }
                    ///////////////////////////////////////////////////////////////////////////

                    //Guardar archivo de contrato//////////////////////////////////////////////
                    if($upFileContract) {
                        // Ruta física real
                        $contractUploadPath = Yii::getAlias('@webroot/uploads/members/contract/') . $model->soc_ficcontrato;
                        // Crear carpeta si no existe
                        if (!is_dir(dirname($contractUploadPath))) {
                            mkdir(dirname($contractUploadPath), 0775, true);
                        }
                        // Guardar archivo
                        if (!$upFileContract->saveAs($contractUploadPath)) {
                            $model->addError('soc_ficcontrato', 'Error al guardar el archivo de contrato del socio.');
                            throw new \Exception('Error al guardar el archivo de contrato del socio.');
                        } 
                    }
                    ///////////////////////////////////////////////////////////////////////////

                    //Guardar archivo de documento/////////////////////////////////////////////
                    if($upFileDocument) {
                        // Ruta física real
                        $docUploadPath = Yii::getAlias('@webroot/uploads/members/document/') . $model->soc_ficdocide;
                        // Crear carpeta si no existe
                        if (!is_dir(dirname($docUploadPath))) {
                            mkdir(dirname($docUploadPath), 0775, true);
                        }
                        // Guardar archivo
                        if (!$upFileDocument->saveAs($docUploadPath)) {
                            $model->addError('soc_ficdocide', 'Error al guardar el archivo de documento del socio.');
                            throw new \Exception('Error al guardar el archivo de documento del socio.');
                        }
                    }
                    ///////////////////////////////////////////////////////////////////////////

                    //Guardar archivo de documento/////////////////////////////////////////////
                    if($upFileOthDocument) {
                        // Ruta física real
                        $otherUploadPath = Yii::getAlias('@webroot/uploads/members/document/') . $model->soc_ficotros;
                        // Crear carpeta si no existe
                        if (!is_dir(dirname($otherUploadPath))) {
                            mkdir(dirname($otherUploadPath), 0775, true);
                        }
                        // Guardar archivo
                        if (!$upFileOthDocument->saveAs($otherUploadPath)) {
                            $model->addError('soc_ficotros', 'Error al guardar el archivo de otros documentos del socio.');
                            throw new \Exception('Error al guardar el archivo de otros documentos del socio.');
                        }
                    }
                    ///////////////////////////////////////////////////////////////////////////

                    //Guardar archivo de PRL///////////////////////////////////////////////////
                    if($upFilePrlDocument) {
                        // Ruta física real
                        $prlUploadPath = Yii::getAlias('@webroot/uploads/members/document/') . $model->soc_fiprl;
                        // Crear carpeta si no existe
                        if (!is_dir(dirname($prlUploadPath))) {
                            mkdir(dirname($prlUploadPath), 0775, true);
                        }
                        // Guardar archivo
                        if (!$upFilePrlDocument->saveAs($prlUploadPath)) {
                            $model->addError('soc_fiprl', 'Error al guardar el archivo de PRL del socio.');
                            throw new \Exception('Error al guardar el archivo de PRL del socio.');
                        }
                    }
                    ///////////////////////////////////////////////////////////////////////////
                    $transaction->commit();

                    //Generar contrato/////////////////////////////////////////////////////////
                    $pdfPath = SocioService::generateContract($model);
                    ///////////////////////////////////////////////////////////////////////////

                    //Redirecciona al listado de socios
                    Yii::$app->session->setFlash('success', 'El socio se creó correctamente.');
                    return $this->redirect(['index']);
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    $model->addError('', 'No fué posible crear el socio. Intente Nuevamente.' . $e->getMessage());
                }
            }
        } else {
            $model->loadDefaultValues();
            try {
                $modelConsecutivoSocio = Consecutivo::findOne(['con_serie' => Consecutivo::CON_SERIE_S]);
                if ($modelConsecutivoSocio !== null) {
                    $model->soc_numero = $modelConsecutivoSocio->con_consecutivo;
                    // guardamos en hidden el valor original
                    $model->soc_numero_original = $modelConsecutivoSocio->con_consecutivo;
                }
            } catch (\Throwable $e) {
                Yii::error('No se pudo obtener consecutivo por defecto: ' . $e->getMessage());
            }
        }

        $categories = Categoria::getList();
        $provinces = Provincia::getSpainProvincesList();

        $renderMethod = $this->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('create', 
            [
                'model' => $model,
                'categories' => $categories, 
                'provinces' => $provinces
            ]
        );
    }

    /**
     * Update a Socio model
     */
    public function actionUpdate($soc_id)
    {
        $model = $this->findModel($soc_id);

        $currentPhoto = $model->soc_foto;
        $currentLogo = $model->soc_ficlogo;
        $currentContract = $model->soc_ficcontrato;
        $currentDocument = $model->soc_ficdocide;
        $currentOthDocument = $model->soc_ficotros;
        $currentPrlDocument = $model->soc_fiprl;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                //Se concatenan los apellidos
                $model->soc_apellido = $model->soc_apellido1.' '.$model->soc_apellido2;

                //Archivo de Foto//////////////////////////////////////////////////////////////////
                $upFilePhoto = UploadedFile::getInstance($model, 'soc_foto');
                if ($upFilePhoto) {
                    // Nombre sin extensión
                    $fileName = pathinfo($upFilePhoto->name, PATHINFO_FILENAME);
                    // Formatear nombre (slug limpio)
                    $safeName = UtilitiesHelper::formatUrl($fileName, '');
                    // Generar nombre único para evitar colisiones
                    $model->soc_foto = $safeName . '_' . time() . '.' . $upFilePhoto->extension;
                } else {
                    $model->soc_foto = $currentPhoto;
                }
                //Archivo de Foto//////////////////////////////////////////////////////////////////

                //Archivo de Logo//////////////////////////////////////////////////////////////////
                $upFileLogo = UploadedFile::getInstance($model, 'soc_ficlogo');
                if ($upFileLogo) {
                    // Nombre sin extensión
                    $fileName = pathinfo($upFileLogo->name, PATHINFO_FILENAME);
                    // Formatear nombre (slug limpio)
                    $safeName = UtilitiesHelper::formatUrl($fileName, '');
                    // Generar nombre único para evitar colisiones
                    $model->soc_ficlogo = $safeName . '_' . time() . '.' . $upFileLogo->extension;
                } else {
                    $model->soc_ficlogo = $currentLogo;
                }
                //Archivo de Logo//////////////////////////////////////////////////////////////////

                //Archivo de Contrato//////////////////////////////////////////////////////////////
                $upFileContract = UploadedFile::getInstance($model, 'soc_ficcontrato');
                if ($upFileContract) {
                    // Nombre sin extensión
                    $fileName = pathinfo($upFileContract->name, PATHINFO_FILENAME);
                    // Formatear nombre (slug limpio)
                    $safeName = UtilitiesHelper::formatUrl($fileName, '');
                    // Generar nombre único para evitar colisiones
                    $model->soc_ficcontrato = $safeName . '_' . time() . '.' . $upFileContract->extension;
                } else {
                    $model->soc_ficcontrato = $currentContract;
                }
                //Archivo de Contrato//////////////////////////////////////////////////////////////

                //Archivo de Documento/////////////////////////////////////////////////////////////
                $upFileDocument = UploadedFile::getInstance($model, 'soc_ficdocide');
                if ($upFileDocument) {
                    // Nombre sin extensión
                    $fileName = pathinfo($upFileDocument->name, PATHINFO_FILENAME);
                    // Formatear nombre (slug limpio)
                    $safeName = UtilitiesHelper::formatUrl($fileName, '');
                    // Generar nombre único para evitar colisiones
                    $model->soc_ficdocide = $safeName . '_' . time() . '.' . $upFileDocument->extension;
                } else {
                    $model->soc_ficdocide = $currentDocument;
                }
                //Archivo de Documento/////////////////////////////////////////////////////////////

                //Archivo de Otros/////////////////////////////////////////////////////////////////
                $upFileOthDocument = UploadedFile::getInstance($model, 'soc_ficotros');
                if ($upFileOthDocument) {
                    // Nombre sin extensión
                    $fileName = pathinfo($upFileOthDocument->name, PATHINFO_FILENAME);
                    // Formatear nombre (slug limpio)
                    $safeName = UtilitiesHelper::formatUrl($fileName, '');
                    // Generar nombre único para evitar colisiones
                    $model->soc_ficotros = $safeName . '_' . time() . '.' . $upFileOthDocument->extension;
                } else {
                    $model->soc_ficotros = $currentOthDocument;
                }
                //Archivo de Documento/////////////////////////////////////////////////////////////

                //Archivo de PRL///////////////////////////////////////////////////////////////////
                $upFilePrlDocument = UploadedFile::getInstance($model, 'soc_fiprl');
                if ($upFilePrlDocument) {
                    // Nombre sin extensión
                    $fileName = pathinfo($upFilePrlDocument->name, PATHINFO_FILENAME);
                    // Formatear nombre (slug limpio)
                    $safeName = UtilitiesHelper::formatUrl($fileName, '');
                    // Generar nombre único para evitar colisiones
                    $model->soc_fiprl = $safeName . '_' . time() . '.' . $upFilePrlDocument->extension;
                } else {
                    $model->soc_fiprl = $currentPrlDocument;
                }
                //Archivo de PRL///////////////////////////////////////////////////////////////////

                //Se asignan pariticipaciones y consecutivo de socio y se guarda registro
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    /* Manejo de numero de socio */
                    if ($model->soc_numero != $model->soc_numero_original) {
                        // Usuario lo modificó → validar duplicado
                        $memberExists = Socio::find()
                                        ->where(['soc_numero' => $model->soc_numero])
                                        ->andWhere(['<>', 'soc_id', $model->soc_id])
                                        ->andWhere(['soc_eliminado' => 0])
                                        ->exists();
                        if ($memberExists) {
                            $model->addError('soc_numero', 'El número ya existe.');
                            throw new \Exception('Número de socio duplicado.');
                        }
                    }

                    /* Guardar Socio */
                    if (!$model->save()) {
                        throw new \Exception('Error guardando el socio.');
                    }

                    /* Crear cuentas de socio */

                    //Guardar archivo de foto//////////////////////////////////////////////////
                    if ($upFilePhoto) {
                        // Ruta física real
                        $photoUploadPath = Yii::getAlias('@webroot/uploads/members/photo/') . $model->soc_foto;
                        // Crear carpeta si no existe
                        if (!is_dir(dirname($photoUploadPath))) {
                            mkdir(dirname($photoUploadPath), 0775, true);
                        }
                        // Guardar archivo
                        if (!$upFilePhoto->saveAs($photoUploadPath)) {
                            $model->addError('soc_foto', 'Error al guardar el archivo de foto del socio.');
                            throw new \Exception('Error al guardar el archivo de foto del socio.');
                        }
                    }
                    ///////////////////////////////////////////////////////////////////////////

                    //Guardar archivo de logo//////////////////////////////////////////////////
                    if ($upFileLogo) {
                        // Ruta física real
                        $logoUploadPath = Yii::getAlias('@webroot/uploads/members/logo/') . $model->soc_ficlogo;
                        // Crear carpeta si no existe
                        if (!is_dir(dirname($logoUploadPath))) {
                            mkdir(dirname($logoUploadPath), 0775, true);
                        }
                        // Guardar archivo
                        if (!$upFileLogo->saveAs($logoUploadPath)) {
                            $model->addError('soc_ficlogo', 'Error al guardar el archivo de logo del socio.');
                            throw new \Exception('Error al guardar el archivo de logo del socio.');
                        }
                    }
                    ///////////////////////////////////////////////////////////////////////////

                    //Guardar archivo de contrato//////////////////////////////////////////////
                    if($upFileContract) {
                        // Ruta física real
                        $contractUploadPath = Yii::getAlias('@webroot/uploads/members/contract/') . $model->soc_ficcontrato;
                        // Crear carpeta si no existe
                        if (!is_dir(dirname($contractUploadPath))) {
                            mkdir(dirname($contractUploadPath), 0775, true);
                        }
                        // Guardar archivo
                        if (!$upFileContract->saveAs($contractUploadPath)) {
                            $model->addError('soc_ficcontrato', 'Error al guardar el archivo de contrato del socio.');
                            throw new \Exception('Error al guardar el archivo de contrato del socio.');
                        } 
                    }
                    ///////////////////////////////////////////////////////////////////////////

                    //Guardar archivo de documento/////////////////////////////////////////////
                    if($upFileDocument) {
                        // Ruta física real
                        $docUploadPath = Yii::getAlias('@webroot/uploads/members/document/') . $model->soc_ficdocide;
                        // Crear carpeta si no existe
                        if (!is_dir(dirname($docUploadPath))) {
                            mkdir(dirname($docUploadPath), 0775, true);
                        }
                        // Guardar archivo
                        if (!$upFileDocument->saveAs($docUploadPath)) {
                            $model->addError('soc_ficdocide', 'Error al guardar el archivo de documento del socio.');
                            throw new \Exception('Error al guardar el archivo de documento del socio.');
                        }
                    }
                    ///////////////////////////////////////////////////////////////////////////

                    //Guardar archivo de documento/////////////////////////////////////////////
                    if($upFileOthDocument) {
                        // Ruta física real
                        $otherUploadPath = Yii::getAlias('@webroot/uploads/members/document/') . $model->soc_ficotros;
                        // Crear carpeta si no existe
                        if (!is_dir(dirname($otherUploadPath))) {
                            mkdir(dirname($otherUploadPath), 0775, true);
                        }
                        // Guardar archivo
                        if (!$upFileOthDocument->saveAs($otherUploadPath)) {
                            $model->addError('soc_ficotros', 'Error al guardar el archivo de otros documentos del socio.');
                            throw new \Exception('Error al guardar el archivo de otros documentos del socio.');
                        }
                    }
                    ///////////////////////////////////////////////////////////////////////////

                    //Guardar archivo de PRL///////////////////////////////////////////////////
                    if($upFilePrlDocument) {
                        // Ruta física real
                        $prlUploadPath = Yii::getAlias('@webroot/uploads/members/document/') . $model->soc_fiprl;
                        // Crear carpeta si no existe
                        if (!is_dir(dirname($prlUploadPath))) {
                            mkdir(dirname($prlUploadPath), 0775, true);
                        }
                        // Guardar archivo
                        if (!$upFilePrlDocument->saveAs($prlUploadPath)) {
                            $model->addError('soc_fiprl', 'Error al guardar el archivo de PRL del socio.');
                            throw new \Exception('Error al guardar el archivo de PRL del socio.');
                        }
                    }
                    ///////////////////////////////////////////////////////////////////////////
                    $transaction->commit();
                    //Redirecciona al listado de socios
                    Yii::$app->session->setFlash('success', 'El socio se actualizó correctamente.');
                    return $this->redirect(['index']);
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    $model->addError('', 'No fué posible actualizar el socio. Intente Nuevamente.');
                }
            }
        } else {
            // guardamos en hidden el valor original
            $model->soc_numero_original = $model->soc_numero;
        }

        $categories = Categoria::getList();
        $provinces = Provincia::getSpainProvincesList();

        $renderMethod = $this->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('update', 
            [
                'model' => $model,
                'categories' => $categories, 
                'provinces' => $provinces
            ]
        );
    }

    public function actionToggleStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;

        $socId = $request->post('soc_id');
        $dateRequest = $request->post('sab_fecha');
        $comments = $request->post('sab_observaciones');

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $socio = Socio::findOne($socId);
            if (!$socio) {
                throw new \Exception('Socio no encontrado');
            }

            // Determinar acción según estado actual
            $newStatus = $socio->soc_estado === SocAltaBaja::SAB_ACCION_ACTIVO 
                ? SocAltaBaja::SAB_ACCION_INACTIVO 
                : SocAltaBaja::SAB_ACCION_ACTIVO;

            $action = $socio->soc_estado === SocAltaBaja::SAB_ACCION_ACTIVO 
                ? SocAltaBaja::SAB_ACCION_BAJA 
                : SocAltaBaja::SAB_ACCION_ALTA;

            // Crear registro en soc_alta_baja
            $altaBaja = new SocAltaBaja();
            $altaBaja->soc_id = $socId;
            $altaBaja->usu_id = Yii::$app->user->id;
            $altaBaja->sab_accion = $action;
            $altaBaja->sab_fecha = \DateTime::createFromFormat('d/m/Y', $dateRequest)->format('Y-m-d');
            $altaBaja->sab_observaciones = $comments;

            if (!$altaBaja->save()) {
                throw new \Exception(json_encode($altaBaja->errors));
            }

            // Cambiar estado del socio
            $socio->soc_estado = $newStatus;
            if (!$socio->save(false)) {
                throw new \Exception(json_encode($socio->errors));
            }

            $transaction->commit();
            return ['success' => true, 'nuevo_estado' => $newStatus];

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error('Error cambiar estado socio: ' . $e->getMessage(), __METHOD__);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /*public function actionToggleStatus($soc_id)
    {
        $model = $this->findModel($soc_id);

        $modelSocAltaBaja = new SocAltaBaja();
        $modelSocAltaBaja->soc_id = $soc_id;
        $modelSocAltaBaja->usu_id = Yii::$app->user->id;
        $modelSocAltaBaja->sab_fecha = date('Y-m-d H:i:s');

        if ($model->soc_estado === Socio::SOC_ESTADO_ACTIVO) {
            $model->soc_estado = Socio::SOC_ESTADO_INACTIVO;
            $modelSocAltaBaja->sab_accion = SocAltaBaja::SAB_ACCION_INACTIVO;
            $modelSocAltaBaja->sab_observaciones = 'Socio inactivado';
        } else {
            $model->soc_estado = Socio::SOC_ESTADO_ACTIVO;
            $modelSocAltaBaja->sab_accion = SocAltaBaja::SAB_ACCION_ACTIVO;
            $modelSocAltaBaja->sab_observaciones = 'Socio activado';
        }

        if ($model->save(['soc_estado'])) {
            $modelSocAltaBaja->save();
            Yii::$app->session->setFlash('success', $modelSocAltaBaja->sab_observaciones.' correctamente.');
        } else {
            Yii::$app->session->setFlash('danger', 'No se pudo cambiar el estado del socio.');
        }

        return $this->redirect(['index']);
    }*/

    /**
     * Delete a Socio model
     */
    public function actionDelete($soc_id)
    {
        $model = $this->findModel($soc_id);
        $model->soc_eliminado = 1;
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->save(false)) {
                $alta_baja = new SocAltaBaja();
                $alta_baja->soc_id = $soc_id;
                $alta_baja->usu_id = Yii::$app->user->id;
                $alta_baja->sab_accion = SocAltaBaja::SAB_ACCION_BAJA;
                $alta_baja->sab_fecha = date('Y-m-d H:i:s');
                $alta_baja->sab_observaciones = 'Eliminación de socio';
                if (!$alta_baja->save()) {
                    throw new \Exception('Error al guardar en el historial.');
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Socio eliminado correctamente.');
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', 'No se pudo eliminar al socio.');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionBatchDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ids = Yii::$app->request->post('ids');
        if (empty($ids)) {
            return ['success' => false, 'message' => 'No se han seleccionado socios.'];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $count = Socio::updateAll(['soc_eliminado' => 1], ['in', 'soc_id', $ids]);

            $alta_baja_records = [];
            foreach ($ids as $id) {
                $alta_baja_records[] = [
                    $id,
                    Yii::$app->user->id,
                    SocAltaBaja::SAB_ACCION_BAJA,
                    date('Y-m-d H:i:s'),
                    'Socio eliminado en lote'
                ];
            }
            Yii::$app->db->createCommand()->batchInsert(SocAltaBaja::tableName(), ['soc_id', 'usu_id', 'sab_accion', 'sab_fecha', 'sab_observaciones'], $alta_baja_records)->execute();
            
            $transaction->commit();

            return ['success' => true, 'message' => $count . ' socio(s) eliminado(s) correctamente.'];
        } catch (\yii\db\Exception $e) {
            $transaction->rollBack();
            // Log the error if needed
            return ['success' => false, 'message' => 'Ocurrió un error al eliminar los socios.'];
        }
    }

    /**
     * Creates a new Socio model via AJAX.
     * If creation is successful, returns JSON with success=true and the new ID/Name.
     * If creation fails, returns JSON with success=false and validation errors.
     */
    public function actionCreateAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Socio();

        if ($model->load(Yii::$app->request->post())) {
            // Force some defaults if not present
            if (empty($model->soc_password)) {
                $model->soc_password = Yii::$app->security->generateRandomString(8); // Temporary password if required
            }
            
            if ($model->save()) {
                return [
                    'success' => true,
                    'id' => $model->soc_id,
                    'nombre' => $model->soc_nombre . ' ' . $model->soc_apellido,
                ];
            } else {
                return [
                    'success' => false,
                    'errors' => $model->getErrors(),
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'No data received',
        ];
    }

    protected function findModel($soc_id)
    {
        if (($model = Socio::findOne(['soc_id' => $soc_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionContract($id)
    {
        $model = $this->findModel($id);

        $pdfPath = Yii::getAlias("@app/web/uploads/members/contract/ContratoFreelanceSocio{$model->id}.pdf");

        // Si no existe lo genera
        if (!file_exists($pdfPath)) {
            $pdfPath = SocioService::generateContract($model);
        }

        return Yii::$app->response->sendFile($pdfPath, "contrato_{$model->id}.pdf", [
            'mimeType' => 'application/pdf',
            'inline'   => true,  // true = mostrar en el navegador, false = descargar
        ]);
    }

    public function actionSendEmail()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;

        $memberNumber = $request->post('memberNumber');
        $recipient = $request->post('recipient');
        $subject = $request->post('subject');
        $message = $request->post('message');
        $cc = $request->post('cc');
        $attachment = UploadedFile::getInstanceByName('attachment');

        try {
            $mail = Yii::$app->mailer->compose()
                ->setFrom('freelancedevelop25@gmail.com')
                ->setTo($recipient)
                ->setSubject($subject)
                ->setHtmlBody($message);

            if (!empty($cc)) {
                $ccList = array_map('trim', explode(',', $cc));
                $mail->setCc($ccList);
            }

            if ($attachment) {
                $path = Yii::getAlias('@runtime') . '/' . uniqid() . '.' . $attachment->extension;
                $attachment->saveAs($path);
                $mail->attach($path, ['fileName' => $attachment->name]);
            }

            // Adjunto por defecto siempre incluido
            $defaultAttachment = Yii::getAlias('@app/web/uploads/members/contract/ContratoFreelanceSocio'.$memberNumber.'.pdf');
            if (file_exists($defaultAttachment)) {
                $mail->attach($defaultAttachment, ['fileName' => 'ContratoFreelanceSocio'.$memberNumber.'.pdf']);
            }

            if ($mail->send()) {
                // Limpiar archivo temporal si hubo adjunto
                if ($attachment && isset($path)) {
                    @unlink($path);
                }
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => 'No se pudo enviar el email'];
            }

        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
            Yii::error('SMTP Error: ' . $e->getMessage(), __METHOD__);
            return ['success' => false, 'error' => $e->getMessage()];
        } catch (\Exception $e) {
            Yii::error('General Error: ' . $e->getMessage(), __METHOD__);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function actionGetEmailTemplate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $socnumero = Yii::$app->request->post('socnumero');
    
        $html = $this->renderPartial('@app/mail/templates/socio-bienvenida', [
            'socnumero' => $socnumero,
        ]);
    
        return ['success' => true, 'html' => $html];
    }

    /**
    * Genera Excel de informe de socios
    *
    * @author j.r.r.
    */
    public function actionMembersReport()
    {
        $modelsMember = Socio::find()
            ->select([
                'soc_id', 'cat_id', 'soc_numero', 'soc_fecha', 'soc_nombre',
                'soc_apellido', 'soc_apellido1', 'soc_apellido2', 'tdo_id',
                'soc_numdocide', 'soc_feccaddoc', 'soc_ocupacion', 'soc_fecnacimiento',
                'soc_sexo', 'soc_telfijo', 'soc_telmovil', 'soc_direccion', 'prv_id',
                'soc_poblacion', 'soc_codpostal', 'soc_email', 'soc_web',
                'soc_numsegsocial', 'soc_grcotsegsocial', 'soc_coefcotizacion',
                'soc_basecotizacion', 'soc_ctabancaria', 'soc_porcretirpf',
                'soc_observaciones', 'soc_participacion_desde', 'soc_participacion_hasta',
                'soc_pago_participacion', 'soc_estado', 'soc_deuda',
            ])
            ->where(['soc_eliminado' => 0])
            ->orderBy(['soc_id' => SORT_ASC])
            ->all();

        if (empty($modelsMember)) {
            Yii::$app->session->setFlash('information', 'No hay datos de socios para generar el informe.');
            return $this->redirect(['index']);
        }

        $headers = [
            'Código', 'Fecha de alta', 'Nombre', 'Apellidos',
            'Primer Apellido', 'Segundo Apellido', 'Sexo',
            'Tipo documento', 'Número documento', 'Fecha caducidad',
            'Categoría', 'Ocupación', 'Fecha nacimiento',
            'Teléfono fijo', 'Móvil', 'Domicilio', 'Provincia',
            'Población', 'Código postal', 'Email', 'Web',
            'Número seguridad social', 'Grupo cotización seguridad social',
            'Coeficiente de cotización', 'Base de cotización',
            'Cuenta bancaria', 'Porcentaje IRPF',
            'Participaciones (desde)', 'Participaciones (hasta)',
            'Estado', 'Observaciones', 'Deuda', 'Cotejo pago participación',
        ];

        $data = [];
        foreach ($modelsMember as $model) {
            $data[] = [
                $model->soc_numero,
                UtilitiesHelper::db2date($model->soc_fecha),
                $model->soc_nombre,
                $model->soc_apellido,
                $model->soc_apellido1,
                $model->soc_apellido2,
                $model->soc_sexo,
                $model->relSocTdo->tdo_nombre ?? '',
                $model->soc_numdocide,
                $model->soc_feccaddoc !== '' ? UtilitiesHelper::db2date($model->soc_feccaddoc) : '',
                $model->relSocCat->cat_nombre ?? '',
                $model->soc_ocupacion,
                UtilitiesHelper::db2date($model->soc_fecnacimiento),
                $model->soc_telfijo,
                $model->soc_telmovil,
                $model->soc_direccion,
                $model->relSocPrv->prv_nombre ?? '',
                $model->soc_poblacion,
                $model->soc_codpostal,
                $model->soc_email,
                $model->soc_web,
                $model->soc_numsegsocial,
                $model->soc_grcotsegsocial,
                $model->soc_coefcotizacion,
                $model->soc_basecotizacion,
                $model->soc_ctabancaria,
                $model->soc_porcretirpf,
                $model->soc_participacion_desde,
                $model->soc_participacion_hasta,
                $model->soc_estado,
                $model->soc_observaciones,
                $model->soc_deuda,
                $model->soc_pago_participacion,
            ];
        }

        return ExcelExportHelper::export('InformeSocios', $headers, $data);
    }

    /**
     * Genera Excel de informe de altas y bajas de socios
     *
     * @author j.r.r.
     */
    public function actionMembersStatusReport()
    {
        $modelsMember = Socio::find()
            ->select([
                's.soc_id', 's.soc_numero', 's.soc_nombre', 's.soc_apellido',
                'sab.sab_fecha', 'sab.sab_accion', 'sab.sab_observaciones',
            ])
            ->from('socio s')
            ->innerJoin('soc_alta_baja sab', 's.soc_id = sab.soc_id')
            ->where(['s.soc_eliminado' => 0])
            ->orderBy(['s.soc_id' => SORT_ASC])
            ->asArray()
            ->all();

        if (empty($modelsMember)) {
            Yii::$app->session->setFlash('information', 'No hay datos de socios para generar el informe.');
            return $this->redirect(['index']);
        }

        $headers = [
            'Código', 'Nombre', 'Apellidos', 'Fecha', 'Estado', 'Observaciones',
        ];

        $data = [];
        foreach ($modelsMember as $model) {
            $data[] = [
                $model['soc_numero'],
                $model['soc_nombre'],
                $model['soc_apellido'],
                UtilitiesHelper::db2dateHour($model['sab_fecha']),
                $model['sab_accion'],
                $model['sab_observaciones'],
            ];
        }

        return ExcelExportHelper::export('InformeSociosAltasBajas', $headers, $data);
    }
}