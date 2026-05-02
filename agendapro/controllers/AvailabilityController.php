<?php
// controllers/AvailabilityController.php
require_once 'core/Controller.php';
require_once 'helpers/Auth.php';
require_once 'models/Availability.php';
require_once 'models/ActivityLog.php';

class AvailabilityController extends Controller {
    
    public function __construct() {
        Auth::checkRole('facilitador');
    }
    
    public function index() {
        $availabilityModel = new Availability();
        $slots = $availabilityModel->all('fecha_inicio DESC');
        
        $this->view('availability/index', [
            'slots' => $slots,
            'title' => 'Gestión de Disponibilidad'
        ]);
    }
    
    public function create() {
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $fecha_inicio = $this->getPost('fecha_inicio');
            $fecha_fin = $this->getPost('fecha_fin');
            $estado = $this->getPost('estado');
            $motivo = $this->getPost('motivo');
            
            $data = [
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'estado' => $estado,
                'motivo' => $motivo
            ];
            
            $availabilityModel = new Availability();
            $id = $availabilityModel->create($data);
            
            if ($id) {
                ActivityLog::log($_SESSION['user_id'], 'create_availability', 'availability', $id, "Bloque de tiempo creado");
                $this->redirect('/agendapro/availability?success=created');
            } else {
                $this->view('availability/create', ['error' => 'Error al crear el bloque', 'title' => 'Nuevo Bloque de Tiempo']);
            }
        } else {
            $this->view('availability/create', ['title' => 'Nuevo Bloque de Tiempo']);
        }
    }
    
    public function edit($id) {
        $availabilityModel = new Availability();
        $slot = $availabilityModel->find($id);
        
        if (!$slot) {
            $this->redirect('/agendapro/availability?error=notfound');
        }
        
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $data = [
                'fecha_inicio' => $this->getPost('fecha_inicio'),
                'fecha_fin' => $this->getPost('fecha_fin'),
                'estado' => $this->getPost('estado'),
                'motivo' => $this->getPost('motivo')
            ];
            
            if ($availabilityModel->update($id, $data)) {
                ActivityLog::log($_SESSION['user_id'], 'update_availability', 'availability', $id, "Bloque de tiempo actualizado");
                $this->redirect('/agendapro/availability?success=updated');
            } else {
                $this->view('availability/edit', ['slot' => $slot, 'error' => 'Error al actualizar', 'title' => 'Editar Bloque']);
            }
        } else {
            $this->view('availability/edit', ['slot' => $slot, 'title' => 'Editar Bloque de Tiempo']);
        }
    }
    
    public function delete($id) {
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $availabilityModel = new Availability();
            
            if ($availabilityModel->delete($id)) {
                ActivityLog::log($_SESSION['user_id'], 'delete_availability', 'availability', $id, "Bloque de tiempo eliminado");
                $this->redirect('/agendapro/availability?success=deleted');
            } else {
                $this->redirect('/agendapro/availability?error=delete_failed');
            }
        }
    }
    
    public function weekly() {
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $startDate = $this->getPost('start_date');
            $endDate = $this->getPost('end_date');
            $days = $this->getPost('days', []);
            $startTime = $this->getPost('start_time');
            $endTime = $this->getPost('end_time');
            
            $availabilityModel = new Availability();
            $created = $availabilityModel->createWeeklyAvailability($startDate, $endDate, $days, $startTime, $endTime);
            
            ActivityLog::log($_SESSION['user_id'], 'create_weekly_availability', 'availability', 0, "Disponibilidad semanal creada: $created bloques");
            
            $this->view('availability/weekly', [
                'success' => "Se crearon $created bloques de disponibilidad",
                'title' => 'Crear Disponibilidad Semanal'
            ]);
        } else {
            $this->view('availability/weekly', ['title' => 'Crear Disponibilidad Semanal']);
        }
    }
}
?>