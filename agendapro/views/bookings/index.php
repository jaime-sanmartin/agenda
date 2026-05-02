<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <?php if (Session::isFacilitador()): ?>
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-ticket-alt"></i> Reservas de Mis Cursos
                        <span class="badge bg-info ms-2">
                            <i class="fas fa-chalkboard"></i> Solicitudes recibidas
                        </span>
                    </h6>
                    <?php else: ?>
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-ticket-alt"></i> Mis Reservas
                    </h6>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL; ?>/bookings/create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nueva Reserva
                    </a>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php 
                            if ($_GET['success'] == 'created') echo 'Reserva creada exitosamente.';
                            if ($_GET['success'] == 'updated') echo 'Reserva actualizada exitosamente.';
                            if ($_GET['success'] == 'deleted') echo 'Reserva eliminada exitosamente.';
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Curso</th>
                                    <th>OTEC</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($bookings)): ?>
                                    <?php foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($booking['curso_nombre'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($booking['otec_nombre'] ?? 'N/A'); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($booking['fecha_inicio'])); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($booking['fecha_fin'])); ?></td>
                                            <td>
                                                <?php
                                                $badgeClass = 'secondary';
                                                if ($booking['estado'] == 'confirmada') $badgeClass = 'success';
                                                if ($booking['estado'] == 'pendiente') $badgeClass = 'warning';
                                                if ($booking['estado'] == 'aprobada') $badgeClass = 'info';
                                                if ($booking['estado'] == 'rechazada') $badgeClass = 'danger';
                                                if ($booking['estado'] == 'propuesta') $badgeClass = 'purple';
                                                if ($booking['estado'] == 'finalizada') $badgeClass = 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $badgeClass; ?>">
                                                    <?php echo ucfirst($booking['estado']); ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?php echo BASE_URL; ?>/bookings/details/<?php echo $booking['id']; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if (Auth::isFacilitador() && $booking['estado'] == 'pendiente'): ?>
                                                <button class="btn btn-sm btn-success" onclick="updateStatus(<?php echo $booking['id']; ?>, 'aprobada')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="updateStatus(<?php echo $booking['id']; ?>, 'rechazada')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <?php endif; ?>
                                             </div>
                                         </td>
                                     </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No hay reservas registradas</td>
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

<script>
function updateStatus(id, status) {
    if (confirm('¿Está seguro de ' + (status === 'aprobada' ? 'aprobar' : 'rechazar') + ' esta reserva?')) {
        // Crear FormData en lugar de JSON
        const formData = new URLSearchParams();
        formData.append('id', id);
        formData.append('status', status);
        formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
        
        fetch(BASE_URL + '/bookings/updateStatus', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData.toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Estado actualizado correctamente', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.error || 'Error al actualizar', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error de conexión', 'danger');
        });
    }
}
</script>

<style>
.bg-purple {
    background-color: #9b59b6;
    color: white;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>