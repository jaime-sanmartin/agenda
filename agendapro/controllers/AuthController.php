<?php
// controllers/AuthController.php
require_once 'core/Controller.php';
require_once 'helpers/Auth.php';
require_once 'helpers/Security.php';
require_once 'models/User.php';
require_once 'models/ActivityLog.php';
require_once 'helpers/Email.php';

class AuthController extends Controller {
    
    public function login() {
        if ($this->isPost()) {
            $email = $this->getPost('email');
            $password = $this->getPost('password');
            
            $result = Auth::attempt($email, $password);
            
            if (isset($result['success']) && $result['success']) {
                if ($result['rol'] === 'facilitador' or $result['rol'] === 'administrador') {
                    $this->redirect('/agendapro/dashboard/facilitador');
                } else {
                    $this->redirect('/agendapro/dashboard/ejecutivo');
                }
            } else {
                $data = ['error' => $result['error']];
                $this->view('auth/login', $data);
            }
        } else {
            $this->view('auth/login');
        }
    }
    
    public function logout() {
        Auth::logout();
    }
    
    public function recover() {
        if ($this->isPost()) {
            $email = $this->getPost('email');
            $userModel = new User();
            $user = $userModel->findByEmail($email);
            
            if ($user) {
                $token = Security::generatePasswordResetToken();
                $userModel->setResetToken($email, $token);
                
                // Enviar email
                $emailHelper = new Email();
                $resetLink = "https://" . $_SERVER['HTTP_HOST'] . "/agendapro/auth/reset?token=" . $token;
                $subject = "Recuperación de contraseña - AgendaPro";
                $body = "<h2>Recuperación de contraseña</h2>
                         <p>Haga clic en el siguiente enlace para restablecer su contraseña:</p>
                         <p><a href='{$resetLink}'>{$resetLink}</a></p>
                         <p>Este enlace expirará en 1 hora.</p>";
                
                $emailHelper->send($email, $subject, $body);
                
                $data = ['success' => 'Se ha enviado un email con instrucciones para recuperar su contraseña.'];
                $this->view('auth/recover', $data);
            } else {
                $data = ['error' => 'No existe una cuenta con ese email.'];
                $this->view('auth/recover', $data);
            }
        } else {
            $this->view('auth/recover');
        }
    }
    
    public function reset() {
        $token = $this->getQuery('token');
        $userModel = new User();
        $user = $userModel->findByResetToken($token);
        
        if (!$user) {
            $this->view('auth/reset', ['error' => 'Token inválido o expirado.']);
            return;
        }
        
        if ($this->isPost()) {
            $password = $this->getPost('password');
            $confirm = $this->getPost('confirm_password');
            
            if ($password !== $confirm) {
                $this->view('auth/reset', ['error' => 'Las contraseñas no coinciden.', 'token' => $token]);
                return;
            }
            
            if (strlen($password) < 6) {
                $this->view('auth/reset', ['error' => 'La contraseña debe tener al menos 6 caracteres.', 'token' => $token]);
                return;
            }
            
            $userModel->updatePassword($user['id'], $password);
            $userModel->clearResetToken($user['id']);
            
            ActivityLog::log($user['id'], 'password_reset', 'users', $user['id'], 'Contraseña restablecida');
            
            $this->redirect('/agendapro/auth/login?reset=success');
        } else {
            $this->view('auth/reset', ['token' => $token]);
        }
    }
    

    // controllers/AuthController.php
    // SOLO REEMPLAZAR LOS MÉTODOS profile() Y changePassword()
    // El resto del archivo permanece igual

    public function profile() {
        Auth::checkLogin();
        $userModel = new User();
        $user = $userModel->find($_SESSION['user_id']);
        
        // Obtener nombre de OTEC si es ejecutivo
        if ($user['rol'] === 'ejecutivo' && $user['otec_id']) {
            $otecModel = new Otec();
            $otec = $otecModel->find($user['otec_id']);
            $user['otec_nombre'] = $otec['nombre'] ?? null;
        }
        
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $data = [
                'nombre' => $this->getPost('nombre'),
                'telefono' => $this->getPost('telefono')
            ];
            
            if ($userModel->update($_SESSION['user_id'], $data)) {
                // Actualizar sesión
                $_SESSION['user_nombre'] = $data['nombre'];
                
                ActivityLog::log($_SESSION['user_id'], 'profile_update', 'users', $_SESSION['user_id'], 'Perfil actualizado');
                
                $this->redirect('/agendapro/auth/profile?success=updated');
            } else {
                $error = 'Error al actualizar el perfil';
                $this->view('auth/profile', ['user' => $user, 'error' => $error, 'title' => 'Mi Perfil']);
            }
        } else {
            $success = null;
            if (isset($_GET['success']) && $_GET['success'] == 'updated') {
                $success = 'Perfil actualizado correctamente.';
            }
            $this->view('auth/profile', ['user' => $user, 'success' => $success, 'title' => 'Mi Perfil']);
        }
    }
    
    public function changePassword() {
        Auth::checkLogin();
        
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $currentPassword = $this->getPost('current_password');
            $newPassword = $this->getPost('new_password');
            $confirmPassword = $this->getPost('confirm_password');
            
            $userModel = new User();
            $user = $userModel->find($_SESSION['user_id']);
            
            // Validar contraseña actual
            if (!password_verify($currentPassword, $user['password'])) {
                $this->view('auth/change_password', ['error' => 'Contraseña actual incorrecta.', 'title' => 'Cambiar Contraseña']);
                return;
            }
            
            // Validar que las nuevas contraseñas coincidan
            if ($newPassword !== $confirmPassword) {
                $this->view('auth/change_password', ['error' => 'Las contraseñas nuevas no coinciden.', 'title' => 'Cambiar Contraseña']);
                return;
            }
            
            // Validar longitud mínima
            if (strlen($newPassword) < 6) {
                $this->view('auth/change_password', ['error' => 'La nueva contraseña debe tener al menos 6 caracteres.', 'title' => 'Cambiar Contraseña']);
                return;
            }
            
            // Actualizar contraseña
            if ($userModel->updatePassword($_SESSION['user_id'], $newPassword)) {
                ActivityLog::log($_SESSION['user_id'], 'password_change', 'users', $_SESSION['user_id'], 'Contraseña cambiada');
                
                $this->view('auth/change_password', ['success' => 'Contraseña actualizada correctamente.', 'title' => 'Cambiar Contraseña']);
            } else {
                $this->view('auth/change_password', ['error' => 'Error al actualizar la contraseña.', 'title' => 'Cambiar Contraseña']);
            }
        } else {
            $this->view('auth/change_password', ['title' => 'Cambiar Contraseña']);
        }
    }

    /**
     * Obtener conexión a la base de datos
     */
    private function getDbConnection() {
        try {
            require_once __DIR__ . '/../config/database.php';
            $database = new Database();
            return $database->getConnection();
        } catch (Exception $e) {
            error_log("Error de conexión DB: " . $e->getMessage());
            return null;
        }
    }

    
    /**
     * Mostrar formulario de solicitud de registro
     */
    public function solicitar_registro() {
        $this->view('auth/solicitar_registro', ['title' => 'Solicitar Registro']);
    }
    
    /**
     * Procesar la solicitud de registro
     */
    public function procesar_solicitud() {
        if ($this->isPost()) {
            Security::verifyCSRFToken($this->getPost('csrf_token'));
            
            $nombre = trim($this->getPost('nombre'));
            $email = trim($this->getPost('email'));
            $telefono = trim($this->getPost('telefono'));
            $rut = trim($this->getPost('rut'));
            $empresa = trim($this->getPost('empresa'));
            $mensaje = trim($this->getPost('mensaje'));
            
            // Validaciones
            $errors = [];
            if (empty($nombre)) $errors[] = 'El nombre es requerido';
            if (empty($email)) $errors[] = 'El email es requerido';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido';
            
            // Usar el modelo User para verificar existencia
            $userModel = new User();
            
            // Verificar si ya existe un usuario con este email
            $existingUser = $userModel->findByEmail($email);
            if ($existingUser) {
                $errors[] = 'Ya existe un usuario registrado con este email';
            }
            
            // Verificar solicitud pendiente usando conexión directa
            $db = $this->getDbConnection();
            
            if ($db) {
                $query = "SELECT id FROM solicitudes_facilitador WHERE email = :email AND estado = 'pendiente'";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $errors[] = 'Ya existe una solicitud pendiente para este email';
                }
            }
            
            if (empty($errors)) {
                // Generar token único
                $token = bin2hex(random_bytes(32));
                
                // Insertar solicitud
                $query = "INSERT INTO solicitudes_facilitador (nombre, email, telefono, rut, empresa, mensaje, token_aprobacion, estado) 
                          VALUES (:nombre, :email, :telefono, :rut, :empresa, :mensaje, :token, 'pendiente')";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':telefono', $telefono);
                $stmt->bindParam(':rut', $rut);
                $stmt->bindParam(':empresa', $empresa);
                $stmt->bindParam(':mensaje', $mensaje);
                $stmt->bindParam(':token', $token);
                
                if ($stmt->execute()) {
                    // Notificar al administrador por email
                    $this->notificarAdministrador($nombre, $email);
                    
                    $this->redirect('/agendapro/auth/solicitar_registro?success=sent');
                    return;
                } else {
                    $errors[] = 'Error al guardar la solicitud';
                }
            }
            
            $this->view('auth/solicitar_registro', ['errors' => $errors, 'title' => 'Solicitar Registro']);
        }
    }
    
    /**
     * Notificar al administrador sobre nueva solicitud
     */
    private function notificarAdministrador($nombre, $email) {
        try {
            // Buscar administrador (el primer facilitador)
            $userModel = new User();
            $admins = $userModel->getByRol('facilitador');
            $admin = !empty($admins) ? $admins[0] : null;
            
            if ($admin) {
                $emailHelper = new Email();
                $subject = "Nueva solicitud de registro - AgendaPro";
                $body = "
                    <div style='font-family: Arial, sans-serif;'>
                        <h2 style='color: #4e73df;'>Nueva Solicitud de Registro</h2>
                        <p><strong>Nombre:</strong> {$nombre}</p>
                        <p><strong>Email:</strong> {$email}</p>
                        <p>Ingrese al panel de administración para aprobar o rechazar esta solicitud.</p>
                        <a href='" . BASE_URL . "/auth/solicitudes_pendientes' style='background-color: #4e73df; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>
                            Revisar Solicitudes
                        </a>
                    </div>
                ";
                $emailHelper->send($admin['email'], $subject, $body);
            }
        } catch (Exception $e) {
            error_log("Error al notificar administrador: " . $e->getMessage());
        }
    }
    
    /**
     * Mostrar solicitudes pendientes (solo para administrador o facilitador)
     */
    public function solicitudes_pendientes() {
        // Permitir tanto a facilitadores como a administradores
        if (!Auth::isFacilitador() && !Auth::isAdministrador()) {
            $this->redirect(BASE_URL . '/dashboard?error=unauthorized');
            return;
        }
        
        $db = $this->getDbConnection();
        $solicitudes = [];
        
        if ($db) {
            $query = "SELECT * FROM solicitudes_facilitador WHERE estado = 'pendiente' ORDER BY created_at DESC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        $this->view('auth/solicitudes_pendientes', [
            'solicitudes' => $solicitudes,
            'title' => 'Solicitudes Pendientes'
        ]);
    }
    
    /**
     * Aprobar una solicitud de registro
     */
    public function aprobar_solicitud($id) {
        // Verificar permisos: solo administrador o facilitador (según tu lógica)
        if (!Auth::isAdministrador() && !Auth::isFacilitador()) {
            $this->redirect(BASE_URL . '/dashboard?error=unauthorized');
            return;
        }
        
        $db = $this->getDbConnection();
        
        if (!$db) {
            $this->redirect(BASE_URL . '/auth/solicitudes_pendientes?error=db_error');
            return;
        }
        
        // Obtener la solicitud
        $query = "SELECT * FROM solicitudes_facilitador WHERE id = :id AND estado = 'pendiente'";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$solicitud) {
            $this->redirect(BASE_URL . '/auth/solicitudes_pendientes?error=notfound');
            return;
        }
        
        // Generar contraseña aleatoria
        $password = $this->generarPassword();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
             
        // Crear usuario facilitador
        $userModel = new User();
        $userId = $userModel->createUser([
            'nombre' => $solicitud['nombre'],
            'email' => $solicitud['email'],
            'telefono' => $solicitud['telefono'],
            'rut' => $solicitud['rut'],
            'password' => $password,
            'rol' => 'facilitador',
            'activo' => 1,
            'created_by' => $_SESSION['user_id']
        ]);
        
        if (!$userId) {
            $this->redirect(BASE_URL . '/auth/solicitudes_pendientes?error=create_failed');
            return;
        }
        
        // Actualizar estado de la solicitud a 'aprobada'
        $query = "UPDATE solicitudes_facilitador SET estado = 'aprobada', updated_at = NOW() WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        if (!$stmt->execute()) {
            // Aunque falle la actualización, el usuario ya fue creado. Podemos intentar nuevamente o loguear.
            // Por ahora redirigimos con error, pero el usuario quedó creado.
            $this->redirect(BASE_URL . '/auth/solicitudes_pendientes?error=update_failed');
            return;
        }
        
        // Enviar email de bienvenida
        $emailSent = $this->enviarEmailBienvenida($solicitud['email'], $solicitud['nombre'], $password);
        if (!$emailSent) {
            // No detenemos el flujo, pero registramos el error.
        }
        
        $this->redirect(BASE_URL . '/auth/solicitudes_pendientes?success=approved');
    }

    
    /**
     * Rechazar una solicitud
     */
    public function rechazar_solicitud($id) {
        if (!Auth::isAdministrador() && !Auth::isFacilitador()) {
            $this->redirect(BASE_URL . '/dashboard?error=unauthorized');
            return;
        }
        
        $db = $this->getDbConnection();
        if (!$db) {
            $this->redirect(BASE_URL . '/auth/solicitudes_pendientes?error=db_error');
            return;
        }
        
        // Verificar que la solicitud existe y está pendiente
        $query = "SELECT * FROM solicitudes_facilitador WHERE id = :id AND estado = 'pendiente'";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$solicitud) {
            $this->redirect(BASE_URL . '/auth/solicitudes_pendientes?error=notfound');
            return;
        }
        
        // Actualizar estado a rechazada
        $query = "UPDATE solicitudes_facilitador SET estado = 'rechazada', updated_at = NOW() WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Opcional: enviar email de rechazo
        // $this->enviarEmailRechazo($solicitud['email'], $solicitud['nombre']);
        
        $this->redirect(BASE_URL . '/auth/solicitudes_pendientes?success=rejected');
    }

    
    /**
     * Generar contraseña aleatoria
     */
    private function generarPassword($longitud = 10) {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%';
        return substr(str_shuffle($caracteres), 0, $longitud);
    }
    
    /**
     * Enviar email de bienvenida con contraseña
     */
    private function enviarEmailBienvenida($email, $nombre, $password) {
        try {
            $emailHelper = new Email();
            $subject = "Bienvenido a AgendaPro - Su cuenta ha sido aprobada";
            $body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #4e73df;'>¡Bienvenido a AgendaPro, {$nombre}!</h2>
                    <p>Su solicitud de registro como facilitador ha sido <strong style='color: green;'>APROBADA</strong>.</p>
                    <p>Sus credenciales de acceso son:</p>
                    <div style='background-color: #f8f9fc; padding: 15px; border-radius: 10px; margin: 20px 0;'>
                        <p><strong>Email:</strong> {$email}</p>
                        <p><strong>Contraseña:</strong> <code style='background: #e9ecef; padding: 5px; border-radius: 5px;'>{$password}</code></p>
                    </div>
                    <p><strong>Recomendación:</strong> Al iniciar sesión, cambie su contraseña por una más segura.</p>
                    <a href='" . BASE_URL . "/auth/login' style='background-color: #4e73df; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;'>
                        Iniciar Sesión
                    </a>
                    <hr style='margin: 30px 0;'>
                    <p style='color: #666; font-size: 12px;'>Este es un mensaje automático, por favor no responder.</p>
                </div>
            ";
            $emailHelper->send($email, $subject, $body);
        } catch (Exception $e) {
            error_log("Error al enviar email de bienvenida: " . $e->getMessage());
        }
    }    
}
?>