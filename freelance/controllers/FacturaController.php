<?php

namespace app\controllers;

use Yii;
use Mpdf\Mpdf;
use yii\web\UploadedFile;
use app\models\Consecutivo;
use app\models\Factura;
use app\models\DetalleFactura;
use app\models\CuentasFactura;
use app\models\DatosFactura;
use app\models\Banco;
use app\models\FacturaSearch;
use app\models\Empresa;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use app\components\ExcelExportHelper;
use app\components\PdfExportHelper;
use app\components\UtilitiesHelper;
use app\controllers\BaseController;
use Mpdf\Output\Destination;
use yii\db\Exception;
use yii\db\Transaction;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap5\Modal;
use yii\widgets\ActiveForm;


/**
 * FacturaController implements the CRUD actions for Factura model.
 */
class FacturaController extends BaseController
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
                        'batch-delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Factura models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FacturaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Factura model.
     * @param int $fac_id Factura ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($fac_id)
    {
        $model = $this->findModel($fac_id);

        if ($this->request->get('view') === 'modal') {
            return $this->renderAjax('view', ['model' => $model]);
        }

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new Factura model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Factura();

        // Asegurar que al crear la factura el estado sea siempre 'Sin Pagar'
        $model->fac_estado = Factura::FAC_ESTADO_SIN_PAGAR;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Forzar estado a 'Sin Pagar' incluso si el formulario intentara cambiarlo
                $model->fac_estado = Factura::FAC_ESTADO_SIN_PAGAR;

                // Handle File Upload
                $archivo = UploadedFile::getInstance($model, 'fac_archivo');
                if ($archivo) {
                    $uploadPath = 'uploads/facturas/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    $fileName = uniqid('fac_') . '.' . $archivo->extension;
                    $filePath = $uploadPath . $fileName;
                    if ($archivo->saveAs($filePath)) {
                        $model->fac_archivo = $filePath;
                    }
                }

                // Primero construimos los modelos de detalle y los validamos sin persistir
                $detallesData = Yii::$app->request->post('DetalleFactura', []);
                $detalleModels = [];
                $detalleRowErrors = [];
                foreach ($detallesData as $idx => $d) {
                    $cantidad = isset($d['dtf_cantidad']) ? floatval($d['dtf_cantidad']) : 0.0;
                    $precio = isset($d['dtf_precio']) ? floatval($d['dtf_precio']) : 0.0;

                    // Si la línea está vacía (cantidad y precio 0), ignorarla
                    if ($cantidad == 0 && $precio == 0) {
                        continue;
                    }

                    $det = new DetalleFactura();
                    // No asignamos fac_id aún (se asignará al guardar la factura)
                    $det->cof_id = isset($d['cof_id']) && $d['cof_id'] !== '' ? $d['cof_id'] : null;
                    $det->dtf_descripcion = isset($d['dtf_descripcion']) ? $d['dtf_descripcion'] : '';
                    $det->dtf_cantidad = $cantidad;
                    $det->dtf_precio = $precio;
                    $det->dtf_iva = isset($d['dtf_iva']) ? floatval($d['dtf_iva']) : 0.0;
                    $det->dtf_subtotal = $cantidad * $precio;

                    // Validar sólo los atributos del detalle que provienen del formulario
                    // (no validamos fac_id aquí porque la factura aún no está guardada)
                    if (!$det->validate(['cof_id', 'dtf_descripcion', 'dtf_cantidad', 'dtf_precio', 'dtf_iva'])) {
                        // Guardar errores por fila (por cada atributo)
                        $detalleRowErrors[$idx] = $det->getErrors();
                    }
                    $detalleModels[] = $det;
                }
                if (!empty($detalleRowErrors)) {
                    // Añadir errores agrupados al modelo principal para mostrarlos en errorSummary
                    foreach ($detalleRowErrors as $idx => $errs) {
                        $model->addError('detalles', 'Fila ' . ($idx+1) . ': ' . implode('; ', array_map(function($a){ return is_array($a) ? implode('|', $a) : $a; }, $errs)));
                    }
                    // Renderizar formulario con errores (no guardar) y devolver los datos de detalle y errores por fila
                    $socios = \app\models\Socio::find()->all();
                    $formasDePago = \app\models\FormaDePago::find()->all();
                    // Lista de bancos para seleccionar cuenta de pago
                    $bancos = \app\models\Banco::find()->where(['ban_eliminado' => 0])->all();
                    $bancosMap = ArrayHelper::map($bancos, 'ban_id', function($b){ return $b->ban_nombre . ' - ' . $b->ban_numcuenta; });
                    $selectedBanco = isset($detallesData['cuenta_ban_id']) ? $detallesData['cuenta_ban_id'] : (Yii::$app->request->post('CuentasFactura', [])['ban_id'] ?? null);
                    $renderMethod = $this->request->isAjax ? 'renderAjax' : 'render';
                    return $this->$renderMethod('create', [
                        'model' => $model,
                        'clientes' => [],
                        'socios' => ArrayHelper::map($socios, 'soc_id', 'soc_nombre'),
                        'formasDePago' => ArrayHelper::map($formasDePago, 'fdp_id', 'fdp_nombre'),
                        'detallesData' => $detallesData,
                        'detalleRowErrors' => $detalleRowErrors,
                        'bancos' => $bancosMap,
                        'selectedBanco' => $selectedBanco,
                        'provincias' => \app\models\Provincia::getSpainProvincesList(),
                    ]);
                }

                // Si todo es válido, guardar factura y detalles en transacción
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$model->save()) {
                        throw new \Exception('No se pudo guardar la factura: ' . json_encode($model->getErrors()));
                    }

                    $subtotal = 0.0;
                    $ivaTotal = 0.0;
                    foreach ($detalleModels as $det) {
                        $det->fac_id = $model->fac_id;
                        if (!$det->save(false)) {
                            throw new \Exception('Error al guardar detalle: ' . json_encode($det->getErrors()));
                        }
                        $subtotal += $det->dtf_subtotal;
                        $ivaTotal += $det->dtf_subtotal * ($det->dtf_iva / 100.0);
                    }

                    // Guardar cuentas seleccionadas para la transferencia (checkbox list)
                    $postedCuentas = Yii::$app->request->post('CuentasFactura', []);
                    $banIds = isset($postedCuentas['ban_id']) ? $postedCuentas['ban_id'] : [];
                    
                    // Si se envía un solo valor (no array), convertirlo a array
                    if (!is_array($banIds) && $banIds !== '') {
                        $banIds = [$banIds];
                    }

                    if (is_array($banIds)) {
                        foreach ($banIds as $banId) {
                            if (!empty($banId)) {
                                $cf = new CuentasFactura();
                                $cf->ban_id = $banId;
                                $cf->fac_id = $model->fac_id;
                                if (!$cf->save()) {
                                    throw new \Exception('No se pudo guardar la cuenta de factura: ' . json_encode($cf->getErrors()));
                                }
                            }
                        }
                    }

                    // Guardar datos del emisor (empresa)
                    $empresa = Empresa::find()->one();
                    if ($empresa) {
                        $dataEmisor = new DatosFactura();
                        $dataEmisor->fac_id       = $model->fac_id;
                        $dataEmisor->daf_tipo     = DatosFactura::DAF_TIPO_EMISOR;
                        $dataEmisor->daf_nombre   = $empresa->emp_razon_social;
                        $dataEmisor->daf_direccion= $empresa->emp_direccion;
                        $dataEmisor->daf_cod_postal = $empresa->emp_codpostal;
                        $dataEmisor->daf_poblacion  = $empresa->emp_poblacion;
                        $dataEmisor->tdo_id         = $empresa->tdo_id;
                        $dataEmisor->daf_numdocide  = $empresa->emp_numdocide;
                        $dataEmisor->prv_id         = 29; // Madrid, igual que en Yii1
                        if (!$dataEmisor->save()) {
                            throw new \Exception('Error al guardar datos emisor: ' . json_encode($dataEmisor->getErrors()));
                        }
                    }

                    $postedReceptor = Yii::$app->request->post('DatosReceptor', []);
                    $dataReceptor = new DatosFactura();
                    $dataReceptor->fac_id         = $model->fac_id;
                    $dataReceptor->daf_tipo       = DatosFactura::DAF_TIPO_RECEPTOR;
                    $dataReceptor->daf_nombre     = $postedReceptor['daf_nombre']    ?? '';
                    $dataReceptor->daf_direccion  = $postedReceptor['daf_direccion'] ?? '-';
                    $dataReceptor->daf_cod_postal = $postedReceptor['daf_cod_postal'] ?? '';
                    $dataReceptor->daf_poblacion  = $postedReceptor['daf_poblacion'] ?? null;
                    $dataReceptor->tdo_id         = $postedReceptor['tdo_id']        ?? 1;
                    $dataReceptor->daf_numdocide  = $postedReceptor['daf_numdocide'] ?? '';
                    $dataReceptor->pai_id         = $postedReceptor['pai_id']        ?? null;
                    $dataReceptor->prv_id         = $postedReceptor['prv_id']        ?? null;

                    if (!$dataReceptor->save()) {
                        throw new \Exception('Error al guardar datos receptor: ' . json_encode($dataReceptor->getErrors()));
                    }

                    // Recalcular totales y guardar en la factura
                    $gastos = isset($model->fac_gastos_suplidos) ? floatval($model->fac_gastos_suplidos) : 0.0;
                    $model->fac_subtotal = $subtotal;
                    $model->fac_iva = $ivaTotal;
                    $model->fac_total = $subtotal + $ivaTotal + $gastos;
                    $model->save(false, ['fac_subtotal', 'fac_iva', 'fac_total']);

                    $transaction->commit();

                    // Debug temporal
                    $pdfResult = $this->generateInvoicePdf($model);
                    Yii::error('Resultado generateInvoicePdf: ' . var_export($pdfResult, true), 'pdf');
                    Yii::error('fac_id: ' . $model->fac_id . ' | fac_numero: ' . $model->fac_numero, 'pdf');

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ['success' => true, 'message' => 'Factura creada correctamente.'];
                    }

                    Yii::$app->session->setFlash('success', 'Factura creada.');
                    return $this->redirect(['index']);
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::error($e->getMessage());
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ['success' => false, 'errors' => $e->getMessage()];
                    }
                    
                    // Para solicitudes no-AJAX, añadir el error al modelo para mostrarlo en el formulario
                    $model->addError('fac_numero', 'Error al guardar la factura: ' . $e->getMessage());
                    Yii::$app->session->setFlash('error', 'No se pudo crear la factura. Por favor, revise los errores.');
                }
            }
        } else {
            $model->loadDefaultValues();
            // Asegurar valor por defecto cuando se muestra el formulario
            $model->fac_estado = Factura::FAC_ESTADO_SIN_PAGAR;
            
            // Cargar consecutivo
            $consecutivo = Consecutivo::findOne(['con_serie' => 'F']);
            if ($consecutivo) {
                $consecutivo->con_consecutivo++;
                $model->fac_numero = $consecutivo->con_consecutivo;
                $consecutivo->save();
            }

            // Cargar numero pedido (Serie P) - COMENTADO PARA QUE SALGA VACIO
            /*
            $consecutivoP = Consecutivo::findOne(['con_serie' => Consecutivo::CON_SERIE_P]);
            if (!$consecutivoP) {
                $consecutivoP = new Consecutivo();
                $consecutivoP->con_serie = Consecutivo::CON_SERIE_P;
                $consecutivoP->con_consecutivo = 0; // Initialize
                $consecutivoP->save();
            }
            $consecutivoP->con_consecutivo++;
            $model->fac_numero_pedido = $consecutivoP->con_consecutivo;
            $consecutivoP->save();
            */

            // Cargar fecha actual
            $model->fac_fecha = date('d/m/Y');
        }

        $socios = \app\models\Socio::find()->all();
        $formasDePago = \app\models\FormaDePago::find()->all();
        $paises = \app\models\Pais::find()->where(['pai_eliminado' => 0])->all();
        $provincias = \app\models\Provincia::find()->where(['prv_eliminada' => 0])->all();

        // Bancos disponibles para seleccionar cuenta de transferencia
        $bancos = \app\models\Banco::find()->where(['ban_eliminado' => 0])->all();
        $bancosMap = ArrayHelper::map($bancos, 'ban_id', function($b){ return $b->ban_nombre . ' - ' . $b->ban_numcuenta; });

        $renderMethod = $this->request->isAjax ? 'renderAjax' : 'render';
        return $this->$renderMethod('create', [
            'model' => $model,
            'clientes' => [], // Se cargan por AJAX
            'socios' => ArrayHelper::map($socios, 'soc_id', 'soc_nombre'),
            'formasDePago' => ArrayHelper::map($formasDePago, 'fdp_id', 'fdp_nombre'),
            'bancos' => $bancosMap,
            'selectedBanco' => null,
            'paises' => ArrayHelper::map($paises, 'pai_id', 'pai_nombre'),
            'provincias' => ArrayHelper::map($provincias, 'prv_id', 'prv_nombre'),
        ]);
    }

    public function actionListadoClientes($term = null, $type = 'name') {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (!is_null($term)) {
            $query = new \yii\db\Query();
            $query->from('cliente')
                ->where(['cli_eliminado' => 0])
                ->limit(20);

            if ($type === 'name') {
                $query->andWhere(['like', 'cli_nombre', $term]);
                $query->select(['value' => 'cli_id', 'label' => 'cli_nombre']);
            } elseif ($type === 'doc') {
                $query->andWhere(['like', 'cli_numdocide', $term]);
                $query->select(['value' => 'cli_id', 'label' => new Expression("CONCAT(cli_numdocide, ' - ', cli_nombre)")]);
            }

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out = array_values($data);
        }
        return $out;
    }

    public function actionListadoSocios($term = null) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $term = $term ?? Yii::$app->request->get('term');
        $out = [];
        if ($term !== null && $term !== '') {
            $query = new \yii\db\Query();
            $query->from('socio')
                ->where(['or', 
                    ['like', 'soc_nombre', $term],
                    ['like', 'soc_apellido', $term],
                    ['like', 'soc_numero', $term]
                ])
                ->limit(20);
            
            $command = $query->createCommand();
            $data = $command->queryAll();
            foreach ($data as $soc) {
                $out[] = [
                    'value' => $soc['soc_id'],
                    'label' => $soc['soc_numero'] . ' - ' . $soc['soc_nombre'] . ' ' . ($soc['soc_apellido'] ?? ''),
                ];
            }
        }
        return $out;
    }

    public function actionListadoProvincias($pais_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $provincias = \app\models\Provincia::find()
            ->where(['pai_id' => $pais_id, 'prv_eliminada' => 0])
            ->orderBy('prv_nombre')
            ->all();

        return ArrayHelper::map($provincias, 'prv_id', 'prv_nombre');
    }

    public function actionDatosCliente($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $cliente = \app\models\Cliente::findOne($id);
        if ($cliente) {
            return [
                'nif' => $cliente->cli_numdocide,
                'razon_social' => $cliente->cli_nombre,
                'nombre' => $cliente->cli_nombre,
                'tipo_doc' => $cliente->tdo ? $cliente->tdo->tdo_nombre : '',
                'tipo_doc_id' => $cliente->tdo_id,
                'num_identificacion' => $cliente->cli_numdocide,
                'direccion' => $cliente->cli_direccion,
                'cp' => $cliente->cli_codpostal,
                'provincia' => $cliente->prv ? $cliente->prv->prv_nombre : '',
                'prv_id' => $cliente->prv_id, // Añadido para seleccionar en dropdown
                'poblacion' => $cliente->cli_poblacion,
                'pais' => $cliente->pai ? $cliente->pai->pai_nombre : '',
                'pai_id' => $cliente->pai_id,
                'forma_pago' => $cliente->fdp ? $cliente->fdp->fdp_nombre : '',
                'socio' => $cliente->soc ? $cliente->soc->soc_nombre : '',
            ];
        }
        return [];
    }

    /**
     * Updates an existing Factura model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $fac_id Factura ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($fac_id)
    {
        $model = $this->findModel($fac_id);
        $detalleModels = $model->detalleFacturas;
        $cuentasFactura = $model->cuentasFacturas;
        $cuentasFactura = $model->cuentasFacturas;
        $selectedBanco = []; // Array for multiple selection
        if (!empty($cuentasFactura)) {
            foreach ($cuentasFactura as $cf) {
                $selectedBanco[] = $cf->ban_id;
            }
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                
                // Handle File Upload
                $archivo = UploadedFile::getInstance($model, 'fac_archivo');
                if ($archivo) {
                    $uploadPath = 'uploads/facturas/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    $fileName = uniqid('fac_') . '.' . $archivo->extension;
                    $filePath = $uploadPath . $fileName;
                    if ($archivo->saveAs($filePath)) {
                        $model->fac_archivo = $filePath;
                    }
                } else {
                    // Keep old file if no new file is uploaded
                    $model->fac_archivo = $model->getOldAttribute('fac_archivo');
                }

                // Primero construimos los modelos de detalle y los validamos sin persistir
                $detallesData = Yii::$app->request->post('DetalleFactura', []);
                $detalleModels = [];
                $detalleRowErrors = [];
                foreach ($detallesData as $idx => $d) {
                    $cantidad = isset($d['dtf_cantidad']) ? floatval($d['dtf_cantidad']) : 0.0;
                    $precio = isset($d['dtf_precio']) ? floatval($d['dtf_precio']) : 0.0;

                    // Si la línea está vacía (cantidad y precio 0), ignorarla
                    if ($cantidad == 0 && $precio == 0) {
                        continue;
                    }

                    $det = new DetalleFactura();
                    // No asignamos fac_id aún (se asignará al guardar la factura)
                    $det->cof_id = isset($d['cof_id']) && $d['cof_id'] !== '' ? $d['cof_id'] : null;
                    $det->dtf_descripcion = isset($d['dtf_descripcion']) ? $d['dtf_descripcion'] : '';
                    $det->dtf_cantidad = $cantidad;
                    $det->dtf_precio = $precio;
                    $det->dtf_iva = isset($d['dtf_iva']) ? floatval($d['dtf_iva']) : 0.0;
                    $det->dtf_subtotal = $cantidad * $precio;

                    // Validar sólo los atributos del detalle que provienen del formulario
                    // (no validamos fac_id aquí porque la factura aún no está guardada)
                    if (!$det->validate(['cof_id', 'dtf_descripcion', 'dtf_cantidad', 'dtf_precio', 'dtf_iva'])) {
                        // Guardar errores por fila (por cada atributo)
                        $detalleRowErrors[$idx] = $det->getErrors();
                    }
                    $detalleModels[] = $det;
                }
                if (!empty($detalleRowErrors)) {
                    // Añadir errores agrupados al modelo principal para mostrarlos en errorSummary
                    foreach ($detalleRowErrors as $idx => $errs) {
                        $model->addError('detalles', 'Fila ' . ($idx+1) . ': ' . implode('; ', array_map(function($a){ return is_array($a) ? implode('|', $a) : $a; }, $errs)));
                    }

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ['success' => false, 'errors' => $model->getErrors()];
                    }

                    // Renderizar formulario con errores (no guardar) y devolver los datos de detalle y errores por fila
                    $socios = \app\models\Socio::find()->all();
                    $formasDePago = \app\models\FormaDePago::find()->all();
                    $provincias = \app\models\Provincia::getSpainProvincesList();
                    // Lista de bancos para seleccionar cuenta de pago
                    $bancos = \app\models\Banco::find()->where(['ban_eliminado' => 0])->all();
                    $bancosMap = ArrayHelper::map($bancos, 'ban_id', function($b){ return $b->ban_nombre . ' - ' . $b->ban_numcuenta; });
                    $selectedBanco = isset($detallesData['cuenta_ban_id']) ? $detallesData['cuenta_ban_id'] : (Yii::$app->request->post('CuentasFactura', [])['ban_id'] ?? null);
                    
                    return $this->render('update', [
                        'model' => $model,
                        'clientes' => [],
                        'socios' => ArrayHelper::map($socios, 'soc_id', 'soc_nombre'),
                        'formasDePago' => ArrayHelper::map($formasDePago, 'fdp_id', 'fdp_nombre'),
                        'detallesData' => $detallesData,
                        'detalleRowErrors' => $detalleRowErrors,
                        'bancos' => $bancosMap,
                        'selectedBanco' => $selectedBanco,
                        'provincias' => $provincias,
                    ]);
                }

                // Si todo es válido, calcular totales y guardar factura y detalles en transacción
                $subtotal = 0.0;
                $ivaTotal = 0.0;
                foreach ($detalleModels as $det) {
                    $subtotal += $det->dtf_subtotal;
                    $ivaTotal += $det->dtf_subtotal * ($det->dtf_iva / 100.0);
                }

                $gastos = isset($model->fac_gastos_suplidos) ? floatval($model->fac_gastos_suplidos) : 0.0;
                $model->fac_subtotal = $subtotal;
                $model->fac_iva = $ivaTotal;
                $model->fac_total = $subtotal + $ivaTotal + $gastos;

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$model->save()) {
                        throw new \Exception('No se pudo guardar la factura: ' . json_encode($model->getErrors()));
                    }

                    // Eliminar detalles existentes y guardar los nuevos
                    DetalleFactura::deleteAll(['fac_id' => $model->fac_id]);
                    foreach ($detalleModels as $det) {
                        $det->fac_id = $model->fac_id;
                        if (!$det->save(false)) {
                            throw new \Exception('Error al guardar detalle: ' . json_encode($det->getErrors()));
                        }
                    }

                    // Eliminar cuentas existentes y guardar las nuevas (seleccion multiple)
                    CuentasFactura::deleteAll(['fac_id' => $model->fac_id]);
                    $postedCuentas = Yii::$app->request->post('CuentasFactura', []);
                    $banIds = isset($postedCuentas['ban_id']) ? $postedCuentas['ban_id'] : [];
                    
                    if (!is_array($banIds) && $banIds !== '') {
                        $banIds = [$banIds];
                    }

                    if (is_array($banIds)) {
                        foreach ($banIds as $banId) {
                            if (!empty($banId)) {
                                $cf = new CuentasFactura();
                                $cf->ban_id = $banId;
                                $cf->fac_id = $model->fac_id;
                                if (!$cf->save()) {
                                    throw new \Exception('No se pudo guardar la cuenta de factura: ' . json_encode($cf->getErrors()));
                                }
                            }
                        }
                    }

                    $transaction->commit();

                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ['success' => true, 'message' => 'Factura actualizada correctamente.'];
                    }

                    Yii::$app->session->setFlash('success', 'Factura actualizada.');
                    return $this->redirect(['index']);
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::error($e->getMessage());
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        $model->addError('fac_numero', 'Error al actualizar: ' . $e->getMessage());
                        return ['success' => false, 'errors' => $model->getErrors()];
                    }
                    Yii::$app->session->setFlash('error', 'Error al actualizar la factura: ' . $e->getMessage());
                    return $this->redirect(['index']);
                }
            }
        }

        $socios = \app\models\Socio::find()->all();
        $formasDePago = \app\models\FormaDePago::find()->all();
        $paises = \app\models\Pais::find()->where(['pai_eliminado' => 0])->all();
        $provincias = \app\models\Provincia::find()->where(['prv_eliminada' => 0])->all();
        $bancos = \app\models\Banco::find()->where(['ban_eliminado' => 0])->all();
        $bancosMap = ArrayHelper::map($bancos, 'ban_id', function($b){ return $b->ban_nombre . ' - ' . $b->ban_numcuenta; });

        $detallesData = [];
        foreach ($detalleModels as $det) {
            $detallesData[] = [
                'cof_id' => $det->cof_id,
                'dtf_descripcion' => $det->dtf_descripcion,
                'dtf_cantidad' => $det->dtf_cantidad,
                'dtf_precio' => $det->dtf_precio,
                'dtf_iva' => $det->dtf_iva,
                'dtf_subtotal' => $det->dtf_subtotal,
            ];
        }

        $renderMethod = $this->request->get('view') === 'modal' ? 'renderAjax' : 'render';
        return $this->$renderMethod('update', [
            'model' => $model,
            'clientes' => [], // Se cargan por AJAX
            'socios' => ArrayHelper::map($socios, 'soc_id', 'soc_nombre'),
            'formasDePago' => ArrayHelper::map($formasDePago, 'fdp_id', 'fdp_nombre'),
            'paises' => ArrayHelper::map($paises, 'pai_id', 'pai_nombre'),
            'provincias' => ArrayHelper::map($provincias, 'prv_id', 'prv_nombre'),
            'bancos' => $bancosMap,
            'selectedBanco' => $selectedBanco,
            'detallesData' => $detallesData,
            'detalleRowErrors' => [],
        ]);
    }

    /**
     * Deletes an existing Factura model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $fac_id Factura ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($fac_id)
    {
        $model = $this->findModel($fac_id);
        $model->fac_eliminada = 1;
        $model->save(false, ['fac_eliminada']);

        return $this->redirect(['index']);
    }
    
    public function actionBatchDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ids = Yii::$app->request->post('ids');
        if (empty($ids)) {
            return ['success' => false, 'message' => 'No se han seleccionado facturas.'];
        }

        try {
            $count = 0;
            foreach ($ids as $id) {
                $model = $this->findModel($id);
                if ($model) {
                    $model->fac_eliminada = 1;
                    if ($model->save(false, ['fac_eliminada'])) {
                        $count++;
                    }
                }
            }
            return ['success' => true, 'message' => $count . ' factura(s) eliminada(s) correctamente.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Ocurrió un error al eliminar las facturas.'];
        }
    }

    /**
     * Finds the Factura model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $fac_id Factura ID
     * @return Factura the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($fac_id)
    {
        if (($model = Factura::findOne(['fac_id' => $fac_id, 'fac_eliminada' => 0])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExportExcel()
    {
        $facturas = Factura::find()->where(['fac_eliminada' => 0])->all();

        $headers = ['Número', 'Fecha', 'Cliente', 'Total', 'Estado'];
        $data = [];

        foreach ($facturas as $factura) {
            $data[] = [
                $factura->fac_numero,
                Yii::$app->formatter->asDate($factura->fac_fecha, 'php:d-m-Y'),
                $factura->cli->cli_nombre,
                $factura->fac_total,
                $factura->fac_estado,
            ];
        }

        return ExcelExportHelper::export('Listado_Facturas', $headers, $data);
    }

    public function actionExportPdf()
    {
        $facturas = Factura::find()->where(['fac_eliminada' => 0])->all();
        $headers = ['Número', 'Fecha', 'Cliente', 'Total', 'Estado'];
        $rows = [];

        foreach ($facturas as $factura) {
            $rows[] = [
                $factura->fac_numero,
                Yii::$app->formatter->asDate($factura->fac_fecha, 'php:d-m-Y'),
                $factura->cli->cli_nombre,
                Yii::$app->formatter->asCurrency($factura->fac_total),
                $factura->fac_estado,
            ];
        }

        $html = $this->renderPartial('@app/views/export/_tabla_pdf', [
            'titulo' => 'Listado de Facturas',
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return PdfExportHelper::export('Listado_Facturas', $html);
    }

    /**
    * Muestra el PDF de una factura guardado en el servidor.
    */
    public function actionPrint($fac_id = null)
    {
        if ($fac_id !== null) {
            $model = $this->findModel($fac_id);

            $pdfPath = Yii::getAlias('@webroot') . '/uploads/facturas/F' . $model->fac_numero . '.pdf';

            // Si el PDF no existe (facturas antiguas), generarlo al vuelo
            if (!file_exists($pdfPath)) {
                $pdfPath = $this->generateInvoicePdf($model);
            }

            if (empty($pdfPath) || !file_exists($pdfPath)) {
                throw new \yii\web\NotFoundHttpException('No se pudo generar el PDF de la factura.');
            }

            return Yii::$app->response->sendFile($pdfPath, 'F' . $model->fac_numero . '.pdf', [
                'mimeType' => 'application/pdf',
                'inline'   => true, // se abre en el navegador en lugar de descargarse
            ]);
        }

        $headers = ['Número', 'Fecha', 'Cliente', 'Total', 'Estado'];
        $rows = $this->_getInvoiceListData();

        return $this->renderPartial('@app/views/export/print_table', [
            'titulo' => 'Listado de Facturas',
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }

    public function actionSendEmail($fac_id)
    {
        $model = $this->findModel($fac_id);
        return $this->renderAjax('_send_email_form', [
            'model' => $model,
        ]);
    }

    public function actionDoSendEmail1($fac_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $model = $this->findModel($fac_id);

            $emailTo = Yii::$app->request->post('email_to');
            $emailSubject = Yii::$app->request->post('email_subject');
            $emailBody = Yii::$app->request->post('email_body');

            if (empty($emailTo)) {
                return ['success' => false, 'errors' => ['email_to' => 'El destinatario del correo no puede estar vacío.']];
            }

            // Generate PDF content
            $html = $this->renderPartial('print', ['model' => $model]);
            
            $mpdf = new Mpdf([
                'format' => 'A4',
                'orientation' => 'P',
                'margin_top' => 10,
                'margin_bottom' => 10,
            ]);
            $mpdf->SetTitle('Factura ' . $model->fac_numero);
            $mpdf->WriteHTML($html);
            $pdfContent = $mpdf->Output(null, Destination::STRING_RETURN);

            // Send email
            $mail = Yii::$app->mailer->compose()
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']]) // Use params for sender
                ->setTo($emailTo)
                ->setSubject($emailSubject)
                ->setTextBody($emailBody)
                ->attachContent($pdfContent, ['fileName' => 'Factura_' . $model->fac_numero . '.pdf', 'contentType' => 'application/pdf']);
            
            if ($mail->send()) {
                Yii::$app->session->setFlash('success', 'Correo electrónico enviado a ' . $emailTo . '.');
                return ['success' => true];
            } else {
                Yii::error("Error sending email for invoice " . $model->fac_id);
                return ['success' => false, 'errors' => ['email' => 'Hubo un error al enviar el correo.']];
            }
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            return ['success' => false, 'errors' => ['exception' => 'Ocurrió una excepción: ' . $e->getMessage()]];
        }
    }

    public function actionDoSendEmail($fac_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $model = $this->findModel($fac_id);

            if (empty($model->cli->cli_email)) {
                return ['success' => false, 'errors' => ['email' => 'El cliente no tiene un correo electrónico configurado.']];
            }

            // Generate PDF content
            $html = $this->renderPartial('print', ['model' => $model]);
            
            $mpdf = new Mpdf([
                'format' => 'A4',
                'orientation' => 'P',
                'margin_top' => 10,
                'margin_bottom' => 10,
            ]);
            $mpdf->SetTitle('Factura ' . $model->fac_numero);
            $mpdf->WriteHTML($html);
            $pdfContent = $mpdf->Output(null, Destination::STRING_RETURN);

            // Send email
            $mail = Yii::$app->mailer->compose()
                ->setFrom(['noreply@freelance.com' => 'Freelance App']) // Replace with a valid sender
                ->setTo($model->cli->cli_email)
                ->setSubject('Factura: ' . $model->fac_numero)
                ->setTextBody('Estimado cliente, adjuntamos su factura ' . $model->fac_numero . '.')
                ->attachContent($pdfContent, ['fileName' => 'Factura_' . $model->fac_numero . '.pdf', 'contentType' => 'application/pdf']);
            
            if ($mail->send()) {
                Yii::$app->session->setFlash('success', 'Correo electrónico enviado a ' . $model->cli->cli_email . '.');
                return ['success' => true];
            } else {
                Yii::error("Error sending email for invoice " . $model->fac_id);
                return ['success' => false, 'errors' => ['email' => 'Hubo un error al enviar el correo.']];
            }
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            return ['success' => false, 'errors' => ['exception' => 'Ocurrió una excepción: ' . $e->getMessage()]];
        }
    }

    public function actionMarkAsPaid($fac_id)
    {
        $model = $this->findModel($fac_id);
        $model->fac_estado = Factura::FAC_ESTADO_LIQUIDADA;
        $model->save(false, ['fac_estado']);
        Yii::$app->session->setFlash('success', 'Factura marcada como liquidada.');
        return $this->redirect(['index']);
    }

    public function actionChangeStatus($fac_id)
    {
        $model = $this->findModel($fac_id); // $fac_id is already defined as a parameter

        if ($this->request->isPost && $model->load($this->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->save()) {
                return ['success' => true, 'message' => 'Situación de la factura actualizada.'];
            } else {
                return ['success' => false, 'errors' => $model->getErrors()];
            }
        }

        return $this->renderAjax('_change_status_form', [
            'model' => $model,
            'fac_id' => $fac_id,
        ]);
    }

    public function actionToggleAprobacion($fac_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($fac_id);
        $model->fac_aprobada = $model->fac_aprobada ? 0 : 1;
        if ($model->save(false, ['fac_aprobada'])) {
            return ['success' => true, 'message' => 'Estado de aprobación actualizado.', 'nuevo_estado' => $model->fac_aprobada];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar el estado.'];
        }
    }

    public function actionBillsReport()
    {
        $searchModel  = new FacturaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = false;

        $models = $dataProvider->getModels();

        if (empty($models)) {
            Yii::$app->session->setFlash('information', 'No hay datos de facturas para generar el listado.');
            return $this->redirect(['index']);
        }

        $headers = [
            'No Factura', 'Fecha', 'LETRAS CIF', 'CIF/DNI', 'Cuenta Contable',
            'Cliente', 'Concepto', 'Importe Factura', 'Base Imponible', 'Suplidos',
            '% IVA', 'Cuota IVA', 'Socio Cod', 'Socio Nombre', 'Estado',
            'Situación', 'Fecha Situación',
        ];

        $data = [];
        foreach ($models as $model) {

            if ($model->fac_gastos_suplidos != 0 && $model->fac_iva != 0) {
                $facSubtotal = $model->fac_subtotal - $model->fac_gastos_suplidos;
            } else {
                $facSubtotal = $model->fac_subtotal;
            }

            $ivaPercentage = ($model->fac_iva != 0 && $facSubtotal != 0)
                ? round(($model->fac_iva * 100) / $facSubtotal)
                : 0;

            $data[] = [
                $model->fac_numero,
                UtilitiesHelper::db2dateHour($model->fac_fecha, false),
                $model->cli->cli_docinipais      ?? '',
                $model->cli->cli_numdocide       ?? '',
                $model->cli->cli_cuenta_contable ?? '',
                $model->cli->cli_nombre          ?? '',
                $model->cli->cli_nombre          ?? '',
                $model->fac_total,
                $facSubtotal,
                $model->fac_gastos_suplidos,
                $ivaPercentage,
                $model->fac_iva,
                $model->soc->soc_numero          ?? '',
                trim(($model->soc->soc_nombre    ?? '') . ' ' . ($model->soc->soc_apellido ?? '')),
                $model->fac_estado,
                $model->fac_situacion,
                UtilitiesHelper::db2date($model->fac_fecha_situacion),
            ];
        }

        return ExcelExportHelper::export('Facturas', $headers, $data);
    }

    /**
     * Genera y guarda el PDF de una factura en el servidor.
     * @param Factura $model
     * @return string|null Ruta del archivo generado, o null si falló
     */
    private function generateInvoicePdf(Factura $model): ?string
    {
        try {
            // Datos del emisor (empresa)
            $modelBillDataE = DatosFactura::find()
                ->where(['fac_id' => $model->fac_id, 'daf_tipo' => DatosFactura::DAF_TIPO_EMISOR])
                ->one();

            // Datos del receptor (cliente)
            $modelBillDataR = DatosFactura::find()
                ->where(['fac_id' => $model->fac_id, 'daf_tipo' => DatosFactura::DAF_TIPO_RECEPTOR])
                ->one();

            // Líneas de detalle con su concepto
            $modelsBillDetail = DetalleFactura::find()
                ->with('cof')
                ->where(['fac_id' => $model->fac_id])
                ->all();

            // Cuentas bancarias con datos del banco
            $modelsAccountBill = CuentasFactura::find()
                ->with('ban')
                ->where(['fac_id' => $model->fac_id])
                ->all();

            // Cálculos de IVA
            $baseIva = 0.0;
            $porcIva = [];
            $cuota   = 0.0;
            foreach ($modelsBillDetail as $det) {
                if ($det->dtf_iva > 0) {
                    $baseIva += ($det->dtf_cantidad * $det->dtf_precio);
                    if (!in_array($det->dtf_iva, $porcIva)) {
                        $porcIva[] = $det->dtf_iva;
                    }
                    $cuota += (($det->dtf_cantidad * $det->dtf_precio) * $det->dtf_iva) / 100;
                }
            }

            // Logo
            $logoPath = null;
            if ($model->fac_logo === 'socio' && !empty($model->soc->soc_ficlogo)) {
                $candidate = Yii::getAlias('@webroot') . '/uploads/members/logos/' . $model->soc->soc_ficlogo;
                if (file_exists($candidate)) {
                    $logoPath = $candidate;
                }
            }

            // Renderizar la vista como HTML puro (sin layout)
            $html = $this->renderPartial('format-bill', [
                'model'            => $model,
                'modelBillDataE'   => $modelBillDataE,
                'modelBillDataR'   => $modelBillDataR,
                'modelsBillDetail' => $modelsBillDetail,
                'modelsAccountBill'=> $modelsAccountBill,
                'baseIva'          => $baseIva,
                'porcIva'          => $porcIva,
                'cuota'            => $cuota,
                'logoPath'         => $logoPath,
            ]);

            // Ruta donde se guarda el PDF
            $savePath = Yii::getAlias('@webroot') . '/uploads/bills/F' . $model->fac_numero . '.pdf';

            return \app\components\PdfExportHelper::save(
                'F' . $model->fac_numero,
                $html,
                [
                    'savePath' => $savePath,
                    'title'    => 'Factura ' . $model->fac_numero,
                    'margins'  => ['top' => 15, 'bottom' => 15, 'left' => 10, 'right' => 10],
                ]
            );

        } catch (\Throwable $e) {
            Yii::error('Error generando PDF factura ' . $model->fac_numero . ': ' . $e->getMessage(), 'pdf');
            return null;
        }
    }
}