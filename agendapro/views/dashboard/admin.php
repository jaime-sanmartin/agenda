<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-shield"></i> Dashboard - Administrador
        </h1>
        <div>
            <a href="<?php echo BASE_URL; ?>/auth/solicitudes_pendientes" class="btn btn-warning btn-sm">
                <i class="fas fa-user-clock"></i> Solicitudes Pendientes
                <?php if ($solicitudes_pendientes > 0): ?>
                    <span class="badge bg-danger ms-1"><?php echo $solicitudes_pendientes; ?></span>
                <?php endif; ?>
            </a>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Usuarios
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_usuarios; ?></div>
                            <small>Facilitadores: <?php echo $total_facilitadores; ?> | Ejecutivos: <?php echo $total_ejecutivos; ?> | Administradores: <?php echo $total_administradores; ?></small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                Total Cursos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_cursos; ?></div>
                            <small>Presenciales: <?php echo $total_presenciales; ?> | Online: <?php echo $total_online; ?> | Hibridos: <?php echo $total_hibridos; ?></small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
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
    </div>

    <!-- Gráfico de Reservas por Estado -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie"></i> Reservas por Estado
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="estadosChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-clock"></i> Solicitudes Pendientes
                    </h6>
                </div>
                <div class="card-body text-center">
                    <?php if ($solicitudes_pendientes > 0): ?>
                        <div class="display-1 text-warning"><?php echo $solicitudes_pendientes; ?></div>
                        <p class="mb-0">Solicitudes de registro de facilitadores esperando aprobación</p>
                        <a href="<?php echo BASE_URL; ?>/auth/solicitudes_pendientes" class="btn btn-primary mt-3">
                            <i class="fas fa-eye"></i> Revisar Solicitudes
                        </a>
                    <?php else: ?>
                        <div class="display-1 text-success">0</div>
                        <p class="mb-0">No hay solicitudes pendientes</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos registros -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-plus"></i> Últimos Usuarios Registrados
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($ultimos_usuarios)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ultimos_usuarios as $usuario): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $usuario['rol'] == 'facilitador' ? 'primary' : ($usuario['rol'] == 'administrador' ? 'danger' : 'info'); 
                                            ?>">
                                                <?php echo ucfirst($usuario['rol']); ?>
                                            </span>
                                        </div>
                                      </div>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No hay usuarios registrados</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-ticket-alt"></i> Últimas Reservas
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($ultimas_reservas)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Curso</th>
                                        <th>OTEC</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ultimas_reservas as $reserva): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($reserva['curso_nombre'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($reserva['otec_nombre'] ?? 'N/A'); ?></div>
                                          </div>
                                        <td>
                                            <?php
                                            $badgeClass = 'secondary';
                                            if ($reserva['estado'] == 'pendiente') $badgeClass = 'warning';
                                            if ($reserva['estado'] == 'aprobada') $badgeClass = 'success';
                                            if ($reserva['estado'] == 'rechazada') $badgeClass = 'danger';
                                            ?>
                                            <span class="badge bg-<?php echo $badgeClass; ?>">
                                                <?php echo ucfirst($reserva['estado']); ?>
                                            </span>
                                        </div>
                                      </div>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No hay reservas registradas</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const estadosData = <?php echo json_encode($reservas_por_estado); ?>;
    const ctx = document.getElementById('estadosChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Pendiente', 'Aprobada', 'Rechazada', 'Anulada'],
            datasets: [{
                data: [
                    estadosData.pendiente || 0,
                    estadosData.aprobada || 0,
                    estadosData.rechazada || 0,
                    estadosData.anulada || 0
                ],
                backgroundColor: ['#f39c12', '#2ecc71', '#e74c3c', '#95a5a6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>