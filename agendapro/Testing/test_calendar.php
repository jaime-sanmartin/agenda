<?php
// test_calendar.php - Crear en la raíz del proyecto
require_once 'config/database.php';
require_once 'models/Booking.php';
require_once 'helpers/Auth.php';
require_once 'helpers/Session.php';

Session::start();

// Simular usuario para prueba
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 1,
        'rol' => 'facilitador'
    ];
}

$start = date('Y-m-d');
$end = date('Y-m-d', strtotime('+1 month'));

$bookingModel = new Booking();

// Obtener eventos
$bookings = $bookingModel->getEventsInRange($start, $end, 'facilitador', null);

echo "<h1>Prueba de Eventos</h1>";
echo "<pre>";
print_r($bookings);
echo "</pre>";

// Verificar datos
echo "<h2>Cantidad de eventos: " . count($bookings) . "</h2>";

if (empty($bookings)) {
    echo "<p style='color:red'>No se encontraron eventos. Verifica que existan reservas en la base de datos.</p>";
    
    // Verificar reservas
    $db = (new Database())->getConnection();
    $stmt = $db->query("SELECT COUNT(*) as total FROM bookings");
    $result = $stmt->fetch();
    echo "<p>Total de reservas en BD: " . $result['total'] . "</p>";
}
?>