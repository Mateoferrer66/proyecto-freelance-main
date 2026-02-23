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
use app\components\UtilitiesHelper;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
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
                    Yii::$app->session->setFlash('success', 'El socio se creó correctamente.');
                    return $this->redirect(['index']);
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    $model->addError('', 'No fué posible crear el socio. Intente Nuevamente.');
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

    public function actionToggleStatus($soc_id)
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
    }

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
}
