<?php
// controllers/CalendarController.php
require_once 'core/Controller.php';
require_once 'helpers/Auth.php';
require_once 'models/Booking.php';
require_once 'models/User.php';

class CalendarController extends Controller {
    private $bookingModel;

    public function __construct() {
        Auth::checkLogin();
        $this->bookingModel = new Booking();
    }

    public function index() {
        $user = Auth::user();
        $userRol = $user['rol'] ?? $_SESSION['user_rol'] ?? null;
        $otecId = $user['otec_id'] ?? $_SESSION['user_otec_id'] ?? null;
        $userId = $user['id'] ?? $_SESSION['user_id'] ?? null;

        if ($userRol === 'facilitador') {
            $counts = $this->bookingModel->getReservasCountByStatus(null, $userId);
        } else {
            $counts = $this->bookingModel->getReservasCountByStatus($otecId, null);
        }
        
        $facilitadores = [];
        if ($userRol === 'ejecutivo' && $otecId) {
            $userModel = new User();
            $facilitadores = $userModel->getFacilitadoresByOtec($otecId);
        }
        
        $data = [
            'title' => 'Calendario de Capacitaciones',
            'role' => $userRol,
            'counts' => $counts,
            'facilitadores' => $facilitadores,
            'otec_id' => $otecId,
            'user_id' => $userId
        ];
        
        $this->view('calendar/index', $data);
    }

    private function forceUtf8($data) {
        if (is_string($data)) {
            if (mb_check_encoding($data, 'UTF-8')) return $data;
            $converted = @mb_convert_encoding($data, 'UTF-8', 'ISO-8859-1');
            if ($converted !== false && mb_check_encoding($converted, 'UTF-8')) return $converted;
            return mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        }
        if (is_array($data)) return array_map([$this, 'forceUtf8'], $data);
        return $data;
    }

    public function getEvents() {
        if (ob_get_level()) ob_clean();
        
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, must-revalidate');
        
        try {
            $start = $_GET['start'] ?? date('Y-m-d');
            $end = $_GET['end'] ?? date('Y-m-d', strtotime('+1 month'));
            
            $start = date('Y-m-d H:i:s', strtotime($start));
            $end = date('Y-m-d H:i:s', strtotime($end));
            
            $user = Auth::user();
            $userRol = $user['rol'] ?? $_SESSION['user_rol'] ?? null;
            $otecId = $user['otec_id'] ?? $_SESSION['user_otec_id'] ?? null;
            $userId = $user['id'] ?? $_SESSION['user_id'] ?? null;
            
            $facilitadorId = null;
            
            if ($userRol === 'ejecutivo' && isset($_GET['facilitador_id']) && $_GET['facilitador_id'] !== 'mi_otec') {
                $facilitadorId = $_GET['facilitador_id'];
                $bookings = $this->bookingModel->getEventsInRange($start, $end, $userRol, $otecId, $facilitadorId);
            } elseif ($userRol === 'facilitador') {
                $bookings = $this->bookingModel->getEventsByFacilitador($start, $end, $userId);
            } else {
                $bookings = $this->bookingModel->getEventsInRange($start, $end, $userRol, $otecId);
            }
            
            $events = [];
            
            // Obtener sesiones (excluye eliminadas)
            $sessionEvents = $this->bookingModel->getSessionEventsInRange($start, $end, $userRol, $otecId, $facilitadorId);
            
            foreach ($sessionEvents as $session) {
                $cursoNombre = $session['curso_nombre'] ?? 'Curso sin nombre';
                $otecNombre = $session['otec_nombre'] ?? '';
                $notas = $session['booking_notas'] ?? '';
                $createdBy = $session['created_by_nombre'] ?? '';
                $modalidad = $session['curso_modalidad'] ?? 'presencial';
                $duracionHoras = $session['curso_duracion'] ?? 0;
                $valorAcordado = $session['valor_acordado'] ?? 0;
                $esMiOtec = ($session['otec_id'] == $otecId);
                
                $sesionNumero = $this->getNumeroSesion($session['booking_id'], $session['fecha_inicio']);
                $title = "Sesión {$sesionNumero}: {$cursoNombre}";
                
                if ($userRol === 'ejecutivo' && $facilitadorId && !$esMiOtec) {
                    $title = '🔒 ' . $title;
                }
                
                $events[] = [
                    'id' => 'session_' . $session['id'],
                    'title' => $title,
                    'start' => $session['fecha_inicio'],
                    'end' => $session['fecha_fin'],
                    'backgroundColor' => $this->getSessionColorByStatus($session['estado']),
                    'borderColor' => $this->getSessionColorByStatus($session['estado']),
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'type' => 'session',
                        'id' => $session['id'],
                        'booking_id' => $session['booking_id'],
                        'status' => $session['estado'],
                        'booking_status' => $session['booking_estado'],
                        'status_text' => $this->getEstadoTexto($session['estado']),
                        'otec' => $otecNombre,
                        'curso' => $cursoNombre,
                        'modalidad' => $modalidad === 'online' ? 'Online' : 'Presencial',
                        'duracion' => $duracionHoras,
                        'valor' => $valorAcordado,
                        'notas' => $notas,
                        'created_by' => $createdBy,
                        'es_mi_otec' => $esMiOtec,
                        'numero_sesion' => $sesionNumero,
                        'fecha_inicio' => $session['fecha_inicio'],
                        'fecha_fin' => $session['fecha_fin']
                    ]
                ];
            }
            
            foreach ($bookings as $booking) {
                $hasSessions = $this->bookingModel->getSessions($booking['id']);
                if (!empty($hasSessions)) continue;
                
                $cursoNombre = $booking['curso_nombre'] ?? $booking['nombre'] ?? 'Curso sin nombre';
                $otecNombre = $booking['otec_nombre'] ?? '';
                $notas = $booking['notas'] ?? '';
                $createdBy = $booking['created_by_nombre'] ?? $booking['created_by'] ?? '';
                $modalidad = $booking['modalidad'] ?? $booking['curso_modalidad'] ?? 'presencial';
                $duracionHoras = $booking['duracion_horas'] ?? 0;
                $valorAcordado = $booking['valor_acordado'] ?? 0;
                $esMiOtec = ($booking['otec_id'] == $otecId);
                
                $title = $cursoNombre;
                
                if ($userRol === 'ejecutivo' && $facilitadorId && !$esMiOtec) {
                    $title = '🔒 ' . $title;
                }
                
                $events[] = [
                    'id' => 'booking_' . $booking['id'],
                    'title' => $title,
                    'start' => $booking['fecha_inicio'],
                    'end' => $booking['fecha_fin'],
                    'backgroundColor' => $this->getEventColorByStatus($booking['estado']),
                    'borderColor' => $this->getEventColorByStatus($booking['estado']),
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'type' => 'booking',
                        'id' => $booking['id'],
                        'status' => $booking['estado'],
                        'status_text' => $this->getEstadoTexto($booking['estado']),
                        'otec' => $otecNombre,
                        'curso' => $cursoNombre,
                        'modalidad' => $modalidad === 'online' ? 'Online' : 'Presencial',
                        'duracion' => $duracionHoras,
                        'valor' => $valorAcordado,
                        'notas' => $notas,
                        'created_by' => $createdBy,
                        'es_mi_otec' => $esMiOtec
                    ]
                ];
            }
            
            $cleanEvents = $this->forceUtf8($events);
            echo json_encode($cleanEvents, JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            error_log("Error en getEvents: " . $e->getMessage());
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }
    
    public function getReservasByStatus() {
        header('Content-Type: application/json; charset=utf-8');
        
        if (!Auth::isLoggedIn()) {
            echo json_encode(['error' => 'No autorizado']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $estado = $_POST['estado'] ?? '';
        $estadosValidos = ['pendiente', 'aprobada', 'rechazada', 'anulada'];
        if (!in_array($estado, $estadosValidos)) {
            echo json_encode(['error' => 'Estado no válido']);
            return;
        }
        
        $user = Auth::user();
        $userRol = $user['rol'] ?? $_SESSION['user_rol'] ?? null;
        $otecId = $user['otec_id'] ?? $_SESSION['user_otec_id'] ?? null;
        $userId = $user['id'] ?? $_SESSION['user_id'] ?? null;
        
        if ($userRol === 'facilitador') {
            $reservas = $this->bookingModel->getReservasByStatusWithDetails($estado, null, $userId);
        } else {
            $reservas = $this->bookingModel->getReservasByStatusWithDetails($estado, $otecId, null);
        }
        
        echo json_encode(['success' => true, 'estado' => $estado, 'reservas' => $this->forceUtf8($reservas)]);
    }
    
    public function updateReservaStatus() {
        header('Content-Type: application/json; charset=utf-8');
        
        if (!Auth::isLoggedIn()) {
            echo json_encode(['error' => 'No autorizado']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $reservaId = $_POST['reserva_id'] ?? 0;
        $nuevoEstado = $_POST['estado'] ?? '';
        
        if (!$reservaId || !$nuevoEstado) {
            echo json_encode(['error' => 'Datos incompletos']);
            return;
        }
        
        if (!Auth::isFacilitador()) {
            echo json_encode(['error' => 'No autorizado. Solo facilitadores pueden cambiar estados.']);
            return;
        }
        
        $bookingModel = new Booking();
        $reservaActual = $bookingModel->find($reservaId);
        
        if (!$reservaActual) {
            echo json_encode(['error' => 'Reserva no encontrada']);
            return;
        }
        
        // Actualizar estado
        $result = $bookingModel->updateReservaStatus($reservaId, $nuevoEstado);
        
        if ($result) {
            // Si se rechaza, eliminar lógicamente las sesiones
            if ($nuevoEstado === 'rechazada') {
                $bookingModel->eliminarSesionesByBookingId($reservaId, 'Reserva rechazada por facilitador', false);
            }
            
            // Si se anula (después de aprobada), eliminar solo sesiones futuras
            if ($nuevoEstado === 'anulada' && $reservaActual['estado'] === 'aprobada') {
                $bookingModel->eliminarSesionesByBookingId($reservaId, 'Reserva anulada después de aprobada', true);
            }
            
            echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
        } else {
            echo json_encode(['error' => 'Error al actualizar el estado']);
        }
    }
    
    private function getNumeroSesion($bookingId, $fechaInicio) {
        $sessions = $this->bookingModel->getSessions($bookingId);
        $contador = 1;
        foreach ($sessions as $session) {
            if ($session['fecha_inicio'] == $fechaInicio) return $contador;
            $contador++;
        }
        return '?';
    }
    
    private function getSessionColorByStatus($status) {
        $colors = [
            'pendiente' => '#f39c12',
            'realizada' => '#2ecc71',
            'suspendida' => '#e67e22',
            'eliminada' => '#95a5a6'
        ];
        return $colors[$status] ?? '#3498db';
    }
    
    private function getEventColorByStatus($status) {
        $colors = [
            'pendiente' => '#f39c12',
            'aprobada' => '#2ecc71',
            'rechazada' => '#e74c3c',
            'anulada' => '#95a5a6'
        ];
        return $colors[$status] ?? '#3498db';
    }
    
    private function getEstadoTexto($status) {
        $textos = [
            'pendiente' => 'Pendiente',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
            'anulada' => 'Anulada',
            'realizada' => 'Realizada',
            'suspendida' => 'Suspendida',
            'eliminada' => 'Eliminada'
        ];
        return $textos[$status] ?? $status;
    }
}
?>