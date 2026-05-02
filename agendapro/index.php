<?php
// index.php
session_start();

// Definir URL base
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$scriptName = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$baseUrl = $protocol . $host . $scriptName;

define('BASE_URL', $baseUrl);

// Autocargador
spl_autoload_register(function ($class_name) {
    $paths = [
        __DIR__ . '/core/',
        __DIR__ . '/controllers/',
        __DIR__ . '/models/',
        __DIR__ . '/helpers/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Incluir helpers que no siguen el patrón de clase
require_once __DIR__ . '/helpers/Session.php';
require_once __DIR__ . '/helpers/Security.php';

// Configuración de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de rutas
$router = new Router();

// =====================================================
// RUTAS PÚBLICAS (no requieren autenticación)
// =====================================================
$router->add('auth/login', 'AuthController', 'login');
$router->add('auth/logout', 'AuthController', 'logout');
$router->add('auth/recover', 'AuthController', 'recover');
$router->add('auth/reset', 'AuthController', 'reset');
$router->add('auth/solicitar_registro', 'AuthController', 'solicitar_registro');
$router->add('auth/procesar_solicitud', 'AuthController', 'procesar_solicitud');

// =====================================================
// RUTAS PROTEGIDAS (requieren autenticación)
// =====================================================

// Dashboard
$router->add('dashboard', 'DashboardController', 'goToDashboard');
$router->add('dashboard/facilitador', 'DashboardController', 'facilitator');
$router->add('dashboard/ejecutivo', 'DashboardController', 'executive');
$router->add('dashboard/admin', 'DashboardController', 'admin');  // ← NUEVA RUTA ADMIN

// Calendario
$router->add('calendar', 'CalendarController', 'index');
$router->add('calendar/getEvents', 'CalendarController', 'getEvents');

// OTEC
$router->add('otec', 'OtecController', 'index');
$router->add('otec/create', 'OtecController', 'create');
$router->add('otec/edit', 'OtecController', 'edit');
$router->add('otec/details', 'OtecController', 'details');
$router->add('otec/delete', 'OtecController', 'delete');
$router->add('otec/toggleStatus', 'OtecController', 'toggleStatus');
$router->add('otec/verificarRut', 'OtecController', 'verificarRut');

// Usuarios
$router->add('users', 'UserController', 'index');
$router->add('users/create', 'UserController', 'create');
$router->add('users/edit', 'UserController', 'edit');
$router->add('users/details', 'UserController', 'details');
$router->add('users/delete', 'UserController', 'delete');
$router->add('users/toggleStatus', 'UserController', 'toggleStatus');
$router->add('users/verificarEmail', 'UserController', 'verificarEmail');

// Cursos
$router->add('courses', 'CourseController', 'index');
$router->add('courses/create', 'CourseController', 'create');
$router->add('courses/edit', 'CourseController', 'edit');
$router->add('courses/details', 'CourseController', 'details');
$router->add('courses/delete', 'CourseController', 'delete');
$router->add('courses/toggleStatus', 'CourseController', 'toggleStatus');
$router->add('courses/public', 'CourseController', 'publicCatalog');

// Reservas
$router->add('bookings', 'BookingController', 'index');
$router->add('bookings/create', 'BookingController', 'create');
$router->add('bookings/edit', 'BookingController', 'edit');
$router->add('bookings/details', 'BookingController', 'details');
$router->add('bookings/delete', 'BookingController', 'delete');
$router->add('bookings/updateStatus', 'BookingController', 'updateStatus');
$router->add('bookings/registrarAsistencia', 'BookingController', 'registrarAsistencia');

// Disponibilidad
$router->add('availability', 'AvailabilityController', 'index');
$router->add('availability/create', 'AvailabilityController', 'create');
$router->add('availability/edit', 'AvailabilityController', 'edit');
$router->add('availability/delete', 'AvailabilityController', 'delete');
$router->add('availability/weekly', 'AvailabilityController', 'weekly');

// Reportes
$router->add('reports', 'ReportController', 'dashboard');
$router->add('reports/dashboard', 'ReportController', 'dashboard');

// Perfil
$router->add('auth/profile', 'AuthController', 'profile');
$router->add('auth/change-password', 'AuthController', 'changePassword');

// API
$router->add('api/courses', 'ApiController', 'courses');
$router->add('api/availability', 'ApiController', 'availability');
$router->add('api/create-booking', 'ApiController', 'createBooking');

// Notificaciones
$router->add('notifications', 'NotificationController', 'index');
$router->add('notifications/unread', 'NotificationController', 'unread');
$router->add('notifications/markRead', 'NotificationController', 'markRead');
$router->add('notifications/markAllRead', 'NotificationController', 'markAllRead');

// Solicitudes (solo administrador)
$router->add('auth/solicitudes_pendientes', 'AuthController', 'solicitudes_pendientes');
$router->add('auth/aprobar_solicitud', 'AuthController', 'aprobar_solicitud');
$router->add('auth/rechazar_solicitud', 'AuthController', 'rechazar_solicitud');

// =====================================================
// PROCESAMIENTO DE LA URL
// =====================================================

// Obtener URL
$url = $_GET['url'] ?? 'auth/login';

// Definir rutas públicas (no requieren autenticación)
$rutas_publicas = [
    'auth/login',
    'auth/logout', 
    'auth/recover',
    'auth/reset',
    'auth/solicitar_registro',
    'auth/procesar_solicitud'
];

// Verificar si la ruta actual es pública
$es_publica = false;
foreach ($rutas_publicas as $publica) {
    if (strpos($url, $publica) === 0) {
        $es_publica = true;
        break;
    }
}

// =====================================================
// VERIFICACIÓN DE AUTENTICACIÓN
// =====================================================
// Si no está autenticado y la ruta no es pública, redirigir al login
if (!Session::isAuthenticated() && !$es_publica) {
    $url = 'auth/login';
}

// =====================================================
// REDIRECCIÓN POR ROL (solo para ruta 'dashboard')
// =====================================================
// Si el usuario está autenticado y la ruta es exactamente 'dashboard'
if (Session::isAuthenticated() && $url === 'dashboard') {
    $rol = $_SESSION['user_rol'] ?? null;
    if ($rol === 'facilitador') {
        $url = 'dashboard/facilitador';
    } elseif ($rol === 'ejecutivo') {
        $url = 'dashboard/ejecutivo';
    } elseif ($rol === 'administrador') {
        $url = 'dashboard/admin';
    }
}

// Despachar la ruta
$router->dispatch($url);
?>