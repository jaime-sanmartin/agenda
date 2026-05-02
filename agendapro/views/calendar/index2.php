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
                        <span class="badge status-badge" 
                              style="background-color: <?php echo $estado['color']; ?>; cursor: pointer;" 
                              data-estado="<?php echo $key; ?>">
                            <?php echo $estado['icono']; ?> <?php echo $estado['texto']; ?> 
                            <span class="badge-count" style="background-color: rgba(0,0,0,0.2); border-radius: 10px; padding: 0 5px; margin-left: 3px;"><?php echo $count; ?></span>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="card-body">
                    <!-- SELECTOR DE FACILITADOR PARA EJECUTIVOS -->
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
                                                Al seleccionar un facilitador, ver&aacute;s TODAS las capacitaciones que gestiona.<br>
                                                <strong class="text-warning">Los datos de otras OTEC aparecer&aacute;n con &#128274; y parcialmente ocultos.</strong>
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

<!-- Modal para listado de reservas por estado (POPUP INTERACTIVO) -->
<div class="modal fade" id="reservasModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" id="reservasModalHeader">
                <h5 class="modal-title">
                    <i class="fas fa-list"></i> Reservas <span id="modalEstadoTitle"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="reservasList" class="reservas-list-container">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando reservas...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles de la sesión (con opción de reagendar) -->
<div class="modal fade" id="sessionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="sessionModalHeader">
                <h5 class="modal-title">
                    <i class="fas fa-chalkboard"></i> Detalles de la Sesión
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="sessionId" value="">
                <input type="hidden" id="bookingId" value="">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold text-primary">Curso:</label>
                            <p id="sessionCurso" class="mb-0">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-primary">OTEC:</label>
                            <p id="sessionOtec" class="mb-0">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-primary">Modalidad:</label>
                            <p id="sessionModalidad" class="mb-0">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-primary">Estado Sesión:</label>
                            <p id="sessionEstado" class="mb-0">-</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold text-primary">Número Sesión:</label>
                            <p id="sessionNumero" class="mb-0">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-primary">Duración:</label>
                            <p id="sessionDuracion" class="mb-0">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-primary">Asistencia:</label>
                            <p id="sessionAsistencia" class="mb-0">-</p>
                        </div>
                    </div>
                </div>
                
                <!-- Información de fecha actual -->
                <div class="alert alert-info">
                    <i class="fas fa-calendar-alt"></i> <strong>Fecha actual:</strong> <span id="currentFechaInicio"></span>
                    <br>
                    <i class="fas fa-clock"></i> <strong>Horario actual:</strong> <span id="currentHorario"></span>
                </div>
                
                <!-- Formulario para reagendar -->
                <div class="card mt-3" id="reagendarSection" style="display: none;">
                    <div class="card-header bg-warning text-white">
                        <i class="fas fa-calendar-plus"></i> Reagendar Sesión
                    </div>
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
                        <button class="btn btn-warning" onclick="reagendarSesion()">
                            <i class="fas fa-calendar-check"></i> Confirmar Reagendamiento
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="registrarAsistenciaBtn" style="display: none;" onclick="registrarAsistencia()">
                    <i class="fas fa-user-check"></i> Registrar Asistencia
                </button>
                <button type="button" class="btn btn-danger" id="suspenderSesionBtn" style="display: none;" onclick="suspenderSesion()">
                    <i class="fas fa-pause-circle"></i> Suspender Sesión
                </button>
                <button type="button" class="btn btn-info" id="reagendarBtn" style="display: none;" onclick="mostrarFormReagendar()">
                    <i class="fas fa-calendar-alt"></i> Reagendar Sesión
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles de la reserva (curso continuo sin sesiones) -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i> Detalles de la Capacitaci&oacute;n
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold text-primary">Curso:</label>
                            <p id="eventCurso" class="mb-0">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-primary">Estado:</label>
                            <p id="eventStatus" class="mb-0">-</p>
                        </div>
                        <div class="mb-3" id="otecRow">
                            <label class="fw-bold text-primary">OTEC:</label>
                            <p id="eventOtec" class="mb-0">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-primary">Modalidad:</label>
                            <p id="eventModalidad" class="mb-0">-</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold text-primary">Fecha Inicio:</label>
                            <p id="eventStart" class="mb-0">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-primary">Fecha Fin:</label>
                            <p id="eventEnd" class="mb-0">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-primary">Duraci&oacute;n:</label>
                            <p id="eventDuracion" class="mb-0">-</p>
                        </div>
                        <div class="mb-3" id="valorRow">
                            <label class="fw-bold text-primary">Valor Acordado:</label>
                            <p id="eventValor" class="mb-0">-</p>
                        </div>
                    </div>
                </div>
                <div class="mb-3" id="notasRow">
                    <label class="fw-bold text-primary">Notas:</label>
                    <p id="eventNotas" class="mb-0 bg-light p-2 rounded">-</p>
                </div>
                <div class="mb-3" id="solicitadoRow">
                    <label class="fw-bold text-primary">Solicitado por:</label>
                    <p id="eventCreatedBy" class="mb-0">-</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="approveBtn" style="display: none;">
                    <i class="fas fa-check"></i> Aprobar
                </button>
                <button type="button" class="btn btn-danger" id="rejectBtn" style="display: none;">
                    <i class="fas fa-times"></i> Rechazar
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
#calendar {
    min-height: 600px;
}

.fc .fc-toolbar-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #4e73df;
}

.fc .fc-button-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.fc .fc-button-primary:hover {
    background-color: #2e59d9;
    border-color: #2e59d9;
}

.fc .fc-daygrid-day.fc-day-today {
    background-color: rgba(78, 115, 223, 0.05);
}

.custom-date-range {
    font-size: 0.85rem;
    font-weight: 500;
    background-color: #f8f9fc;
    padding: 6px 12px;
    border-radius: 20px;
    display: inline-block;
    color: #4e73df;
    border: 1px solid #e3e6f0;
    margin-left: 10px;
}

.fc-daygrid-event {
    cursor: pointer;
    border-radius: 4px;
    padding: 2px 4px;
    font-size: 0.75rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 1px;
    transition: all 0.2s;
}

.fc-daygrid-event:hover {
    opacity: 0.9;
    transform: scale(1.01);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    z-index: 10;
}

/* Tooltip personalizado para eventos */
.fc-event-tooltip {
    position: absolute;
    background: rgba(0,0,0,0.85);
    color: white;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12px;
    z-index: 9999;
    pointer-events: none;
    white-space: nowrap;
    font-family: inherit;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.fc-event-tooltip:before {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    border-width: 5px 5px 0;
    border-style: solid;
    border-color: rgba(0,0,0,0.85) transparent transparent;
}

.legend .badge {
    padding: 8px 15px;
    font-size: 0.85rem;
    font-weight: 500;
    color: white;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.legend .badge:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    cursor: pointer;
}

.legend .badge i {
    font-size: 0.9rem;
}

.status-badge {
    cursor: pointer;
}

.reservas-list-container {
    max-height: 500px;
    overflow-y: auto;
}

.reserva-item {
    border-left: 4px solid;
    margin-bottom: 12px;
    padding: 15px;
    background: #f8f9fc;
    border-radius: 8px;
    transition: all 0.2s;
}

.reserva-item:hover {
    background: #eef2f7;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.reserva-item.border-pendiente { border-left-color: #f39c12; }
.reserva-item.border-aprobada { border-left-color: #2ecc71; }
.reserva-item.border-rechazada { border-left-color: #e74c3c; }
.reserva-item.border-anulada { border-left-color: #95a5a6; }

.btn-estado {
    margin: 2px;
    padding: 4px 12px;
    font-size: 12px;
}

.modal-header.status-pendiente { background: linear-gradient(135deg, #f39c12, #e67e22); }
.modal-header.status-aprobada { background: linear-gradient(135deg, #2ecc71, #27ae60); }
.modal-header.status-rechazada { background: linear-gradient(135deg, #e74c3c, #c0392b); }
.modal-header.status-anulada { background: linear-gradient(135deg, #95a5a6, #7f8c8d); }
.modal-header.session-modal { background: linear-gradient(135deg, #4e73df, #224abe); }

.modal-header {
    color: white;
    border-radius: 12px 12px 0 0;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.toast-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
}

.toast {
    opacity: 0.95;
    border-radius: 8px;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .fc .fc-toolbar {
        flex-direction: column;
        gap: 10px;
    }
    
    .legend {
        justify-content: center;
    }
    
    .toast-notification {
        min-width: 250px;
        bottom: 10px;
        right: 10px;
    }
}
</style>

<script>
const BASE_URL = window.location.origin + '/agendapro';
const USER_ROLE = '<?php echo $role; ?>';

// Variable para almacenar el tooltip actual
var currentTooltip = null;

// Función para mostrar tooltip personalizado
function showTooltip(element, title, content) {
    if (currentTooltip) {
        currentTooltip.remove();
    }
    
    var tooltip = document.createElement('div');
    tooltip.className = 'fc-event-tooltip';
    tooltip.innerHTML = '<strong>' + title + '</strong><br>' + content;
    
    var rect = element.getBoundingClientRect();
    tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = (rect.top - tooltip.offsetHeight - 10) + 'px';
    
    document.body.appendChild(tooltip);
    currentTooltip = tooltip;
    
    setTimeout(function() {
        if (currentTooltip) {
            currentTooltip.remove();
            currentTooltip = null;
        }
    }, 3000);
}

function hideTooltip() {
    if (currentTooltip) {
        currentTooltip.remove();
        currentTooltip = null;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    if (!calendarEl) {
        console.error('Elemento calendar no encontrado');
        return;
    }
    
    var currentDateRange = '';
    
    function updateDateRange(view) {
        var rangeText = '';
        
        if (view.type === 'dayGridMonth') {
            rangeText = view.title;
        } else if (view.type === 'timeGridWeek') {
            var start = view.currentStart;
            var end = view.currentEnd;
            var endDate = new Date(end);
            endDate.setDate(endDate.getDate() - 1);
            
            var startStr = start.toLocaleDateString('es', { day: 'numeric', month: 'short' });
            var endStr = endDate.toLocaleDateString('es', { day: 'numeric', month: 'short', year: 'numeric' });
            rangeText = startStr + ' – ' + endStr;
        } else if (view.type === 'timeGridDay') {
            var date = view.currentStart;
            rangeText = date.toLocaleDateString('es', { day: 'numeric', month: 'long', year: 'numeric' });
        } else {
            rangeText = view.title;
        }
        
        var rangeContainer = document.getElementById('customDateRange');
        if (!rangeContainer) {
            var todayBtn = document.querySelector('.fc-today-button');
            if (todayBtn && todayBtn.parentNode) {
                rangeContainer = document.createElement('span');
                rangeContainer.id = 'customDateRange';
                rangeContainer.className = 'custom-date-range ms-2';
                todayBtn.parentNode.insertBefore(rangeContainer, todayBtn.nextSibling);
            }
        }
        
        if (rangeContainer) {
            rangeContainer.textContent = rangeText;
            currentDateRange = rangeText;
        }
    }
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Dia'
        },
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
            success: function(data) {
                console.log('Eventos recibidos:', data);
                if (data && data.error) {
                    console.error('Error del servidor:', data.error);
                    showToast('Error: ' + data.error, 'danger');
                }
            },
            failure: function(xhr, status, error) {
                console.error('Error en la petición:', status, error);
                showToast('Error al cargar el calendario', 'danger');
            }
        },
        editable: USER_ROLE === 'facilitador',
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        displayEventTime: false,
        
        eventContent: function(arg) {
            var status = arg.event.extendedProps.status;
            var cursoNombre = arg.event.extendedProps.curso || arg.event.title;
            var esMiOtec = arg.event.extendedProps.es_mi_otec;
            var userRole = USER_ROLE;
            var tipo = arg.event.extendedProps.type;
            
            var iconHtml = '';
            if (tipo === 'session') {
                iconHtml = '<i class="fas fa-chalkboard" style="margin-right: 4px; font-size: 0.7rem;"></i>';
            } else {
                switch(status) {
                    case 'pendiente':
                        iconHtml = '<i class="fas fa-hourglass-half" style="margin-right: 4px; font-size: 0.7rem;"></i>';
                        break;
                    case 'aprobada':
                        iconHtml = '<i class="fas fa-check-circle" style="margin-right: 4px; font-size: 0.7rem;"></i>';
                        break;
                    default:
                        iconHtml = '<i class="fas fa-calendar-alt" style="margin-right: 4px; font-size: 0.7rem;"></i>';
                }
            }
            
            var lockHtml = '';
            if (userRole === 'ejecutivo' && !esMiOtec) {
                lockHtml = '<i class="fas fa-lock" style="margin-right: 4px; font-size: 0.7rem;"></i>';
            }
            
            return {
                html: `<div style="display: flex; align-items: center; overflow: hidden; gap: 2px;">
                            ${lockHtml}${iconHtml}
                            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${cursoNombre}</span>
                        </div>`
            };
        },
        
        eventMouseEnter: function(info) {
            // Mostrar tooltip solo para sesiones
            if (info.event.extendedProps.type === 'session') {
                var startTime = info.event.start;
                var endTime = info.event.end;
                var duracionHoras = info.event.extendedProps.duracion;
                var otec = info.event.extendedProps.otec || 'N/A';
                var modalidad = info.event.extendedProps.modalidad || 'Presencial';
                
                var horaInicio = startTime.toLocaleTimeString('es-CL', { hour: '2-digit', minute: '2-digit' });
                var horaFin = endTime ? endTime.toLocaleTimeString('es-CL', { hour: '2-digit', minute: '2-digit' }) : '';
                
                var title = info.event.title;
                var content = `🕐 ${horaInicio} - ${horaFin} | ⏱ ${duracionHoras}h<br>🏢 ${otec} | 📋 ${modalidad}`;
                
                // Obtener el elemento DOM del evento
                var eventEl = info.el;
                showTooltip(eventEl, title, content);
            }
        },
        
        eventMouseLeave: function(info) {
            hideTooltip();
        },
        
        eventClick: function(info) {
            var event = info.event;
            var props = event.extendedProps;
            
            // Si es una sesión, mostrar modal de sesión con opciones
            if (props.type === 'session') {
                mostrarModalSesion(event, props);
            } else {
                // Es una reserva (curso continuo sin sesiones individuales)
                mostrarModalReserva(event, props);
            }
        },
        
        select: function(info) {
            if (USER_ROLE === 'ejecutivo') {
                var startDate = info.startStr;
                var endDate = info.endStr;
                window.location.href = BASE_URL + '/bookings/create?start=' + encodeURIComponent(startDate) + '&end=' + encodeURIComponent(endDate);
            }
        },
        
        datesSet: function(info) {
            updateDateRange(info.view);
        }
    });
    
    calendar.render();
    
    setTimeout(function() {
        if (calendar.view) {
            updateDateRange(calendar.view);
        }
    }, 200);
    
    // =====================================================
    // FUNCIONES PARA MODAL DE SESIÓN
    // =====================================================
    
    function mostrarModalSesion(event, props) {
        var sessionId = props.id;
        var bookingId = props.booking_id;
        var cursoNombre = props.curso || event.title.replace(/^Sesión \d+:\s*/, '');
        var otec = props.otec || 'N/A';
        var modalidad = props.modalidad || 'Presencial';
        var estadoSesion = props.status || 'pendiente';
        var estadoTexto = '';
        var duracion = props.duracion || 0;
        var asistencia = props.asistencia || 0;
        
        // Obtener número de sesión del título
        var numeroSesion = event.title.match(/Sesión (\d+):/);
        numeroSesion = numeroSesion ? numeroSesion[1] : '?';
        
        switch(estadoSesion) {
            case 'pendiente': estadoTexto = '<span class="badge bg-warning">Pendiente</span>'; break;
            case 'realizada': estadoTexto = '<span class="badge bg-success">Realizada</span>'; break;
            case 'suspendida': estadoTexto = '<span class="badge bg-danger">Suspendida</span>'; break;
            case 'anulada': estadoTexto = '<span class="badge bg-secondary">Anulada</span>'; break;
            default: estadoTexto = '<span class="badge bg-info">' + estadoSesion + '</span>';
        }
        
        var asistenciaTexto = asistencia > 0 ? asistencia + ' personas' : 'No registrada';
        
        var start = event.start;
        var end = event.end;
        var fechaInicioStr = start.toLocaleDateString('es-CL', { day: '2-digit', month: '2-digit', year: 'numeric' });
        var horaInicioStr = start.toLocaleTimeString('es-CL', { hour: '2-digit', minute: '2-digit' });
        var horaFinStr = end ? end.toLocaleTimeString('es-CL', { hour: '2-digit', minute: '2-digit' }) : '';
        
        document.getElementById('sessionId').value = sessionId;
        document.getElementById('bookingId').value = bookingId;
        document.getElementById('sessionCurso').innerHTML = '<strong>' + escapeHtml(cursoNombre) + '</strong>';
        document.getElementById('sessionOtec').innerText = escapeHtml(otec);
        document.getElementById('sessionModalidad').innerText = modalidad;
        document.getElementById('sessionEstado').innerHTML = estadoTexto;
        document.getElementById('sessionNumero').innerText = numeroSesion;
        document.getElementById('sessionDuracion').innerText = duracion + ' horas';
        document.getElementById('sessionAsistencia').innerHTML = asistenciaTexto;
        document.getElementById('currentFechaInicio').innerHTML = '<strong>' + fechaInicioStr + '</strong>';
        document.getElementById('currentHorario').innerHTML = '<strong>' + horaInicioStr + ' - ' + horaFinStr + '</strong>';
        
        // Configurar fechas para el formulario de reagendar (valor por defecto = fecha actual)
        var currentStartISO = start.toISOString().slice(0, 16);
        var currentEndISO = end ? end.toISOString().slice(0, 16) : '';
        document.getElementById('nuevaFechaInicio').value = currentStartISO;
        document.getElementById('nuevaFechaFin').value = currentEndISO;
        
        // Mostrar/ocultar botones según rol y estado
        var registrarBtn = document.getElementById('registrarAsistenciaBtn');
        var suspenderBtn = document.getElementById('suspenderSesionBtn');
        var reagendarBtn = document.getElementById('reagendarBtn');
        var reagendarSection = document.getElementById('reagendarSection');
        
        // Ocultar sección de reagendar al inicio
        reagendarSection.style.display = 'none';
        
        if (USER_ROLE === 'facilitador') {
            if (estadoSesion === 'pendiente') {
                registrarBtn.style.display = 'inline-block';
                suspenderBtn.style.display = 'inline-block';
                reagendarBtn.style.display = 'inline-block';
            } else if (estadoSesion === 'suspendida') {
                registrarBtn.style.display = 'inline-block';
                suspenderBtn.style.display = 'none';
                reagendarBtn.style.display = 'inline-block';
            } else if (estadoSesion === 'realizada') {
                registrarBtn.style.display = 'none';
                suspenderBtn.style.display = 'none';
                reagendarBtn.style.display = 'none';
            } else {
                registrarBtn.style.display = 'none';
                suspenderBtn.style.display = 'none';
                reagendarBtn.style.display = 'none';
            }
        } else {
            registrarBtn.style.display = 'none';
            suspenderBtn.style.display = 'none';
            reagendarBtn.style.display = 'none';
        }
        
        var modalHeader = document.getElementById('sessionModalHeader');
        modalHeader.className = 'modal-header session-modal';
        
        var modal = new bootstrap.Modal(document.getElementById('sessionModal'));
        modal.show();
    }
    
    function mostrarModalReserva(event, props) {
        var cursoNombre = props.curso || event.title;
        
        document.getElementById('eventCurso').innerText = cursoNombre;
        document.getElementById('eventStatus').innerHTML = '<span class="badge" style="background-color: ' + event.backgroundColor + '">' + (props.status_text || props.status) + '</span>';
        document.getElementById('eventModalidad').innerText = props.modalidad === 'online' ? 'Online' : (props.modalidad === 'presencial' ? 'Presencial' : props.modalidad);
        document.getElementById('eventStart').innerText = formatDateTime(event.start);
        document.getElementById('eventEnd').innerText = event.end ? formatDateTime(event.end) : 'N/A';
        document.getElementById('eventDuracion').innerText = props.duracion ? props.duracion + ' horas' : 'N/A';
        
        if (props.es_mi_otec || USER_ROLE === 'facilitador') {
            document.getElementById('eventOtec').innerText = props.otec || 'N/A';
            document.getElementById('eventValor').innerText = props.valor ? '$' + formatNumber(props.valor) : 'No especificado';
            document.getElementById('eventNotas').innerText = props.notas || 'Sin notas';
            document.getElementById('eventCreatedBy').innerText = props.created_by || 'N/A';
        } else {
            document.getElementById('eventOtec').innerHTML = '<span class="text-muted"><i class="fas fa-lock"></i> Información privada de otra OTEC</span>';
            document.getElementById('eventValor').innerHTML = '<span class="text-muted"><i class="fas fa-lock"></i> Confidencial</span>';
            document.getElementById('eventNotas').innerHTML = '<span class="text-muted"><i class="fas fa-lock"></i> No disponible</span>';
            document.getElementById('eventCreatedBy').innerHTML = '<span class="text-muted"><i class="fas fa-lock"></i> No disponible</span>';
        }
        
        var approveBtn = document.getElementById('approveBtn');
        var rejectBtn = document.getElementById('rejectBtn');
        
        if (USER_ROLE === 'facilitador' && props.status === 'pendiente') {
            approveBtn.style.display = 'inline-block';
            rejectBtn.style.display = 'inline-block';
            approveBtn.onclick = function() { updateBookingStatus(event.id, 'aprobada'); };
            rejectBtn.onclick = function() { updateBookingStatus(event.id, 'rechazada'); };
        } else {
            approveBtn.style.display = 'none';
            rejectBtn.style.display = 'none';
        }
        
        var modal = new bootstrap.Modal(document.getElementById('eventModal'));
        modal.show();
    }
    
    // =====================================================
    // FUNCIONES DE ACCIONES SOBRE SESIONES
    // =====================================================
    
    window.mostrarFormReagendar = function() {
        var section = document.getElementById('reagendarSection');
        if (section.style.display === 'none') {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    };
    
    window.reagendarSesion = function() {
        var sessionId = document.getElementById('sessionId').value;
        var nuevaFechaInicio = document.getElementById('nuevaFechaInicio').value;
        var nuevaFechaFin = document.getElementById('nuevaFechaFin').value;
        var motivo = document.getElementById('motivoReagendar').value;
        
        if (!nuevaFechaInicio || !nuevaFechaFin) {
            showToast('Debe completar ambas fechas', 'danger');
            return;
        }
        
        var startDate = new Date(nuevaFechaInicio);
        var endDate = new Date(nuevaFechaFin);
        
        if (endDate <= startDate) {
            showToast('La fecha de término debe ser posterior a la fecha de inicio', 'danger');
            return;
        }
        
        if (confirm('¿Estás seguro de reagendar esta sesión?\nMotivo: ' + (motivo || 'No especificado'))) {
            fetch(BASE_URL + '/sessions/reagendar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    nueva_fecha_inicio: nuevaFechaInicio,
                    nueva_fecha_fin: nuevaFechaFin,
                    motivo: motivo
                })
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    showToast('Sesión reagendada correctamente', 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showToast(data.error || 'Error al reagendar la sesión', 'danger');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                showToast('Error de conexión al servidor', 'danger');
            });
        }
    };
    
    window.registrarAsistencia = function() {
        var sessionId = document.getElementById('sessionId').value;
        var asistencia = prompt('Ingrese el número de asistentes:', '0');
        
        if (asistencia !== null) {
            var numAsistencia = parseInt(asistencia);
            if (isNaN(numAsistencia)) {
                showToast('Debe ingresar un número válido', 'danger');
                return;
            }
            
            fetch(BASE_URL + '/sessions/registrarAsistencia', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    asistencia: numAsistencia
                })
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    showToast('Asistencia registrada correctamente', 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showToast(data.error || 'Error al registrar asistencia', 'danger');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                showToast('Error de conexión al servidor', 'danger');
            });
        }
    };
    
    window.suspenderSesion = function() {
        var sessionId = document.getElementById('sessionId').value;
        var motivo = prompt('Motivo de la suspensión:', '');
        
        if (motivo !== null) {
            fetch(BASE_URL + '/sessions/suspender', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    motivo: motivo
                })
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    showToast('Sesión suspendida correctamente', 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showToast(data.error || 'Error al suspender la sesión', 'danger');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                showToast('Error de conexión al servidor', 'danger');
            });
        }
    };
    
    // =====================================================
    // CONTADORES INTERACTIVOS - POPUP CON LISTADO
    // =====================================================
    
    document.querySelectorAll('.status-badge').forEach(function(badge) {
        badge.addEventListener('click', function() {
            var estado = this.getAttribute('data-estado');
            var estadoTexto = '';
            var headerClass = '';
            
            switch(estado) {
                case 'pendiente': 
                    estadoTexto = 'Pendientes'; 
                    headerClass = 'status-pendiente';
                    break;
                case 'aprobada': 
                    estadoTexto = 'Aprobadas'; 
                    headerClass = 'status-aprobada';
                    break;
                case 'rechazada': 
                    estadoTexto = 'Rechazadas'; 
                    headerClass = 'status-rechazada';
                    break;
                case 'anulada': 
                    estadoTexto = 'Anuladas'; 
                    headerClass = 'status-anulada';
                    break;
            }
            
            document.getElementById('modalEstadoTitle').innerHTML = '<span class="badge" style="background-color: ' + this.style.backgroundColor + '">' + estadoTexto + '</span>';
            
            var modalHeader = document.getElementById('reservasModalHeader');
            modalHeader.className = 'modal-header ' + headerClass;
            
            var modal = new bootstrap.Modal(document.getElementById('reservasModal'));
            modal.show();
            
            var reservasList = document.getElementById('reservasList');
            reservasList.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Cargando reservas...</p></div>';
            
            fetch(BASE_URL + '/calendar/getReservasByStatus', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'estado=' + encodeURIComponent(estado)
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    mostrarReservas(data.reservas, estado);
                } else {
                    reservasList.innerHTML = '<div class="alert alert-danger">Error: ' + (data.error || 'No se pudieron cargar las reservas') + '</div>';
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                reservasList.innerHTML = '<div class="alert alert-danger">Error de conexión al servidor</div>';
            });
        });
    });
    
    function mostrarReservas(reservas, estadoActual) {
        var reservasList = document.getElementById('reservasList');
        
        if (!reservas || reservas.length === 0) {
            reservasList.innerHTML = '<div class="alert alert-info text-center">No hay reservas en este estado</div>';
            return;
        }
        
        var html = '';
        reservas.forEach(function(reserva) {
            var borderClass = '';
            switch(reserva.estado) {
                case 'pendiente': borderClass = 'border-pendiente'; break;
                case 'aprobada': borderClass = 'border-aprobada'; break;
                case 'rechazada': borderClass = 'border-rechazada'; break;
                case 'anulada': borderClass = 'border-anulada'; break;
            }
            
            var fechaInicio = reserva.fecha_inicio ? new Date(reserva.fecha_inicio).toLocaleString('es-CL') : 'No especificada';
            var fechaFin = reserva.fecha_fin ? new Date(reserva.fecha_fin).toLocaleString('es-CL') : 'No especificada';
            
            html += '<div class="reserva-item ' + borderClass + '">';
            html += '<div class="d-flex justify-content-between align-items-start flex-wrap">';
            html += '<div class="flex-grow-1">';
            html += '<h6 class="mb-1"><strong>' + escapeHtml(reserva.curso_nombre || 'Curso sin nombre') + '</strong></h6>';
            html += '<small class="text-muted">OTEC: ' + escapeHtml(reserva.otec_nombre || 'N/A') + '</small><br>';
            html += '<small class="text-muted">Facilitador: ' + escapeHtml(reserva.facilitador_nombre || 'No asignado') + '</small><br>';
            html += '<small><i class="fas fa-calendar-alt"></i> ' + fechaInicio + ' hasta ' + fechaFin + '</small><br>';
            html += '<small><i class="fas fa-clock"></i> Duración: ' + (reserva.duracion_horas || 0) + ' horas</small><br>';
            if (reserva.valor_acordado) {
                html += '<small><i class="fas fa-dollar-sign"></i> Valor: $' + formatNumber(reserva.valor_acordado) + '</small><br>';
            }
            html += '<small><i class="fas fa-user"></i> Solicitado por: ' + escapeHtml(reserva.created_by_nombre || reserva.created_by_email || 'N/A') + '</small>';
            if (reserva.notas) {
                html += '<div class="mt-2"><small class="text-muted"><i class="fas fa-sticky-note"></i> Notas: ' + escapeHtml(reserva.notas) + '</small></div>';
            }
            html += '</div>';
            
            if (USER_ROLE === 'facilitador') {
                if (estadoActual === 'pendiente') {
                    html += '<div class="ms-3 mt-2 mt-sm-0">';
                    html += '<button class="btn btn-sm btn-success btn-estado" onclick="cambiarEstadoReserva(' + reserva.id + ', \'aprobada\')">';
                    html += '<i class="fas fa-check"></i> Aprobar</button> ';
                    html += '<button class="btn btn-sm btn-danger btn-estado" onclick="cambiarEstadoReserva(' + reserva.id + ', \'rechazada\')">';
                    html += '<i class="fas fa-times"></i> Rechazar</button>';
                    html += '</div>';
                } else if (estadoActual === 'aprobada') {
                    html += '<div class="ms-3 mt-2 mt-sm-0">';
                    html += '<button class="btn btn-sm btn-secondary btn-estado" onclick="cambiarEstadoReserva(' + reserva.id + ', \'anulada\')">';
                    html += '<i class="fas fa-ban"></i> Anular</button>';
                    html += '</div>';
                }
            }
            
            html += '</div>';
            html += '</div>';
        });
        
        reservasList.innerHTML = html;
    }
    
    var facilitadorFilter = document.getElementById('facilitadorFilter');
    if (facilitadorFilter) {
        facilitadorFilter.addEventListener('change', function() {
            calendar.refetchEvents();
        });
    }
});

function formatDateTime(date) {
    if (!date) return 'N/A';
    try {
        return date.toLocaleString('es-CL', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (e) {
        return date.toString();
    }
}

function formatNumber(num) {
    if (!num) return '0';
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function escapeHtml(text) {
    if (!text) return '';
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function updateBookingStatus(eventId, newStatus) {
    var bookingId = eventId.replace('booking_', '');
    
    fetch(BASE_URL + '/bookings/updateStatus', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: bookingId,
            status: newStatus
        })
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            showToast('Estado actualizado correctamente', 'success');
            setTimeout(function() {
                location.reload();
            }, 1000);
        } else {
            showToast(data.error || 'Error al actualizar', 'danger');
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        showToast('Error de conexión', 'danger');
    });
}

function cambiarEstadoReserva(reservaId, nuevoEstado) {
    var estadoTexto = '';
    switch(nuevoEstado) {
        case 'aprobada': estadoTexto = 'aprobar'; break;
        case 'rechazada': estadoTexto = 'rechazar'; break;
        case 'anulada': estadoTexto = 'anular'; break;
        default: estadoTexto = nuevoEstado;
    }
    
    if (confirm('¿Estás seguro de ' + estadoTexto + ' esta reserva?')) {
        fetch(BASE_URL + '/calendar/updateReservaStatus', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'reserva_id=' + encodeURIComponent(reservaId) + '&estado=' + encodeURIComponent(nuevoEstado)
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                showToast(data.message || 'Estado actualizado correctamente', 'success');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                showToast(data.error || 'Error al actualizar el estado', 'danger');
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            showToast('Error de conexión al servidor', 'danger');
        });
    }
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
    
    setTimeout(function() {
        if (toast && toast.parentNode) {
            toast.remove();
        }
    }, 5000);
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>