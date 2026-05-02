<?php
// controllers/UserController.php
require_once 'core/Controller.php';
require_once 'helpers/Auth.php';
require_once 'models/User.php';
require_once 'models/Otec.php';
require_once 'models/Booking.php';
require_once 'models/ActivityLog.php';
require_once 'helpers/Email.php';

class UserController extends Controller {
    
    public function __construct() {
        Auth::checkLogin();
    }
    
    public function index() {
         Auth::checkLogin();
         
        $userModel = new User();
        
        if (Auth::isAdministrador()) {
            // Administrador: ver TODOS los usuarios
            $users = $userModel->getAllUsersAdmin();
        } elseif (Auth::isFacilitador()) {
            // Facilitador: ver SOLO los usuarios que creó
            $users = $userModel->getEjecutivosByFacilitador($_SESSION['user_id']);
        } else {
            // Ejecutivo: redirigir (no debería ver usuarios)
            $this->redirect(BASE_URL . '/dashboard/ejecutivo?error=unauthorized');
            return;
        }
        
        $this->view('users/index', [
            'users' => $users, 
            'title' => 'Gestión de Usuarios'
        ]);
    }

    // =====================================================
    // AJAX: Verificar si un email ya existe y devolver datos
    // =====================================================
    public function verificarEmail() {
        if (!$this->isPost()) {
            $this->json(['error' => 'Método no permitido'], 405);
            return;
        }
        
        $email = $this->getPost('email');
        if (empty($email)) {
            $this->json(['exists' => false]);
            return;
        }
        
        $userModel = new User();
        $user = $userModel->findByEmail($email);
        
        if ($user && $user['rol'] === 'ejecutivo') {
            // Verificar si el ejecutivo ya está asociado a la OTEC que se seleccionará
            $otecId = $this->getPost('otec_id');
            $yaAsociado = ($otecId && $user['otec_id'] == $otecId);
            $this->json([
                'exists' => true,
                'data' => [
                    'id' => $user['id'],
                    'nombre' => $user['nombre'],
                    'telefono' => $user['telefono'],
                    'otec_id' => $user['otec_id']
                ],
                'ya_asociado' => $yaAsociado
            ]);
        } else {
            $this->json(['exists' => false]);
        }
    }
    
    // =====================================================
    // CREATE: Crear nuevo ejecutivo o asociar existente
    // =====================================================
    public function create() {
        $otecModel = new Otec();
        $facilitadorId = $_SESSION['user_id'];
        
        // Obtener OTEC del facilitador (solo las que él administra)
        $otecs = $otecModel->getOtecForFacilitador($facilitadorId);
        
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $modo = $this->getPost('modo'); // 'crear' o 'asociar'
            $email = trim($this->getPost('email'));
            $otecId = $this->getPost('otec_id');
            $nombre = trim($this->getPost('nombre'));
            $telefono = trim($this->getPost('telefono'));
            
            $userModel = new User();
            $emailHelper = new Email();
            
            if ($modo === 'asociar') {
                // ASOCIAR EJECUTIVO EXISTENTE
                $ejecutivoId = $this->getPost('user_id');
                if (!$ejecutivoId) {
                    $this->view('users/create', ['error' => 'No se pudo identificar el ejecutivo.', 'otecs' => $otecs]);
                    return;
                }
                
                // Actualizar OTEC si es diferente
                $ejecutivo = $userModel->find($ejecutivoId);
                if ($ejecutivo['otec_id'] != $otecId) {
                    $userModel->update($ejecutivoId, ['otec_id' => $otecId]);
                }
                
                // Enviar correo notificando asociación
                $subject = "Has sido asociado a una nueva OTEC - AgendaPro";
                $body = "<h2>Hola {$ejecutivo['nombre']}</h2>
                         <p>El facilitador {$_SESSION['user_nombre']} te ha asociado a la OTEC <strong>" . $this->getNombreOtec($otecId) . "</strong>.</p>
                         <p>Ahora podrás gestionar capacitaciones con este facilitador.</p>
                         <p>Si tienes dudas, contacta a tu administrador.</p>";
                $emailHelper->send($email, $subject, $body);
                
                ActivityLog::log($facilitadorId, 'asociar_ejecutivo', 'users', $ejecutivoId, "Ejecutivo asociado a OTEC ID: $otecId");
                $this->redirect('/agendapro/users?success=associated');
                return;
            }
            
            // MODO CREAR NUEVO EJECUTIVO
            // Generar contraseña aleatoria
            $password = $this->generarPassword();
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            $data = [
                'nombre' => $nombre,
                'email' => $email,
                'telefono' => $telefono,
                'rol' => 'ejecutivo',
                'otec_id' => $otecId,
                'password' => $password,
                'activo' => 1,
                'created_by' => $facilitadorId
            ];
            
            // Validar email único
            if (!$userModel->validateEmail($email)) {
                $this->view('users/create', ['error' => 'El email ya está registrado. Use la opción de asociar.', 'otecs' => $otecs, 'data' => $data]);
                return;
            }
            
            $id = $userModel->createUser($data);
            if ($id) {
                // Enviar correo de bienvenida con credenciales
                $subject = "Bienvenido a AgendaPro - Tu cuenta ha sido creada";
                $body = "<h2>Hola {$nombre}</h2>
                         <p>El facilitador {$_SESSION['user_nombre']} te ha dado de alta en el sistema AgendaPro.</p>
                         <p>Tus credenciales de acceso son:</p>
                         <ul>
                             <li><strong>Email:</strong> {$email}</li>
                             <li><strong>Contraseña temporal:</strong> {$password}</li>
                         </ul>
                         <p>Por favor, cambia tu contraseña al iniciar sesión.</p>
                         <a href='" . BASE_URL . "/auth/login'>Iniciar Sesión</a>";
                $emailHelper->send($email, $subject, $body);
                
                ActivityLog::log($facilitadorId, 'create_user', 'users', $id, "Ejecutivo creado: {$nombre}");
                $this->redirect('/agendapro/users?success=created');
            } else {
                $this->view('users/create', ['error' => 'Error al crear el ejecutivo.', 'otecs' => $otecs, 'data' => $data]);
            }
        } else {
            $this->view('users/create', ['otecs' => $otecs, 'title' => 'Nuevo Ejecutivo / Asociar Ejecutivo']);
        }
    }
    
    public function edit($id) {
        $userModel = new User();
        $otecModel = new Otec();
        
        $user = $userModel->find($id);
        $otecs = $otecModel->getActiveOtec();
        
        if (!$user) {
            $this->redirect('/agendapro/users?error=notfound');
            return;
        }
        
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $data = [
                'rut' => $this->getPost('rut'),
                'nombre' => $this->getPost('nombre'),
                'email' => $this->getPost('email'),
                'telefono' => $this->getPost('telefono'),
                'rol' => $this->getPost('rol'),
                'otec_id' => $this->getPost('otec_id') ?: null,
                'activo' => $this->getPost('activo', 1)
            ];
            
            $password = $this->getPost('password');
            if (!empty($password)) {
                $data['password'] = $password;
            }
            
            // Validar email único
            if ($data['email'] !== $user['email'] && !$userModel->validateEmail($data['email'], $id)) {
                $this->view('users/edit', ['error' => 'El email ya está registrado.', 'user' => $user, 'otecs' => $otecs, 'data' => $data]);
                return;
            }
            
            // Validar RUT único
            /*
            if ($data['rut'] !== $user['rut'] && $data['rut'] && !$userModel->validateRut($data['rut'], $id)) {
                $this->view('users/edit', ['error' => 'El RUT ya está registrado.', 'user' => $user, 'otecs' => $otecs, 'data' => $data]);
                return;
            }
            */
            
            if ($userModel->updateUser($id, $data)) {
                ActivityLog::log($_SESSION['user_id'], 'update_user', 'users', $id, "Usuario actualizado: {$data['nombre']}");
                $this->redirect('/agendapro/users?success=updated');
            } else {
                $this->view('users/edit', ['error' => 'Error al actualizar el usuario.', 'user' => $user, 'otecs' => $otecs]);
            }
        } else {
            $this->view('users/edit', ['user' => $user, 'otecs' => $otecs, 'title' => 'Editar Usuario']);
        }
    }
    
    public function details($id) {
        $userModel = new User();
        $bookingModel = new Booking();
        
        $user = $userModel->find($id);
        
        if (!$user) {
            $this->redirect('/agendapro/users?error=notfound');
            return;
        }
        
        // Obtener el nombre de la OTEC si es ejecutivo
        if ($user['rol'] === 'ejecutivo' && $user['otec_id']) {
            $otecModel = new Otec();
            $otec = $otecModel->find($user['otec_id']);
            $user['otec_nombre'] = $otec['nombre'] ?? 'No asignada';
        }
        
        // Obtener reservas del usuario si es ejecutivo
        $reservas = [];
        if ($user['rol'] === 'ejecutivo') {
            $reservas = $bookingModel->getAllWithDetails(['created_by' => $id]);
        }
        
        $this->view('users/view', [
            'user' => $user,
            'reservas' => $reservas,
            'title' => 'Detalles de Usuario'
        ]);
    }
    
    public function delete($id) {
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $userModel = new User();
            
            // No permitir eliminar el propio usuario
            if ($id == $_SESSION['user_id']) {
                $this->redirect('/agendapro/users?error=cannot_delete_self');
                return;
            }
            
            if ($userModel->delete($id)) {
                ActivityLog::log($_SESSION['user_id'], 'delete_user', 'users', $id, "Usuario eliminado");
                $this->redirect('/agendapro/users?success=deleted');
            } else {
                $this->redirect('/agendapro/users?error=delete_failed');
            }
        }
    }
    
    public function toggleStatus($id) {
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $userModel = new User();
            $user = $userModel->find($id);
            
            if ($user && $id != $_SESSION['user_id']) {
                $newStatus = $user['activo'] == 1 ? 0 : 1;
                $userModel->update($id, ['activo' => $newStatus]);
                
                ActivityLog::log($_SESSION['user_id'], 'toggle_user_status', 'users', $id, "Estado cambiado a: " . ($newStatus ? 'activo' : 'inactivo'));
                $this->redirect('/agendapro/users?success=status_changed');
            } else {
                $this->redirect('/agendapro/users?error=cannot_change_self');
            }
        }
    }

    private function generarPassword($longitud = 10) {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%';
        return substr(str_shuffle($caracteres), 0, $longitud);
    }
    
    private function getNombreOtec($otecId) {
        $otecModel = new Otec();
        $otec = $otecModel->find($otecId);
        return $otec ? $otec['nombre'] : 'Desconocida';
    }
    
    
}
?>