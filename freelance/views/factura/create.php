<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Factura;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Factura $model */
/** @var array $socios */
/** @var array $formasDePago */
/** @var array $bancos */
/** @var int|null $selectedBanco */
/** @var array $detallesData */
/** @var array $detalleRowErrors */
/** @var array $estados */
/** @var array $situaciones */
/** @var array $provincias */

$this->title = 'CREAR FACTURA';
$this->params['breadcrumbs'][] = ['label' => 'Facturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// CSS y JS para jQuery UI (Autocomplete)
$this->registerCssFile("https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("https://code.jquery.com/ui/1.13.2/jquery-ui.js", ['depends' => [\yii\web\JqueryAsset::class]]);

// Select2 para selects buscables en los conceptos
$this->registerCssFile("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);

// CSS para corregir el color del texto en Select2

// URLs para AJAX
$urlListado = Url::to(['factura/listado-clientes']);
$urlListadoSocios = Url::to(['factura/listado-socios']);
$urlListadoProvincias = Url::to(['factura/listado-provincias']);
$urlDatos = Url::to(['factura/datos-cliente']);

// Cargamos los conceptos disponibles para autocompletar/llenar filas
$conceptos = \app\models\ConceptoFacturacion::find()->with('iva')->all();
$conceptosJs = json_encode(array_map(function($c){
    return [
        'id' => $c->cof_id,
        'nombre' => $c->cof_nombre,
        'iva' => $c->iva ? floatval($c->iva->iva_porcentaje) : 0,
    ];
}, $conceptos));

$detallesDataJs = json_encode($detallesData ?? []);

// Convertir heredoc en nowdoc
$js = <<<'JS'
$(function(){
    // --- Inicialización del Datepicker ---
    $('#factura-fac_fecha').bootstrapMaterialDatePicker({
        format: 'DD/MM/YYYY',
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
            minLength: 0,
            select: function(event, ui) {
                // Cuando se selecciona un item
                $("#factura-cli_id").val(ui.item.value).trigger('change'); // Asigna el ID y dispara el change
                
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
        }).focus(function(){ 
            $(this).autocomplete("search");
        });
    }

    setupAutocomplete('search-by-name', 'name');
    setupAutocomplete('search-by-doc', 'doc');

    // --- Cargar Provincias por País ---
    function loadProvincias(paisId, selectedProvId = null) {
        if (!paisId) {
            $('#cliente-provincia').empty().append('<option value="">Seleccione Provincia</option>');
            return;
        }
        $.ajax({
            url: '__URL_LISTADO_PROVINCIAS__',
            data: {pais_id: paisId},
            success: function(data) {
                var $select = $('#cliente-provincia');
                $select.empty().append('<option value="">Seleccione Provincia</option>');
                $.each(data, function(id, name) {
                    $select.append(new Option(name, id));
                });
                if (selectedProvId) {
                    $select.val(selectedProvId).trigger('change');
                }
            }
        });
    }

    $('#cliente-pais').on('change', function() {
        loadProvincias($(this).val());
    });

    // Setup Socio Autocomplete
    $("#search-socio").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: '__URL_LISTADO_SOCIOS__',
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 0,
        select: function(event, ui) {
            $("#factura-soc_id").val(ui.item.value);
            $(this).val(ui.item.label);
            return false;
        }
    }).focus(function(){ 
        $(this).autocomplete("search");
    });


    // --- Cargar datos del cliente ---
    $('#factura-cli_id').on('change', function(){
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
                        // $('#cliente-provincia').val(data.provincia); // Old text input
                        $('#cliente-provincia').val(data.prv_id).trigger('change'); // New select input
                        $('#cliente-poblacion').val(data.poblacion);
                        // $('#cliente-pais').val(data.pais); // Old text input
                        $('#cliente-pais').val(data.pai_id).trigger('change');
                        
                        // Cargar provincias y seleccionar la correcta
                        loadProvincias(data.pai_id, data.prv_id);
                        
                        $('#cliente-forma_pago').val(data.forma_pago);
                        $('#cliente-socio').val(data.socio);
                        
                        // Actualizar ambos campos de búsqueda para mantener consistencia
                        $('#search-by-name').val(data.nombre);
                        $('#search-by-doc').val(data.nif);

                        // ← Sincronizar hidden fields para DatosFactura
                        $('#hidden-nombre').val(data.nombre);
                        $('#hidden-tipo_doc').val(data.tipo_doc_id); // ID numérico del tipo doc
                        $('#hidden-num_doc').val(data.num_identificacion);
                        $('#hidden-direccion').val(data.direccion);
                        $('#hidden-cp').val(data.cp);
                        $('#hidden-poblacion').val(data.poblacion);
                        $('#hidden-provincia').val(data.prv_id);
                        $('#hidden-pais').val(data.pai_id);
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

    $('#cliente-provincia').on('change', function(){
        $('#hidden-provincia').val($(this).val());
    });
    $('#cliente-pais').on('change', function(){
        $('#hidden-pais').val($(this).val());
    });

    // --- Añadir Conceptos dinámicos ---
    var conceptos = __CONCEPTOS_JS__;
    var rowIndex = 0;

    function formatNumber(n){
        return parseFloat(parseFloat(n || 0).toFixed(2));
    }

    function getCurrencySymbol() {
        var moneyCtx = $('#factura-fac_money').val();
        if (moneyCtx == '<?= Factura::FAC_MONEY_US ?>') {
            return '$';
        }
        return '€';
    }

    // Update symbols on change
    $('#factura-fac_money').on('change', function(){
        var sym = getCurrencySymbol();
        $('.currency-symbol-display').text(sym);
        recalculateTotals(); // Re-render table rows to update text? Or just loop table
        $('#concepts-table tbody tr').each(function(){
            var $row = $(this);
            recalcRow($row);
        });
    });

    function recalculateTotals(){
        var subtotal = 0;
        var ivaTotal = 0;
        var suplidosTotal = 0;

        $('#concepts-table tbody tr').each(function(){
            var $row = $(this);
            var importe = parseFloat($row.find('.row-importe').data('val') || 0); // Use stored numeric value
            var iva = parseFloat($row.find('.row-iva').val() || 0);
            var desc = $row.find('.row-descripcion').val().toLowerCase();

            // Detectar Suplidos
            if (desc.includes('suplido')) {
                suplidosTotal += importe;
            } else {
                subtotal += importe;
                ivaTotal += importe * (iva/100);
            }
        });

        subtotal = formatNumber(subtotal);
        ivaTotal = formatNumber(ivaTotal);
        suplidosTotal = formatNumber(suplidosTotal); // Calculated sum of suplidos lines
        
        // El campo Gasto Suplidos ahora es de solo lectura y se calcula automáticamente
        $('#factura-fac_gastos_suplidos').val(suplidosTotal);

        var total = formatNumber(subtotal + ivaTotal + suplidosTotal);

        // Actualizar campos del formulario
        $('#factura-fac_subtotal').val(subtotal);
        $('#factura-fac_iva').val(ivaTotal);
        $('#factura-fac_total').val(total);
    }

    // --- Añadir Conceptos Logic (Updated) ---
    
    // Init Select2 for new concept select
    $('#new-concept-select').select2({
        width: '100%',
        dropdownParent: $('.page-content') // Ensure it renders correctly
    });

    // Populate new concept select
    conceptos.forEach(function(c){
        $('#new-concept-select').append(new Option(c.nombre, c.id, false, false)).trigger('change');
    });
    $('#new-concept-select').val('').trigger('change');

    // On concept change, fill fields
    $('#new-concept-select').on('change', function(){
        var id = $(this).val();
        if(!id) return;
        var c = conceptos.find(x => x.id == id);
        if(c){
            $('#new-desc').val(c.nombre);
            $('#new-iva').val(c.iva);
            recalcNewRow();
        }
    });

    // Recalculate new row values
    $('#new-cant, #new-precio, #new-iva').on('input', recalcNewRow);

    function recalcNewRow(){
        var cant = parseFloat($('#new-cant').val()||0);
        var prec = parseFloat($('#new-precio').val()||0);
        var sub = cant * prec;
        $('#new-importe').val(sub.toFixed(2));
    }

    // Add Row Button
    $('#btn-add-concept-row').on('click', function(){
        var desc = $('#new-desc').val();
        var iva = $('#new-iva').val();
        var cant = $('#new-cant').val();
        var prec = $('#new-precio').val();
        var cofId = $('#new-concept-select').val();

        if(!desc && !prec && !cant) {
            alert('Ingrese datos del concepto');
            return;
        }

        addConceptRow({
            cof_id: cofId,
            dtf_descripcion: desc,
            dtf_iva: iva,
            dtf_cantidad: cant,
            dtf_precio: prec
        });
        
        // Reset fields
        $('#new-concept-select').val('').trigger('change');
        $('#new-desc').val('');
        $('#new-iva').val(0);
        $('#new-cant').val(1);
        $('#new-precio').val(0);
        $('#new-importe').val('0.00');
    });


    function addConceptRow(data){
        data = data || {};
        var idx = rowIndex++;
        var subtotal = parseFloat(data.dtf_cantidad||0) * parseFloat(data.dtf_precio||0);
        
        var $tr = $(
            '<tr data-idx="'+idx+'">'+ 
            '<td>'+ 
                '<input type="hidden" name="DetalleFactura['+idx+'][cof_id]" value="'+(data.cof_id||'')+'">' +
                '<input type="text" name="DetalleFactura['+idx+'][dtf_descripcion]" class="form-control row-descripcion" value="'+(data.dtf_descripcion||'')+'">'+
            '</td>'+ 
            '<td><input type="number" step="0.01" name="DetalleFactura['+idx+'][dtf_iva]" class="form-control row-iva" value="'+(data.dtf_iva||0)+'"></td>'+ 
            '<td><input type="number" step="0.01" name="DetalleFactura['+idx+'][dtf_cantidad]" class="form-control row-cantidad" value="'+(data.dtf_cantidad||1)+'"></td>'+ 
            '<td><input type="number" step="0.01" name="DetalleFactura['+idx+'][dtf_precio]" class="form-control row-precio" value="'+(data.dtf_precio||0)+'"></td>'+ 
            '<td class="row-importe text-end">'+subtotal.toFixed(2)+' '+getCurrencySymbol()+'</td>'+ 
            '<td><button type="button" class="btn text-orange radius-30 btn-remove">Eliminar</button></td>'+ 
            '</tr>'
        );

        $tr.find('.row-importe').data('val', subtotal); // Store numeric value

        // Eventos para recalculo en tabla
        $tr.on('input', '.row-cantidad, .row-precio, .row-iva', function(){
            var $row = $(this).closest('tr');
            recalcRow($row);
        });

        // Eliminación
        $tr.on('click', '.btn-remove', function(){
            $(this).closest('tr').remove();
            recalculateTotals();
        });

        $('#concepts-table tbody').append($tr);
        recalculateTotals();
    }

    // Pre-fill client data if model has cli_id
    if ($('#factura-cli_id').val()) {
        $('#factura-cli_id').trigger('change');
    }

    // Pre-fill concept rows if detallesData is available
    var detallesData = __DETALLES_DATA_JS__;
    if (detallesData.length > 0) {
        $('#concepts-table tbody').empty(); // Clear the initial empty row
        $.each(detallesData, function(index, detail) {
            addConceptRow(detail);
        });
    }

    // Si no hay datos, no añadimos fila vacía por defecto
    // addConceptRow(); // REMOVED


    // Recalcular totales cuando cambian los gastos suplidos (YA NO ES NECESARIO SI ES AUTOMATICO)
    // $('#factura-fac_gastos_suplidos').on('input', recalculateTotals);
    // Pero si el usuario cambia manual, podríamos querer recalcular? No, el usuario pidió "Debería trasladar el valor", implicando automatico.
    // Lo haremos readonly en el HTML.

    // Inicializar Select2 para Socio
    // Removed duplicate select2 initialization for socio here as it's now an autocomplete input
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
    <h6 class="mb-0 text-uppercase">CREAR FACTURA <dl>* Datos obligatorios</dl></h6>
    <hr/>
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card border-top border-0 border-4 border-white">
                <div class="card-body p-5">
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'form',
                            'id' => 'facturaForm',
                        ]
                    ]); ?>

                    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>

                    <?= $form->field($model, 'cli_id')->hiddenInput()->label(false) ?>

                    <!-- Campos hidden para DatosFactura del receptor -->
                    <input type="hidden" id="hidden-nombre" name="DatosReceptor[daf_nombre]">
                    <input type="hidden" id="hidden-tipo_doc" name="DatosReceptor[tdo_id]">
                    <input type="hidden" id="hidden-num_doc" name="DatosReceptor[daf_numdocide]">
                    <input type="hidden" id="hidden-direccion" name="DatosReceptor[daf_direccion]">
                    <input type="hidden" id="hidden-cp" name="DatosReceptor[daf_cod_postal]">
                    <input type="hidden" id="hidden-poblacion" name="DatosReceptor[daf_poblacion]">
                    <input type="hidden" id="hidden-provincia" name="DatosReceptor[prv_id]">
                    <input type="hidden" id="hidden-pais" name="DatosReceptor[pai_id]">

                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <?= $form->field($model, 'fac_numero', [
                                'template' => "<label>Número Factura*</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3', 'required' => true]
                            ])->textInput() ?>
                        </div>
                         <div class="col-12 col-md-6">
                            <?= $form->field($model, 'fac_numero_pedido', [
                                'template' => "<label>Número Pedido</label>\n{input}\n{hint}\n{error}"
                            ])->textInput(['maxlength' => true, 'class' => 'form-control mb-3', 'placeholder' => '']) ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <?= $form->field($model, 'fac_fecha', [
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
                         <div class="col-12 col-md-6">
                            <label for="search-by-doc" class="form-label">NIF</label>
                            <input type="text" id="search-by-doc" class="form-control mb-3">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="search-by-name" class="form-label">Nombre / Razón social</label>
                            <input type="text" id="search-by-name" class="form-control mb-3">
                        </div>
                    </div>

                    <div id="datos-cliente" class="row mb-3">
                       
                        <div class="col-12 col-md-4 mb-3">
                            <label>Nombre*</label>
                            <input type="text" id="cliente-nombre" class="form-control" disabled>
                        </div>
                         <div class="col-12 col-md-4 mb-3">
                            <label>Tipo Documento*</label>
                            <input type="text" id="cliente-tipo_doc" class="form-control" disabled>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label>Número identificación Fiscal</label>
                            <input type="text" id="cliente-num_identificacion" class="form-control" disabled>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label>Dirección</label>
                            <input type="text" id="cliente-direccion" class="form-control" disabled>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label>Código postal</label>
                            <input type="text" id="cliente-cp" class="form-control" disabled>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label>Provincia (Para la factura)</label>
                            <?= Html::dropDownList('provincia_factura', null, $provincias, [
                                'id' => 'cliente-provincia', 
                                'class' => 'form-select mb-3', 
                                'prompt' => 'Seleccione Provincia',
                            ]) ?>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label>Población</label>
                            <input type="text" id="cliente-poblacion" class="form-control" disabled>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label>País (Para la factura)</label>
                            <?= Html::dropDownList('pais_factura', null, $paises, [
                                'id' => 'cliente-pais',
                                'class' => 'form-select mb-3',
                                'prompt' => 'Seleccione País',
                            ]) ?>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <?= $form->field($model, 'fdp_id', [
                                'template' => "<label>Forma de Pago *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                $formasDePago,
                                ['prompt' => 'Seleccione', 'class' => 'form-select mb-3', 'required' => true]
                            ) ?>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label>Socio *</label>
                            <?php 
                            // Socio Autocomplete
                            // Hidden input for ID
                            echo $form->field($model, 'soc_id')->hiddenInput()->label(false);
                            
                            // Visual input for search
                            $socioName = '';
                            if ($model->soc_id && isset($socios[$model->soc_id])) {
                                $socioName = $socios[$model->soc_id];
                            }
                            ?>
                            <input type="text" id="search-socio" class="form-control mb-3" placeholder="Buscar socio..." value="<?= $socioName ?>">
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <?= $form->field($model, 'fac_logo', [
                                'template' => "<label>Logo a utilizar*</label>\n<div class='d-flex gap-4'>{input}</div>\n{hint}\n{error}",
                            ])->radioList(
                                [
                                    Factura::FAC_LOGO_EMPRESA => 'Logo Freelance',
                                    Factura::FAC_LOGO_SOCIO => 'Logo Socio'
                                ],
                                [
                                    'class' => 'mt-2',
                                    'item' => function($index, $label, $name, $checked, $value) use ($model) {
                                        $checked = ($model->fac_logo === $value) ? 'checked' : '';
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
                        <div class="col-12 col-md-4 mb-3">
                            <?php $bancos = isset($bancos) ? $bancos : []; $selectedBanco = isset($selectedBanco) ? $selectedBanco : []; ?>
                            <label>Cuentas para transferencia</label>
                            <?= Html::checkboxList('CuentasFactura[ban_id]', $selectedBanco, $bancos, [
                                'class' => 'form-check',
                                'item' => function ($index, $label, $name, $checked, $value) {
                                    $checkedStr = $checked ? 'checked' : '';
                                    return '<div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="'.$name.'" value="'.$value.'" '.$checkedStr.' id="banco_'.$index.'">
                                                <label class="form-check-label" for="banco_'.$index.'">'.$label.'</label>
                                            </div>';
                                }
                            ]) ?>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <?= $form->field($model, 'fac_language', [
                                'template' => "<label>Idioma *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                [
                                    Factura::FAC_LANGUAGE_ES => 'Español',
                                    Factura::FAC_LANGUAGE_EN => 'English'
                                ],
                                ['prompt' => 'Seleccione', 'class' => 'form-select mb-3']
                            ) ?>
                        </div>
                        <div class="col-12 col-md-2 mb-3">
                            <?= $form->field($model, 'fac_money', [
                                'template' => "<label>Moneda *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                [
                                    Factura::FAC_MONEY_EUROS => 'Euro (€)',
                                    Factura::FAC_MONEY_US => 'Dolares ($)'
                                ],
                                ['prompt' => 'Seleccione', 'class' => 'form-select mb-3', 'value' => Factura::FAC_MONEY_EUROS]
                            ) ?>
                        </div>
                    </div>

                    <?php // No se muestran 'fac_estado' ni 'fac_situacion' en la creación (se asignan por defecto en el controlador) ?>
                    <?= $form->field($model, 'fac_estado')->hiddenInput(['value' => Factura::FAC_ESTADO_SIN_PAGAR])->label(false) ?>
                    <?= $form->field($model, 'fac_situacion')->hiddenInput(['value' => Factura::FAC_SITUACION_NO_RECLAMADA])->label(false) ?>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">DETALLE FACTURA</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-12">
                            <!-- New Concept Section -->
                            <div class="p-3 border rounded mb-3">
                                <div class="row g-3">
                                    <div class="col-12 col-md-3"> <!-- Shorter as requested -->
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
                            <!-- Removed old Add button -->
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">IMPORTES</h5>
                    </div>
                    <hr>

   

                    <div class="row mb-3">
                        <div class="col-12 col-md-3">
                            <label class="text-white">Subtotal</label>
                            <div class="input-group">
                                <?= $form->field($model, 'fac_subtotal')->textInput(['type' => 'number', 'step' => '0.01', 'readonly' => true])->label(false) ?>
                                <span class="input-group-text currency-symbol-display">€</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="text-white">IVA</label>
                           <div class="input-group">
                                <?= $form->field($model, 'fac_iva')->textInput(['type' => 'number', 'step' => '0.01', 'readonly' => true])->label(false) ?>
                                <span class="input-group-text currency-symbol-display">€</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="text-white">Gastos Suplidos</label>
                            <div class="input-group">
                                <?= $form->field($model, 'fac_gastos_suplidos')->textInput(['type' => 'number', 'step' => '0.01', 'readonly' => true])->label(false) ?>
                                <span class="input-group-text currency-symbol-display">€</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                             <label class="text-white">Total</label>
                            <div class="input-group">
                                <?= $form->field($model, 'fac_total')->textInput(['type' => 'number', 'step' => '0.01', 'readonly' => true])->label(false) ?>
                                <span class="input-group-text currency-symbol-display">€</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">OBSERVACIONES</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-12 mb-3">
                            <?= $form->field($model, 'fac_archivo', [
                                'template' => "<label class='text-white'>Archivo Adjunto</label>\n{input}\n{hint}\n{error}"
                            ])->fileInput(['class' => 'form-control']) ?>
                            <small class="text-white">Si sube un archivo, no es necesario escribir observaciones.</small>
                        </div>
                        <div class="col-12">
                            <?= $form->field($model, 'fac_observaciones', [
                                'template' => "<label class='text-white'>Observaciones</label>\n{input}\n{hint}\n{error}",
                            ])->textarea(['rows' => 6, 'class' => 'form-control'])
                            ?>
                        </div>
                    </div>

                    <hr>

                    <div class="col-12">

                                            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success px-5 radius-30'])
                                            ?>

                                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Create Socio -->
<div class="modal fade" id="modalCreateSocio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Socio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-create-socio">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Número *</label>
                            <input type="number" name="Socio[soc_numero]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha *</label>
                            <input type="date" name="Socio[soc_fecha]" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="Socio[soc_nombre]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellido *</label>
                            <input type="text" name="Socio[soc_apellido]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo Doc *</label>
                            <?= Html::dropDownList('Socio[tdo_id]', null, \yii\helpers\ArrayHelper::map(\app\models\TipoDocIdentidad::find()->all(), 'tdo_id', 'tdo_nombre'), ['class' => 'form-select', 'required' => true]) ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Num Doc *</label>
                            <input type="text" name="Socio[soc_numdocide]" class="form-control" required>
                        </div>
                         <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Nacimiento *</label>
                            <input type="date" name="Socio[soc_fecnacimiento]" class="form-control" required>
                        </div>
                         <div class="col-md-6 mb-3">
                            <label class="form-label">Sexo *</label>
                            <select name="Socio[soc_sexo]" class="form-select" required>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                        </div>
                         <div class="col-md-6 mb-3">
                            <label class="form-label">Num Seg Social *</label>
                            <input type="text" name="Socio[soc_numsegsocial]" class="form-control" required>
                        </div>
                         <div class="col-md-6 mb-3">
                            <label class="form-label">Cuenta Bancaria *</label>
                            <input type="text" name="Socio[soc_ctabancaria]" class="form-control" required>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">Guardar Socio</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$urlCreateSocio = Url::to(['socio/create-ajax']);
$jsSocio = <<<JS
$(function(){
    $('#form-create-socio').on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: '$urlCreateSocio',
            type: 'POST',
            data: form.serialize(),
            success: function(resp){
                if(resp.success){
                    // Add to dropdown and select
                    var newOption = new Option(resp.nombre, resp.id, true, true);
                    $('#factura-soc_id').append(newOption).trigger('change');
                    
                    // Close modal and reset form
                    var modal = bootstrap.Modal.getInstance(document.getElementById('modalCreateSocio'));
                    modal.hide();
                    form[0].reset();
                    alert('Socio creado correctamente');
                } else {
                    var msg = '';
                    $.each(resp.errors, function(k, v){
                        msg += v.join(', ') + '\\n';
                    });
                    alert('Error:\\n' + msg);
                }
            },
            error: function(){
                alert('Error en el servidor');
            }
        });
    });

    // Validacion de Observaciones vs Archivo
    $('form#facturaForm').on('submit', function(e){
        var file = $('#factura-fac_archivo').val();
        var obs = $('#factura-fac_observaciones').val().trim();
        
        if(!file && !obs){
            e.preventDefault();
            alert('Debe escribir observaciones o subir un archivo.');
            $('#factura-fac_observaciones').focus();
        }
    });
});
JS;
$this->registerJs($jsSocio);
?>
