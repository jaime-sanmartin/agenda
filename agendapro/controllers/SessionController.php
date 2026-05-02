<?php
// controllers/SessionController.php
require_once 'core/Controller.php';
require_once 'helpers/Auth.php';
require_once 'models/Booking.php';

class SessionController extends Controller {
    private $bookingModel;

    public function __construct() {
        Auth::checkLogin();
        $this->bookingModel = new Booking();
    }

    /**
     * Suspender una sesión (POST)
     */
    public function suspender() {
        header('Content-Type: application/json');
        
        if (!Auth::isLoggedIn()) {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $sessionId = $input['session_id'] ?? 0;
        $motivo = $input['motivo'] ?? '';
        
        if (!$sessionId) {
            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
            return;
        }
        
        $user = Auth::user();
        $userRol = $user['rol'] ?? $_SESSION['user_rol'] ?? null;
        
        // Verificar permiso: facilitador o ejecutivo dueño pueden suspender
        $session = $this->bookingModel->getSessionById($sessionId);
        if (!$session) {
            echo json_encode(['success' => false, 'error' => 'Sesión no encontrada']);
            return;
        }
        
        $booking = $this->bookingModel->find($session['booking_id']);
        if (!$booking) {
            echo json_encode(['success' => false, 'error' => 'Reserva no encontrada']);
            return;
        }
        
        $esFacilitador = ($userRol === 'facilitador');
        $esEjecutivoDueno = ($userRol === 'ejecutivo' && $booking['created_by'] == $_SESSION['user_id']);
        
        if (!$esFacilitador && !$esEjecutivoDueno) {
            echo json_encode(['success' => false, 'error' => 'No autorizado para suspender esta sesión']);
            return;
        }
        
        // Solo se puede suspender si la reserva está aprobada
        if ($booking['estado'] !== 'aprobada') {
            echo json_encode(['success' => false, 'error' => 'La reserva debe estar aprobada para suspender sesiones']);
            return;
        }
        
        $result = $this->bookingModel->suspenderSesion($sessionId, $motivo);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Sesión suspendida correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al suspender la sesión']);
        }
    }
    
    /**
     * Reagendar una sesión (POST) - Solo ejecutivo dueño
     */
    public function reagendar() {
        header('Content-Type: application/json');
        
        if (!Auth::isLoggedIn()) {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $sessionId = $input['session_id'] ?? 0;
        $nuevaFechaInicio = $input['nueva_fecha_inicio'] ?? '';
        $nuevaFechaFin = $input['nueva_fecha_fin'] ?? '';
        $motivo = $input['motivo'] ?? '';
        
        if (!$sessionId || !$nuevaFechaInicio || !$nuevaFechaFin) {
            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
            return;
        }
        
        // Validar fechas
        if (strtotime($nuevaFechaFin) <= strtotime($nuevaFechaInicio)) {
            echo json_encode(['success' => false, 'error' => 'La fecha de término debe ser posterior a la fecha de inicio']);
            return;
        }
        
        $user = Auth::user();
        $userRol = $user['rol'] ?? $_SESSION['user_rol'] ?? null;
        
        // Solo ejecutivo dueño puede reagendar
        $session = $this->bookingModel->getSessionById($sessionId);
        if (!$session) {
            echo json_encode(['success' => false, 'error' => 'Sesión no encontrada']);
            return;
        }
        
        $booking = $this->bookingModel->find($session['booking_id']);
        if (!$booking) {
            echo json_encode(['success' => false, 'error' => 'Reserva no encontrada']);
            return;
        }
        
        $esEjecutivoDueno = ($userRol === 'ejecutivo' && $booking['created_by'] == $_SESSION['user_id']);
        
        if (!$esEjecutivoDueno) {
            echo json_encode(['success' => false, 'error' => 'No autorizado. Solo el ejecutivo que creó la reserva puede reagendar sesiones']);
            return;
        }
        
        // Solo se puede reagendar si la reserva está aprobada
        if ($booking['estado'] !== 'aprobada') {
            echo json_encode(['success' => false, 'error' => 'La reserva debe estar aprobada para reagendar sesiones']);
            return;
        }
        
        $result = $this->bookingModel->reagendarSesion($sessionId, $nuevaFechaInicio, $nuevaFechaFin, $motivo);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Sesión reagendada correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al reagendar la sesión']);
        }
    }
}
?>