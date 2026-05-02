<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line"></i> Dashboard Ejecutivo
        </h1>
        <div>
            <a href="<?php echo BASE_URL; ?>/courses/public" class="btn btn-primary btn-sm">
                <i class="fas fa-book-open"></i> Ver Catálogo de Cursos
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Mis Reservas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $mis_reservas; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
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
                                Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $reservas_pendientes; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Confirmadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $reservas_confirmadas; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Finalizadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $reservas_finalizadas; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-flag-checkered fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cursos Disponibles Section -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book-open"></i> Cursos Disponibles para Solicitar
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($cursos_disponibles)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Curso</th>
                                    <th>Modalidad</th>
                                    <th>Duración</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cursos_disponibles as $curso): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($curso['nombre']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $curso['modalidad'] == 'online' ? 'bg-info' : 'bg-success'; ?>">
                                            <?php echo $curso['modalidad'] == 'online' ? 'Online' : 'Presencial'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $curso['duracion_horas']; ?> hrs</td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/bookings/create?curso_id=<?php echo $curso['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-calendar-plus"></i> Solicitar
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-book-open fa-3x text-gray-300 mb-3"></i>
                        <p class="text-muted">No hay cursos públicos disponibles en este momento.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Próximas Capacitaciones -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-week"></i> Próximas Capacitaciones
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($proximas_capacitaciones)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($proximas_capacitaciones as $cap): ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo htmlspecialchars($cap['curso_nombre'] ?? 'N/A'); ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($cap['fecha_inicio'])); ?>
                                        </small>
                                        <br>
                                        <?php
                                        // Función para obtener texto de estado
                                        $estadoTextos = [
                                            'propuesta' => 'Propuesta',
                                            'pendiente' => 'Pendiente',
                                            'aprobada' => 'Aprobada',
                                            'rechazada' => 'Rechazada',
                                            'confirmada' => 'Confirmada',
                                            'finalizada' => 'Finalizada'
                                        ];
                                        $estadoTexto = $estadoTextos[$cap['estado']] ?? $cap['estado'];
                                        
                                        $badgeClass = 'secondary';
                                        if ($cap['estado'] == 'confirmada') $badgeClass = 'success';
                                        if ($cap['estado'] == 'pendiente') $badgeClass = 'warning';
                                        if ($cap['estado'] == 'aprobada') $badgeClass = 'info';
                                        if ($cap['estado'] == 'rechazada') $badgeClass = 'danger';
                                        if ($cap['estado'] == 'propuesta') $badgeClass = 'purple';
                                        ?>
                                        <span class="badge bg-<?php echo $badgeClass; ?> mt-1"><?php echo $estadoTexto; ?></span>
                                    </div>
                                    <a href="<?php echo BASE_URL; ?>/bookings/view/<?php echo $cap['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-check fa-3x text-gray-300 mb-2"></i>
                            <p class="text-muted mb-0">No hay próximas capacitaciones</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Notificaciones Recientes -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bell"></i> Notificaciones Recientes
                        <?php if ($unread_count > 0): ?>
                        <span class="badge bg-danger float-end"><?php echo $unread_count; ?> nuevas</span>
                        <?php endif; ?>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($notificaciones)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($notificaciones as $notif): ?>
                            <div class="list-group-item <?php echo $notif['leido'] ? '' : 'bg-light'; ?>">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-<?php 
                                            echo $notif['tipo'] == 'success' ? 'check-circle text-success' : 
                                                ($notif['tipo'] == 'warning' ? 'exclamation-triangle text-warning' : 
                                                ($notif['tipo'] == 'danger' ? 'exclamation-circle text-danger' : 'info-circle text-info')); 
                                        ?>"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="small fw-bold"><?php echo htmlspecialchars($notif['titulo']); ?></div>
                                        <div class="small text-muted"><?php echo htmlspecialchars(substr($notif['mensaje'], 0, 80)); ?>...</div>
                                        <div class="small text-muted mt-1">
                                            <i class="far fa-clock"></i> <?php echo date('d/m/Y H:i', strtotime($notif['created_at'])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center py-2">
                            <a href="<?php echo BASE_URL; ?>/notifications" class="small">Ver todas</a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash fa-3x text-gray-300 mb-2"></i>
                            <p class="text-muted mb-0">No hay notificaciones</p>
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
</style>

<?php require_once 'views/layouts/footer.php'; ?>