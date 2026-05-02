<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book"></i> Mis Cursos
                        <span class="badge bg-info ms-2">
                            <i class="fas fa-user-check"></i> Solo mis cursos
                        </span>
                    </h6>
                    <a href="<?php echo BASE_URL; ?>/courses/create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Curso
                    </a>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php 
                            if ($_GET['success'] == 'created') echo 'Curso creado exitosamente.';
                            if ($_GET['success'] == 'updated') echo 'Curso actualizado exitosamente.';
                            if ($_GET['success'] == 'deleted') echo 'Curso eliminado exitosamente.';
                            if ($_GET['success'] == 'status_changed') echo 'Estado del curso actualizado.';
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php 
                            if ($_GET['error'] == 'notfound') echo 'Curso no encontrado.';
                            if ($_GET['error'] == 'has_bookings') echo 'No se puede eliminar el curso porque tiene capacitaciones asociadas.';
                            if ($_GET['error'] == 'delete_failed') echo 'Error al eliminar el curso.';
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Modalidad</th>
                                    <th>Duración (horas)</th>
                                    <th>Público</th>
                                    <th>Capacitaciones</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($courses)): ?>
                                    <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($course['nombre']); ?></td>
                                            <td>
                                                <span class="badge <?php echo $course['modalidad'] == 'online' ? 'bg-info' : 'bg-success'; ?>">
                                                    <?php echo $course['modalidad'] == 'online' ? 'Online' : 'Presencial'; ?>
                                                </span>
                                            </td>
                                            <td class="text-center"><?php echo $course['duracion_horas']; ?> hrs</td>
                                            <td class="text-center">
                                                <?php if ($course['publico'] == 1): ?>
                                                    <span class="badge bg-primary">Público</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Privado</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary"><?php echo $course['total_capacitaciones'] ?? 0; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($course['activo'] == 1): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?php echo BASE_URL; ?>/courses/details/<?php echo $course['id']; ?>" class="btn btn-sm btn-info" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($course['created_by'] == $_SESSION['user_id']): ?>
                                                    <a href="<?php echo BASE_URL; ?>/courses/edit/<?php echo $course['id']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm <?php echo $course['activo'] == 1 ? 'btn-secondary' : 'btn-success'; ?>" 
                                                            onclick="toggleStatus(<?php echo $course['id']; ?>, <?php echo $course['activo']; ?>)" title="<?php echo $course['activo'] == 1 ? 'Desactivar' : 'Activar'; ?>">
                                                        <i class="fas <?php echo $course['activo'] == 1 ? 'fa-ban' : 'fa-check-circle'; ?>"></i>
                                                    </button>
                                                    <?php if (($course['total_capacitaciones'] ?? 0) == 0): ?>
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $course['id']; ?>)" title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No hay cursos registrados</td>
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
                <p>¿Está seguro que desea eliminar este curso?</p>
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
    form.action = BASE_URL + '/courses/delete/' + id;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function toggleStatus(id, currentStatus) {
    const form = document.getElementById('statusForm');
    form.action = BASE_URL + '/courses/toggleStatus/' + id;
    const message = currentStatus == 1 ? '¿Desea desactivar este curso?' : '¿Desea activar este curso?';
    document.getElementById('statusMessage').innerText = message;
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>