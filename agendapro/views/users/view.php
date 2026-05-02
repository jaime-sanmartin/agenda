<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-circle"></i> Detalles del Usuario
                    </h6>
                    <div>
                        <a href="<?php echo BASE_URL; ?>/users/edit/<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?php echo BASE_URL; ?>/users" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Información Personal -->
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <i class="fas fa-info-circle text-primary"></i> Información Personal
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="35%">RUT:</th>
                                            <td><?php echo htmlspecialchars($user['rut'] ?? 'No especificado'); ?></div>
                                          </div>
                                        </div>
                                        <tr>
                                            <th>Nombre Completo:</th>
                                            <td><?php echo htmlspecialchars($user['nombre']); ?></div>
                                          </div>
                                        </div>
                                        <tr>
                                            <th>Email:</th>
                                            <td><?php echo htmlspecialchars($user['email']); ?></div>
                                          </div>
                                        </div>
                                        <tr>
                                            <th>Teléfono:</th>
                                            <td><?php echo htmlspecialchars($user['telefono'] ?? 'No especificado'); ?></div>
                                          </div>
                                        </div>
                                    </table>
                                </div>
                            </div>

                            <!-- Información de Cuenta -->
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <i class="fas fa-cog text-primary"></i> Información de Cuenta
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="35%">Rol:</th>
                                            <td>
                                                <?php if ($user['rol'] == 'facilitador'): ?>
                                                    <span class="badge bg-primary"><i class="fas fa-crown"></i> Facilitador</span>
                                                <?php elseif ($user['rol'] == 'ejecutivo'): ?>
                                                    <span class="badge bg-info"><i class="fas fa-building"></i> Ejecutivo OTEC</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?php echo ucfirst($user['rol']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                          </div>
                                        </div>
                                        
                                        <?php if ($user['rol'] == 'ejecutivo'): ?>
                                        <tr>
                                            <th>OTEC Asignada:</th>
                                            <td>
                                                <?php if ($user['otec_nombre']): ?>
                                                    <?php echo htmlspecialchars($user['otec_nombre']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">No asignada</span>
                                                <?php endif; ?>
                                            </div>
                                          </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <tr>
                                            <th>Estado:</th>
                                            <td>
                                                <?php if ($user['activo'] == 1): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif; ?>
                                            </div>
                                          </div>
                                        </div>
                                        <tr>
                                            <th>Fecha Registro:</th>
                                            <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'] ?? 'now')); ?></div>
                                          </div>
                                        </div>
                                        <tr>
                                            <th>Última Actualización:</th>
                                            <td><?php echo date('d/m/Y H:i', strtotime($user['updated_at'] ?? 'now')); ?></div>
                                          </div>
                                        </div>
                                    </table>
                                </div>
                            </div>

                            <!-- Reservas Realizadas (si es ejecutivo) -->
                            <?php if ($user['rol'] == 'ejecutivo' && !empty($reservas)): ?>
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <i class="fas fa-ticket-alt text-primary"></i> Reservas Realizadas (<?php echo count($reservas); ?>)
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Curso</th>
                                                    <th>OTEC</th>
                                                    <th>Fecha Inicio</th>
                                                    <th>Fecha Fin</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($reservas as $reserva): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($reserva['curso_nombre'] ?? 'N/A'); ?></div>
                                                  </div>
                                                    <td><?php echo htmlspecialchars($reserva['otec_nombre'] ?? 'N/A'); ?></div>
                                                  </div>
                                                    <td><?php echo date('d/m/Y', strtotime($reserva['fecha_inicio'])); ?></div>
                                                  </div>
                                                    <td><?php echo date('d/m/Y', strtotime($reserva['fecha_fin'])); ?></div>
                                                  </div>
                                                    <td>
                                                        <?php
                                                        $badgeClass = 'secondary';
                                                        if ($reserva['estado'] == 'confirmada') $badgeClass = 'success';
                                                        if ($reserva['estado'] == 'pendiente') $badgeClass = 'warning';
                                                        if ($reserva['estado'] == 'aprobada') $badgeClass = 'info';
                                                        if ($reserva['estado'] == 'rechazada') $badgeClass = 'danger';
                                                        if ($reserva['estado'] == 'propuesta') $badgeClass = 'purple';
                                                        if ($reserva['estado'] == 'finalizada') $badgeClass = 'secondary';
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
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
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