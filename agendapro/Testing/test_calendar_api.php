<?php
// test_calendar_api.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';
require_once 'models/Booking.php';
require_once 'helpers/Session.php';
require_once 'helpers/Auth.php';

Session::start();

// Simular usuario
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 1,
        'rol' => 'facilitador'
    ];
}

header('Content-Type: application/json; charset=utf-8');

try {
    $start = date('Y-m-d 00:00:00');
    $end = date('Y-m-d', strtotime('+1 month')) . ' 23:59:59';
    
    $bookingModel = new Booking();
    $bookings = $bookingModel->getEventsInRange($start, $end, 'facilitador', null);
    
    $events = [];
    foreach ($bookings as $booking) {
        $events[] = [
            'id' => 'booking_' . $booking['id'],
            'title' => $booking['curso_nombre'],
            'start' => $booking['fecha_inicio'],
            'end' => $booking['fecha_fin'],
            'backgroundColor' => '#3498db'
        ];
    }
    
    echo json_encode($events, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>