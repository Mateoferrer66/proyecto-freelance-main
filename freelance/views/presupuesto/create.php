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

$this->title = 'CREAR PRESUPUESTO';
$this->params['breadcrumbs'][] = ['label' => 'Presupuestos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// CSS y JS para jQuery UI (Autocomplete)
$this->registerCssFile("https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("https://code.jquery.com/ui/1.13.2/jquery-ui.js", ['depends' => [\yii\web\JqueryAsset::class]]);

// Select2 para selects buscables en los conceptos
$this->registerCssFile("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css", ['depends' => [\yii\web\JqueryAsset::class]]);
 $this->registerJsFile("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);

// CSS para corregir el color del texto en Select2
$this->registerCss(
    ".select2-container .select2-selection--single .select2-selection__rendered, .select2-results__option {
        color: #444;
    }"
);

// URLs para AJAX
$urlListado = Url::to(['presupuesto/listado-clientes']);
$urlDatos = Url::to(['presupuesto/datos-cliente']);

// Cargamos los conceptos disponibles para autocompletar/llenar filas
// TODO: Change ConceptoFacturacion to ConceptoPresupuesto if it exists
$conceptos = \app\models\ConceptoFacturacion::find()->with('iva')->all();
$conceptosJs = json_encode(array_map(function($c){
    return [
        'id' => $c->cof_id,
        'nombre' => $c->cof_nombre,
        'iva' => $c->iva ? floatval($c->iva->iva_porcentaje) : 0,
    ];
}, $conceptos));

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
            minLength: 0,
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
        }).focus(function(){ 
            $(this).autocomplete("search");
        });
    }

    setupAutocomplete('search-by-name', 'name');
    setupAutocomplete('search-by-doc', 'doc');


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
                        $('#cliente-provincia').val(data.provincia);
                        $('#cliente-poblacion').val(data.poblacion);
                        $('#cliente-pais').val(data.pais);
                        $('#cliente-forma_pago').val(data.forma_pago);
                        $('#cliente-socio').val(data.socio);
                        
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

    function formatNumber(n){
        return parseFloat(parseFloat(n || 0).toFixed(2));
    }

    function recalculateTotals(){
        var subtotal = 0;
        var ivaTotal = 0;
        $('#concepts-table tbody tr').each(function(){
            var importe = parseFloat($(this).find('.row-importe').text() || 0);
            var iva = parseFloat($(this).find('.row-iva').val() || 0);
            subtotal += importe;
            ivaTotal += importe * (iva/100);
        });
        subtotal = formatNumber(subtotal);
        ivaTotal = formatNumber(ivaTotal);
        var total = formatNumber(subtotal + ivaTotal + parseFloat($('#presupuesto-pre_gastos_suplidos').val() || 0));

        // Actualizar campos del formulario
        $('#presupuesto-pre_subtotal').val(subtotal);
        $('#presupuesto-pre_iva').val(ivaTotal);
        $('#presupuesto-pre_total').val(total);
    }

    function addConceptRow(data){
        data = data || {};
        var idx = rowIndex++;
        var $tr = $(
            '<tr data-idx="'+idx+'">'+ 
            '<td>'+ 
                '<select name="DetallePresupuesto['+idx+'][cof_id]" class="form-control row-concepto">'+ 
                    '<option value="">-</option>'+ 
                '</select>'+ 
            '</td>'+ 
            '<td><input type="text" name="DetallePresupuesto['+idx+'][dtp_descripcion]" class="form-control row-descripcion" value="'+(data.descripcion||'')+'"></td>'+ 
            '<td><input type="number" step="0.01" name="DetallePresupuesto['+idx+'][dtp_iva]" class="form-control row-iva" value="'+(data.iva||0)+'"></td>'+ 
            '<td><input type="number" step="0.01" name="DetallePresupuesto['+idx+'][dtp_cantidad]" class="form-control row-cantidad" value="'+(data.cantidad||1)+'"></td>'+ 
            '<td><input type="number" step="0.01" name="DetallePresupuesto['+idx+'][dtp_precio]" class="form-control row-precio" value="'+(data.precio||0)+'"></td>'+ 
            '<td class="row-importe text-end">0.00</td>'+ 
            '<td><button type="button" class="btn text-orange radius-30 btn-remove">Eliminar</button></td>'+ 
            '</tr>'
        );

        // Poblar select de conceptos
        conceptos.forEach(function(c){
            var selected = data.cof_id && data.cof_id == c.id ? 'selected' : '';
            $tr.find('.row-concepto').append('<option value="'+c.id+'" '+selected+' data-iva="'+c.iva+'">'+c.nombre+'</option>');
        });

        // Si el row fue creado con cof_id, disparar el cambio para autocompletar
        $tr.find('.row-concepto').on('change', function(){
            var $sel = $(this).find('option:selected');
            var iva = $sel.data('iva') || 0;
            var nombre = $sel.text() || '';
            var $row = $(this).closest('tr');
            // Si la descripción está vacía, usar nombre del concepto
            if($row.find('.row-descripcion').val().trim() === ''){
                $row.find('.row-descripcion').val(nombre);
            }
            $row.find('.row-iva').val(iva);
            recalcRow($row);
        });

        // Eventos para cantidad/precio/iva
        $tr.on('input', '.row-cantidad, .row-precio, .row-iva, .row-descripcion', function(){
            var $row = $(this).closest('tr');
            recalcRow($row);
        });

        // Eliminación
        $tr.on('click', '.btn-remove', function(){
            $(this).closest('tr').remove();
            recalculateTotals();
        });

        $('#concepts-table tbody').append($tr);
        // Inicializar Select2 en el select del concepto
        if(typeof $.fn.select2 !== 'undefined'){
            $tr.find('.row-concepto').select2({ width: '100%', dropdownParent: $tr.closest('table') });
        }
        // Disparar cambio inicial si vino con concepto
        if(data.cof_id){
            $tr.find('.row-concepto').trigger('change');
        } else {
            recalcRow($tr);
        }
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

    // Inicializar con una fila vacía
    addConceptRow();

    // Recalcular totales cuando cambian los gastos suplidos
    $('#presupuesto-pre_gastos_suplidos').on('input', recalculateTotals);
});
JS;

$js = str_replace('__URL_LISTADO__', $urlListado, $js);
$js = str_replace('__URL_DATOS__', $urlDatos, $js);
$js = str_replace('__CONCEPTOS_JS__', $conceptosJs, $js);
$this->registerJs($js);

?>

<div class="page-content">
    <h6 class="mb-0 text-uppercase">CREAR PRESUPUESTO <dl>* Datos obligatorios</dl></h6>
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
                        <div class="col-12 col-md-6">
                            <?= $form->field($model, 'pre_numero', [
                                'template' => "<label>Número Presupuesto*</label>\n{input}\n{hint}\n{error}",
                                'inputOptions' => ['class' => 'form-control mb-3', 'required' => true]
                            ])->textInput() ?>
                        </div>
                        <div class="col-12 col-md-6">
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
                        <div class="col-12 col-md-6">
                            <label for="search-by-doc" class="form-label">NIF</label>
                            <input type="text" id="search-by-doc" class="form-control mb-3">
                        </div>
                         <div class="col-12 col-md-6">
                            <label for="search-by-name" class="form-label">Nombre / Razón Social</label>
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
                            <label>Provincia*</label>
                            <input type="text" id="cliente-provincia" class="form-control" disabled>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label>Población</label>
                            <input type="text" id="cliente-poblacion" class="form-control" disabled>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label>País</label>
                            <input type="text" id="cliente-pais" class="form-control" disabled>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <?= $form->field($model, 'fdp_id', [
                                'template' => "<label>Forma de Pago *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                $formasDePago,
                                ['prompt' => 'Seleccione', 'class' => 'form-control mb-3', 'required' => true]
                            ) ?>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <?= $form->field($model, 'soc_id', [
                                'template' => "<label>Socio *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                $socios,
                                ['prompt' => 'Seleccione', 'class' => 'form-control mb-3', 'required' => true]
                            ) ?>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <?= $form->field($model, 'pre_logo', [
                                'template' => "<label>Logo a utilizar*</label>\n<div class='d-flex gap-4'>{input}</div>\n{hint}\n{error}",
                            ])->radioList(
                                [
                                    Presupuesto::PRE_LOGO_EMPRESA => 'Logo Freelance',
                                    Presupuesto::PRE_LOGO_SOCIO => 'Logo Socio'
                                ],
                                [
                                    'class' => 'mt-2',
                                    'item' => function($index, $label, $name, $checked, $value) {
                                        $checked = $value === Presupuesto::PRE_LOGO_EMPRESA ? 'checked' : '';
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
                            <?php $bancos = isset($bancos) ? $bancos : []; $selectedBanco = isset($selectedBanco) ? $selectedBanco : null; ?>
                            <label>Cuenta destino</label>
                            <?= Html::dropDownList('CuentasPresupuesto[ban_id]', $selectedBanco, $bancos, ['prompt' => 'Seleccione cuenta', 'class' => 'form-control mb-3'])
                            ?>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <?= $form->field($model, 'pre_language', [
                                'template' => "<label>Idioma *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                [
                                    Presupuesto::PRE_LANGUAGE_ES => 'Español',
                                    Presupuesto::PRE_LANGUAGE_EN => 'English'
                                ],
                                ['prompt' => 'Seleccione', 'class' => 'form-control mb-3', 'value' => Presupuesto::PRE_LANGUAGE_ES]
                            ) ?>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <?= $form->field($model, 'pre_money', [
                                'template' => "<label>Moneda *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                [
                                    Presupuesto::PRE_MONEY_EUROS => 'Euro (€)',
                                    Presupuesto::PRE_MONEY_BS => 'Dolares ($)'
                                ],
                                ['prompt' => 'Seleccione', 'class' => 'form-control mb-3', 'value' => Presupuesto::PRE_MONEY_EUROS]
                            ) ?>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <?= $form->field($model, 'pre_estado', [
                                'template' => "<label>Estado *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                $estados,
                                ['prompt' => 'Seleccione', 'class' => 'form-control mb-3']
                            ) ?>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <?= $form->field($model, 'pre_situacion', [
                                'template' => "<label>Situación *</label>\n{input}\n{hint}\n{error}"
                            ])->dropDownList(
                                $situaciones,
                                ['prompt' => 'Seleccione', 'class' => 'form-control mb-3']
                            ) ?>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">AÑADIR CONCEPTOS</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label>Añadir Conceptos</label>
                            <div class="table-responsive">
                                <table class="table" id="concepts-table">
                                    <thead>
                                        <tr>
                                            <th>Concepto</th>
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
                            <button type="button" id="btn-add-concept" class="btn text-orange radius-30">Añadir concepto</button>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">IMPORTES</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-12 col-md-3">
                            <?= $form->field($model, 'pre_subtotal')->textInput(['type' => 'number', 'step' => '0.01'])
                            ?>
                        </div>
                        <div class="col-12 col-md-3">
                            <?= $form->field($model, 'pre_iva')->textInput(['type' => 'number', 'step' => '0.01'])
                            ?>
                        </div>
                        <div class="col-12 col-md-3">
                            <?= $form->field($model, 'pre_gastos_suplidos')->textInput(['type' => 'number', 'step' => '0.01'])
                            ?>
                        </div>
                        <div class="col-12 col-md-3">
                            <?= $form->field($model, 'pre_total')->textInput(['type' => 'number', 'step' => '0.01'])
                            ?>
                        </div>
                    </div>

                    <div class="card-title d-flex align-items-center mt-3">
                        <h5 class="mb-0 text-white">OBSERVACIONES</h5>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-12">
                            <?= $form->field($model, 'pre_observaciones', [
                                'template' => "<label>Observaciones</label>\n{input}\n{hint}\n{error}",
                            ])->textarea(['rows' => 6, 'class' => 'form-control'])
                            ?>
                        </div>
                    </div>

                    <hr>

                    <div class="col-12">

                                            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success px-5 radius-30']) ?>

                                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>