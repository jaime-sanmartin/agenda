<?php
// controllers/OtecController.php
require_once 'core/Controller.php';
require_once 'helpers/Auth.php';
require_once 'models/Otec.php';
require_once 'models/User.php';
require_once 'models/ActivityLog.php';

class OtecController extends Controller {
    
    public function __construct() {
        Auth::checkLogin();
    }


    // =====================================================
    // AJAX: Verificar si un RUT ya existe y devolver datos
    // =====================================================
    public function verificarRut() {
        if (!$this->isPost()) {
            $this->json(['error' => 'Método no permitido'], 405);
            return;
        }
        
        $rut = $this->getPost('rut');
        if (empty($rut)) {
            $this->json(['exists' => false]);
            return;
        }
        
        $otecModel = new Otec();
        $otec = $otecModel->findByRut($rut);
        
        if ($otec) {
            // Verificar si el facilitador actual ya está asociado
            $yaAsociado = $otecModel->isFacilitadorAsociado($otec['id'], $_SESSION['user_id']);
            $this->json([
                'exists' => true,
                'data' => [
                    'id' => $otec['id'],
                    'nombre' => $otec['nombre'],
                    'direccion' => $otec['direccion'],
                    'contacto' => $otec['contacto'],
                    'telefono' => $otec['telefono'],
                    'email' => $otec['email']
                ],
                'ya_asociado' => $yaAsociado
            ]);
        } else {
            $this->json(['exists' => false]);
        }
    }
    
    // =====================================================
    // CREATE: Crear nueva OTEC o Asociar existente
    // =====================================================
    public function create() {
        $otecModel = new Otec();
        $facilitadorId = $_SESSION['user_id'];
        
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $modo = $this->getPost('modo'); // 'crear' o 'asociar'
            $rut = $this->getPost('rut');
            
            if ($modo === 'asociar') {
                // ASOCIAR OTEC EXISTENTE
                $otecId = $this->getPost('otec_id');
                if (!$otecId) {
                    $this->view('otec/create', ['error' => 'No se pudo identificar la OTEC a asociar.']);
                    return;
                }
                
                // Verificar si ya está asociado
                if ($otecModel->isFacilitadorAsociado($otecId, $facilitadorId)) {
                    $this->redirect('/agendapro/otec?warning=already_associated');
                    return;
                }
                
                if ($otecModel->asociarFacilitador($otecId, $facilitadorId)) {
                    ActivityLog::log($facilitadorId, 'asociar_otec', 'otec_facilitadores', $otecId, "Facilitador asociado a OTEC existente");
                    $this->redirect('/agendapro/otec?success=associated');
                } else {
                    $this->view('otec/create', ['error' => 'Error al asociar la OTEC.']);
                }
                return;
            }
            
            // MODO CREAR NUEVA OTEC
            $data = [
                'nombre' => $this->getPost('nombre'),
                'rut' => $rut,
                'direccion' => $this->getPost('direccion'),
                'contacto' => $this->getPost('contacto'),
                'telefono' => $this->getPost('telefono'),
                'email' => $this->getPost('email'),
                'activo' => $this->getPost('activo', 1),
                'created_by' => $facilitadorId
            ];
            
            // Validar RUT único
            if (!$otecModel->validateRut($data['rut'])) {
                $this->view('otec/create', ['error' => 'El RUT ya está registrado. Use la opción de asociar.', 'data' => $data]);
                return;
            }
            
            $id = $otecModel->create($data);
            if ($id) {
                // Asociar automáticamente al facilitador que la creó
                $otecModel->asociarFacilitador($id, $facilitadorId);
                ActivityLog::log($facilitadorId, 'create_otec', 'otec', $id, "OTEC creada: {$data['nombre']}");
                $this->redirect('/agendapro/otec?success=created');
            } else {
                $this->view('otec/create', ['error' => 'Error al crear la OTEC.', 'data' => $data]);
            }
        } else {
            // Mostrar formulario vacío
            $this->view('otec/create', ['title' => 'Nueva OTEC / Asociar OTEC']);
        }
    }

    
    public function index() {
        
        $otecModel = new Otec();
        
        if (Auth::isAdministrador()) {
            $otecs = $otecModel->getAllOtecAdmin();
            error_log("Admin - OTEC encontradas: " . count($otecs));
        } elseif (Auth::isFacilitador()) {
            $otecs = $otecModel->getOtecForFacilitador($_SESSION['user_id']);
            error_log("Facilitador - OTEC encontradas: " . count($otecs));
        } else {
            $this->redirect(BASE_URL . '/dashboard/ejecutivo?error=unauthorized');
            return;
        }
        
        $this->view('otec/index', [
            'otecs' => $otecs, 
            'title' => 'Gestión de OTEC'
        ]);
    }
    

    public function edit($id) {
        $otecModel = new Otec();
        $otec = $otecModel->find($id);
        
        if (!$otec) {
            $this->redirect('/agendapro/otec?error=notfound');
        }
        
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $data = [
                'nombre' => $this->getPost('nombre'),
                'rut' => $this->getPost('rut'),
                'direccion' => $this->getPost('direccion'),
                'contacto' => $this->getPost('contacto'),
                'telefono' => $this->getPost('telefono'),
                'email' => $this->getPost('email'),
                'activo' => $this->getPost('activo', 1)
            ];
            
            // Validar RUT único (excepto el actual)
            if ($data['rut'] !== $otec['rut'] && !$otecModel->validateRut($data['rut'])) {
                $this->view('otec/edit', ['error' => 'El RUT ya está registrado.', 'otec' => $otec, 'data' => $data]);
                return;
            }
            
            if ($otecModel->update($id, $data)) {
                ActivityLog::log($_SESSION['user_id'], 'update_otec', 'otec', $id, "OTEC actualizada: {$data['nombre']}");
                $this->redirect('/agendapro/otec?success=updated');
            } else {
                $this->view('otec/edit', ['error' => 'Error al actualizar la OTEC.', 'otec' => $otec]);
            }
        } else {
            $this->view('otec/edit', ['otec' => $otec, 'title' => 'Editar OTEC']);
        }
    }
    
    public function details($id) {
        $otecModel = new Otec();
        $userModel = new User();
        $bookingModel = new Booking();
        
        $otec = $otecModel->find($id);
        
        if (!$otec) {
            $this->redirect('/agendapro/otec?error=notfound');
        }
        
        $ejecutivos = $userModel->getByOtec($id);
        $capacitaciones = $bookingModel->getAllWithDetails(['otec_id' => $id]);
        
        $this->view('otec/view', [
            'otec' => $otec,
            'ejecutivos' => $ejecutivos,
            'capacitaciones' => $capacitaciones,
            'title' => 'Detalles OTEC'
        ]);
    }
    
    public function delete($id) {
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $otecModel = new Otec();
            $userModel = new User();
            
            // Verificar si tiene ejecutivos asociados
            $ejecutivos = $userModel->getByOtec($id);
            if (!empty($ejecutivos)) {
                $this->redirect('/agendapro/otec?error=has_users');
                return;
            }
            
            if ($otecModel->delete($id)) {
                ActivityLog::log($_SESSION['user_id'], 'delete_otec', 'otec', $id, "OTEC eliminada");
                $this->redirect('/agendapro/otec?success=deleted');
            } else {
                $this->redirect('/agendapro/otec?error=delete_failed');
            }
        }
    }
    
    public function toggleStatus($id) {
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $otecModel = new Otec();
            $otec = $otecModel->find($id);
            
            if ($otec) {
                $newStatus = $otec['activo'] == 1 ? 0 : 1;
                $otecModel->update($id, ['activo' => $newStatus]);
                
                ActivityLog::log($_SESSION['user_id'], 'toggle_otec_status', 'otec', $id, "Estado cambiado a: " . ($newStatus ? 'activo' : 'inactivo'));
                $this->redirect('/agendapro/otec?success=status_changed');
            }
        }
    }
}
?>