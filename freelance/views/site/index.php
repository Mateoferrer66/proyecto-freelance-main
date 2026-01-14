<?php

/** @var yii\web\View $this */

$this->title = 'Dashboard Freelance';
?>
<!-- Load Chart.js directly (UMD version for global access) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>

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
                        <div class="text-warning x-small"><?= $statFactura ?></div>
                    </div>
                </div>
                <!-- Card 2: Total Socios (was Total Usuarios) -->
                <div class="col-6 col-md-3">
                    <div class="dashboard-card p-2 p-md-3">
                        <div class="icon-box success mb-2">
                             <span class="material-icons text-success">perm_identity</span>
                        </div>
                        <h4 class="text-white mb-0"><?= $countSocios ?></h4>
                        <div class="text-secondary small">Total Socios</div>
                        <div class="text-success x-small"><?= $statSocios ?> por total de socios</div>
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
                        <div class="text-secondary x-small"><?= $statPresupuesto ?></div>
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
                        <div class="text-info x-small"><?= $statClientes ?></div>
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
                <!-- Facturado Por Socio (Was Facturado por Cliente) -->
                <div class="col-12 col-md-5">
                     <div class="dashboard-card p-3 h-100">
                        <div class="mb-4">
                            <h5 class="text-white mb-0">FACTURADO POR SOCIO</h5>
                            <div class="text-secondary small">Top 5 Socios</div>
                        </div>
                        <div class="chart-container mb-3" style="position: relative; height: 180px; width:100%">
                            <canvas id="socioChart"></canvas>
                        </div>
                        <!-- Top List -->
                        <div class="mt-3">
                            <h6 class="text-white-50 small mb-2 text-uppercase">Mejores Socios</h6>
                            <ul class="list-group list-group-flush bg-transparent" style="max-height: 200px; overflow-y: auto;">
                                <?php foreach($socioList as $idx => $socio): ?>
                                <li class="list-group-item bg-transparent text-secondary d-flex justify-content-between px-0 py-1 border-0">
                                    <span><?= $idx + 1 ?>. <?= $socio['name'] ?></span>
                                    <span class="text-white"><?= number_format($socio['amount'], 2) ?> €</span>
                                </li>
                                <?php endforeach; ?>
                                <?php if(empty($socioList)): ?>
                                    <li class="list-group-item bg-transparent text-secondary small px-0">Sin datos de facturación.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                     </div>
                </div>
                
                 <!-- Active Clients per Year Chart (Dynamic) -->
                <div class="col-12 col-md-7">
                     <div class="dashboard-card p-3 h-100 position-relative header-on-chart">
                        <div class="d-flex justify-content-between">
                            <h5 class="text-white mb-0">CLIENTES AL AÑO (NUEVOS <?= $clientsChartYear ?>)</h5>
                            <span class="badge bg-dark border border-warning text-warning">Tiempo Real</span>
                        </div>
                        
                        <div class="chart-area mt-4 d-flex align-items-center justify-content-center" style="height: 250px;">
                             <canvas id="clientActivityChart"></canvas>
                        </div>
                     </div>
                </div>
             </div>

        </div>

    </div>
</div>

<?php
// JS Data injection
$jsSocioLabels = json_encode($socioChartLabels);
$jsSocioData = json_encode($socioChartData);
$jsSocioColors = json_encode($socioChartColors);

$jsClientActivityData = json_encode($clientActivityData);

$script = <<< JS
(function() {
    function initDashboardCharts() {
        console.log("Dashboard JS initializing...");
        
        if (typeof Chart === 'undefined') {
            console.error("Chart.js is not loaded!");
            return;
        }

        try {
            // 1. Socio Chart (Pie)
            var ctxSocio = document.getElementById('socioChart');
            if (ctxSocio) {
                // Destroy existing chart if any (prevents duplicate canvas issues)
                var existingSocio = Chart.getChart(ctxSocio);
                if (existingSocio) existingSocio.destroy();

                new Chart(ctxSocio.getContext('2d'), {
                    type: 'doughnut', 
                    data: {
                        labels: $jsSocioLabels,
                        datasets: [{
                            data: $jsSocioData,
                            backgroundColor: $jsSocioColors,
                            borderColor: '#2b2b2b',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: { color: '#adb5bd', boxWidth: 10, font: { size: 10 } }
                            }
                        },
                        cutout: '70%'
                    }
                });
            } else {
                console.error("Canvas socioChart not found");
            }

            // 2. Client Activity Chart (Line/Area)
            var ctxActivity = document.getElementById('clientActivityChart');
            if (ctxActivity) {
                var ctx2d = ctxActivity.getContext('2d');
                
                // Destroy existing chart if any
                var existingActivity = Chart.getChart(ctxActivity);
                if (existingActivity) existingActivity.destroy();
                
                // Create gradient
                var gradient = ctx2d.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(75, 192, 192, 0.5)'); // Top color
                gradient.addColorStop(1, 'rgba(75, 192, 192, 0.0)'); // Bottom color

                new Chart(ctx2d, {
                    type: 'line',
                    data: {
                        labels: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                        datasets: [{
                            label: 'Nuevos Clientes',
                            data: $jsClientActivityData,
                            borderColor: '#4bc0c0',
                            backgroundColor: gradient,
                            borderWidth: 2,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#4bc0c0',
                            pointHoverBackgroundColor: '#4bc0c0',
                            pointHoverBorderColor: '#fff',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                ticks: { color: '#adb5bd' }
                            },
                            y: {
                                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                ticks: { color: '#adb5bd', stepSize: 1 },
                                beginAtZero: true
                            }
                        }
                    }
                });
            } else {
                 console.error("Canvas clientActivityChart not found");
            }
        } catch (e) {
            console.error("Error creating charts:", e);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDashboardCharts);
    } else {
        initDashboardCharts();
    }
})();
JS;
$this->registerJs($script);
?>
