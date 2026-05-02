<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-building"></i> Detalles de OTEC
                    </h6>
                    <div>
                        <a href="<?php echo BASE_URL; ?>/otec/edit/<?php echo $otec['id']; ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?php echo BASE_URL; ?>/otec" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">RUT:</th>
                                    <td><?php echo htmlspecialchars($otec['rut']); ?></td>
                                </tr>
                                <tr>
                                    <th>Nombre:</th>
                                    <td><?php echo htmlspecialchars($otec['nombre']); ?></td>
                                </tr>
                                <tr>
                                    <th>Dirección:</th>
                                    <td><?php echo htmlspecialchars($otec['direccion'] ?? 'No especificada'); ?></td>
                                </tr>
                                <tr>
                                    <th>Contacto:</th>
                                    <td><?php echo htmlspecialchars($otec['contacto'] ?? 'No especificado'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">Teléfono:</th>
                                    <td><?php echo htmlspecialchars($otec['telefono'] ?? 'No especificado'); ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo htmlspecialchars($otec['email'] ?? 'No especificado'); ?></td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        <?php if ($otec['activo'] == 1): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha Registro:</th>
                                    <td><?php echo date('d/m/Y', strtotime($otec['created_at'] ?? 'now')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6 class="font-weight-bold text-primary mb-3">
                        <i class="fas fa-users"></i> Ejecutivos Asignados (<?php echo count($ejecutivos); ?>)
                    </h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Rol</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($ejecutivos)): ?>
                                    <?php foreach ($ejecutivos as $ejecutivo): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($ejecutivo['nombre'] . ' ' . ($ejecutivo['apellido'] ?? '')); ?></td>
                                            <td><?php echo htmlspecialchars($ejecutivo['email']); ?></td>
                                            <td><?php echo htmlspecialchars($ejecutivo['telefono'] ?? '-'); ?></td>
                                            <td>
                                                <span class="badge bg-info"><?php echo ucfirst($ejecutivo['rol'] ?? 'ejecutivo'); ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No hay ejecutivos asignados a esta OTEC</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <h6 class="font-weight-bold text-primary mb-3">
                        <i class="fas fa-chalkboard"></i> Capacitaciones (<?php echo count($capacitaciones); ?>)
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Curso</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($capacitaciones)): ?>
                                    <?php foreach ($capacitaciones as $cap): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($cap['curso_nombre'] ?? 'N/A'); ?></td>
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
                                        <td colspan="4" class="text-center">No hay capacitaciones registradas para esta OTEC</td>
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