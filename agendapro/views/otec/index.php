<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-building"></i> Mis OTEC
                        <span class="badge bg-info ms-2">
                            <i class="fas fa-user-check"></i> Solo mis OTEC
                        </span>
                    </h6>
                    <a href="<?php echo BASE_URL; ?>/otec/create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nueva OTEC
                    </a>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php 
                            if ($_GET['success'] == 'created') echo 'OTEC creada exitosamente.';
                            if ($_GET['success'] == 'updated') echo 'OTEC actualizada exitosamente.';
                            if ($_GET['success'] == 'deleted') echo 'OTEC eliminada exitosamente.';
                            if ($_GET['success'] == 'status_changed') echo 'Estado de OTEC actualizado.';
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php 
                            if ($_GET['error'] == 'notfound') echo 'OTEC no encontrada.';
                            if ($_GET['error'] == 'has_users') echo 'No se puede eliminar la OTEC porque tiene ejecutivos asociados.';
                            if ($_GET['error'] == 'delete_failed') echo 'Error al eliminar la OTEC.';
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>RUT</th>
                                    <th>Nombre</th>
                                    <th>Contacto</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Ejecutivos</th>
                                    <!-- 
                                    <th>Capacitaciones</th>
                                    -->
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($otecs)): ?>
                                    <?php foreach ($otecs as $otec): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($otec['rut']); ?></td>
                                            <td><?php echo htmlspecialchars($otec['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($otec['contacto'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($otec['telefono'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($otec['email'] ?? '-'); ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-info"><?php echo $otec['total_ejecutivos'] ?? 0; ?></span>
                                            </td>
                                            <!--
                                            <td class="text-center">
                                                <span class="badge bg-secondary"><?php echo $otec['total_capacitaciones'] ?? 0; ?></span>
                                            </td>
                                            -->
                                            <td class="text-center">
                                                <?php if ($otec['activo'] == 1): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?php echo BASE_URL; ?>/otec/details/<?php echo $otec['id']; ?>" class="btn btn-sm btn-info" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php  if ($_SESSION['user_rol'] == 'administrador'):  ?>
                                                <a href="<?php echo BASE_URL; ?>/otec/edit/<?php echo $otec['id']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm <?php echo $otec['activo'] == 1 ? 'btn-secondary' : 'btn-success'; ?>" 
                                                        onclick="toggleStatus(<?php echo $otec['id']; ?>, <?php echo $otec['activo']; ?>)" title="<?php echo $otec['activo'] == 1 ? 'Desactivar' : 'Activar'; ?>">
                                                    <i class="fas <?php echo $otec['activo'] == 1 ? 'fa-ban' : 'fa-check-circle'; ?>"></i>
                                                </button>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $otec['id']; ?>)" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php elseif ($otec['created_by'] == $_SESSION['user_id']):  ?>
                                                <a href="<?php echo BASE_URL; ?>/otec/edit/<?php echo $otec['id']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm <?php echo $otec['activo'] == 1 ? 'btn-secondary' : 'btn-success'; ?>" 
                                                        onclick="toggleStatus(<?php echo $otec['id']; ?>, <?php echo $otec['activo']; ?>)" title="<?php echo $otec['activo'] == 1 ? 'Desactivar' : 'Activar'; ?>">
                                                    <i class="fas <?php echo $otec['activo'] == 1 ? 'fa-ban' : 'fa-check-circle'; ?>"></i>
                                                </button>
                                                <?php if (($otec['total_ejecutivos'] ?? 0) == 0): ?>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $otec['id']; ?>)" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; 
                                                      endif;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No hay OTEC registradas</td>
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

<!-- Modal para confirmar eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar esta OTEC?</p>
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
    form.action = BASE_URL + '/otec/delete/' + id;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function toggleStatus(id, currentStatus) {
    const form = document.getElementById('statusForm');
    form.action = BASE_URL + '/otec/toggleStatus/' + id;
    const message = currentStatus == 1 ? '¿Desea desactivar esta OTEC?' : '¿Desea activar esta OTEC?';
    document.getElementById('statusMessage').innerText = message;
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>