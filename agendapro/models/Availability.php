<?php
// models/Availability.php
require_once 'core/Model.php';

class Availability extends Model {
    protected $table = 'availability';
    
    public function getInRange($start, $end) {
        $query = "SELECT * FROM " . $this->table . "
                  WHERE fecha_inicio <= :end AND fecha_fin >= :start
                  ORDER BY fecha_inicio";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAvailableSlots($date) {
        $startOfDay = $date . ' 00:00:00';
        $endOfDay = $date . ' 23:59:59';
        
        $query = "SELECT * FROM " . $this->table . "
                  WHERE fecha_inicio >= :start AND fecha_fin <= :end
                  AND estado = 'disponible'
                  ORDER BY fecha_inicio";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':start', $startOfDay);
        $stmt->bindParam(':end', $endOfDay);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function createWeeklyAvailability($startDate, $endDate, $days, $startTime, $endTime) {
        $created = 0;
        $currentDate = strtotime($startDate);
        $endTimestamp = strtotime($endDate);
        
        while ($currentDate <= $endTimestamp) {
            $dayOfWeek = date('N', $currentDate);
            if (in_array($dayOfWeek, $days)) {
                $fechaInicio = date('Y-m-d', $currentDate) . ' ' . $startTime;
                $fechaFin = date('Y-m-d', $currentDate) . ' ' . $endTime;
                
                // Verificar si ya existe
                $exists = $this->where([
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin
                ]);
                
                if (empty($exists)) {
                    $this->create([
                        'fecha_inicio' => $fechaInicio,
                        'fecha_fin' => $fechaFin,
                        'estado' => 'disponible'
                    ]);
                    $created++;
                }
            }
            $currentDate = strtotime('+1 day', $currentDate);
        }
        
        return $created;
    }
    
    public function checkAvailability($fecha_inicio, $fecha_fin) {
        $query = "SELECT id FROM " . $this->table . "
                  WHERE fecha_inicio <= :fecha_fin 
                  AND fecha_fin >= :fecha_inicio
                  AND estado = 'disponible'
                  AND NOT EXISTS (
                      SELECT 1 FROM bookings b
                      WHERE b.fecha_inicio < :fecha_fin2 
                      AND b.fecha_fin > :fecha_inicio2
                      AND b.estado NOT IN ('rechazada', 'cancelada')
                  )";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        $stmt->bindParam(':fecha_inicio2', $fecha_inicio);
        $stmt->bindParam(':fecha_fin2', $fecha_fin);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
?>