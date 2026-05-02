<?php
// test_clean.php
header('Content-Type: application/json; charset=utf-8');

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

try {
    $start = date('Y-m-d 00:00:00');
    $end = date('Y-m-d', strtotime('+1 month')) . ' 23:59:59';
    
    $bookingModel = new Booking();
    $bookings = $bookingModel->getEventsInRange($start, $end, 'facilitador', null);
    
    $events = [];
    foreach ($bookings as $booking) {
        // Limpiar cada campo
        $clean = function($str) {
            if (!is_string($str)) return $str;
            $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
            $str = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $str);
            $str = html_entity_decode($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            return $str;
        };
        
        $events[] = [
            'id' => $booking['id'],
            'curso' => $clean($booking['curso_nombre']),
            'fecha_inicio' => $booking['fecha_inicio'],
            'fecha_fin' => $booking['fecha_fin'],
            'estado' => $booking['estado']
        ];
    }
    
    echo json_encode($events, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>