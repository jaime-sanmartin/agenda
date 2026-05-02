<?php
// controllers/ApiController.php
require_once 'core/Controller.php';
require_once 'helpers/Auth.php';
require_once 'models/Course.php';
require_once 'models/Booking.php';
require_once 'models/Availability.php';

class ApiController extends Controller {
    
    // Endpoint público: Obtener cursos disponibles
    public function courses() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        $courseModel = new Course();
        $courses = $courseModel->getPublicCourses();
        
        // Remover información sensible
        foreach ($courses as &$course) {
            unset($course['created_at']);
            unset($course['updated_at']);
        }
        
        $this->json([
            'success' => true,
            'data' => $courses
        ]);
    }
    
    // Endpoint: Obtener disponibilidad (requiere API Key)
    public function availability() {
        header('Content-Type: application/json');
        
        $this->validateApiKey();
        
        $date = $this->getQuery('date', date('Y-m-d'));
        $availabilityModel = new Availability();
        $slots = $availabilityModel->getAvailableSlots($date);
        
        $this->json([
            'success' => true,
            'date' => $date,
            'slots' => $slots
        ]);
    }
    
    // Endpoint: Crear reserva (requiere API Key)
    public function createBooking() {
        header('Content-Type: application/json');
        
        $this->validateApiKey();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $data = [
            'otec_id' => $input['otec_id'] ?? null,
            'curso_id' => $input['curso_id'] ?? null,
            'fecha_inicio' => $input['fecha_inicio'] ?? null,
            'fecha_fin' => $input['fecha_fin'] ?? null,
            'valor_acordado' => $input['valor_acordado'] ?? null,
            'notas' => $input['notas'] ?? null,
            'estado' => 'pendiente',
            'created_by' => 1 // Usuario facilitador por defecto para API
        ];
        
        // Validaciones
        $errors = [];
        if (empty($data['otec_id'])) $errors[] = 'otec_id es requerido';
        if (empty($data['curso_id'])) $errors[] = 'curso_id es requerido';
        if (empty($data['fecha_inicio'])) $errors[] = 'fecha_inicio es requerida';
        if (empty($data['fecha_fin'])) $errors[] = 'fecha_fin es requerida';
        
        if (!empty($errors)) {
            $this->json(['error' => $errors], 400);
            return;
        }
        
        $bookingModel = new Booking();
        
        // Verificar conflictos
        if ($bookingModel->checkConflict($data['fecha_inicio'], $data['fecha_fin'])) {
            $this->json(['error' => 'Conflicto de horario'], 409);
            return;
        }
        
        $id = $bookingModel->create($data);
        
        if ($id) {
            $this->json([
                'success' => true,
                'booking_id' => $id,
                'status' => 'pendiente'
            ], 201);
        } else {
            $this->json(['error' => 'Error al crear la reserva'], 500);
        }
    }
    
    private function validateApiKey() {
        $headers = getallheaders();
        $apiKey = $headers['X-API-Key'] ?? null;
        
        // Configurar API Key en configuración
        $validKey = 'tu_api_key_secreta_aqui';
        
        if (!$apiKey || $apiKey !== $validKey) {
            $this->json(['error' => 'API Key inválida'], 401);
            exit;
        }
    }
}
?>