<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Presupuesto;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Presupuesto $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $socios */
/** @var array $formasDePago */
/** @var array $detallesData */
/** @var array $detalleRowErrors */
/** @var array $paises */
/** @var array $provincias */

$this->title = 'ACTUALIZAR PRESUPUESTO: ' . $model->pre_numero;
$this->params['breadcrumbs'][] = ['label' => 'Presupuestos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pre_numero, 'url' => ['view', 'pre_id' => $model->pre_id]];
$this->params['breadcrumbs'][] = 'Actualizar';

// CSS y JS para jQuery UI (Autocomplete)
$this->registerCssFile("https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("https://code.jquery.com/ui/1.13.2/jquery-ui.js", ['depends' => [\yii\web\JqueryAsset::class]]);

// Select2 para selects buscables en los conceptos
$this->registerCssFile("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);


// URLs para AJAX
$urlListado = Url::to(['presupuesto/listado-clientes']);
$urlDatos = Url::to(['presupuesto/datos-cliente']);
$urlListadoSocios = Url::to(['presupuesto/listado-socios']);
$urlListadoProvincias = Url::to(['presupuesto/listado-provincias']);

// Cargamos los conceptos disponibles para autocompletar/llenar filas
$conceptos = \app\models\ConceptoFacturacion::find()->with('iva')->all();
$conceptosJs = json_encode(array_map(function($c){
    return [
        'id' => $c->cof_id,
        'nombre' => $c->cof_nombre,
        'iva' => $c->iva ? floatval($c->iva->iva_porcentaje) : 0,
    ];
}, $conceptos));

$detallesDataJs = json_encode($detallesData);

// Convertir heredoc en nowdoc
$js = <<<'JS'
$(function(){
    // --- Inicialización del Datepicker ---
    $('#presupuesto-pre_fecha').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        time: false,
        lang: 'es',
        weekStart: 1
    });

    function setupAutocomplete(elementId, searchType) {
        $("#" + elementId).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: '__URL_LISTADO__',
                    dataType: "json",
                    data: {
                        term: request.term,
                        type: searchType
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                // Cuando se selecciona un item
                $("#presupuesto-cli_id").val(ui.item.value).trigger('change'); // Asigna el ID y dispara el change
                
                // Rellenar el campo de búsqueda actual con la selección
                if (searchType === 'name') {
                    $("#search-by-name").val(ui.item.label);
                } else if (searchType === 'doc') {
                    // El label puede ser "DOC - NOMBRE", extraemos solo el doc
                    let doc = ui.item.label.split(' - ')[0];
                    $("#search-by-doc").val(doc);
                }

                return false; // Prevenir que el valor del ID se ponga en el input
            }
        });
    }

    setupAutocomplete('search-by-name', 'name');
    setupAutocomplete('search-by-doc', 'doc');

    // --- Autocomplete para Socio ---
    $("#search-socio").autocomplete({
        source: '__URL_LISTADO_SOCIOS__',
        minLength: 0,
        select: function(event, ui) {
            $("#presupuesto-soc_id").val(ui.item.value);
            $(this).val(ui.item.label);
            return false;
        }
    }).focus(function(){ 
        $(this).autocomplete("search");
    });

    // --- Dynamic Provinces ---
    function loadProvincias(paisId, selectedProvId = null) {
        if (!paisId) {
            $('#cliente-provincia').html('<option value="">Seleccione Provincia</option>');
            return;
        }
        $.ajax({
            url: '__URL_LISTADO_PROVINCIAS__',
            data: {pais_id: paisId},
            success: function(data) {
                var $select = $('#cliente-provincia');
                $select.html('<option value="">Seleccione Provincia</option>');
                $.each(data, function(id, name) {
                    var selected = (selectedProvId && selectedProvId == id) ? 'selected' : '';
                    $select.append('<option value="' + id + '" ' + selected + '>' + name + '</option>');
                });
            }
        });
    }

    $('#cliente-pais').on('change', function() {
        loadProvincias($(this).val());
    });


    // --- Cargar datos del cliente ---
    $('#presupuesto-cli_id').on('change', function(){
        var clienteId = $(this).val();
        if (clienteId) {
            $.ajax({
                url: '__URL_DATOS__',
                type: 'GET',
                data: {id: clienteId},
                dataType: 'json',
                success: function(data){
                    if(data){
                        $('#cliente-nif').val(data.nif);
                        $('#cliente-razon_social').val(data.razon_social);
                        $('#cliente-nombre').val(data.nombre);
                        $('#cliente-tipo_doc').val(data.tipo_doc);
                        $('#cliente-num_identificacion').val(data.num_identificacion);
                        $('#cliente-direccion').val(data.direccion);
                        $('#cliente-cp').val(data.cp);
                        $('#cliente-poblacion').val(data.poblacion);
                        $('#cliente-forma_pago').val(data.forma_pago);
                        
                        // Dropdowns dinámicos
                        $('#cliente-pais').val(data.pai_id).trigger('change');
                        loadProvincias(data.pai_id, data.prv_id);

                        // Socio autocomplete
                        if(data.socio) {
                            $('#search-socio').val(data.socio);
                        }
                        
                        // Actualizar ambos campos de búsqueda para mantener consistencia
                        $('#search-by-name').val(data.nombre);
                        $('#search-by-doc').val(data.nif);
                    }
                },
                error: function(){
                    // Limpiar campos si hay error
                    $('#datos-cliente input, #search-by-name, #search-by-doc').val('');
                }
            });
        } else {
            // Limpiar campos si no se selecciona cliente
            $('#datos-cliente input, #search-by-name, #search-by-doc').val('');
        }
    });

    // --- Añadir Conceptos dinámicos ---
    var conceptos = __CONCEPTOS_JS__;
    var rowIndex = 0;

    // Populate the concept selector in the "New Concept Section"
    var $newConceptSelect = $('#new-concept-select');
    conceptos.forEach(function(c){
        $newConceptSelect.append('<option value="'+c.id+'" data-iva="'+c.iva+'">'+c.nombre+'</option>');
    });

    // Handle initial row removal functionality for existing rows if any
    $(document).on('click', '.btn-remove', function(){
        $(this).closest('tr').remove();
        recalculateTotals();
    });

    // Handle "Añadir Concepto" button from the modular section
    $('#btn-add-concept-row').on('click', function(){
        var conceptId = $('#new-concept-select').val();
        var desc = $('#new-desc').val();
        var iva = $('#new-iva').val();
        var cant = $('#new-cant').val();
        var precio = $('#new-precio').val();

        if(!desc || cant <= 0){
            alert('Por favor complete la descripción y cantidad.');
            return;
        }

        addConceptRow({
            cof_id: conceptId,
            dtp_descripcion: desc,
            dtp_iva: iva,
            dtp_cantidad: cant,
            dtp_precio: precio
        });

        // Reset new concept fields
        $('#new-concept-select').val('').trigger('change');
        $('#new-desc').val('');
        $('#new-iva').val(0);
        $('#new-cant').val(1);
        $('#new-precio').val(0);
        $('#new-importe').val('0.00');
    });

    // Auto-fill description and IVA when concept is selected in the modular section
    $('#new-concept-select').on('change', function(){
        var $sel = $(this).find('option:selected');
        var iva = $sel.data('iva') || 0;
        var nombre = $sel.text() || '';
        var conceptId = $(this).val(); // Get selected concept ID
        if($('#new-desc').val().trim() === '' && conceptId !== ''){
            $('#new-desc').val(nombre);
        }
        $('#new-iva').val(iva);
        updateNewImporte();
    });

    $('#new-cant, #new-precio').on('input', updateNewImporte);

    function updateNewImporte(){
        var cant = parseFloat($('#new-cant').val() || 0);
        var precio = parseFloat($('#new-precio').val() || 0);
        $('#new-importe').val((cant * precio).toFixed(2));
    }

    function formatNumber(n){
        return parseFloat(parseFloat(n || 0).toFixed(2));
    }

    function recalculateTotals(){
        var subtotal = 0;
        var ivaTotal = 0;
        $('#concepts-table tbody tr').each(function(){
            var importe = parseFloat($(this).find('.row-importe').text() || 0);
            var iva = parseFloat($(this).find('.row-iva-val').val() || 0);
            subtotal += importe;
            ivaTotal += importe * (iva/100);
        });
        subtotal = formatNumber(subtotal);
        ivaTotal = formatNumber(ivaTotal);
        var total = formatNumber(subtotal + ivaTotal + parseFloat($('#presupuesto-pre_gastos_suplidos').val() || 0));

        $('#presupuesto-pre_subtotal').val(subtotal.toFixed(2));
        $('#presupuesto-pre_iva').val(ivaTotal.toFixed(2));
        $('#presupuesto-pre_total').val(total.toFixed(2));
    }

    function addConceptRow(data){
        var idx = rowIndex++;
        var subtotal = (parseFloat(data.dtp_cantidad) * parseFloat(data.dtp_precio)).toFixed(2);
        var $tr = $(
            '<tr data-idx="'+idx+'">'+ 
            '<td>'+ 
                '<input type="hidden" name="DetallePresupuesto['+idx+'][cof_id]" value="'+(data.cof_id||'')+'">'+
                '<input type="text" name="DetallePresupuesto['+idx+'][dtp_descripcion]" class="form-control" value="'+(data.dtp_descripcion||'')+'">'+ 
            '</td>'+ 
            '<td><input type="number" step="0.01" name="DetallePresupuesto['+idx+'][dtp_iva]" class="form-control row-iva-val" value="'+(data.dtp_iva||0)+'"></td>'+ 
            '<td><input type="number" step="0.01" name="DetallePresupuesto['+idx+'][dtp_cantidad]" class="form-control row-cantidad" value="'+(data.dtp_cantidad||1)+'"></td>'+ 
            '<td><input type="number" step="0.01" name="DetallePresupuesto['+idx+'][dtp_precio]" class="form-control row-precio" value="'+(data.dtp_precio||0)+'"></td>'+ 
            '<td class="row-importe text-end">'+subtotal+'</td>'+ 
            '<td><button type="button" class="btn btn-sm btn-danger btn-remove"><i class="bx bx-trash"></i></button></td>'+ 
            '</tr>'
        );

        $tr.on('input', '.row-cantidad, .row-precio, .row-iva-val', function(){
            var $row = $(this).closest('tr');
            var c = parseFloat($row.find('.row-cantidad').val() || 0);
            var p = parseFloat($row.find('.row-precio').val() || 0);
            $row.find('.row-importe').text((c * p).toFixed(2));
            recalculateTotals();
        });

        $('#concepts-table tbody').append($tr);
        recalculateTotals();
    }

    function recalcRow($row){
        var cantidad = parseFloat($row.find('.row-cantidad').val() || 0);
        var precio = parseFloat($row.find('.row-precio').val() || 0);
        var subtotal = formatNumber(cantidad * precio);
        $row.find('.row-importe').text(subtotal.toFixed(2));
        recalculateTotals();
    }

    // Botón Añadir
    $('#btn-add-concept').on('click', function(){
        addConceptRow();
    });

    // Pre-fill client data if model has cli_id
    if ($('#presupuesto-cli_id').val()) {
        $('#presupuesto-cli_id').trigger('change');
    }

    // Pre-fill concept rows if detallesData is available
    var detallesData = __DETALLES_DATA_JS__;
    if (detallesData.length > 0) {
        $('#concepts-table tbody').empty(); // Clear the initial empty row
        $.each(detallesData, function(index, detail) {
            addConceptRow(detail);
        });
    } else {
        // Initialize with one empty row if no details
        addConceptRow();
    }

    // Recalcular totales cuando cambian los gastos suplidos
    $('#presupuesto-pre_gastos_suplidos').on('input', recalculateTotals);
});
JS;

$js = str_replace('__URL_LISTADO__', $urlListado, $js);
$js = str_replace('__URL_LISTADO_SOCIOS__', $urlListadoSocios, $js);
$js = str_replace('__URL_LISTADO_PROVINCIAS__', $urlListadoProvincias, $js);
$js = str_replace('__URL_DATOS__', $urlDatos, $js);
$js = str_replace('__CONCEPTOS_JS__', $conceptosJs, $js);
$js = str_replace('__DETALLES_DATA_JS__', $detallesDataJs, $js);
$this->registerJs($js);

?>

<div class="page-content">
    <h6 class="mb-0 text-uppercase">ACTUALIZAR PRESUPUESTO <dl>* Datos obligatorios</dl></h6>
    <hr/>
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card border-top border-0 border-4 border-white">
                <div class="card-body p-5">
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'form',
                            'id' => 'presupuestoForm',
                        ]
                    ]); ?>

                    <?= $form->errorSummary($model, ['class' => 'alert alert-danger'])
                    ?>

                    <?= $form->field($model, 'cli_id')->hiddenInput()->label(false) ?>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <?= $form->field($model, 'pre_numero', [
                                'template' => "<label>Número Presupuesto*</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3', 'required' => true]
                            ])->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?php 
                            $isCooperativa = !Yii::$app->user->isGuest && Yii::$app->user->identity->usu_rol === 'Cooperativa';
                            ?>
                            <?= $form->field($model, 'pre_numero_pedido', [
                                'template' => "<label>Número Pedido</label>\n{input}\n{hint}\n{error}"
                            ])->textInput(['maxlength' => true, 'class' => 'form-control mb-3', 'readonly' => $isCooperativa]) ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <?= $form->field($model, 'pre_fecha', [
                                'template' => "<label>Fecha*</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3', 'required' => true]
                            ])->textInput() ?>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">DATOS DEL CLIENTE</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="search-by-doc" class="form-label">NIF</label>
                            <input type="text" id="search-by-doc" class="form-control mb-3" value="<?= Html::encode($model->cli->cli_numdocide ?? '') ?>">
                        </div>
                         <div class="col-md-6">
                            <label for="search-by-name" class="form-label">Nombre / Razón Social</label>
                            <input type="text" id="search-by-name" class="form-control mb-3" value="<?= Html::encode($model->cli->cli_nombre ?? '') ?>">
                        </div>
                    </div>

                    <div id="datos-cliente" class="row mb-3">
          
                        <div class="col-md-4 mb-3">
                            <label>Nombre*</label>
                            <input type="text" id="cliente-nombre" class="form-control" disabled value="<?= Html::encode($model->cli->cli_nombre ?? '') ?>">
                        </div>
                         <div class="col-md-4 mb-3">
                            <label>Tipo Documento*</label>
                            <input type="text" id="cliente-tipo_doc" class="form-control" disabled value="<?= Html::encode($model->cli->tdo->tdo_nombre ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Número identificación Fiscal</label>
                            <input type="text" id="cliente-num_identificacion" class="form-control" disabled value="<?= Html::encode($model->cli->cli_numdocide ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Dirección</label>
                            <input type="text" id="cliente-direccion" class="form-control" disabled value="<?= Html::encode($model->cli->cli_direccion ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Código postal</label>
                            <input type="text" id="cliente-cp" class="form-control" disabled value="<?= Html::encode($model->cli->cli_codpostal ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Población</label>
                            <input type="text" id="cliente-poblacion" class="form-control" disabled value="<?= Html::encode($model->cli->cli_poblacion ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>País (Para el presupuesto)</label>
                            <?= Html::dropDownList('pais_presupuesto', $model->cli->cli_pais ?? null, $paises, [
                                'id' => 'cliente-pais',
                                'class' => 'form-select mb-3',
                                'prompt' => 'Seleccione País',
                            ]) ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Provincia (Para el presupuesto)</label>
                            <?= Html::dropDownList('provincia_presupuesto', $model->cli->cli_provincia ?? null, $provincias, [
                                'id' => 'cliente-provincia', 
                                'class' => 'form-select mb-3', 
                                'prompt' => 'Seleccione Provincia',
                            ]) ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <?= $form->field($model, 'fdp_id', [
                                'template' => "<label>Forma de Pago *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                $formasDePago,
                                ['prompt' => 'Seleccione', 'class' => 'form-select mb-3', 'required' => true]
                            ) ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <?= $form->field($model, 'soc_id')->hiddenInput()->label(false) ?>
                            <label class="form-label">Socio *</label>
                            <input type="text" id="search-socio" class="form-control mb-3" 
                                   value="<?= $model->soc ? Html::encode($model->soc->soc_numero . ' - ' . $model->soc->soc_nombre . ' ' . ($model->soc->soc_apellido ?? '')) : '' ?>" 
                                   placeholder="Buscar socio por número o nombre..." required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <?= $form->field($model, 'pre_logo', [
                                'template' => "<label>Logo a utilizar*</label>\n<div class='d-flex gap-4'>{input}</div>\n{hint}\n{error}",
                            ])->radioList(
                                [
                                    Presupuesto::PRE_LOGO_EMPRESA => 'Logo Freelance',
                                    Presupuesto::PRE_LOGO_SOCIO => 'Logo Socio'
                                ],
                                [
                                    'class' => 'mt-2',
                                    'item' => function($index, $label, $name, $checked, $value) use ($model) {
                                        $checked = $model->pre_logo === $value ? 'checked' : '';
                                        return " 
                                            <div class='form-check mb-2'>
                                                <input class='form-check-input' type='radio' name='{$name}' value='{$value}' {$checked} required>
                                                <label class='form-check-label'>{$label}</label>
                                            </div>
                                        ";
                                    }
                                ]
                            ) ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <?php $bancos = isset($bancos) ? $bancos : []; $selectedBanco = isset($selectedBanco) ? $selectedBanco : null; ?>
                            <label>Cuenta destino</label>
                            <?= Html::dropDownList('CuentasPresupuesto[ban_id]', $selectedBanco, $bancos, ['prompt' => 'Seleccione cuenta', 'class' => 'form-select mb-3'])
                            ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <?= $form->field($model, 'pre_language', [
                                'template' => "<label>Idioma *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                [
                                    Presupuesto::PRE_LANGUAGE_ES => 'Español',
                                    Presupuesto::PRE_LANGUAGE_EN => 'English'
                                ],
                                ['prompt' => 'Seleccione', 'class' => 'form-select mb-3']
                            ) ?>
                        </div>
                        <div class="col-md-2 mb-3">
                            <?= $form->field($model, 'pre_money', [
                                'template' => "<label>Moneda *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                [
                                    Presupuesto::PRE_MONEY_EUROS => 'Euro (€)',
                                    Presupuesto::PRE_MONEY_BS => 'Dolares ($)'
                                ],
                                ['prompt' => 'Seleccione', 'class' => 'form-select mb-3']
                            ) ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <?= $form->field($model, 'pre_estado', [
                                'template' => "<label>Estado *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                $estados,
                                ['prompt' => 'Seleccione', 'class' => 'form-select mb-3']
                            ) ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <?= $form->field($model, 'pre_situacion', [
                                'template' => "<label>Situación *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                $situaciones,
                                ['prompt' => 'Seleccione', 'class' => 'form-select mb-3']
                            ) ?>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">DETALLE PRESUPUESTO</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-12">
                            <!-- New Concept Section -->
                            <div class="p-3 border rounded mb-3">
                                <div class="row g-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label text-white">Concepto</label>
                                        <select id="new-concept-select" class="form-select">
                                            <option value="">Seleccione</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label text-white">Descripción</label>
                                        <input type="text" id="new-desc" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-white">IVA (%)</label>
                                        <input type="number" id="new-iva" class="form-control" step="0.01" value="0">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label text-white">Cant</label>
                                        <input type="number" id="new-cant" class="form-control" step="0.01" value="1">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label text-white">Precio</label>
                                        <input type="number" id="new-precio" class="form-control" step="0.01" value="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-white">Importe</label>
                                        <input type="text" id="new-importe" class="form-control" readonly value="0.00">
                                    </div>
                                    <div class="col-12 d-flex justify-content-end mt-3">
                                        <button type="button" id="btn-add-concept-row" class="btn btn-primary"><i class="bx bx-check"></i> Añadir Concepto</button>
                                    </div>
                                </div>
                            </div>
                            <!-- End New Concept Section -->

                            <div class="table-responsive">
                                <table class="table" id="concepts-table">
                                    <thead>
                                        <tr>
                                            <th>Descripción</th>
                                            <th>IVA (%)</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th class="text-end">Importe</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">IMPORTES</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <?= $form->field($model, 'pre_subtotal')->textInput(['type' => 'number', 'step' => '0.01', 'readonly' => true])
                            ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'pre_iva')->textInput(['type' => 'number', 'step' => '0.01', 'readonly' => true])
                            ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'pre_gastos_suplidos')->textInput(['type' => 'number', 'step' => '0.01'])
                            ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'pre_total')->textInput(['type' => 'number', 'step' => '0.01', 'readonly' => true])
                            ?>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">OBSERVACIONES</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <?= $form->field($model, 'pre_observaciones', [
                                'template' => "<label>Observaciones</label>\n{input}\n{hint}\n{error}",
                            ])->textarea(['rows' => 6, 'class' => 'form-control'])
                            ?>
                        </div>
                    </div>

                    <hr>

                    <div class="col-md-12 d-flex gap-2">
                        <?= Html::submitButton('Actualizar', ['class' => 'btn btn-success px-5 radius-30']) ?>
                        
                        <?= Html::button($model->pre_aprobado ? '<i class="bx bx-shield-quarter"></i> Desaprobar' : '<i class="bx bx-check-shield"></i> Aprobar', [
                            'class' => 'btn btn-light px-5 radius-30 toggle-approval-btn',
                            'title' => $model->pre_aprobado ? 'Desaprobar' : 'Aprobar',
                            'data-url' => Url::to(['toggle-aprobacion', 'pre_id' => $model->pre_id]),
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>