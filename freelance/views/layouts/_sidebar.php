<!-- views/layouts/_sidebar.php -->
 
 <?php
 
$this->registerJsFile('@web/js/metisMenu.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJs("
    $('#menu').metisMenu();
");
?>
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div><img src="/assets-custom/images/ico.svg" class="logo-icon" alt="logo icon"></div>
        <div><h4 class="logo-text"><img src="/assets-custom/images/logo.svg"></h4></div>
        <div class="toggle-icon ms-auto"><i class='bx bx-menu'></i></div>
    </div>

    <ul class="metismenu" id="menu">
        <li><a href="/"><div class="parent-icon"><i class="bx bx-home"></i></div><div class="menu-title">Inicio</div></a></li>
        <li class="menu-label">Módulos de administración</li>
        

        <li><a href="/socios"><div class="parent-icon"><i class='bx bx-street-view'></i></div><div class="menu-title">Socios</div></a></li>
        <li><a href="/clientes"><div class="parent-icon"><i class='bx bx-user-voice'></i></div><div class="menu-title">Clientes</div></a></li>

        <li>
            <a class="has-arrow" href="javascript:;" aria-expanded="false">
                <div class="parent-icon"><i class="bx bx-dollar-circle"></i></div>
                <div class="menu-title">Facturación</div>
            </a>
            <ul class="mm-collapse">
                <li><a href="/facturacion"><i class="bx bx-right-arrow-alt"></i>Facturas</a></li>
                <li><a href="/presupuestos"><i class="bx bx-right-arrow-alt"></i>Presupuestos</a></li>
            </ul>
        </li>

        <li><a href="/seguridad"><div class="parent-icon"><i class='bx bx-error-alt'></i></div><div class="menu-title">Seguridad social</div></a></li>
        <li><a href="/liquidaciones"><div class="parent-icon"><i class='bx bx-calculator'></i></div><div class="menu-title">Liquidaciones</div></a></li>

        <li>
            <a class="has-arrow" href="javascript:;" aria-expanded="false">
                <div class="parent-icon"><i class="bx bx-export"></i></div>
                <div class="menu-title">Exportaciones</div>
            </a>
            <ul class="mm-collapse">
                <li><a href="#"><i class="bx bx-right-arrow-alt"></i>Socios</a></li>
                <li><a href="#"><i class="bx bx-right-arrow-alt"></i>Clientes</a></li>
                <li><a href="#"><i class="bx bx-right-arrow-alt"></i>Facturas</a></li>
                <li><a href="#"><i class="bx bx-right-arrow-alt"></i>Liquidaciones Socios</a></li>
                <li><a href="#"><i class="bx bx-right-arrow-alt"></i>Liquidaciones Clientes</a></li>
                <li><a href="#"><i class="bx bx-right-arrow-alt"></i>Transferencias</a></li>
            </ul>
        </li>

        <li><a href="/usuarios"><div class="parent-icon"><i class='bx bx-group'></i></div><div class="menu-title">Usuarios</div></a></li>

        <li>
            <a class="has-arrow" href="javascript:;" aria-expanded="false">
                <div class="parent-icon"><i class='bx bx-diamond'></i></div>
                <div class="menu-title">Utilidades</div>
            </a>
            <ul class="mm-collapse">
                <li><a href="/utilidades"><i class="bx bx-right-arrow-alt"></i>Empresa</a></li>
                <li><a href="/utilidades-consecutivos"><i class="bx bx-right-arrow-alt"></i>Consecutivos</a></li>
                <li><a href="/utilidades-iva"><i class="bx bx-right-arrow-alt"></i>IVA</a></li>
                <li><a href="/utilidades-facturacion"><i class="bx bx-right-arrow-alt"></i>Conceptos facturación</a></li>
                <li><a href="/utilidades-liquidacion"><i class="bx bx-right-arrow-alt"></i>Conceptos liquidación</a></li>
                <li><a href="/utilidades-banco"><i class="bx bx-right-arrow-alt"></i>Banco</a></li>
                <li><a href="/utilidades-pagos"><i class="bx bx-right-arrow-alt"></i>Formas de pago</a></li>
                <li><a href="/utilidades-id"><i class="bx bx-right-arrow-alt"></i>Tipos ID</a></li>
                <li><a href="/utilidades-categorias"><i class="bx bx-right-arrow-alt"></i>Categorías profesionales</a></li>
                <li><a href="/utilidades-locacion"><i class="bx bx-right-arrow-alt"></i>País y provincia</a></li>
            </ul>
        </li>

        <li><a href="/logout"><div class="parent-icon"><i class="bx bx-log-out-circle"></i></div><div class="menu-title">Cerrar Sesión</div></a></li>
    </ul>

    <!-- <footer class="page-footer d-none"></footer> -->
</div>