<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt"></i> Calendario de Capacitaciones
                    </h6>
                    <div class="legend d-flex flex-wrap gap-2 mt-2 mt-sm-0">
                        <?php
                        $estados = [
                            'pendiente' => ['color' => '#f39c12', 'icono' => '<i class="fas fa-hourglass-half"></i>', 'texto' => 'Pendiente'],
                            'aprobada' => ['color' => '#2ecc71', 'icono' => '<i class="fas fa-check-circle"></i>', 'texto' => 'Aprobada'],
                            'rechazada' => ['color' => '#e74c3c', 'icono' => '<i class="fas fa-times-circle"></i>', 'texto' => 'Rechazada'],
                            'anulada' => ['color' => '#95a5a6', 'icono' => '<i class="fas fa-ban"></i>', 'texto' => 'Anulada']
                        ];
                        foreach ($estados as $key => $estado):
                            $count = isset($counts[$key]) ? $counts[$key] : 0;
                        ?>
                        <span class="badge status-badge" style="background-color: <?php echo $estado['color']; ?>; cursor: pointer;" data-estado="<?php echo $key; ?>">
                            <?php echo $estado['icono']; ?> <?php echo $estado['texto']; ?> 
                            <span class="badge-count" style="background-color: rgba(0,0,0,0.2); border-radius: 10px; padding: 0 5px; margin-left: 3px;"><?php echo $count; ?></span>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($role === 'ejecutivo' && !empty($facilitadores)): ?>
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-5">
                            <div class="card border-left-info shadow-sm">
                                <div class="card-body py-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <i class="fas fa-user-tie fa-2x text-info"></i>
                                        </div>
                                        <div class="col">
                                            <label class="form-label fw-bold mb-1">
                                                <i class="fas fa-chalkboard-user"></i> Ver carga de trabajo del facilitador:
                                            </label>
                                            <select id="facilitadorFilter" class="form-select">
                                                <option value="mi_otec">Mis capacitaciones (solo mi OTEC)</option>
                                                <?php foreach ($facilitadores as $facilitador): ?>
                                                <option value="<?php echo $facilitador['id']; ?>">
                                                    <?php echo htmlspecialchars($facilitador['nombre']); ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="text-muted mt-2 d-block">
                                                <i class="fas fa-info-circle"></i> 
                                                Al seleccionar un facilitador, verás TODAS las capacitaciones que gestiona.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para listado de reservas por estado -->
<div class="modal fade" id="reservasModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" id="reservasModalHeader">
                <h5 class="modal-title"><i class="fas fa-list"></i> Reservas <span id="modalEstadoTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="reservasList" class="reservas-list-container">
                    <div class="text-center py-4"><div class="spinner-border text-primary"></div><p>Cargando...</p></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ÚNICO: Detalle de Evento (Sesión o Reserva) -->
<div class="modal fade" id="eventDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="eventDetailHeader">
                <h5 class="modal-title"><i class="fas fa-info-circle"></i> <span id="modalTitle">Detalles</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="eventType" value="">
                <input type="hidden" id="eventId" value="">
                <input type="hidden" id="bookingId" value="">
                
                <!-- Sección para SESIÓN -->
                <div id="sessionSection" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3"><label class="fw-bold text-primary">Curso:</label><p id="sessionCurso" class="mb-0">-</p></div>
                            <div class="mb-3"><label class="fw-bold text-primary">OTEC:</label><p id="sessionOtec" class="mb-0">-</p></div>
                            <div class="mb-3"><label class="fw-bold text-primary">Modalidad:</label><p id="sessionModalidad" class="mb-0">-</p></div>
                            <div class="mb-3"><label class="fw-bold text-primary">Estado Sesión:</label><p id="sessionEstado" class="mb-0">-</p></div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3"><label class="fw-bold text-primary">N° Sesión:</label><p id="sessionNumero" class="mb-0">-</p></div>
                            <div class="mb-3"><label class="fw-bold text-primary">Duración:</label><p id="sessionDuracion" class="mb-0">-</p></div>
                            <div class="mb-3"><label class="fw-bold text-primary">Fecha/Hora Inicio:</label><p id="sessionFechaInicio" class="mb-0">-</p></div>
                            <div class="mb-3"><label class="fw-bold text-primary">Fecha/Hora Término:</label><p id="sessionFechaFin" class="mb-0">-</p></div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info" id="sessionInfoBlock">
                        <i class="fas fa-calendar-alt"></i> <strong>Información actual de la sesión</strong>
                    </div>
                    
                    <!-- Formulario para reagendar (solo ejecutivo dueño) -->
                    <div class="card mt-3 border-warning" id="reagendarSection" style="display: none;">
                        <div class="card-header bg-warning text-white"><i class="fas fa-calendar-plus"></i> Reagendar Sesión</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Nueva Fecha y Hora de Inicio:</label>
                                    <input type="datetime-local" id="nuevaFechaInicio" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Nueva Fecha y Hora de Término:</label>
                                    <input type="datetime-local" id="nuevaFechaFin" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Motivo del Reagendamiento:</label>
                                <textarea id="motivoReagendar" class="form-control" rows="2" placeholder="Ej: Conflicto de horario, solicitud del cliente, etc."></textarea>
                            </div>
                            <button class="btn btn-warning" onclick="reagendarSesion()"><i class="fas fa-calendar-check"></i> Confirmar Reagendamiento</button>
                        </div>
                    </div>
                </div>
                
                <!-- Sección para RESERVA (sin sesiones) -->
                <div id="bookingSection" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3"><label class="fw-bold text-primary">Curso:</label><p id="bookingCurso" class="mb-0">-</p></div>
                            <div class="mb-3"><label class="fw-bold text-primary">Estado Reserva:</label><p id="bookingEstado" class="mb-0">-</p></div>
                            <div class="mb-3"><label class="fw-bold text-primary">OTEC:</label><p id="bookingOtec" class="mb-0">-</p></div>
                            <div class="mb-3"><label class="fw-bold text-primary">Modalidad:</label><p id="bookingModalidad" class="mb-0">-</p></div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3"><label class="fw-bold text-primary">Fecha Inicio:</label><p id="bookingStart" class="mb-0">-</p></div>
                            <div class="mb-3"><label class="fw-bold text-primary">Fecha Fin:</label><p id="bookingEnd" class="mb-0">-</p></div>
                            <div class="mb-3"><label class="fw-bold text-primary">Duración:</label><p id="bookingDuracion" class="mb-0">-</p></div>
                            <div class="mb-3"><label class="fw-bold text-primary">Valor Acordado:</label><p id="bookingValor" class="mb-0">-</p></div>
                        </div>
                    </div>
                    <div class="mb-3"><label class="fw-bold text-primary">Notas:</label><p id="bookingNotas" class="mb-0 bg-light p-2 rounded">-</p></div>
                    <div class="mb-3"><label class="fw-bold text-primary">Solicitado por:</label><p id="bookingCreatedBy" class="mb-0">-</p></div>
                </div>
            </div>
            <div class="modal-footer">
                <div id="sessionActions" style="display: none;">
                    <button type="button" class="btn btn-danger" id="suspenderBtn" onclick="suspenderSesion()"><i class="fas fa-pause-circle"></i> Suspender</button>
                    <button type="button" class="btn btn-info" id="reagendarBtn" onclick="mostrarFormReagendar()"><i class="fas fa-calendar-alt"></i> Reagendar</button>
                </div>
                <div id="bookingActions" style="display: none;">
                    <button type="button" class="btn btn-success" id="approveBtn" onclick="aprobarReserva()"><i class="fas fa-check"></i> Aprobar</button>
                    <button type="button" class="btn btn-danger" id="rejectBtn" onclick="rechazarReserva()"><i class="fas fa-times"></i> Rechazar</button>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
#calendar { min-height: 600px; }
.fc .fc-toolbar-title { font-size: 1.2rem; font-weight: 600; color: #4e73df; }
.fc .fc-button-primary { background-color: #4e73df; border-color: #4e73df; }
.fc-daygrid-event { cursor: pointer; border-radius: 4px; padding: 2px 4px; font-size: 0.75rem; transition: all 0.2s; }
.fc-daygrid-event:hover { opacity: 0.9; transform: scale(1.01); box-shadow: 0 2px 5px rgba(0,0,0,0.2); }

/* Tooltip personalizado */
.fc-event-tooltip {
    position: fixed;
    background: rgba(0,0,0,0.9);
    color: white;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12px;
    z-index: 9999;
    pointer-events: none;
    white-space: nowrap;
    border-left: 3px solid #f39c12;
}
.fc-event-tooltip:before {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    border-width: 5px 5px 0;
    border-style: solid;
    border-color: rgba(0,0,0,0.9) transparent transparent;
}

.legend .badge { padding: 8px 15px; font-size: 0.85rem; font-weight: 500; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px; transition: transform 0.2s; cursor: pointer; }
.legend .badge:hover { transform: scale(1.05); box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
.reservas-list-container { max-height: 500px; overflow-y: auto; }
.reserva-item { border-left: 4px solid; margin-bottom: 12px; padding: 15px; background: #f8f9fc; border-radius: 8px; }
.reserva-item:hover { background: #eef2f7; }
.reserva-item.border-pendiente { border-left-color: #f39c12; }
.reserva-item.border-aprobada { border-left-color: #2ecc71; }
.reserva-item.border-rechazada { border-left-color: #e74c3c; }
.reserva-item.border-anulada { border-left-color: #95a5a6; }
.btn-estado { margin: 2px; padding: 4px 12px; font-size: 12px; }
.toast-notification { position: fixed; bottom: 20px; right: 20px; z-index: 9999; min-width: 300px; }
.toast { opacity: 0.95; border-radius: 8px; margin-bottom: 10px; }
@media (max-width: 768px) {
    .fc .fc-toolbar { flex-direction: column; gap: 10px; }
    .legend { justify-content: center; }
    .toast-notification { min-width: 250px; }
}
</style>

<script>
const BASE_URL = window.location.origin + '/agendapro';
const USER_ROLE = '<?php echo $role; ?>';
const USER_ID = <?php echo $_SESSION['user_id']; ?>;

var currentTooltip = null;
var currentSessionData = null;
var currentBookingData = null;

function showTooltip(element, html) {
    if (currentTooltip) currentTooltip.remove();
    var tooltip = document.createElement('div');
    tooltip.className = 'fc-event-tooltip';
    tooltip.innerHTML = html;
    var rect = element.getBoundingClientRect();
    tooltip.style.left = (rect.left + rect.width / 2 - 100) + 'px';
    tooltip.style.top = (rect.top - tooltip.offsetHeight - 10) + 'px';
    document.body.appendChild(tooltip);
    currentTooltip = tooltip;
    setTimeout(function() { if (currentTooltip) { currentTooltip.remove(); currentTooltip = null; } }, 4000);
}

function hideTooltip() { if (currentTooltip) { currentTooltip.remove(); currentTooltip = null; } }

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
        buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', day: 'Dia' },
        events: {
            url: BASE_URL + '/calendar/getEvents',
            format: 'json',
            method: 'GET',
            extraParams: function() {
                var params = { _t: Date.now() };
                var facilitadorFilter = document.getElementById('facilitadorFilter');
                if (facilitadorFilter && facilitadorFilter.value !== 'mi_otec') {
                    params.facilitador_id = facilitadorFilter.value;
                }
                return params;
            },
            failure: function() { showToast('Error al cargar el calendario', 'danger'); }
        },
        editable: false,
        selectable: true,
        eventDidMount: function(info) {
            info.el.addEventListener('mouseenter', function(e) {
                var props = info.event.extendedProps;
                var start = info.event.start;
                var end = info.event.end;
                var horaInicio = start ? start.toLocaleTimeString('es-CL', { hour: '2-digit', minute: '2-digit' }) : '';
                var horaFin = end ? end.toLocaleTimeString('es-CL', { hour: '2-digit', minute: '2-digit' }) : '';
                var duracion = props.duracion || '';
                var otec = props.otec || 'N/A';
                var modalidad = props.modalidad || 'Presencial';
                var tooltipHtml = `<strong>${info.event.title}</strong><br>🕐 ${horaInicio} - ${horaFin}<br>⏱ ${duracion} horas | 🏢 ${otec}<br>📋 ${modalidad}`;
                showTooltip(info.el, tooltipHtml);
            });
            info.el.addEventListener('mouseleave', function() { hideTooltip(); });
        },
        eventClick: function(info) {
            var props = info.event.extendedProps;
            if (props.type === 'session') {
                mostrarModalSesion(info.event, props);
            } else {
                mostrarModalReserva(info.event, props);
            }
        },
        select: function(info) {
            if (USER_ROLE === 'ejecutivo') {
                window.location.href = BASE_URL + '/bookings/create?start=' + encodeURIComponent(info.startStr) + '&end=' + encodeURIComponent(info.endStr);
            }
        }
    });
    calendar.render();
    
    // Contadores interactivos
    document.querySelectorAll('.status-badge').forEach(function(badge) {
        badge.addEventListener('click', function() {
            var estado = this.getAttribute('data-estado');
            var estadoTexto = '';
            var headerClass = '';
            switch(estado) {
                case 'pendiente': estadoTexto = 'Pendientes'; headerClass = 'status-pendiente'; break;
                case 'aprobada': estadoTexto = 'Aprobadas'; headerClass = 'status-aprobada'; break;
                case 'rechazada': estadoTexto = 'Rechazadas'; headerClass = 'status-rechazada'; break;
                case 'anulada': estadoTexto = 'Anuladas'; headerClass = 'status-anulada'; break;
            }
            document.getElementById('modalEstadoTitle').innerHTML = '<span class="badge" style="background-color: ' + this.style.backgroundColor + '">' + estadoTexto + '</span>';
            document.getElementById('reservasModalHeader').className = 'modal-header ' + headerClass;
            var modal = new bootstrap.Modal(document.getElementById('reservasModal'));
            modal.show();
            document.getElementById('reservasList').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p>Cargando...</p></div>';
            fetch(BASE_URL + '/calendar/getReservasByStatus', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) mostrarReservas(data.reservas, estado);
                else document.getElementById('reservasList').innerHTML = '<div class="alert alert-danger">Error al cargar</div>';
            })
            .catch(() => document.getElementById('reservasList').innerHTML = '<div class="alert alert-danger">Error de conexión</div>');
        });
    });
    
    var facilitadorFilter = document.getElementById('facilitadorFilter');
    if (facilitadorFilter) facilitadorFilter.addEventListener('change', function() { calendar.refetchEvents(); });
});

function mostrarReservas(reservas, estadoActual) {
    var html = '';
    reservas.forEach(function(r) {
        var borderClass = 'border-' + r.estado;
        var fechaInicio = r.fecha_inicio ? new Date(r.fecha_inicio).toLocaleString('es-CL') : 'No especificada';
        var fechaFin = r.fecha_fin ? new Date(r.fecha_fin).toLocaleString('es-CL') : 'No especificada';
        html += `<div class="reserva-item ${borderClass}">
                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><strong>${escapeHtml(r.curso_nombre || 'Curso sin nombre')}</strong></h6>
                            <small>OTEC: ${escapeHtml(r.otec_nombre || 'N/A')}</small><br>
                            <small>Facilitador: ${escapeHtml(r.facilitador_nombre || 'No asignado')}</small><br>
                            <small><i class="fas fa-calendar-alt"></i> ${fechaInicio} hasta ${fechaFin}</small><br>
                            <small><i class="fas fa-clock"></i> Duración: ${r.duracion_horas || 0} horas</small><br>
                            ${r.valor_acordado ? `<small><i class="fas fa-dollar-sign"></i> Valor: $${formatNumber(r.valor_acordado)}</small><br>` : ''}
                            <small><i class="fas fa-user"></i> Solicitado por: ${escapeHtml(r.created_by_nombre || r.created_by_email || 'N/A')}</small>
                        </div>
                        ${USER_ROLE === 'facilitador' && estadoActual === 'pendiente' ? `
                        <div class="ms-3">
                            <button class="btn btn-sm btn-success btn-estado" onclick="cambiarEstadoReserva(${r.id}, 'aprobada')"><i class="fas fa-check"></i> Aprobar</button>
                            <button class="btn btn-sm btn-danger btn-estado" onclick="cambiarEstadoReserva(${r.id}, 'rechazada')"><i class="fas fa-times"></i> Rechazar</button>
                        </div>` : ''}
                        ${USER_ROLE === 'facilitador' && estadoActual === 'aprobada' ? `
                        <div class="ms-3">
                            <button class="btn btn-sm btn-secondary btn-estado" onclick="cambiarEstadoReserva(${r.id}, 'anulada')"><i class="fas fa-ban"></i> Anular</button>
                        </div>` : ''}
                    </div>
                </div>`;
    });
    document.getElementById('reservasList').innerHTML = html || '<div class="alert alert-info">No hay reservas en este estado</div>';
}

function mostrarModalSesion(event, props) {
    document.getElementById('eventType').value = 'session';
    document.getElementById('eventId').value = props.id;
    document.getElementById('bookingId').value = props.booking_id;
    
    var start = new Date(props.fecha_inicio);
    var end = new Date(props.fecha_fin);
    var fechaInicioStr = start.toLocaleDateString('es-CL', { day: '2-digit', month: '2-digit', year: 'numeric' });
    var horaInicioStr = start.toLocaleTimeString('es-CL', { hour: '2-digit', minute: '2-digit' });
    var fechaFinStr = end.toLocaleDateString('es-CL', { day: '2-digit', month: '2-digit', year: 'numeric' });
    var horaFinStr = end.toLocaleTimeString('es-CL', { hour: '2-digit', minute: '2-digit' });
    
    document.getElementById('sessionCurso').innerHTML = '<strong>' + escapeHtml(props.curso) + '</strong>';
    document.getElementById('sessionOtec').innerText = props.otec || 'N/A';
    document.getElementById('sessionModalidad').innerText = props.modalidad;
    document.getElementById('sessionEstado').innerHTML = '<span class="badge bg-warning">' + (props.status_text || props.status) + '</span>';
    document.getElementById('sessionNumero').innerText = props.numero_sesion || '?';
    document.getElementById('sessionDuracion').innerText = props.duracion + ' horas';
    document.getElementById('sessionFechaInicio').innerHTML = '<strong>' + fechaInicioStr + ' ' + horaInicioStr + '</strong>';
    document.getElementById('sessionFechaFin').innerHTML = '<strong>' + fechaFinStr + ' ' + horaFinStr + '</strong>';
    
    document.getElementById('nuevaFechaInicio').value = start.toISOString().slice(0, 16);
    document.getElementById('nuevaFechaFin').value = end.toISOString().slice(0, 16);
    document.getElementById('motivoReagendar').value = '';
    
    document.getElementById('sessionSection').style.display = 'block';
    document.getElementById('bookingSection').style.display = 'none';
    document.getElementById('reagendarSection').style.display = 'none';
    
    var puedeSuspender = (USER_ROLE === 'facilitador') || (USER_ROLE === 'ejecutivo' && props.es_mi_otec);
    var puedeReagendar = (USER_ROLE === 'ejecutivo' && props.es_mi_otec);
    
    document.getElementById('sessionActions').style.display = (puedeSuspender || puedeReagendar) ? 'block' : 'none';
    document.getElementById('suspenderBtn').style.display = puedeSuspender ? 'inline-block' : 'none';
    document.getElementById('reagendarBtn').style.display = puedeReagendar ? 'inline-block' : 'none';
    document.getElementById('bookingActions').style.display = 'none';
    
    document.getElementById('modalTitle').innerText = 'Detalles de la Sesión';
    document.getElementById('eventDetailHeader').className = 'modal-header bg-primary text-white';
    
    currentSessionData = { id: props.id, booking_id: props.booking_id, estado: props.status };
    
    var modal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
    modal.show();
}

function mostrarModalReserva(event, props) {
    document.getElementById('eventType').value = 'booking';
    document.getElementById('eventId').value = props.id;
    
    document.getElementById('bookingCurso').innerText = props.curso;
    document.getElementById('bookingEstado').innerHTML = '<span class="badge" style="background-color: ' + event.backgroundColor + '">' + (props.status_text || props.status) + '</span>';
    document.getElementById('bookingOtec').innerText = props.otec || 'N/A';
    document.getElementById('bookingModalidad').innerText = props.modalidad;
    document.getElementById('bookingStart').innerText = event.start ? event.start.toLocaleString('es-CL') : 'N/A';
    document.getElementById('bookingEnd').innerText = event.end ? event.end.toLocaleString('es-CL') : 'N/A';
    document.getElementById('bookingDuracion').innerText = props.duracion + ' horas';
    document.getElementById('bookingValor').innerText = props.valor ? '$' + formatNumber(props.valor) : 'No especificado';
    document.getElementById('bookingNotas').innerText = props.notas || 'Sin notas';
    document.getElementById('bookingCreatedBy').innerText = props.created_by || 'N/A';
    
    document.getElementById('sessionSection').style.display = 'none';
    document.getElementById('bookingSection').style.display = 'block';
    document.getElementById('sessionActions').style.display = 'none';
    
    var puedeAprobar = (USER_ROLE === 'facilitador' && props.status === 'pendiente');
    document.getElementById('bookingActions').style.display = puedeAprobar ? 'block' : 'none';
    
    document.getElementById('modalTitle').innerText = 'Detalles de la Capacitación';
    document.getElementById('eventDetailHeader').className = 'modal-header bg-primary text-white';
    
    currentBookingData = { id: props.id, status: props.status };
    
    var modal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
    modal.show();
}

function mostrarFormReagendar() {
    var section = document.getElementById('reagendarSection');
    section.style.display = section.style.display === 'none' ? 'block' : 'none';
}

function suspenderSesion() {
    var motivo = prompt('Motivo de la suspensión:', '');
    if (motivo === null) return;
    
    fetch(BASE_URL + '/sessions/suspender', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ session_id: currentSessionData.id, motivo: motivo })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) { showToast('Sesión suspendida', 'success'); setTimeout(() => location.reload(), 1000); }
        else showToast(data.error || 'Error', 'danger');
    })
    .catch(() => showToast('Error de conexión', 'danger'));
}

function reagendarSesion() {
    var nuevaFechaInicio = document.getElementById('nuevaFechaInicio').value;
    var nuevaFechaFin = document.getElementById('nuevaFechaFin').value;
    var motivo = document.getElementById('motivoReagendar').value;
    
    if (!nuevaFechaInicio || !nuevaFechaFin) { showToast('Complete ambas fechas', 'danger'); return; }
    if (new Date(nuevaFechaFin) <= new Date(nuevaFechaInicio)) { showToast('La fecha de término debe ser posterior', 'danger'); return; }
    if (!confirm('¿Reagendar esta sesión?\nMotivo: ' + (motivo || 'No especificado'))) return;
    
    fetch(BASE_URL + '/sessions/reagendar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ session_id: currentSessionData.id, nueva_fecha_inicio: nuevaFechaInicio, nueva_fecha_fin: nuevaFechaFin, motivo: motivo })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) { showToast('Sesión reagendada', 'success'); setTimeout(() => location.reload(), 1500); }
        else showToast(data.error || 'Error', 'danger');
    })
    .catch(() => showToast('Error de conexión', 'danger'));
}

function aprobarReserva() { cambiarEstadoReserva(currentBookingData.id, 'aprobada'); }
function rechazarReserva() { cambiarEstadoReserva(currentBookingData.id, 'rechazada'); }

function cambiarEstadoReserva(reservaId, nuevoEstado) {
    var texto = nuevoEstado === 'aprobada' ? 'aprobar' : (nuevoEstado === 'rechazada' ? 'rechazar' : 'anular');
    if (!confirm('¿Estás seguro de ' + texto + ' esta reserva?')) return;
    
    fetch(BASE_URL + '/calendar/updateReservaStatus', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'reserva_id=' + encodeURIComponent(reservaId) + '&estado=' + encodeURIComponent(nuevoEstado)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) { showToast('Estado actualizado', 'success'); setTimeout(() => location.reload(), 1000); }
        else showToast(data.error || 'Error', 'danger');
    })
    .catch(() => showToast('Error de conexión', 'danger'));
}

function formatNumber(num) { if (!num) return '0'; return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
function escapeHtml(text) { if (!text) return ''; var div = document.createElement('div'); div.textContent = text; return div.innerHTML; }

function showToast(message, type) {
    var container = document.getElementById('toastContainer');
    if (!container) { container = document.createElement('div'); container.id = 'toastContainer'; container.className = 'toast-notification'; document.body.appendChild(container); }
    var toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-' + (type === 'danger' ? 'danger' : type) + ' border-0 fade show';
    toast.innerHTML = `<div class="d-flex"><div class="toast-body"><i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    container.appendChild(toast);
    setTimeout(() => { if (toast && toast.parentNode) toast.remove(); }, 4000);
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>