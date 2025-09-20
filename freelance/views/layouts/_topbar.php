<?php

use yii\helpers\Html;

// Obtener la URL actual para determinar el ícono
$currentUrl = Yii::$app->request->url;

// Mapeo de rutas a clases de íconos de Boxicons
$iconMap = [
    // Mapeo para las subsecciones de Utilidades
    'r=empresa' => 'bx-store-alt',
    'r=consecutivo' => 'bx-right-indent',
    'r=iva' => 'bx-calculator',
    'r=concepto-facturacion' => 'bx-task',
    'r=concepto-liquidacion' => 'bx-terminal',
    'r=banco' => 'bx-building-house',
    'r=forma-de-pago' => 'bx-dollar',
    'r=tipo-doc-identidad' => 'bx-id-card',
    'r=categoria' => 'bx-grid-alt',
    'r=provincia' => 'bx-flag',
    'r=utilidades' => 'bx-diamond', // Este es el principal de utilidades
];

$currentIcon = 'bx-task'; // Icono por defecto
foreach ($iconMap as $path => $iconClass) {
    if (strpos($currentUrl, $path) !== false) {
        $currentIcon = $iconClass;
        break;
    }
}
?>

<header class="topbar">
    <nav class="navbar navbar-expand">

        <!-- Título dinámico del módulo -->
        <div class="page-breadcrumb d-flex align-items-center px-3 py-2">
            <div class="breadcrumb-title pe-3">
                <?= Html::encode($this->title ?? 'Utilidades') ?>
            </div>
        </div>
        <i class="bx <?= Html::encode($currentIcon) ?> bx-sm"></i>
        <!-- Menú superior derecho -->
        <div class="top-menu ms-auto">
            <ul class="navbar-nav align-items-center">
                <!-- Dropdown de accesos rápidos -->
                <li class="nav-item dropdown dropdown-large">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                        <div class="projects">
                            <i class='bx bx-task'></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div style="background-color: #2a2f3c; color: #e0e0e0; padding: 15px; width: 350px;">
                            <h6 style="margin-bottom: 15px; font-weight: bold; font-size: 1.1rem;">Agenda</h6>
                            
                            <a href="#" class="dropdown-item" style="padding: 10px 0; border-bottom: 1px solid #4f5460;">
                                <div style="display: flex; align-items: center;">
                                    <div style="margin-right: 15px;">
                                        <div style="width: 32px; height: 32px; border: 1px solid #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="bx bx-play" style="font-size: 20px; color: #e0e0e0;"></i>
                                        </div>
                                    </div>
                                    <div style="color: #e0e0e0; font-size: 0.9rem; line-height: 1.3;">
                                        Texto de la tarea para tenerla presente en<br>
                                        todo momento de la navegación
                                    </div>
                                </div>
                            </a>
                            
                            <a href="#" class="dropdown-item" style="padding: 10px 0; border-bottom: 1px solid #4f5460;">
                                <div style="display: flex; align-items: center;">
                                    <div style="margin-right: 15px;">
                                        <div style="width: 32px; height: 32px; border: 1px solid #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="bx bx-play" style="font-size: 20px; color: #e0e0e0;"></i>
                                        </div>
                                    </div>
                                    <div style="color: #e0e0e0; font-size: 0.9rem; line-height: 1.3;">
                                        Texto de la tarea para tenerla presente en<br>
                                        todo momento de la navegación
                                    </div>
                                </div>
                            </a>
                            
                            <a href="#" class="dropdown-item" style="padding: 10px 0; border-bottom: 1px solid #4f5460;">
                                <div style="display: flex; align-items: center;">
                                    <div style="margin-right: 15px;">
                                        <div style="width: 32px; height: 32px; border: 1px solid #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="bx bx-play" style="font-size: 20px; color: #e0e0e0;"></i>
                                        </div>
                                    </div>
                                    <div style="color: #e0e0e0; font-size: 0.9rem; line-height: 1.3;">
                                        Texto de la tarea para tenerla presente en<br>
                                        todo momento de la navegación
                                    </div>
                                </div>
                            </a>
                            
                            <a href="#" class="dropdown-item" style="padding: 10px 0; border-bottom: 1px solid #4f5460;">
                                <div style="display: flex; align-items: center;">
                                    <div style="margin-right: 15px;">
                                        <div style="width: 32px; height: 32px; border: 1px solid #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="bx bx-play" style="font-size: 20px; color: #e0e0e0;"></i>
                                        </div>
                                    </div>
                                    <div style="color: #e0e0e0; font-size: 0.9rem; line-height: 1.3;">
                                        Texto de la tarea para tenerla presente en<br>
                                        todo momento de la navegación
                                    </div>
                                </div>
                            </a>
                            
                            <a href="#" class="dropdown-item" style="padding: 10px 0;">
                                <div style="display: flex; align-items: center;">
                                    <div style="margin-right: 15px;">
                                        <div style="width: 32px; height: 32px; border: 1px solid #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="bx bx-play" style="font-size: 20px; color: #e0e0e0;"></i>
                                        </div>
                                    </div>
                                    <div style="color: #e0e0e0; font-size: 0.9rem; line-height: 1.3;">
                                        Texto de la tarea para tenerla presente en<br>
                                        todo momento de la navegación
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Dropdown de usuario -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                        <div class="user-box d-flex align-items-center">
                            <i class="bx bx-cog bx-spin"></i>
                            <div class="user-info ps-2">
                                <p class="user-name mb-0">Nombre del Usuario</p>
                                <p class="designattion mb-0">Super Administrador</p>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bx bx-user"></i><span>Perfil</span></a></li>
                        <li><a class="dropdown-item" href="/logout"><i class="bx bx-log-out-circle"></i><span>Cerrar sesión</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>