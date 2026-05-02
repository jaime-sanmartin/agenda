<?php
// controllers/ReportController.php
require_once 'core/Controller.php';
require_once 'helpers/Auth.php';
require_once 'models/Booking.php';
require_once 'models/Course.php';
require_once 'models/Otec.php';
require_once 'models/User.php';

class ReportController extends Controller {
    
    public function __construct() {
        Auth::checkLogin();
    }
    
    public function index() {
        $this->dashboard();
    }
    
    public function dashboard() {
        $user = Auth::user();
        $userRol = $user['rol'] ?? $_SESSION['user_rol'] ?? null;
        $userId = $user['id'] ?? $_SESSION['user_id'] ?? null;
        
        $bookingModel = new Booking();
        $courseModel = new Course();
        $otecModel = new Otec();
        
        // =====================================================
        // 1. ESTADÍSTICAS GENERALES
        // =====================================================
        
        if ($userRol === 'facilitador') {
            // Estadísticas personales del facilitador
            $totalCursos = $courseModel->countByFacilitador($userId);
            $totalOtec = $otecModel->countByFacilitador($userId);
            $totalReservas = $bookingModel->countByFacilitadorCourses($userId);
            $ingresosTotales = $this->getIngresosByFacilitador($userId);
            $horasTotales = $this->getHorasByFacilitador($userId);
            $rendimientoPromedio = $this->getRendimientoByFacilitador($userId);
            
            // Reservas por estado
            $reservasPorEstado = [
                'pendiente' => $bookingModel->countByFacilitadorCourses($userId, 'pendiente'),
                'aprobada' => $bookingModel->countByFacilitadorCourses($userId, 'aprobada'),
                'confirmada' => $bookingModel->countByFacilitadorCourses($userId, 'confirmada'),
                'rechazada' => $bookingModel->countByFacilitadorCourses($userId, 'rechazada'),
                'finalizada' => $bookingModel->countByFacilitadorCourses($userId, 'finalizada')
            ];
            
            // Cursos más populares
            $cursosPopulares = $courseModel->getPopularByFacilitador($userId, 5);
            
            // Ingresos por mes (últimos 12 meses)
            $ingresosPorMes = $this->getIngresosPorMesByFacilitador($userId);
            
            // Evolución de reservas
            $evolucionReservas = $bookingModel->getStatsByPeriodForFacilitador($userId, 'month');
            
        } else {
            // Estadísticas globales (para ejecutivos y admin)
            $totalCursos = $courseModel->count();
            $totalOtec = $otecModel->count();
            $totalReservas = $bookingModel->count();
            $ingresosTotales = $this->getIngresosTotales();
            $horasTotales = $this->getHorasTotales();
            $rendimientoPromedio = $this->getRendimientoGlobal();
            
            $reservasPorEstado = [
                'pendiente' => $bookingModel->count(['estado' => 'pendiente']),
                'aprobada' => $bookingModel->count(['estado' => 'aprobada']),
                'confirmada' => $bookingModel->count(['estado' => 'confirmada']),
                'rechazada' => $bookingModel->count(['estado' => 'rechazada']),
                'finalizada' => $bookingModel->count(['estado' => 'finalizada'])
            ];
            
            $cursosPopulares = $courseModel->getPopularCourses(5);
            $ingresosPorMes = $this->getIngresosPorMes();
            $evolucionReservas = $bookingModel->getStatsByPeriod('month');
        }
        
        // Datos para gráficos
        $data = [
            'title' => 'Reportes y Estadísticas',
            'user_rol' => $userRol,
            'total_cursos' => $totalCursos,
            'total_otec' => $totalOtec,
            'total_reservas' => $totalReservas,
            'ingresos_totales' => $ingresosTotales,
            'horas_totales' => $horasTotales,
            'rendimiento_promedio' => $rendimientoPromedio,
            'reservas_por_estado' => $reservasPorEstado,
            'cursos_populares' => $cursosPopulares,
            'ingresos_por_mes' => $ingresosPorMes,
            'evolucion_reservas' => $evolucionReservas
        ];
        
        $this->view('reports/dashboard', $data);
    }
    
    // =====================================================
    // MÉTODOS AUXILIARES PARA CÁLCULOS
    // =====================================================
    
    private function getIngresosTotales() {
        $query = "SELECT SUM(valor_acordado) as total FROM bookings 
                  WHERE estado IN ('confirmada', 'aprobada', 'finalizada')";
        $stmt = (new Database())->getConnection()->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    private function getIngresosByFacilitador($facilitadorId) {
        $query = "SELECT SUM(b.valor_acordado) as total 
                  FROM bookings b
                  INNER JOIN courses c ON b.curso_id = c.id
                  WHERE c.created_by = :facilitador_id
                  AND b.estado IN ('confirmada', 'aprobada', 'finalizada')";
        $stmt = (new Database())->getConnection()->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    private function getHorasTotales() {
        $query = "SELECT SUM(c.duracion_horas) as total 
                  FROM bookings b
                  INNER JOIN courses c ON b.curso_id = c.id
                  WHERE b.estado IN ('confirmada', 'aprobada', 'finalizada')";
        $stmt = (new Database())->getConnection()->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    private function getHorasByFacilitador($facilitadorId) {
        $query = "SELECT SUM(c.duracion_horas) as total 
                  FROM bookings b
                  INNER JOIN courses c ON b.curso_id = c.id
                  WHERE c.created_by = :facilitador_id
                  AND b.estado IN ('confirmada', 'aprobada', 'finalizada')";
        $stmt = (new Database())->getConnection()->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    private function getRendimientoGlobal() {
        $query = "SELECT 
                    COUNT(*) as total_reservas,
                    SUM(c.duracion_horas) as total_horas,
                    SUM(b.valor_acordado) as total_ingresos
                  FROM bookings b
                  INNER JOIN courses c ON b.curso_id = c.id
                  WHERE b.estado IN ('confirmada', 'aprobada', 'finalizada')";
        $stmt = (new Database())->getConnection()->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['total_horas'] > 0) {
            return round($result['total_ingresos'] / $result['total_horas'], 0);
        }
        return 0;
    }
    
    private function getRendimientoByFacilitador($facilitadorId) {
        $query = "SELECT 
                    COUNT(*) as total_reservas,
                    SUM(c.duracion_horas) as total_horas,
                    SUM(b.valor_acordado) as total_ingresos
                  FROM bookings b
                  INNER JOIN courses c ON b.curso_id = c.id
                  WHERE c.created_by = :facilitador_id
                  AND b.estado IN ('confirmada', 'aprobada', 'finalizada')";
        $stmt = (new Database())->getConnection()->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['total_horas'] > 0) {
            return round($result['total_ingresos'] / $result['total_horas'], 0);
        }
        return 0;
    }
    
    private function getIngresosPorMes() {
        $query = "SELECT 
                    DATE_FORMAT(fecha_inicio, '%Y-%m') as mes,
                    SUM(valor_acordado) as total
                  FROM bookings
                  WHERE fecha_inicio >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                  AND estado IN ('confirmada', 'aprobada', 'finalizada')
                  GROUP BY DATE_FORMAT(fecha_inicio, '%Y-%m')
                  ORDER BY mes ASC";
        $stmt = (new Database())->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getIngresosPorMesByFacilitador($facilitadorId) {
        $query = "SELECT 
                    DATE_FORMAT(b.fecha_inicio, '%Y-%m') as mes,
                    SUM(b.valor_acordado) as total
                  FROM bookings b
                  INNER JOIN courses c ON b.curso_id = c.id
                  WHERE c.created_by = :facilitador_id
                  AND b.fecha_inicio >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                  AND b.estado IN ('confirmada', 'aprobada', 'finalizada')
                  GROUP BY DATE_FORMAT(b.fecha_inicio, '%Y-%m')
                  ORDER BY mes ASC";
        $stmt = (new Database())->getConnection()->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>