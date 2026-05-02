<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-clock"></i> Solicitudes de Registro Pendientes
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php 
                            if ($_GET['success'] == 'approved') echo 'Solicitud aprobada correctamente. Se ha enviado la contraseña al usuario.';
                            if ($_GET['success'] == 'rejected') echo 'Solicitud rechazada correctamente.';
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php 
                            if ($_GET['error'] == 'notfound') echo 'Solicitud no encontrada.';
                            if ($_GET['error'] == 'create_failed') echo 'Error al crear el usuario.';
                            if ($_GET['error'] == 'reject_failed') echo 'Error al rechazar la solicitud.';
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (empty($solicitudes)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-gray-300 mb-3"></i>
                            <p class="text-muted">No hay solicitudes pendientes</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>RUT</th>
                                        <th>Empresa</th>
                                        <th>Mensaje</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($solicitudes as $solicitud): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y H:i', strtotime($solicitud['created_at'])); ?></td>
                                            <td><?php echo htmlspecialchars($solicitud['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($solicitud['email']); ?></td>
                                            <td><?php echo htmlspecialchars($solicitud['telefono'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($solicitud['rut'] ?? '-'); ?></div>
                                          </div>
                                            <td><?php echo htmlspecialchars($solicitud['empresa'] ?? '-'); ?></div>
                                          </div>
                                            <td>
                                                <?php if (!empty($solicitud['mensaje'])): ?>
                                                    <button type="button" class="btn btn-sm btn-info" 
                                                            onclick="verMensaje('<?php echo addslashes($solicitud['mensaje']); ?>')">
                                                        <i class="fas fa-comment"></i> Ver
                                                    </button>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </div>
                                          </div>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-success" 
                                                        onclick="aprobar(<?php echo $solicitud['id']; ?>, '<?php echo htmlspecialchars($solicitud['nombre']); ?>')">
                                                    <i class="fas fa-check"></i> Aprobar
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="rechazar(<?php echo $solicitud['id']; ?>)">
                                                    <i class="fas fa-times"></i> Rechazar
                                                </button>
                                            </div>
                                          </div>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver mensaje -->
<div class="modal fade" id="mensajeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mensaje del Solicitante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="mensajeTexto"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
function verMensaje(mensaje) {
    document.getElementById('mensajeTexto').innerText = mensaje;
    new bootstrap.Modal(document.getElementById('mensajeModal')).show();
}

function aprobar(id, nombre) {
    if (confirm(`¿Está seguro de aprobar la solicitud de ${nombre}? Se enviará un email con la contraseña.`)) {
        window.location.href = BASE_URL + '/auth/aprobar_solicitud/' + id;
    }
}

function rechazar(id) {
    if (confirm('¿Está seguro de rechazar esta solicitud?')) {
        window.location.href = BASE_URL + '/auth/rechazar_solicitud/' + id;
    }
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>