<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus-circle"></i> Nueva Reserva
                    </h6>
                    <div id="otecImagenContainer">
                        <?php if (!empty($otec_imagen_inicial) and Session::isEjecutivo()): ?>
                            <img src="<?php echo BASE_URL . '/assets/img/otecs/' . $otec_imagen_inicial; ?>" 
                                 alt="Logo OTEC" 
                                 style="height: 40px; width: auto; max-width: 120px; object-fit: contain;">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Errores:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/bookings/create" id="bookingForm">
                        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
                        
                        <!-- Selector de Ejecutivo (solo para facilitador) -->
                        <?php if (Session::isFacilitador()): ?>
                        <div class="mb-3">
                            <label for="ejecutivo_id" class="form-label">Ejecutivo <span class="text-danger">*</span></label>
                            <select class="form-select" id="ejecutivo_id" name="ejecutivo_id" required>
                                <option value="">Seleccione un ejecutivo...</option>
                                <?php foreach ($ejecutivos as $ejecutivo): ?>
                                    <option value="<?php echo $ejecutivo['id']; ?>"
                                        data-otec-id="<?php echo $ejecutivo['otec_id']; ?>"
                                        <?php echo (($data['ejecutivo_id'] ?? '') == $ejecutivo['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($ejecutivo['nombre'] . ' - OTEC: ' . ($ejecutivo['otec_nombre'] ?? 'OTEC')); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="otec_id" id="otec_id" value="">
                        </div>
                        <?php else: ?>
                            <input type="hidden" name="otec_id" value="<?php echo $_SESSION['user_otec_id']; ?>">
                        <?php endif; ?>
                        
                        <!-- Curso -->
                        <div class="mb-3">
                            <label for="curso_id" class="form-label">Curso <span class="text-danger">*</span></label>
                            <select class="form-select" id="curso_id" name="curso_id" required>
                                <option value="">Seleccione un curso...</option>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?php echo $course['id']; ?>"
                                        data-duracion="<?php echo $course['duracion_horas']; ?>"
                                        <?php echo (($data['curso_id'] ?? '') == $course['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($course['nombre']); ?> 
                                        (<?php echo $course['duracion_horas']; ?> hrs - <?php echo $course['modalidad']; ?>)
                                        <?php if (!empty($course['facilitador_nombre'])): ?>
                                            - <b>Facilitador:</b> <?php echo htmlspecialchars($course['facilitador_nombre']); ?>
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Tipo de Calendario -->
                        <div class="mb-3">
                            <label class="form-label">Tipo de Calendario</label>
                            <select id="tipoCalendario" name="tipo_calendario" class="form-select">
                                <option value="continuo" <?php echo (($data['tipo_calendario'] ?? 'continuo') == 'continuo') ? 'selected' : ''; ?>>Continuo</option>
                                <option value="sesiones" <?php echo (($data['tipo_calendario'] ?? '') == 'sesiones') ? 'selected' : ''; ?>>Sesiones</option>
                            </select>
                        </div>
                        
                        <!-- Fechas para modo continuo -->
                        <div id="fechasContinuo">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                    <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                                           value="<?php echo htmlspecialchars($data['fecha_inicio'] ?? $start ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                    <input type="datetime-local" class="form-control" id="fecha_fin" name="fecha_fin" 
                                           value="<?php echo htmlspecialchars($data['fecha_fin'] ?? $end ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Configuración para cursos con sesiones -->
                        <div id="sesionesConfig" style="display: none;">
                            <div class="card mb-3 border-info">
                                <div class="card-header bg-info text-white">
                                    <i class="fas fa-calendar-week"></i> Configuración de Sesiones
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Fecha de inicio del curso:</label>
                                            <input type="date" id="fechaInicioCurso" name="fecha_inicio_curso" class="form-control">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Fecha límite (opcional):</label>
                                            <input type="date" id="fechaLimite" name="fecha_limite" class="form-control">
                                            <small class="text-muted">Dejar en blanco para calcular automáticamente</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Días de la semana:</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" value="1" id="diaLunes" name="dias_semana[]">
                                                        <label class="form-check-label">Lunes</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" value="2" id="diaMartes" name="dias_semana[]">
                                                        <label class="form-check-label">Martes</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" value="3" id="diaMiercoles" name="dias_semana[]">
                                                        <label class="form-check-label">Miércoles</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" value="4" id="diaJueves" name="dias_semana[]">
                                                        <label class="form-check-label">Jueves</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" value="5" id="diaViernes" name="dias_semana[]">
                                                        <label class="form-check-label">Viernes</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" value="6" id="diaSabado" name="dias_semana[]">
                                                        <label class="form-check-label">Sábado</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="horaInicio" class="form-label">Hora de inicio:</label>
                                            <input type="time" id="horaInicio" name="hora_inicio" class="form-control" value="09:00">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="duracionSesion" class="form-label">Duración por sesión (horas):</label>
                                            <input type="number" id="duracionSesion" name="duracion_sesion" class="form-control" value="3" step="1" min="1">
                                        </div>
                                    </div>
                                    <div class="alert alert-info mt-2">
                                        <i class="fas fa-info-circle"></i>
                                        Las sesiones se generarán automáticamente según los días seleccionados.
                                        <br>
                                        Total de horas del curso: <strong id="totalHorasCurso">0</strong> horas
                                        <span id="sesionesEstimadas"></span>
                                    </div>
                                </div>
                            </div>
                            <div id="resumenSesiones" style="display: none;">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <i class="fas fa-list-check"></i> Resumen de Sesiones a Crear
                                    </div>
                                    <div class="card-body">
                                        <div id="listaSesionesPreview" class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Fecha</th>
                                                        <th>Horario</th>
                                                        <th>Duración</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="sesionesPreviewBody">
                                                    <tr><td colspan="4" class="text-center text-muted">Seleccione días y hora para ver el preview</td></tr>
                                                </tbody>
                                             </div>
                                          </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="valor_acordado" class="form-label">Valor Acordado</label>
                                <input type="number" class="form-control" id="valor_acordado" name="valor_acordado" 
                                       value="<?php echo htmlspecialchars($data['valor_acordado'] ?? ''); ?>" step="1000">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="notas" class="form-label">Notas / Observaciones</label>
                                <textarea class="form-control" id="notas" name="notas" rows="3"><?php echo htmlspecialchars($data['notas'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>/bookings" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Crear Reserva
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cuando se selecciona un ejecutivo, se asigna su OTEC al campo oculto
const ejecutivoSelect = document.getElementById('ejecutivo_id');
if (ejecutivoSelect) {
    ejecutivoSelect.addEventListener('change', function() {
        let selectedOption = this.options[this.selectedIndex];
        let otecId = selectedOption.getAttribute('data-otec-id');
        document.getElementById('otec_id').value = otecId || '';
    });
}

// Mostrar/ocultar configuración de sesiones
document.getElementById('tipoCalendario').addEventListener('change', function() {
    const fechasContinuo = document.getElementById('fechasContinuo');
    const sesionesConfig = document.getElementById('sesionesConfig');
    if (this.value === 'sesiones') {
        fechasContinuo.style.display = 'none';
        sesionesConfig.style.display = 'block';
    } else {
        fechasContinuo.style.display = 'block';
        sesionesConfig.style.display = 'none';
    }
});

// Obtener duración del curso seleccionado
const cursoSelect = document.getElementById('curso_id');
const totalHorasSpan = document.getElementById('totalHorasCurso');
cursoSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const duracion = selectedOption.getAttribute('data-duracion');
    totalHorasSpan.innerText = duracion || 0;
    if (document.getElementById('tipoCalendario').value === 'sesiones') {
        calcularSesiones();
    }
});

// Calcular sesiones (versión simplificada)
function calcularSesiones() {
    const diasSeleccionados = Array.from(document.querySelectorAll('input[name="dias_semana[]"]:checked')).map(cb => parseInt(cb.value));
    const horaInicio = document.getElementById('horaInicio').value;
    const duracionSesion = parseInt(document.getElementById('duracionSesion').value) || 3;
    const fechaInicioCurso = document.getElementById('fechaInicioCurso').value;
    const duracionCurso = parseInt(totalHorasSpan.innerText);
    
    if (diasSeleccionados.length === 0 || !fechaInicioCurso || duracionCurso === 0) {
        document.getElementById('sesionesPreviewBody').innerHTML = '<tr><td colspan="4" class="text-center text-muted">Complete todos los campos</td></tr>';
        document.getElementById('sesionesEstimadas').innerHTML = '';
        return;
    }
    
    const sesionesNecesarias = Math.ceil(duracionCurso / duracionSesion);
    const sesionesPorSemana = diasSeleccionados.length;
    const semanasNecesarias = Math.ceil(sesionesNecesarias / sesionesPorSemana);
    document.getElementById('sesionesEstimadas').innerHTML = ` | ${sesionesNecesarias} sesiones (≈ ${semanasNecesarias} semanas)`;
    
    // Preview de hasta 10 sesiones
    let preview = [];
    let currentDate = new Date(fechaInicioCurso);
    let horasRestantes = duracionCurso;
    let generadas = 0;
    while (horasRestantes > 0 && generadas < 10) {
        let diaSemana = currentDate.getDay();
        let diaNumero = diaSemana === 0 ? 7 : diaSemana;
        if (diasSeleccionados.includes(diaNumero)) {
            let fechaHora = new Date(currentDate);
            let [h, m] = horaInicio.split(':');
            fechaHora.setHours(parseInt(h), parseInt(m), 0);
            let fechaFin = new Date(fechaHora);
            fechaFin.setHours(fechaFin.getHours() + duracionSesion);
            preview.push({
                num: generadas+1,
                fecha: fechaHora.toLocaleDateString('es-CL'),
                horaInicio: fechaHora.toLocaleTimeString('es-CL', {hour:'2-digit', minute:'2-digit'}),
                horaFin: fechaFin.toLocaleTimeString('es-CL', {hour:'2-digit', minute:'2-digit'}),
                duracion: duracionSesion
            });
            horasRestantes -= duracionSesion;
            generadas++;
        }
        currentDate.setDate(currentDate.getDate() + 1);
    }
    let html = '';
    preview.forEach(s => {
        html += `<tr>
                    <td>${s.num}</td>
                    <td>${s.fecha}</td>
                    <td>${s.horaInicio} - ${s.horaFin}</td>
                    <td>${s.duracion} hrs</td>
                 </tr>`;
    });
    if (horasRestantes > 0) {
        html += `<tr class="table-warning"><td colspan="4">... y más sesiones</td></tr>`;
    }
    document.getElementById('sesionesPreviewBody').innerHTML = html;
}

// Eventos para recalcular sesiones
const diasCheckboxes = document.querySelectorAll('input[name="dias_semana[]"]');
diasCheckboxes.forEach(cb => cb.addEventListener('change', calcularSesiones));
document.getElementById('horaInicio')?.addEventListener('change', calcularSesiones);
document.getElementById('duracionSesion')?.addEventListener('change', calcularSesiones);
document.getElementById('fechaInicioCurso')?.addEventListener('change', calcularSesiones);
</script>

<?php require_once 'views/layouts/footer.php'; ?>