<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-ticket-alt"></i> Detalles de la Reserva
                    </h6>
                    <div>
                        <?php if (Auth::isFacilitador()): ?>
                            <a href="<?php echo BASE_URL; ?>/bookings/edit/<?php echo $booking['id']; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo BASE_URL; ?>/bookings" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Estado de la reserva -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <?php
                            $estadoClass = 'secondary';
                            $estadoIcono = 'fa-question-circle';
                            if ($booking['estado'] == 'confirmada') {
                                $estadoClass = 'success';
                                $estadoIcono = 'fa-check-circle';
                            } elseif ($booking['estado'] == 'pendiente') {
                                $estadoClass = 'warning';
                                $estadoIcono = 'fa-clock';
                            } elseif ($booking['estado'] == 'aprobada') {
                                $estadoClass = 'info';
                                $estadoIcono = 'fa-check-double';
                            } elseif ($booking['estado'] == 'rechazada') {
                                $estadoClass = 'danger';
                                $estadoIcono = 'fa-times-circle';
                            } elseif ($booking['estado'] == 'propuesta') {
                                $estadoClass = 'purple';
                                $estadoIcono = 'fa-file-alt';
                            } elseif ($booking['estado'] == 'finalizada') {
                                $estadoClass = 'secondary';
                                $estadoIcono = 'fa-flag-checkered';
                            }
                            ?>
                            <div class="alert alert-<?php echo $estadoClass; ?>">
                                <i class="fas <?php echo $estadoIcono; ?>"></i>
                                <strong>Estado:</strong> <?php echo ucfirst($booking['estado']); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Información General -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <i class="fas fa-info-circle text-primary"></i> Información General
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="35%">Curso:</th>
                                            <td><?php echo htmlspecialchars($booking['curso_nombre'] ?? 'N/A'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>OTEC:</th>
                                            <td><?php echo htmlspecialchars($booking['otec_nombre'] ?? 'N/A'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Modalidad:</th>
                                            <td>
                                                <span class="badge <?php echo ($booking['curso_modalidad'] ?? '') == 'online' ? 'bg-info' : 'bg-success'; ?>">
                                                    <?php echo ($booking['curso_modalidad'] ?? '') == 'online' ? 'Online' : 'Presencial'; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tipo de Calendario:</th>
                                            <td>
                                                <?php if (($booking['tipo_calendario'] ?? 'continuo') == 'sesiones'): ?>
                                                    <span class="badge bg-info">Curso con Sesiones</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Curso Continuo</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <i class="fas fa-calendar-alt text-primary"></i> Fechas
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <?php if (($booking['tipo_calendario'] ?? 'continuo') == 'continuo'): ?>
                                        <tr>
                                            <th width="35%">Fecha Inicio:</th>
                                            <td><?php echo date('d/m/Y H:i', strtotime($booking['fecha_inicio'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Fecha Fin:</th>
                                            <td><?php echo date('d/m/Y H:i', strtotime($booking['fecha_fin'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Duración Total:</th>
                                            <td><?php echo $booking['duracion_horas'] ?? 0; ?> horas</td>
                                        </tr>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="2" class="text-muted">Las sesiones se listan más abajo</td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <th>Valor Acordado:</th>
                                            <td><?php echo $booking['valor_acordado'] ? '$' . number_format($booking['valor_acordado'], 0, ',', '.') : 'No especificado'; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notas -->
                    <?php if (!empty($booking['notas'])): ?>
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <i class="fas fa-sticky-note text-primary"></i> Notas
                        </div>
                        <div class="card-body">
                            <?php echo nl2br(htmlspecialchars($booking['notas'])); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Información de Solicitud -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <i class="fas fa-user text-primary"></i> Información de Solicitud
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">Solicitado por:</th>
                                    <td><?php echo htmlspecialchars($booking['created_by_nombre'] ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Fecha de Solicitud:</th>
                                    <td><?php echo date('d/m/Y H:i', strtotime($booking['created_at'] ?? 'now')); ?></td>
                                </tr>
                                <tr>
                                    <th>Última Actualización:</th>
                                    <td><?php echo date('d/m/Y H:i', strtotime($booking['updated_at'] ?? 'now')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- SESIONES (para cursos con sesiones) -->
                    <?php if (isset($sesiones) && !empty($sesiones)): ?>
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <i class="fas fa-calendar-week text-primary"></i> Sesiones del Curso
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha y Hora</th>
                                            <th>Horario</th>
                                            <th>Duración</th>
                                            <th>Estado</th>
                                            <th>Asistencia</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $num = 1; ?>
                                        <?php foreach ($sesiones as $sesion): ?>
                                        <tr>
                                            <td class="text-center"><?php echo $num++; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($sesion['fecha_inicio'])); ?></td>
                                            <td><?php echo date('H:i', strtotime($sesion['fecha_inicio'])) . ' - ' . date('H:i', strtotime($sesion['fecha_fin'])); ?></td>
                                            <td class="text-center">
                                                <?php 
                                                $inicio = new DateTime($sesion['fecha_inicio']);
                                                $fin = new DateTime($sesion['fecha_fin']);
                                                $diff = $inicio->diff($fin);
                                                echo $diff->h . ' hrs';
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $estadoSesionClass = 'secondary';
                                                if ($sesion['estado'] == 'confirmada') $estadoSesionClass = 'success';
                                                if ($sesion['estado'] == 'realizada') $estadoSesionClass = 'info';
                                                if ($sesion['estado'] == 'cancelada') $estadoSesionClass = 'danger';
                                                if ($sesion['estado'] == 'pendiente') $estadoSesionClass = 'warning';
                                                ?>
                                                <span class="badge bg-<?php echo $estadoSesionClass; ?>">
                                                    <?php echo ucfirst($sesion['estado']); ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($sesion['asistencia'] > 0): ?>
                                                    <span class="badge bg-success"><?php echo $sesion['asistencia']; ?> participantes</span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-info" onclick="verSesion(<?php echo $sesion['id']; ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <?php if (Auth::isFacilitador() && $sesion['estado'] != 'realizada'): ?>
                                                <button type="button" class="btn btn-sm btn-success" onclick="registrarAsistencia(<?php echo $sesion['id']; ?>, <?php echo $booking['id']; ?>)">
                                                    <i class="fas fa-users"></i>
                                                </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Botones de acción para facilitador -->
                    <?php if (Auth::isFacilitador() && $booking['estado'] == 'pendiente'): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-success" onclick="updateStatus(<?php echo $booking['id']; ?>, 'aprobada')">
                                    <i class="fas fa-check"></i> Aprobar Reserva
                                </button>
                                <button type="button" class="btn btn-danger" onclick="updateStatus(<?php echo $booking['id']; ?>, 'rechazada')">
                                    <i class="fas fa-times"></i> Rechazar Reserva
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para registrar asistencia -->
<div class="modal fade" id="asistenciaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-users"></i> Registrar Asistencia
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="asistenciaForm">
                    <input type="hidden" id="sessionId" name="session_id">
                    <div class="mb-3">
                        <label for="asistencia" class="form-label">Número de participantes:</label>
                        <input type="number" class="form-control" id="asistencia" name="asistencia" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="notasSesion" class="form-label">Notas de la sesión:</label>
                        <textarea class="form-control" id="notasSesion" name="notas" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarAsistencia()">Guardar Asistencia</button>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(bookingId, status) {
    if (confirm('¿Está seguro de ' + (status === 'aprobada' ? 'aprobar' : 'rechazar') + ' esta reserva?')) {
        fetch(BASE_URL + '/bookings/updateStatus', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                id: bookingId,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Estado actualizado correctamente', 'success');
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

function verSesion(sessionId) {
    // Implementar vista de detalles de sesión
    showToast('Función en desarrollo', 'info');
}

function registrarAsistencia(sessionId, bookingId) {
    document.getElementById('sessionId').value = sessionId;
    document.getElementById('asistencia').value = '';
    document.getElementById('notasSesion').value = '';
    var modal = new bootstrap.Modal(document.getElementById('asistenciaModal'));
    modal.show();
}

function guardarAsistencia() {
    var sessionId = document.getElementById('sessionId').value;
    var asistencia = document.getElementById('asistencia').value;
    var notas = document.getElementById('notasSesion').value;
    
    if (!asistencia || asistencia < 0) {
        alert('Ingrese un número válido de participantes');
        return;
    }
    
    fetch(BASE_URL + '/bookings/registrarAsistencia', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({
            session_id: sessionId,
            asistencia: asistencia,
            notas: notas
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Asistencia registrada correctamente', 'success');
            location.reload();
        } else {
            showToast(data.error || 'Error al registrar asistencia', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error de conexión', 'danger');
    });
}

function showToast(message, type) {
    var toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'toast-notification';
        document.body.appendChild(toastContainer);
    }
    
    var toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-' + (type === 'danger' ? 'danger' : type) + ' border-0 fade show';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}
</script>

<style>
.bg-purple {
    background-color: #9b59b6;
    color: white;
}
.toast-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>