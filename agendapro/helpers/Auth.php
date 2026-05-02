<?php
// helpers/Auth.php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/ActivityLog.php';
require_once __DIR__ . '/Session.php';

class Auth {
    
    /**
     * Intentar iniciar sesión
     */
    public static function attempt($email, $password) {
        $userModel = new User();
        $user = $userModel->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            if ($user['activo'] == 0) {
                Session::flash('error', 'Usuario desactivado. Contacte al administrador.');
                return ['error' => 'Usuario desactivado'];
            }
            
            // Iniciar sesión - guardar datos en sesión
            self::setUser($user);
            Session::regenerate();
            
            // Actualizar último login
            $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            
            // Registrar actividad
            if (class_exists('ActivityLog')) {
                ActivityLog::log($user['id'], 'login', 'users', $user['id'], 'Usuario inició sesión');
            }
            
            return ['success' => true, 'rol' => $user['rol']];
        }
        
        return ['error' => 'Credenciales incorrectas'];
    }
    
    /**
     * Establecer datos del usuario en sesión
     */
    public static function setUser($user) {
        // Guardar en formato simple para acceso directo
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nombre'] = $user['nombre'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_rol'] = $user['rol'];
        $_SESSION['user_otec_id'] = $user['otec_id'] ?? null;
        
        // Guardar también en formato array para compatibilidad
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nombre' => $user['nombre'],
            'email' => $user['email'],
            'rol' => $user['rol'],
            'otec_id' => $user['otec_id'] ?? null
        ];
    }
    
    /**
     * Obtener usuario actual
     */
    public static function user() {
        return Session::user();
    }
    
    /**
     * Verificar si hay usuario autenticado
     */
    public static function check() {
        return Session::isAuthenticated();
    }
    
    /**
     * Verificar login (redirige si no está autenticado)
     */
    public static function checkLogin() {
        if (!Session::isAuthenticated()) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
    }
    
    /**
     * Verificar rol específico
     */
    public static function checkRole($role) {
        self::checkLogin();
        if (!self::isRole($role) && !self::isFacilitador()) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }
    
    /**
     * Verificar si es facilitador
     */
    public static function isFacilitador() {
        return Session::isFacilitador();
    }
    
    /**
     * Verificar si es ejecutivo
     */
    public static function isEjecutivo() {
        return Session::isEjecutivo();
    }
    
    /**
     * Verificar rol específico
     */
    public static function isRole($role) {
        return Session::isRole($role);
    }
    
    /**
     * Obtener ID del usuario actual
     */
    public static function id() {
        $user = self::user();
        return $user['id'] ?? null;
    }
    
    /**
     * Obtener OTEC del usuario actual (si es ejecutivo)
     */
    public static function otecId() {
        $user = self::user();
        return $user['otec_id'] ?? null;
    }
    
    /**
     * Cerrar sesión
     */
    public static function logout() {
        if (Session::isAuthenticated()) {
            $user = Session::user();
            if (class_exists('ActivityLog')) {
                ActivityLog::log($user['id'], 'logout', 'users', $user['id'], 'Usuario cerró sesión');
            }
        }
        Session::destroy();
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }

    /**
     * Verificar si es administrador
     */
    public static function isAdministrador() {
        return Session::isAdministrador();
    }
    
    /**
     * Verificar rol de administrador (redirige si no)
     */
    public static function checkRoleAdmin() {
        self::checkLogin();
        if (!self::isAdministrador()) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }
    
    /**
     * Verificar si es administrador o facilitador (para ciertas acciones)
     */
    public static function isAdminOrFacilitador() {
        return self::isAdministrador() || self::isFacilitador();
    }    
    
}
?>