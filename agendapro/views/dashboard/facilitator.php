<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chalkboard-teacher"></i> Mi Dashboard
            <small class="text-muted">- Bienvenido, <?php echo htmlspecialchars($facilitador_nombre); ?></small>
        </h1>
        <!--
        <div>
            <span class="badge bg-primary p-2">
                <i class="fas fa-user"></i> <?php echo htmlspecialchars($facilitador_nombre); ?>
            </span>
        </div>
        -->
    </div>

    <!-- Statistics Cards - Solo sus estadísticas -->
    <div class="row">
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Mis Reservas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_reservas; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Mis Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $reservas_pendientes; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Mis Confirmadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $reservas_confirmadas; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-purple shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">
                                Mis OTECs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_otec; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Cursos Impartidos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_cursos; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Horas Impartidas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($horas_totales, 0, ',', '.'); ?> hrs</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Gráfico de Reservas por Mes -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line"></i> Mis Reservas por Mes
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="reservasChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Próximas Reservas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-week"></i> Próximas Reservas
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($proximas_reservas)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($proximas_reservas as $reserva): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo htmlspecialchars($reserva['curso_nombre'] ?? 'N/A'); ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-building"></i> <?php echo htmlspecialchars($reserva['otec_nombre'] ?? 'N/A'); ?>
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($reserva['fecha_inicio'])); ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-<?php 
                                        echo $reserva['estado'] == 'confirmada' ? 'success' : 
                                            ($reserva['estado'] == 'pendiente' ? 'warning' : 'info'); 
                                    ?>">
                                        <?php echo ucfirst($reserva['estado']); ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-check fa-3x text-gray-300 mb-2"></i>
                            <p class="text-muted mb-0">No hay próximas reservas</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- OTEC y Cursos -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-building"></i> OTEC Atendidas
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($otecTrabajadas)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>OTEC</th>
                                        <th>Reservas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($otecTrabajadas as $otec): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($otec['nombre']); ?></td>
                                        <td><span class="badge bg-primary"><?php echo $otec['total_reservas']; ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No hay OTEC registradas</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book"></i> Cursos que Imparto
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($cursos_impartidos)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Curso</th>
                                        <th>Modalidad</th>
                                        <th>Veces</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cursos_impartidos as $curso): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($curso['nombre']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $curso['modalidad'] == 'online' ? 'bg-info' : 'bg-success'; ?>">
                                                <?php echo $curso['modalidad'] == 'online' ? 'Online' : 'Presencial'; ?>
                                            </span>
                                        </td>
                                        <td><span class="badge bg-secondary"><?php echo $curso['total_veces']; ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No hay cursos registrados</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservas Recientes -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Mis Reservas Recientes
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($reservas_recientes)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Curso</th>
                                        <th>OTEC</th>
                                        <th>Fecha Inicio</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservas_recientes as $reserva): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($reserva['curso_nombre'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($reserva['otec_nombre'] ?? 'N/A'); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($reserva['fecha_inicio'])); ?></td>
                                        <td>
                                            <?php
                                            $badgeClass = 'secondary';
                                            if ($reserva['estado'] == 'confirmada') $badgeClass = 'success';
                                            if ($reserva['estado'] == 'pendiente') $badgeClass = 'warning';
                                            if ($reserva['estado'] == 'aprobada') $badgeClass = 'info';
                                            if ($reserva['estado'] == 'rechazada') $badgeClass = 'danger';
                                            ?>
                                            <span class="badge bg-<?php echo $badgeClass; ?>">
                                                <?php echo ucfirst($reserva['estado']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>/bookings/view/<?php echo $reserva['id']; ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                         </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-gray-300 mb-2"></i>
                            <p class="text-muted mb-0">No hay reservas recientes</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-purple {
    background-color: #9b59b6;
    color: white;
}
.text-purple {
    color: #9b59b6;
}
.border-left-purple {
    border-left: 0.25rem solid #9b59b6 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de reservas por mes
    var ctx = document.getElementById('reservasChart');
    if (ctx && <?php echo json_encode($reservas_por_mes); ?>) {
        var reservasData = <?php echo json_encode($reservas_por_mes); ?>;
        var labels = reservasData.map(function(item) { return item.periodo; });
        var values = reservasData.map(function(item) { return item.total; });
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Mis Reservas',
                    data: values,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>