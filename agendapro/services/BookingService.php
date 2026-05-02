<?php
class BookingService {

    public static function create($data) {

        $db = Database::connect();

        $stmt = $db->prepare("
            INSERT INTO bookings 
            (course_id, user_id, otec_id, start_date, end_date, status)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");

        return $stmt->execute([
            $data['course_id'],
            $data['user_id'],
            $data['otec_id'],
            $data['start'],
            $data['end']
        ]);
    }

    public static function updateStatus($id, $status) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE bookings SET status=? WHERE id=?");
        return $stmt->execute([$status, $id]);
    }
}