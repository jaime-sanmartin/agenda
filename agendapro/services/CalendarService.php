<?php
class CalendarService {

    public static function getEvents($user_id, $role) {
        $db = Database::connect();

        $events = [];

        // 1. Disponibilidad
        $stmt = $db->prepare("SELECT * FROM availability WHERE user_id = ?");
        $stmt->execute([$user_id]);

        while ($row = $stmt->fetch()) {
            $events[] = [
                'title' => 'Disponible',
                'start' => $row['start_date'],
                'end' => $row['end_date'],
                'color' => '#28a745'
            ];
        }

        // 2. Reservas
        $stmt = $db->prepare("
            SELECT b.*, c.name as course_name
            FROM bookings b
            JOIN courses c ON b.course_id = c.id
            WHERE b.user_id = ?
        ");
        $stmt->execute([$user_id]);

        while ($row = $stmt->fetch()) {

            $color = match($row['status']) {
                'pending' => '#ffc107',
                'approved' => '#007bff',
                'rejected' => '#dc3545',
                'confirmed' => '#28a745',
                default => '#6c757d'
            };

            $events[] = [
                'title' => $row['course_name'],
                'start' => $row['start_date'],
                'end' => $row['end_date'],
                'color' => $color
            ];
        }

        return $events;
    }
}