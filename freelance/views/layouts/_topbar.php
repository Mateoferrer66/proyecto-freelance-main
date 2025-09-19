<?php

use yii\helpers\Html;
?>

<header class="topbar">
    <nav class="navbar navbar-expand">

        <!-- Título dinámico del módulo -->
        <div class="page-breadcrumb d-flex align-items-center px-3 py-2">
            <div class="breadcrumb-title pe-3">
                <?= Html::encode($this->title ?? 'Utilidades') ?>
            </div>
        </div>
        <i class="bx bx-diamond"></i>
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
                        <div class="header-notifications-list">
                            <a class="dropdown-item" href="#">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">Agenda</h6>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">Perfil</h6>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item" href="/logout">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">Cerrar sesión</h6>
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
                            <img src="/assets-custom/images/icons/user.png" class="user-img" alt="user avatar">
                            <div class="user-info ps-3">
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