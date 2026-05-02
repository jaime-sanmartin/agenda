<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book"></i> Detalles del Curso
                    </h6>
                    <div>
                        <a href="<?php echo BASE_URL; ?>/courses/edit/<?php echo $course['id']; ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?php echo BASE_URL; ?>/courses" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Nombre:</th>
                                    <td><?php echo htmlspecialchars($course['nombre']); ?></td>
                                </tr>
                                <tr>
                                    <th>Descripción:</th>
                                    <td><?php echo nl2br(htmlspecialchars($course['descripcion'] ?? 'No especificada')); ?></td>
                                </tr>
                                <tr>
                                    <th>Modalidad:</th>
                                    <td>
                                        <span class="badge <?php echo $course['modalidad'] == 'online' ? 'bg-info' : 'bg-success'; ?>">
                                            <?php echo $course['modalidad'] == 'online' ? 'Online' : 'Presencial'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Duración:</th>
                                    <td><?php echo $course['duracion_horas']; ?> horas</td>
                                </tr>
                                <tr>
                                    <th>Público:</th>
                                    <td>
                                        <?php if ($course['publico'] == 1): ?>
                                            <span class="badge bg-primary">Público</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Privado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        <?php if ($course['activo'] == 1): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha Registro:</th>
                                    <td><?php echo date('d/m/Y H:i', strtotime($course['created_at'] ?? 'now')); ?></td>
                                </tr>
                                <tr>
                                    <th>Última Actualización:</th>
                                    <td><?php echo date('d/m/Y H:i', strtotime($course['updated_at'] ?? 'now')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6 class="font-weight-bold text-primary mb-3">
                        <i class="fas fa-chalkboard"></i> Capacitaciones Asociadas (<?php echo count($capacitaciones); ?>)
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>OTEC</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($capacitaciones)): ?>
                                    <?php foreach ($capacitaciones as $cap): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($cap['otec_nombre'] ?? 'N/A'); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($cap['fecha_inicio'])); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($cap['fecha_fin'])); ?></td>
                                            <td>
                                                <?php
                                                $badgeClass = 'secondary';
                                                if ($cap['estado'] == 'confirmada') $badgeClass = 'success';
                                                if ($cap['estado'] == 'pendiente') $badgeClass = 'warning';
                                                if ($cap['estado'] == 'aprobada') $badgeClass = 'info';
                                                if ($cap['estado'] == 'rechazada') $badgeClass = 'danger';
                                                ?>
                                                <span class="badge bg-<?php echo $badgeClass; ?>">
                                                    <?php echo ucfirst($cap['estado']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No hay capacitaciones asociadas a este curso</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>