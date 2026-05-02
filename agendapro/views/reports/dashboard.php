<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar"></i> Reportes y Estadísticas
        </h1>
        <div>
            <button onclick="window.print()" class="btn btn-secondary btn-sm">
                <i class="fas fa-print"></i> Imprimir
            </button>
        </div>
    </div>

    <!-- Tarjetas de estadísticas generales -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Cursos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_cursos; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total OTEC
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_otec; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Reservas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_reservas; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Ingresos Totales
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                $<?php echo number_format($ingresos_totales, 0, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda fila de tarjetas -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Horas Impartidas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($horas_totales, 0, ',', '.'); ?> hrs</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Rendimiento por Hora
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                $<?php echo number_format($rendimiento_promedio, 0, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos principales -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line"></i> Ingresos por Mes
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="ingresosChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie"></i> Reservas por Estado
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="estadosChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trophy"></i> Cursos Más Populares
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="cursosChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line"></i> Evolución de Reservas
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="evolucionChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Gráfico de Ingresos por Mes
    const ingresosData = <?php echo json_encode($ingresos_por_mes); ?>;
    if (ingresosData.length > 0) {
        const ctxIngresos = document.getElementById('ingresosChart').getContext('2d');
        new Chart(ctxIngresos, {
            type: 'line',
            data: {
                labels: ingresosData.map(item => {
                    const [year, month] = item.mes.split('-');
                    const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                    return meses[parseInt(month) - 1] + ' ' + year;
                }),
                datasets: [{
                    label: 'Ingresos ($)',
                    data: ingresosData.map(item => item.total),
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw.toLocaleString('es-CL');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString('es-CL');
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. Gráfico de Reservas por Estado
    const estadosData = <?php echo json_encode($reservas_por_estado); ?>;
    const ctxEstados = document.getElementById('estadosChart').getContext('2d');
    new Chart(ctxEstados, {
        type: 'doughnut',
        data: {
            labels: ['Pendiente', 'Aprobada', 'Confirmada', 'Rechazada', 'Finalizada'],
            datasets: [{
                data: [
                    estadosData.pendiente || 0,
                    estadosData.aprobada || 0,
                    estadosData.confirmada || 0,
                    estadosData.rechazada || 0,
                    estadosData.finalizada || 0
                ],
                backgroundColor: ['#f39c12', '#3498db', '#2ecc71', '#e74c3c', '#95a5a6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // 3. Gráfico de Cursos Populares
    const cursosData = <?php echo json_encode($cursos_populares); ?>;
    if (cursosData.length > 0) {
        const ctxCursos = document.getElementById('cursosChart').getContext('2d');
        new Chart(ctxCursos, {
            type: 'bar',
            data: {
                labels: cursosData.map(item => item.nombre),
                datasets: [{
                    label: 'Número de Reservas',
                    data: cursosData.map(item => item.total_reservas),
                    backgroundColor: '#4e73df',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }

    // 4. Gráfico de Evolución de Reservas
    const evolucionData = <?php echo json_encode($evolucion_reservas); ?>;
    if (evolucionData.length > 0) {
        const ctxEvolucion = document.getElementById('evolucionChart').getContext('2d');
        new Chart(ctxEvolucion, {
            type: 'line',
            data: {
                labels: evolucionData.map(item => item.periodo),
                datasets: [{
                    label: 'Cantidad de Reservas',
                    data: evolucionData.map(item => item.total),
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' reservas';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

<style>
@media print {
    .sidebar, .navbar, .btn, .sidebar-toggle, .card-header .btn {
        display: none !important;
    }
    .main-content, .card {
        margin: 0 !important;
        padding: 0 !important;
    }
    body {
        background: white;
    }
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>