<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users"></i> 
                        <?php echo Session::isAdministrador() ? 'Gestión de Usuarios' : 'Mis Ejecutivos'; ?>
                    </h6>
                    <a href="<?php echo BASE_URL; ?>/users/create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> 
                        <?php echo Session::isAdministrador() ? 'Nuevo Usuario' : 'Asociar Ejecutivo'; ?>
                    </a>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php 
                            if ($_GET['success'] == 'created') echo 'Usuario creado exitosamente.';
                            if ($_GET['success'] == 'updated') echo 'Usuario actualizado exitosamente.';
                            if ($_GET['success'] == 'deleted') echo 'Usuario eliminado exitosamente.';
                            if ($_GET['success'] == 'associated') echo 'Ejecutivo asociado correctamente. Se ha enviado un correo de notificación.';
                            if ($_GET['success'] == 'status_changed') echo 'Estado del usuario actualizado.';
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php 
                            if ($_GET['error'] == 'notfound') echo 'Usuario no encontrado.';
                            if ($_GET['error'] == 'cannot_delete_self') echo 'No puede eliminar su propio usuario.';
                            if ($_GET['error'] == 'cannot_change_self') echo 'No puede cambiar su propio estado.';
                            if ($_GET['error'] == 'delete_failed') echo 'Error al eliminar el usuario.';
                            if ($_GET['error'] == 'unauthorized') echo 'No tiene permisos para ver este usuario.';
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (empty($users)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-gray-300 mb-3"></i>
                            <p class="text-muted">
                                <?php echo Session::isAdministrador() ? 'No hay usuarios registrados.' : 'No tienes ejecutivos asociados.'; ?>
                            </p>
                            <a href="<?php echo BASE_URL; ?>/users/create" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> 
                                <?php echo Session::isAdministrador() ? 'Crear primer usuario' : 'Asociar ejecutivo'; ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>RUT</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Rol</th>
                                        <th>OTEC</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['rut'] ?? '-'); ?></div>
                                          </div>
                                            <td><?php echo htmlspecialchars($user['nombre']); ?></div>
                                          </div>
                                            <td><?php echo htmlspecialchars($user['email']); ?></div>
                                          </div>
                                            <td><?php echo htmlspecialchars($user['telefono'] ?? '-'); ?></div>
                                          </div>
                                            <td>
                                                <?php if ($user['rol'] == 'facilitador'): ?>
                                                    <span class="badge bg-primary"><i class="fas fa-chalkboard-teacher"></i> Facilitador</span>
                                                <?php elseif ($user['rol'] == 'ejecutivo'): ?>
                                                    <span class="badge bg-info"><i class="fas fa-building"></i> Ejecutivo OTEC</span>
                                                <?php elseif ($user['rol'] == 'administrador'): ?>
                                                    <span class="badge bg-danger"><i class="fas fa-user-shield"></i> Administrador</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?php echo ucfirst($user['rol']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                          </div>
                                            <td><?php echo htmlspecialchars($user['otec_nombre'] ?? '-'); ?></div>
                                          </div>
                                            <td class="text-center">
                                                <?php if ($user['activo'] == 1): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif; ?>
                                            </div>
                                          </div>
                                            <td class="text-center">
                                                <a href="<?php echo BASE_URL; ?>/users/details/<?php echo $user['id']; ?>" class="btn btn-sm btn-info" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if (Session::isAdministrador() || (Session::isFacilitador() && $user['rol'] == 'ejecutivo')): ?>
                                                    <a href="<?php echo BASE_URL; ?>/users/edit/<?php echo $user['id']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if ($user['id'] != $_SESSION['user_id'] && (Session::isAdministrador() || (Session::isFacilitador() && $user['rol'] == 'ejecutivo'))): ?>
                                                    <button type="button" class="btn btn-sm <?php echo $user['activo'] == 1 ? 'btn-secondary' : 'btn-success'; ?>" 
                                                            onclick="toggleStatus(<?php echo $user['id']; ?>, <?php echo $user['activo']; ?>)" 
                                                            title="<?php echo $user['activo'] == 1 ? 'Desactivar' : 'Activar'; ?>">
                                                        <i class="fas <?php echo $user['activo'] == 1 ? 'fa-ban' : 'fa-check-circle'; ?>"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $user['id']; ?>)" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
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

<!-- Modal para confirmar eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-danger"></i> Confirmar eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar este usuario?</p>
                <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar cambio de estado -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar cambio de estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="statusMessage"></p>
            </div>
            <div class="modal-footer">
                <form id="statusForm" method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    const form = document.getElementById('deleteForm');
    form.action = BASE_URL + '/users/delete/' + id;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function toggleStatus(id, currentStatus) {
    const form = document.getElementById('statusForm');
    form.action = BASE_URL + '/users/toggleStatus/' + id;
    const message = currentStatus == 1 ? '¿Desea desactivar este usuario?' : '¿Desea activar este usuario?';
    document.getElementById('statusMessage').innerText = message;
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

// Inicializar DataTable
$(document).ready(function() {
    $('#dataTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        },
        order: [[1, 'asc']]
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>