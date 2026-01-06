<?php

/** @var yii\web\View $this */

$this->title = 'Dashboard Freelance';
?>
<div class="site-index dashboard-container">

    <div class="row g-4">
        <!-- LEFT COLUMN -->
        <div class="col-lg-8">
            
            <!-- VENTAS SECTION -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white">VENTAS</h5>
                <span class="text-secondary small">Progreso de ventas</span>
            </div>
            
            <div class="row g-3 mb-4">
                <!-- Card 1 -->
                <div class="col-md-3">
                    <div class="dashboard-card p-3">
                        <div class="icon-box warning mb-2">
                             <span class="material-icons text-warning">analytics</span>
                        </div>
                        <h4 class="text-white mb-0">$5k</h4>
                        <div class="text-secondary small">Total Ventas</div>
                        <div class="text-warning x-small">+10% desde ayer</div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-md-3">
                    <div class="dashboard-card p-3">
                        <div class="icon-box success mb-2">
                             <span class="material-icons text-success">assignment</span>
                        </div>
                        <h4 class="text-white mb-0">500</h4>
                        <div class="text-secondary small">Total Ordenes</div>
                        <div class="text-success x-small">+8% desde ayer</div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-md-3">
                    <div class="dashboard-card p-3">
                        <div class="icon-box danger mb-2">
                             <span class="material-icons text-danger">shopping_bag</span>
                        </div>
                        <h4 class="text-white mb-0">9</h4>
                        <div class="text-secondary small">Productos</div>
                        <div class="text-secondary x-small">+2% desde ayer</div>
                    </div>
                </div>
                <!-- Card 4 -->
                 <div class="col-md-3">
                    <div class="dashboard-card p-3">
                        <div class="icon-box info mb-2">
                             <span class="material-icons text-info">person_add</span>
                        </div>
                        <h4 class="text-white mb-0">12</h4>
                        <div class="text-secondary small">Nuevos Clientes</div>
                        <div class="text-info x-small">+3% desde ayer</div>
                    </div>
                </div>
            </div>

            <!-- PERSONAS FREELANCE SECTION -->
            <div class="dashboard-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-white mb-0">PERSONAS FREELANCE</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-borderless text-secondary align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th style="width: 40%">Progreso</th>
                                <th class="text-end">Ventas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td>Mateo Ferrer</td>
                                <td>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 46%"></div>
                                    </div>
                                </td>
                                <td class="text-end"><span class="badge bg-dark border border-warning text-warning">46%</span></td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>Mateo Ferrer</td>
                                <td>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 17%"></div>
                                    </div>
                                </td>
                                <td class="text-end"><span class="badge bg-dark border border-info text-info">17%</span></td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td>Mateo Ferrer</td>
                                <td>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 19%"></div>
                                    </div>
                                </td>
                                <td class="text-end"><span class="badge bg-dark border border-primary text-primary">19%</span></td>
                            </tr>
                             <tr>
                                <td>04</td>
                                <td>Mateo Ferrer</td>
                                <td>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 29%"></div>
                                    </div>
                                </td>
                                <td class="text-end"><span class="badge bg-dark border border-secondary text-secondary">29%</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PROGRESO (BOTTOM LEFT) -->
             <div class="row g-4">
                <div class="col-md-5">
                     <div class="dashboard-card p-4 h-100">
                        <div class="mb-4">
                            <h5 class="text-white mb-0">PROGRESO</h5>
                            <div class="text-secondary small">Total Expuesto</div>
                            <h2 class="text-white mt-2">$6078.76</h2>
                             <div class="text-secondary x-small">Mes 48% mas rentable que el mes anterior</div>
                        </div>
                        <div class="position-relative text-center" style="height: 150px; overflow: hidden;">
                            <!-- CSS Only Semi Circle Gauge -->
                            <div class="gauge-container">
                                <div class="gauge-bg"></div>
                                <div class="gauge-value" style="transform: rotate(144deg);"></div> <!-- 80% of 180deg = 144deg -->
                                <div class="gauge-cover">
                                    <span class="h2 text-white">80%</span>
                                </div>
                            </div>
                        </div>
                     </div>
                </div>
                
                 <!-- Customers per Year Chart (Bottom Middle - actually part of left col in design but can be split differently)
                      In the design, "Clientes al Año" spans nicely. Let's make it fill the remaining space next to Progreso -->
                <div class="col-md-7">
                     <div class="dashboard-card p-4 h-100 position-relative header-on-chart">
                        <div class="d-flex justify-content-between">
                            <h5 class="text-white mb-0">CLIENTES AL AÑO</h5>
                            <span class="badge bg-dark border border-warning text-warning">Nuevos Clientes</span>
                        </div>
                        
                         <!-- Hand-coded CSS/SVG Area Chart -->
                        <div class="chart-area mt-4 d-flex align-items-end justify-content-between" style="height: 150px;">
                             <!-- Mock Bars/Points for the "Wave" look using CSS clip-path or simple SVG -->
                             <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="width: 100%; height: 100%;">
                                <defs>
                                    <linearGradient id="grad2" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:rgb(75, 192, 192);stop-opacity:0.5" />
                                    <stop offset="100%" style="stop-color:rgb(75, 192, 192);stop-opacity:0" />
                                    </linearGradient>
                                </defs>
                                <path d="M0,150 L0,120 L50,130 L100,100 L120,50 L150,110 L200,80 L250,50 L300,90 L350,60 L400,100 L450,80 L500,60 L500,150 Z" 
                                      fill="url(#grad2)" stroke="none" />
                                <path d="M0,120 L50,130 L100,100 L120,50 L150,110 L200,80 L250,50 L300,90 L350,60 L400,100 L450,80 L500,60" 
                                      fill="none" stroke="#4bc0c0" stroke-width="2" />
                            </svg>
                        </div>
                        <div class="d-flex justify-content-between text-secondary x-small mt-2">
                            <span>Ene</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span>
                            <span>Jul</span><span>Aug</span><span>Sep</span><span>Oct</span><span>Nov</span><span>Dic</span>
                        </div>
                     </div>
                </div>
             </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-lg-4">
             <!-- SERVICIO SECTION -->
            <div class="dashboard-card p-4 mb-4">
                <h5 class="text-white mb-4">SERVICIO</h5>
                 <div class="d-flex justify-content-between align-items-end" style="height: 100px;">
                    <!-- Mock Bars -->
                    <div class="bar-group text-center">
                        <div class="bar bg-secondary opacity-25 rounded-top" style="height: 40px; width: 15px; margin: 0 auto;"></div>
                    </div>
                    <div class="bar-group text-center">
                        <div class="bar bg-info rounded-top" style="height: 60px; width: 15px; margin: 0 auto;"></div>
                    </div>
                     <div class="bar-group text-center">
                        <div class="bar bg-info rounded-top" style="height: 80px; width: 15px; margin: 0 auto;"></div>
                    </div>
                     <div class="bar-group text-center">
                        <div class="bar bg-secondary opacity-25 rounded-top" style="height: 50px; width: 15px; margin: 0 auto;"></div>
                    </div>
                     <div class="bar-group text-center">
                        <div class="bar bg-secondary opacity-25 rounded-top" style="height: 70px; width: 15px; margin: 0 auto;"></div>
                    </div>
                     <div class="bar-group text-center">
                        <div class="bar bg-info rounded-top" style="height: 40px; width: 15px; margin: 0 auto;"></div>
                    </div>
                     <div class="bar-group text-center">
                        <div class="bar bg-info rounded-top" style="height: 60px; width: 15px; margin: 0 auto;"></div>
                    </div>
                </div>
                 <div class="d-flex justify-content-center mt-3 gap-3">
                    <small class="text-white"><span class="badge bg-info p-1 rounded-circle me-1"> </span> Volumen</small>
                    <small class="text-secondary"><span class="badge bg-secondary p-1 rounded-circle me-1"> </span> Servicio</small>
                </div>
            </div>

             <!-- INGRESOS SECTION -->
            <div class="dashboard-card p-4">
                <h5 class="text-white mb-4">INGRESOS</h5>
                 
                 <div class="chart-container mb-3" style="height: 120px;">
                      <!-- Simple Mock Line Chart -->
                      <svg viewBox="0 0 200 100" preserveAspectRatio="none" style="width: 100%; height: 100%;">
                        <!-- Line 1 -->
                        <path d="M0,50 Q40,30 80,50 T160,50 T200,20" fill="none" stroke="#4bc0c0" stroke-width="2" />
                        <!-- Line 2 -->
                        <path d="M0,70 Q40,80 80,60 T160,80 T200,60" fill="none" stroke="#ffcd56" stroke-width="2" />
                        <!-- Filling area could be complex, sticking to lines for simplicity as placeholders -->
                      </svg>
                 </div>

                 <div class="d-flex justify-content-around text-center border-top border-secondary pt-3">
                     <div>
                        <small class="text-secondary d-block">● Mes Anterior</small>
                        <h5 class="text-white mb-0">$4,087</h5>
                     </div>
                      <div>
                        <small class="text-secondary d-block">● Este Mes</small>
                        <h5 class="text-white mb-0">$5,506</h5>
                     </div>
                 </div>
            </div>

        </div>
    </div>
</div>
