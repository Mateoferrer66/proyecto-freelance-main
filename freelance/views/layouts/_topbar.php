<?php

use yii\helpers\Html;

$controller = Yii::$app->controller->id;

$titleMap = [
    // Mapeo para las subsecciones de Utilidades
    'empresa' => 'Datos de la Empresa',
    'consecutivo' => 'Consecutivos',
    'iva' => 'IVA',
    'concepto-facturacion' => 'Conceptos de Facturación',
    'concepto-liquidacion' => 'Conceptos de Liquidación',
    'banco' => 'Bancos',
    'forma-de-pago' => 'Formas de Pago',
    'tipo-doc-identidad' => 'Tipos de Documento de Identidad',
    'categoria' => 'Categorías Profesionales',
    'provincia' => 'Países y Provincias',
    'utilidades' => 'Utilidades',
    // Mapeo para otros modulos
    'usuario' => 'Usuarios',
    'cliente' => 'Clientes',
    'socio' => 'Socios',
    'factura' => 'Facturación',
    'presupuesto' => 'Presupuestos',
];

// Mapeo de rutas a clases de íconos de Boxicons
$iconMap = [
    // Mapeo para las subsecciones de Utilidades
    'empresa' => 'bx bx-store-alt',
    'consecutivo' => 'bx bx-right-indent',
    'iva' => 'bx bx-calculator',
    'concepto-facturacion' => 'bx bx-task',
    'concepto-liquidacion' => 'bx bx-terminal',
    'banco' => 'bx bx-building-house',
    'forma-de-pago' => 'bx bx-dollar',
    'tipo-doc-identidad' => 'bx bx-id-card',
    'categoria' => 'bx bx-grid-alt',
    'provincia' => 'bx bx-flag',
    'utilidades' => 'bx bx-diamond',
    // Mapeo para otros modulos
    'usuario' => 'bx bx-group',
    'cliente' => 'bx bx-user-voice',
    'socio' => 'bx bx-street-view',
    'factura' => 'bx bx-dollar-circle',
    'presupuesto' => 'bx bx-dollar-circle',
];
?>

<div class="topbar d-flex align-items-center">
    <nav class="navbar navbar-expand">
        <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
         <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3"><?= Html::encode($titleMap[$controller]) ?></div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="<?= $iconMap[$controller] ?>"></i></a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="top-menu ms-auto">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item dropdown dropdown-large">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">12</span>
                                    <i class='bx bx-task'></i>
                    </a>
                    <div class="header-message-list dropdown-menu dropdown-menu-end">
                        <a href="javascript:;">
                            <div class="msg-header">
                                <p class="msg-header-title">Agenda</p>
                            </div>
                        </a>
                        <div class="header-notifications-list ">
                            <div class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <div class="notify"><i class="bx bx-right-arrow"></i></div>
                                    <div class="flex-grow-1">
                                        <p class="msg-info">Texto de la tarea para tenerla presente en todo momento de la navegación</p>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <div class="notify"><i class="bx bx-right-arrow"></i></div>
                                                <div class="flex-grow-1">
                                                    <p class="msg-info">Texto de la tarea para tenerla presente en todo momento de la navegación</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-item">
                                            <div class="d-flex align-items-center">
                                                <div class="notify"><i class="bx bx-right-arrow"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="msg-info">Texto de la tarea para tenerla presente en todo momento de la navegación</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-item">
                                            <div class="d-flex align-items-center">
                                                <div class="notify"><i class="bx bx-right-arrow"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="msg-info">Texto de la tarea para tenerla presente en todo momento de la navegación</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-item">
                                            <div class="d-flex align-items-center">
                                                <div class="notify"><i class="bx bx-right-arrow"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="msg-info">Texto de la tarea para tenerla presente en todo momento de la navegación</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-item">
                                            <div class="d-flex align-items-center">
                                                <div class="notify"><i class="bx bx-right-arrow"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="msg-info">Texto de la tarea para tenerla presente en todo momento de la navegación</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-item">
                                            <div class="d-flex align-items-center">
                                                <div class="notify"><i class="bx bx-right-arrow"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="msg-info">Texto de la tarea para tenerla presente en todo momento de la navegación</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-item">
                                            <div class="d-flex align-items-center">
                                                <div class="notify"><i class="bx bx-right-arrow"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="msg-info">Texto de la tarea para tenerla presente en todo momento de la navegación</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-item">
                                            <div class="d-flex align-items-center">
                                                <div class="notify"><i class="bx bx-right-arrow"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="msg-info">Texto de la tarea para tenerla presente en todo momento de la navegación</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-item">
                                            <div class="d-flex align-items-center">
                                                <div class="notify"><i class="bx bx-right-arrow"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="msg-info">Texto de la tarea para tenerla presente en todo momento de la navegación</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#">
                                        <div class="text-center msg-footer">Ir a agenda</div>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-cog bx-spin"></i>
                            <div class="user-info ps-3">
                                <p class="user-name mb-0"><?= Yii::$app->user->identity->usu_nombre.' '.Yii::$app->user->identity->usu_apellido?></p>
                                <p class="designattion mb-0">Cooperativa</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:;"><i class="bx bx-user"></i><span>Perfil</span></a>
                            </li>
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li>
                                <?= Html::a('<i class="bx bx-log-out-circle"></i><span>Cerrar sesión</span>', ['/site/logout'], [
                                    'class' => 'dropdown-item',
                                    'data' => ['method' => 'post'],
                                ]) ?>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>