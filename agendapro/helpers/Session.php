<?php
// helpers/Session.php
class Session {
    
    /**
     * Iniciar sesión si no está iniciada
     */
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Establecer valor en sesión
     */
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Obtener valor de sesión
     */
    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Establecer datos del usuario autenticado
     */
    public static function setUser($user) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nombre' => $user['nombre'],
            'email' => $user['email'],
            'rol' => $user['rol'],
            'otec_id' => $user['otec_id'] ?? null
        ];
    }
    
    /**
     * Obtener datos del usuario actual
     */
    public static function user() {
        return $_SESSION['user'] ?? null;
    }
    
    /**
     * Verificar si hay usuario autenticado
     */
    public static function isAuthenticated() {
        return isset($_SESSION['user']);
    }
    
    /**
     * Verificar si el usuario es administrador
     */
    public static function isAdministrador() {
        return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'administrador';
    }
    
    /**
     * Verificar si el usuario es facilitador
     */
    public static function isFacilitador() {
        return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'facilitador';
    }
    
    /**
     * Verificar si el usuario es ejecutivo
     */
    public static function isEjecutivo() {
        return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'ejecutivo';
    }
    
    /**
     * Verificar rol específico
     */
    public static function isRole($role) {
        $user = self::user();
        return $user && $user['rol'] === $role;
    }
    
    /**
     * Regenerar ID de sesión (seguridad)
     */
    public static function regenerate() {
        session_regenerate_id(true);
    }
    
    /**
     * Destruir sesión completamente
     */
    public static function destroy() {
        $_SESSION = [];
        
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        session_destroy();
    }
    
    /**
     * Establecer mensaje flash (se elimina al leer)
     */
    public static function flash($key, $message) {
        $_SESSION['_flash'][$key] = $message;
    }
    
    /**
     * Obtener mensaje flash y eliminarlo
     */
    public static function getFlash($key, $default = null) {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }
    
    /**
     * Verificar si hay mensaje flash
     */
    public static function hasFlash($key = null) {
        if ($key) {
            return isset($_SESSION['_flash'][$key]);
        }
        return !empty($_SESSION['_flash']);
    }
    
    /**
     * Eliminar un valor de sesión
     */
    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Obtener toda la sesión
     */
    public static function all() {
        return $_SESSION;
    }
}

// Iniciar sesión automáticamente al cargar el archivo
Session::start();
?>