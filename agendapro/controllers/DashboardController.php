<?php
// controllers/DashboardController.php
require_once 'core/Controller.php';
require_once 'helpers/Auth.php';
require_once 'models/User.php';
require_once 'models/Otec.php';
require_once 'models/Course.php';
require_once 'models/Booking.php';
require_once 'models/Notification.php';

class DashboardController extends Controller {
    
    /**
     * Dashboard del Administrador
     */
    public function admin() {
        Auth::checkRoleAdmin();
        
        $userModel = new User();
        $courseModel = new Course();
        $otecModel = new Otec();
        $bookingModel = new Booking();
        
        // Estadísticas globales
        $total_usuarios = $userModel->count();
        $total_facilitadores = $userModel->countByRol('facilitador');
        $total_ejecutivos = $userModel->countByRol('ejecutivo');
        $total_administradores = $userModel->countByRol('administrador');
        $total_cursos = $courseModel->count();
        $total_presenciales = $courseModel->countByModalidad('presencial');
        $total_online = $courseModel->countByModalidad('online');
        $total_hibridos = $courseModel->countByModalidad('hibrido');
        $total_otec = $otecModel->count();
        $total_reservas = $bookingModel->count();
        
        // Reservas por estado
        $reservas_por_estado = [
            'pendiente' => $bookingModel->count(['estado' => 'pendiente']),
            'aprobada' => $bookingModel->count(['estado' => 'aprobada']),
            'rechazada' => $bookingModel->count(['estado' => 'rechazada']),
            'anulada' => $bookingModel->count(['estado' => 'anulada'])
        ];
        
        // Solicitudes pendientes
        $solicitudes_pendientes = $this->getSolicitudesPendientes();
        
        // Últimos usuarios registrados
        $ultimos_usuarios = $userModel->getAllUsersAdmin();
        $ultimos_usuarios = array_slice($ultimos_usuarios, 0, 5);
        
        // Últimas reservas
        $ultimas_reservas = $bookingModel->getAllBookingsAdmin();
        $ultimas_reservas = array_slice($ultimas_reservas, 0, 5);
        
        $data = [
            'title' => 'Dashboard - Administrador',
            'total_usuarios' => $total_usuarios,
            'total_facilitadores' => $total_facilitadores,
            'total_ejecutivos' => $total_ejecutivos,
            'total_administradores' => $total_administradores,
            'total_cursos' => $total_cursos,
            'total_presenciales' => $total_presenciales,
            'total_online' => $total_online,
            'total_hibridos' => $total_hibridos,
            'total_otec' => $total_otec,
            'total_reservas' => $total_reservas,
            'reservas_por_estado' => $reservas_por_estado,
            'solicitudes_pendientes' => $solicitudes_pendientes,
            'ultimos_usuarios' => $ultimos_usuarios,
            'ultimas_reservas' => $ultimas_reservas
        ];
        
        $this->view('dashboard/admin', $data);
    }
    
    /**
     * Obtener solicitudes de registro pendientes
     */
    private function getSolicitudesPendientes() {
        try {
            $db = (new Database())->getConnection();
            $query = "SELECT COUNT(*) as total FROM solicitudes_facilitador WHERE estado = 'pendiente'";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function facilitator() {
        Auth::checkRole('facilitador');
        
        $bookingModel = new Booking();
        $courseModel = new Course();
        $otecModel = new Otec();
        $notificationModel = new Notification();
        
        // Obtener usuario actual
        $user = Auth::user();
        $userId = $user['id'] ?? null;
        $userNombre = $user['nombre'] ?? $_SESSION['user_nombre'] ?? '';
        
        // =====================================================
        // ESTADÍSTICAS PERSONALES DEL FACILITADOR
        // =====================================================
        
        // Cursos creados por el facilitador
        $totalCursos = $courseModel->countByFacilitador($userId);
        
        // OTEC creadas por el facilitador
        $totalOtec = $otecModel->countAssociatedByFacilitador($userId);
        
        // Reservas de cursos del facilitador
        $totalReservas = $bookingModel->countByFacilitadorCourses($userId);
        $reservasPendientes = $bookingModel->countPendingByFacilitador($userId);
        $reservasConfirmadas = $bookingModel->countByFacilitadorCourses($userId, 'confirmada');
        $reservasFinalizadas = $bookingModel->countByFacilitadorCourses($userId, 'finalizada');
        $reservasRechazadas = $bookingModel->countByFacilitadorCourses($userId, 'rechazada');
        $reservasPropuesta = $bookingModel->countByFacilitadorCourses($userId, 'propuesta');
        
        // Horas totales agendadas de sus cursos
        $query = "SELECT SUM(c.duracion_horas) as total_horas 
                  FROM bookings b 
                  INNER JOIN courses c ON b.curso_id = c.id 
                  WHERE c.created_by = :facilitador_id 
                  AND b.estado IN ('confirmada', 'aprobada', 'finalizada')";
        $db = (new Database())->getConnection();
        $stmt = $db->prepare($query);
        $stmt->bindParam(':facilitador_id', $userId);
        $stmt->execute();
        $horasTotales = $stmt->fetch(PDO::FETCH_ASSOC)['total_horas'] ?? 0;
        
        // OTEC atendidas (con las que tiene reservas)
        //$otecTrabajadas = $bookingModel->getOtecByFacilitador($userId);
        $otecTrabajadas = $bookingModel->getOtecByFacilitador($userId);
        $totalOtecAtendidas = count($otecTrabajadas);
        
        // Cursos que imparte (con reservas)
        $cursosImpartidos = $bookingModel->getCoursesByFacilitador($userId);
        $totalCursosImpartidos = count($cursosImpartidos);
        
        // Reservas por mes (solo sus cursos)
        $reservasPorMes = $bookingModel->getStatsByPeriodForFacilitador($userId, 'month');
        
        // Cursos más populares (solo sus cursos)
        $cursosPopulares = $courseModel->getPopularByFacilitador($userId, 5);
        
        // Próximas reservas y reservas recientes
        $proximasReservas = $bookingModel->getUpcomingByFacilitador($userId, 10);
        $reservasRecientes = $bookingModel->getRecentByFacilitador($userId, 10);
        
        // Notificaciones
        $notificaciones = [];
        $unreadCount = 0;
        if ($userId) {
            $notificaciones = $notificationModel->getUnread($userId);
            $unreadCount = count($notificaciones);
        }
        
        $data = [
            'title' => 'Dashboard - Facilitador',
            'facilitador_nombre' => $userNombre,
            'total_cursos' => $totalCursos,
            'total_otec' => $totalOtec,
            'total_reservas' => $totalReservas,
            'reservas_pendientes' => $reservasPendientes,
            'reservas_confirmadas' => $reservasConfirmadas,
            'reservas_finalizadas' => $reservasFinalizadas,
            'reservas_rechazadas' => $reservasRechazadas,
            'reservas_propuesta' => $reservasPropuesta,
            'horas_totales' => $horasTotales,
            'total_otec_atendidas' => $totalOtecAtendidas,
            'total_cursos_impartidos' => $totalCursosImpartidos,
            'reservas_por_mes' => $reservasPorMes,
            'cursos_populares' => $cursosPopulares,
            'otec_trabajadas' => $otecTrabajadas,
            'cursos_impartidos' => $cursosImpartidos,
            'proximas_reservas' => $proximasReservas,
            'reservas_recientes' => $reservasRecientes,
            'notificaciones' => $notificaciones,
            'unread_count' => $unreadCount
        ];
        
        $this->view('dashboard/facilitator', $data);
    }
    
    public function executive() {
        Auth::checkRole('ejecutivo');
        
        $bookingModel = new Booking();
        $courseModel = new Course();
        $notificationModel = new Notification();
        
        // Obtener usuario actual
        $user = Auth::user();
        $userId = $user['id'] ?? null;
        $otecId = $user['otec_id'] ?? $_SESSION['user_otec_id'] ?? null;
        
        // Estadísticas del ejecutivo
        $misReservas = $bookingModel->count(['otec_id' => $otecId]);
        $reservasPendientes = $bookingModel->count(['otec_id' => $otecId, 'estado' => 'pendiente']);
        $reservasConfirmadas = $bookingModel->count(['otec_id' => $otecId, 'estado' => 'confirmada']);
        $reservasFinalizadas = $bookingModel->count(['otec_id' => $otecId, 'estado' => 'finalizada']);
        
        // Cursos públicos disponibles
        $cursosDisponibles = $courseModel->getPublicCourses();
        
        // Próximas capacitaciones
        $proximas = $bookingModel->getAllWithDetails([
            'otec_id' => $otecId,
            'fecha_desde' => date('Y-m-d')
        ]);
        $proximas = array_slice($proximas, 0, 5);
        
        // Notificaciones
        $notificaciones = [];
        $unreadCount = 0;
        if ($userId) {
            $notificaciones = $notificationModel->getUnread($userId);
            $unreadCount = count($notificaciones);
        }
        
        $data = [
            'title' => 'Dashboard - Ejecutivo OTEC',
            'mis_reservas' => $misReservas,
            'reservas_pendientes' => $reservasPendientes,
            'reservas_confirmadas' => $reservasConfirmadas,
            'reservas_finalizadas' => $reservasFinalizadas,
            'cursos_disponibles' => $cursosDisponibles,
            'proximas_capacitaciones' => $proximas,
            'notificaciones' => $notificaciones,
            'unread_count' => $unreadCount
        ];
        
        $this->view('dashboard/executive', $data);
    }
    
    /**
     * Redirigir al dashboard según el rol del usuario
     */
    public function goToDashboard() {
        Auth::checkLogin();
        
        $rol = $_SESSION['user_rol'] ?? null;
        
        if ($rol === 'facilitador') {
            $this->redirect(BASE_URL . '/dashboard/facilitador');
        } elseif ($rol === 'ejecutivo') {
            $this->redirect(BASE_URL . '/dashboard/ejecutivo');
        } elseif ($rol === 'administrador') {
            $this->redirect(BASE_URL . '/dashboard/admin');
        } else {
            $this->redirect(BASE_URL . '/auth/login');
        }
    }
}
?>