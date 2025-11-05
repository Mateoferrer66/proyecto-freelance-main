<?php

// Obtener la URL actual
$currentUrl = Yii::$app->request->url;

?>
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div><img src="/assets-custom/images/ico.svg" class="logo-icon" alt="logo icon"></div>
        <div>
            <h4 class="logo-text"><a href="<?= \Yii::$app->urlManager->createUrl(['/usuario']) ?>"><img src="/assets-custom/images/logo.svg"></a></h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-menu'></i></div>
    </div>

    <ul class="metismenu" id="menu">
        <li><a href="/">
                <div class="parent-icon"><i class="bx bx-home"></i></div>
                <div class="menu-title">Inicio</div>
            </a></li>
        <li class="menu-label">Módulos de administración</li>

        <li><a href="/socios">
                <div class="parent-icon"><i class='bx bx-street-view'></i></div>
                <div class="menu-title">Socios</div>
            </a></li>
        <li><a href="http://localhost:8080/index.php?r=cliente">
                <div class="parent-icon"><i class='bx bx-user-voice'></i></div>
                <div class="menu-title">Clientes</div>
            </a></li>

        <li class="<?= strpos($currentUrl, '/facturacion') !== false || strpos($currentUrl, '/presupuestos') !== false ? 'mm-active' : '' ?>">
            <a class="has-arrow" href="javascript:;" aria-expanded="<?= strpos($currentUrl, '/facturacion') !== false || strpos($currentUrl, '/presupuestos') !== false ? 'true' : 'false' ?>">
                <div class="parent-icon"><i class="bx bx-dollar-circle"></i></div>
                <div class="menu-title">Facturación</div>
            </a>
            <ul class="mm-collapse" style="<?= strpos($currentUrl, '/facturacion') !== false || strpos($currentUrl, '/presupuestos') !== false ? 'display: block;' : '' ?>">
                <li><a href="http://localhost:8080/index.php?r=factura"><i class="bx bx-right-arrow-alt"></i>Facturas</a></li>
                <li><a href="http://localhost:8080/index.php?r=presupuesto"><i class="bx bx-right-arrow-alt"></i>Presupuestos</a></li>
            </ul>
        </li>

        <li><a href="/seguridad">
                <div class="parent-icon"><i class='bx bx-error-alt'></i></div>
                <div class="menu-title">Seguridad social</div>
            </a></li>
        <li><a href="/liquidaciones">
                <div class="parent-icon"><i class='bx bx-calculator'></i></div>
                <div class="menu-title">Liquidaciones</div>
            </a></li>

        <li class="<?= strpos($currentUrl, '/exportaciones') !== false ? 'mm-active' : '' ?>">
            <a class="has-arrow" href="javascript:;" aria-expanded="<?= strpos($currentUrl, '/exportaciones') !== false ? 'true' : 'false' ?>">
                <div class="parent-icon"><i class="bx bx-export"></i></div>
                <div class="menu-title">Exportaciones</div>
            </a>
            <ul class="mm-collapse" style="<?= strpos($currentUrl, '/exportaciones') !== false ? 'display: block;' : '' ?>">
                <li><a href="#"><i class="bx bx-right-arrow-alt"></i>Socios</a></li>
                <li><a href="#"><i class="bx bx-right-arrow-alt"></i>Clientes</a></li>
                <li><a href="#"><i class="bx bx-right-arrow-alt"></i>Facturas</a></li>
                <li><a href="#"><i class="bx bx-right-arrow-alt"></i>Liquidaciones Socios</a></li>
                <li><a href="#"><i class="bx bx-right-arrow-alt"></i>Liquidaciones Clientes</a></li>
                <li><a href="#"><i class="bx bx-right-arrow-alt"></i>Transferencias</a></li>
            </ul>
        </li>

        <li><a href="http://localhost:8080/index.php?r=usuario">
                <div class="parent-icon"><i class='bx bx-group'></i></div>
                <div class="menu-title">Usuarios</div>
            </a></li>

        <li class="<?= strpos($currentUrl, '/utilidades') !== false ? 'mm-active' : '' ?>">
            <a class="has-arrow" href="javascript:;" aria-expanded="<?= strpos($currentUrl, '/utilidades') !== false ? 'true' : 'false' ?>">
                <div class="parent-icon"><i class='bx bx-diamond'></i></div>
                <div class="menu-title">Utilidades</div>
            </a>
            <ul class="mm-collapse" style="<?= strpos($currentUrl, 'http://localhost:8080/index.php?r=empresa%2Fcreate') !== false ? 'display: block;' : '' ?>">
                <li><a href="http://localhost:8080/index.php?r=empresa%2Fcreate"><i class="bx bx-right-arrow-alt"></i>Empresa</a></li>
                <li><a href="http://localhost:8080/index.php?r=consecutivo"><i class="bx bx-right-arrow-alt"></i>Consecutivos</a></li>
                <li><a href="http://localhost:8080/index.php?r=iva"><i class="bx bx-right-arrow-alt"></i>IVA</a></li>
                <li><a href="http://localhost:8080/index.php?r=concepto-facturacion"><i class="bx bx-right-arrow-alt"></i>Conceptos facturación</a></li>
                <li><a href="http://localhost:8080/index.php?r=concepto-liquidacion"><i class="bx bx-right-arrow-alt"></i>Conceptos liquidación</a></li>
                <li><a href="http://localhost:8080/index.php?r=banco"><i class="bx bx-right-arrow-alt"></i>Banco</a></li>
                <li><a href="http://localhost:8080/index.php?r=forma-de-pago"><i class="bx bx-right-arrow-alt"></i>Formas de pago</a></li>
                <li><a href="http://localhost:8080/index.php?r=tipo-doc-identidad"><i class="bx bx-right-arrow-alt"></i>Tipos ID</a></li>
                <li><a href="http://localhost:8080/index.php?r=categoria"><i class="bx bx-right-arrow-alt"></i>Categorías profesionales</a></li>
                <li><a href="http://localhost:8080/index.php?r=provincia"><i class="bx bx-right-arrow-alt"></i>País y provincia</a></li>
            </ul>
        </li>
        <li>
            <?= \yii\helpers\Html::a(
                '<div class="parent-icon"><i class="bx bx-log-out-circle"></i></div><div class="menu-title">Cerrar Sesión</div>',
                ['/site/logout'],
                ['data-method' => 'post']
            ) ?>
        </li>
    </ul>
    <footer class="page-footer">
        <p class="mb-0">Copyright © 2022. Derechos reservados.</p>
    </footer>
    <!-- <footer class="page-footer d-none"></footer> -->
</div>