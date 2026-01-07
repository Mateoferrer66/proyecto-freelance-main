<?php

/** @var yii\web\View $this */

$this->title = 'Dashboard Freelance';

// Register Chart.js from CDN
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => \yii\web\View::POS_HEAD]);
?>
<div class="site-index dashboard-container">
    <div class="row g-3">
        <!-- LEFT COLUMN -->
        <div class="col-12">
            
            <!-- VENTAS SECTION -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="text-white mb-0">RESUMEN</h5>
                <span class="text-secondary small">Vista general</span>
            </div>
            
            <div class="row g-2 mb-3">
                <!-- Card 1: Facturas Pendientes (was Total Ventas) -->
                <div class="col-6 col-md-3">
                    <div class="dashboard-card p-2 p-md-3">
                        <div class="icon-box warning mb-2">
                             <span class="material-icons text-warning">receipt_long</span>
                        </div>
                        <h4 class="text-white mb-0"><?= $countFacturasPendientes ?></h4>
                        <div class="text-secondary small">Facturas Pendientes</div>
                        <div class="text-warning x-small">+10% desde ayer</div>
                    </div>
                </div>
                <!-- Card 2: Total Usuarios (was Total Ordenes) -->
                <div class="col-6 col-md-3">
                    <div class="dashboard-card p-2 p-md-3">
                        <div class="icon-box success mb-2">
                             <span class="material-icons text-success">perm_identity</span>
                        </div>
                        <h4 class="text-white mb-0"><?= $countUsuarios ?></h4>
                        <div class="text-secondary small">Total Usuarios</div>
                        <div class="text-success x-small">+8% desde ayer</div>
                    </div>
                </div>
                <!-- Card 3: Presupuestos Pendientes -->
                <div class="col-6 col-md-3">
                    <div class="dashboard-card p-2 p-md-3">
                        <div class="icon-box danger mb-2">
                             <span class="material-icons text-danger">request_quote</span>
                        </div>
                        <h4 class="text-white mb-0"><?= $countPresupuestosPendientes ?></h4>
                        <div class="text-secondary small">Presupuestos Pendientes</div>
                        <div class="text-secondary x-small">+2% desde ayer</div>
                    </div>
                </div>
                <!-- Card 4: Clientes (was Nuevos Clientes) -->
                 <div class="col-6 col-md-3">
                    <div class="dashboard-card p-2 p-md-3">
                        <div class="icon-box info mb-2">
                             <span class="material-icons text-info">person_add</span>
                        </div>
                        <h4 class="text-white mb-0"><?= $countClientes ?></h4>
                        <div class="text-secondary small">Total Clientes</div>
                        <div class="text-info x-small">+3% desde ayer</div>
                    </div>
                </div>
            </div>

            <!-- TABLAS DE PENDIENTES -->
            <div class="row g-2">
                <!-- Facturas Pendientes -->
                <div class="col-12 col-xl-6 mb-3">
                    <div class="dashboard-card p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-white mb-0">Facturas Pendientes por Aprobar</h5>
                            <a href="<?= \yii\helpers\Url::to(['factura/index']) ?>" class="btn btn-sm btn-outline-warning">Ver Todo</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-dark table-borderless text-secondary align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(count($facturasPendientes) > 0): ?>
                                        <?php foreach ($facturasPendientes as $fac): ?>
                                        <tr>
                                            <td><?= $fac->fac_numero ?></td>
                                            <td><?= $fac->cli ? $fac->cli->cli_nombre : 'N/A' ?></td>
                                            <td><?= $fac->fac_fecha ?></td>
                                            <td class="text-end"><?= $fac->fac_total ?> <?= $fac->fac_money ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4" class="text-center">No hay facturas pendientes.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Presupuestos Pendientes -->
                <div class="col-12 col-xl-6 mb-3">
                    <div class="dashboard-card p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-white mb-0">Presupuestos Pendientes</h5>
                            <a href="<?= \yii\helpers\Url::to(['presupuesto/index']) ?>" class="btn btn-sm btn-outline-danger">Ver Todo</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-dark table-borderless text-secondary align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(count($presupuestosPendientes) > 0): ?>
                                        <?php foreach ($presupuestosPendientes as $pre): ?>
                                        <tr>
                                            <td><?= $pre->pre_numero ?></td>
                                            <td><?= $pre->cli ? $pre->cli->cli_nombre : 'N/A' ?></td>
                                            <td><?= $pre->pre_fecha ?></td>
                                            <td class="text-end"><?= $pre->pre_total ?> <?= $pre->pre_money ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4" class="text-center">No hay presupuestos pendientes.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PROGRESO / CLIENTES SECTION -->
             <div class="row g-2 mt-2">
                <!-- Importes Facturados por Cliente (Replaces Gauge) -->
                <div class="col-12 col-md-5">
                     <div class="dashboard-card p-3 h-100">
                        <div class="mb-4">
                            <h5 class="text-white mb-0">FACTURADO POR CLIENTE</h5>
                            <div class="text-secondary small">Distribución de Ingresos</div>
                        </div>
                        <div class="chart-container" style="position: relative; height: 200px; width:100%">
                            <canvas id="clientChart"></canvas>
                        </div>
                     </div>
                </div>
                
                 <!-- Customers per Year Chart - MANTENIDO -->
                <div class="col-12 col-md-7">
                     <div class="dashboard-card p-3 h-100 position-relative header-on-chart">
                        <div class="d-flex justify-content-between">
                            <h5 class="text-white mb-0">CLIENTES AL AÑO</h5>
                            <span class="badge bg-dark border border-warning text-warning">Nuevos Clientes</span>
                        </div>
                        
                        <div class="chart-area mt-4 d-flex align-items-end justify-content-between" style="height: 150px;">
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

    </div>
</div>

<?php
// PHP Matrix to JS for Client Chart
$jsClientLabels = json_encode($clientChartLabels);
$jsClientData = json_encode($clientChartData);
$jsClientColors = json_encode($clientChartColors);

$script = <<< JS
document.addEventListener("DOMContentLoaded", function() {
    // Client Chart (Pie or Bar)
    var ctxClient = document.getElementById('clientChart').getContext('2d');
    var clientChart = new Chart(ctxClient, {
        type: 'pie', 
        data: {
            labels: $jsClientLabels,
            datasets: [{
                label: 'Total Facturado',
                data: $jsClientData,
                backgroundColor: $jsClientColors,
                borderColor: 'rgba(0,0,0,0.1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right', // Legend on right for smaller box
                    labels: { 
                        color: '#adb5bd',
                        boxWidth: 10,
                        font: { size: 10 }
                    }
                }
            }
        }
    });
});
JS;
$this->registerJs($script);
?>
