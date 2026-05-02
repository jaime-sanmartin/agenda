<?php
// models/ActivityLog.php
require_once 'core/Model.php';

class ActivityLog extends Model {
    protected $table = 'activity_logs';
    
    /**
     * Método de instancia para registrar actividad
     */
    public function logActivity($userId, $action, $table = null, $recordId = null, $details = null) {
        return $this->create([
            'user_id' => $userId,
            'accion' => $action,
            'tabla_afectada' => $table,
            'registro_id' => $recordId,
            'detalles' => $details,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }
    
    /**
     * Método estático para registrar actividad (fácil de usar)
     */
    public static function log($userId, $action, $table = null, $recordId = null, $details = null) {
        $log = new self();
        return $log->logActivity($userId, $action, $table, $recordId, $details);
    }
    
    /**
     * Obtener logs de un usuario
     */
    public function getUserLogs($userId, $limit = 50) {
        $query = "SELECT * FROM " . $this->table . "
                  WHERE user_id = :user_id
                  ORDER BY created_at DESC
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener logs recientes
     */
    public function getRecentLogs($limit = 100) {
        $query = "SELECT l.*, u.nombre as user_nombre, u.email as user_email
                  FROM " . $this->table . " l
                  INNER JOIN users u ON l.user_id = u.id
                  ORDER BY l.created_at DESC
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Limpiar logs antiguos
     */
    public function cleanOldLogs($days = 30) {
        $query = "DELETE FROM " . $this->table . "
                  WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':days', $days);
        return $stmt->execute();
    }
}
?>