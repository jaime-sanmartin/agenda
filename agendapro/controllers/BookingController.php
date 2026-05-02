<?php
// controllers/BookingController.php
require_once 'core/Controller.php';
require_once 'helpers/Auth.php';
require_once 'models/Booking.php';
require_once 'models/Course.php';
require_once 'models/Otec.php';
require_once 'models/Availability.php';
require_once 'models/Notification.php';
require_once 'helpers/Email.php';

class BookingController extends Controller {
    
    public function __construct() {
        Auth::checkLogin();
    }
    
    public function index() {
        $bookingModel = new Booking();
        
        if (Auth::isAdministrador()) {
            $bookings = $bookingModel->getAllBookingsAdmin();
        } elseif (Auth::isFacilitador()) {
            $bookings = $bookingModel->getByFacilitadorCourses($_SESSION['user_id']);
        } else {
            $bookings = $bookingModel->getAllWithDetails(['otec_id' => $_SESSION['user_otec_id']]);
        }
        
        $this->view('bookings/index', [
            'bookings' => $bookings,
            'title' => 'Gestión de Reservas'
        ]);
    }
    
    /**
     * Obtener ejecutivos asociados al facilitador (para el select)
     */
    private function getEjecutivosParaFacilitador() {
        $userModel = new User();
        return $userModel->getEjecutivosByFacilitador($_SESSION['user_id']);
    }
    
    public function create() {
        $courseModel = new Course();
        $otecModel = new Otec();
        $facilitadorId = $_SESSION['user_id'];
        $esFacilitador = Auth::isFacilitador();
        
        // Para facilitador: obtener ejecutivos y sus OTEC (con imagen)
        $ejecutivos = [];
        $otecImagenInicial = null;
        
        if ($esFacilitador) {
            $ejecutivos = $this->getEjecutivosParaFacilitador();
            // Imagen por defecto del primer ejecutivo (si existe)
            if (!empty($ejecutivos)) {
                $otecImagenInicial = $ejecutivos[0]['otec_imagen'] ?? null;
            }
        } else {
            // Ejecutivo: obtener su propia OTEC e imagen
            $otec = $otecModel->find($_SESSION['user_otec_id']);
            $otecImagenInicial = $otec['imagen_otec'] ?? null;
        }
        
        // Cursos: con nombre del facilitador que los creó
        if ($esFacilitador) {
            $courses = $courseModel->getActiveCoursesWithFacilitador();
        } else {
            $courses = $courseModel->getPublicCoursesWithFacilitador();
        }
        
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $tipoCalendario = $this->getPost('tipo_calendario', 'continuo');
            $curso_id = $this->getPost('curso_id');
            $notas = $this->getPost('notas');
            $valor_acordado = $this->getPost('valor_acordado');
            
            // Determinar OTEC y created_by
            if ($esFacilitador) {
                $ejecutivo_id = $this->getPost('ejecutivo_id');
                if (!$ejecutivo_id) {
                    $this->view('bookings/create', [
                        'courses' => $courses,
                        'ejecutivos' => $ejecutivos,
                        'otec_imagen_inicial' => $otecImagenInicial,
                        'errors' => ['Debe seleccionar un ejecutivo.'],
                        'title' => 'Nueva Reserva'
                    ]);
                    return;
                }
                $userModel = new User();
                $ejecutivo = $userModel->find($ejecutivo_id);
                if (!$ejecutivo || $ejecutivo['rol'] !== 'ejecutivo') {
                    $this->view('bookings/create', [
                        'courses' => $courses,
                        'ejecutivos' => $ejecutivos,
                        'otec_imagen_inicial' => $otecImagenInicial,
                        'errors' => ['Ejecutivo no válido.'],
                        'title' => 'Nueva Reserva'
                    ]);
                    return;
                }
                $otec_id = $ejecutivo['otec_id'];
                $created_by = $ejecutivo_id;
            } else {
                $otec_id = $_SESSION['user_otec_id'];
                $created_by = $_SESSION['user_id'];
            }
            
            // Validar curso
            if (!$curso_id) {
                $this->view('bookings/create', [
                    'courses' => $courses,
                    'ejecutivos' => $ejecutivos,
                    'otec_imagen_inicial' => $otecImagenInicial,
                    'errors' => ['Debe seleccionar un curso.'],
                    'title' => 'Nueva Reserva'
                ]);
                return;
            }
            
            $data = [
                'otec_id' => $otec_id,
                'curso_id' => $curso_id,
                'valor_acordado' => $valor_acordado,
                'notas' => $notas,
                'estado' => 'pendiente',
                'created_by' => $created_by,
                'tipo_calendario' => $tipoCalendario,
                'facilitador_id' => $facilitadorId
            ];
            
            // Validar fechas según tipo
            $errors = [];
            if ($tipoCalendario === 'continuo') {
                $fecha_inicio = $this->getPost('fecha_inicio');
                $fecha_fin = $this->getPost('fecha_fin');
                if (empty($fecha_inicio) || empty($fecha_fin)) {
                    $errors[] = 'Las fechas de inicio y fin son requeridas.';
                } elseif (strtotime($fecha_fin) <= strtotime($fecha_inicio)) {
                    $errors[] = 'La fecha de fin debe ser posterior a la fecha de inicio.';
                } else {
                    $data['fecha_inicio'] = $fecha_inicio;
                    $data['fecha_fin'] = $fecha_fin;
                }
            } else {
                // Para sesiones, las fechas se manejan aparte
                $data['fecha_inicio'] = null;
                $data['fecha_fin'] = null;
            }
            
            $bookingModel = new Booking();
            
            // Verificar conflictos (solo modo continuo)
            if (empty($errors) && $tipoCalendario === 'continuo') {
                if ($bookingModel->checkConflict($data['fecha_inicio'], $data['fecha_fin'])) {
                    $errors[] = 'Conflicto de horario con otra capacitación.';
                }
            }
            
            if (empty($errors)) {
                $bookingId = $bookingModel->create($data);
                
                if ($bookingId) {
                    // Si es por sesiones, generar sesiones
                    if ($tipoCalendario === 'sesiones') {
                        $diasSemana = $this->getPost('dias_semana', []);
                        $horaInicio = $this->getPost('hora_inicio', '09:00');
                        $duracionSesion = (int)$this->getPost('duracion_sesion', 3);
                        $fechaInicioCurso = $this->getPost('fecha_inicio_curso');
                        $fechaLimite = $this->getPost('fecha_limite');
                        
                        $curso = $courseModel->find($curso_id);
                        $totalHoras = $curso['duracion_horas'] ?? 0;
                        
                        $sessions = $bookingModel->generarSesiones(
                            $fechaInicioCurso,
                            $totalHoras,
                            $diasSemana,
                            $horaInicio,
                            $duracionSesion,
                            $fechaLimite
                        );
                        
                        $bookingModel->createSessions($bookingId, $sessions);
                        
                        $configRecurrencia = $bookingModel->generarConfigRecurrencia(
                            $diasSemana,
                            $horaInicio,
                            $duracionSesion,
                            count($sessions)
                        );
                        $bookingModel->update($bookingId, ['recurrencia_config' => $configRecurrencia]);
                    }
                    
                    // Registrar actividad
                    if (class_exists('ActivityLog')) {
                        ActivityLog::log($facilitadorId, 'create_booking', 'bookings', $bookingId, "Reserva creada");
                    }
                    
                    // Notificar al ejecutivo si el facilitador creó la reserva
                    if ($esFacilitador && class_exists('Notification')) {
                        $notificationModel = new Notification();
                        $notificationModel->createNotification(
                            $created_by,
                            'Nueva reserva creada para ti',
                            "El facilitador ha creado una reserva del curso ID: $curso_id",
                            'info'
                        );
                    }
                    
                    $this->redirect('/agendapro/bookings?success=created');
                } else {
                    $errors[] = 'Error al crear la reserva.';
                }
            }
            
            // Si hay errores, volver a mostrar el formulario
            $this->view('bookings/create', [
                'courses' => $courses,
                'ejecutivos' => $ejecutivos,
                'otec_imagen_inicial' => $otecImagenInicial,
                'errors' => $errors,
                'data' => $data,
                'title' => 'Nueva Reserva'
            ]);
        } else {
            // GET: mostrar formulario
            $start = $this->getQuery('start');
            $end = $this->getQuery('end');
            $this->view('bookings/create', [
                'courses' => $courses,
                'ejecutivos' => $ejecutivos,
                'otec_imagen_inicial' => $otecImagenInicial,
                'start' => $start,
                'end' => $end,
                'title' => 'Nueva Reserva'
            ]);
        }
    }
    
    public function edit($id) {
        Auth::checkRole('facilitador');
        
        $bookingModel = new Booking();
        $booking = $bookingModel->find($id);
        
        if (!$booking) {
            $this->redirect('/agendapro/bookings?error=notfound');
        }
        
        $courseModel = new Course();
        $otecModel = new Otec();
        
        $courses = $courseModel->all();
        $otecs = $otecModel->getActiveOtec();
        
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $fecha_inicio = $this->getPost('fecha_inicio');
            $fecha_fin = $this->getPost('fecha_fin');
            
            $data = [
                'otec_id' => $this->getPost('otec_id'),
                'curso_id' => $this->getPost('curso_id'),
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'valor_acordado' => $this->getPost('valor_acordado'),
                'notas' => $this->getPost('notas'),
                'estado' => $this->getPost('estado')
            ];
            
            if ($bookingModel->checkConflict($fecha_inicio, $fecha_fin, $id)) {
                $this->view('bookings/edit', [
                    'booking' => $booking,
                    'courses' => $courses,
                    'otecs' => $otecs,
                    'error' => 'Conflicto de horario con otra capacitación',
                    'title' => 'Editar Reserva'
                ]);
                return;
            }
            
            if ($bookingModel->update($id, $data)) {
                ActivityLog::log($_SESSION['user_id'], 'update_booking', 'bookings', $id, "Reserva actualizada");
                $this->redirect('/agendapro/bookings?success=updated');
            } else {
                $this->view('bookings/edit', [
                    'booking' => $booking,
                    'courses' => $courses,
                    'otecs' => $otecs,
                    'error' => 'Error al actualizar la reserva',
                    'title' => 'Editar Reserva'
                ]);
            }
        } else {
            $this->view('bookings/edit', [
                'booking' => $booking,
                'courses' => $courses,
                'otecs' => $otecs,
                'title' => 'Editar Reserva'
            ]);
        }
    }
    
    public function details($id) {
        $bookingModel = new Booking();
        $bookings = $bookingModel->getAllWithDetails();
        $booking = null;
        
        foreach ($bookings as $b) {
            if ($b['id'] == $id) {
                $booking = $b;
                break;
            }
        }
        
        if (!$booking) {
            $this->redirect('/agendapro/bookings?error=notfound');
        }
        
        if (!Auth::isFacilitador() && $booking['otec_id'] != $_SESSION['user_otec_id']) {
            $this->redirect('/agendapro/bookings?error=unauthorized');
        }
        
        $sesiones = [];
        if (($booking['tipo_calendario'] ?? 'continuo') === 'sesiones') {
            $sesiones = $bookingModel->getSessions($id);
        }
        
        $this->view('bookings/view', [
            'booking' => $booking,
            'sesiones' => $sesiones,
            'title' => 'Detalles de Reserva'
        ]);
    }
    
    public function updateStatus() {
        if (!$this->isPost()) {
            $this->json(['error' => 'Método no permitido'], 405);
            return;
        }
        
        $id = $this->getPost('id');
        $status = $this->getPost('status');
        
        if (!$id || !$status) {
            $this->json(['error' => 'Datos incompletos'], 400);
            return;
        }
        
        Security::verifyCSRFToken($this->getPost('csrf_token'));
        
        $bookingModel = new Booking();
        $booking = $bookingModel->find($id);
        
        if (!$booking) {
            $this->json(['error' => 'Reserva no encontrada'], 404);
            return;
        }
        
        if (!Auth::isFacilitador()) {
            $this->json(['error' => 'No autorizado'], 403);
            return;
        }
        
        $result = $bookingModel->updateStatus($id, $status, $_SESSION['user_id']);
        
        if ($result) {
            if (class_exists('ActivityLog')) {
                ActivityLog::log($_SESSION['user_id'], 'update_booking_status', 'bookings', $id, "Estado cambiado a: $status");
            }
            if (class_exists('Notification')) {
                $notificationModel = new Notification();
                $titulo = "Estado de reserva actualizado";
                $mensaje = "Su reserva ha sido <strong>" . ucfirst($status) . "</strong>";
                $notificationModel->createNotification($booking['created_by'], $titulo, $mensaje, $status === 'aprobada' ? 'success' : 'warning');
            }
            $this->json(['success' => true, 'message' => "Reserva " . ucfirst($status) . " correctamente"]);
        } else {
            $this->json(['error' => 'Error al actualizar el estado'], 500);
        }
    }
    
    public function delete($id) {
        Auth::checkRole('facilitador');
        
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $bookingModel = new Booking();
            if ($bookingModel->delete($id)) {
                ActivityLog::log($_SESSION['user_id'], 'delete_booking', 'bookings', $id, "Reserva eliminada");
                $this->redirect('/agendapro/bookings?success=deleted');
            } else {
                $this->redirect('/agendapro/bookings?error=delete_failed');
            }
        }
    }
    
    public function registrarAsistencia() {
        if (!$this->isPost()) {
            $this->json(['error' => 'Método no permitido'], 405);
            return;
        }
        
        Auth::checkRole('facilitador');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $sessionId = $input['session_id'] ?? null;
        $asistencia = $input['asistencia'] ?? null;
        $notas = $input['notas'] ?? null;
        
        if (!$sessionId || $asistencia === null) {
            $this->json(['error' => 'Datos incompletos'], 400);
            return;
        }
        
        $bookingModel = new Booking();
        $result = $bookingModel->registrarAsistencia($sessionId, $asistencia, $notas);
        
        if ($result) {
            $this->json(['success' => true]);
        } else {
            $this->json(['error' => 'Error al registrar asistencia'], 500);
        }
    }
}
?>