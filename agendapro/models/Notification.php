<?php
// models/Notification.php
require_once 'core/Model.php';

class Notification extends Model {
    protected $table = 'notifications';
    //protected $primaryKey = 'id';
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Crear una notificación
     */
    public function createNotification($userId, $title, $message, $type = 'info', $link = null) {
        $query = "INSERT INTO notifications (user_id, titulo, mensaje, tipo, link, leido, created_at) 
                  VALUES (:user_id, :titulo, :mensaje, :tipo, :link, 0, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':titulo', $title);
        $stmt->bindParam(':mensaje', $message);
        $stmt->bindParam(':tipo', $type);
        $stmt->bindParam(':link', $link);
        return $stmt->execute();
    }
    
    /**
     * Método estático para crear notificaciones (fácil de usar)
     */
    public static function send($userId, $title, $message, $type = 'info', $link = null) {
        $instance = new self();
        return $instance->createNotification($userId, $title, $message, $type, $link);
    }
    
    /**
     * Obtener notificaciones no leídas de un usuario
     */
    public function getUnread($userId) {
        try {
            $query = "SELECT * FROM " . $this->table . "
                      WHERE user_id = :user_id 
                      -- AND leido = 0
                      ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getUnread: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener todas las notificaciones de un usuario (con paginación)
     */
    public function getUserNotifications($userId, $limit = 50, $offset = 0) {
        try {
            $query = "SELECT * FROM " . $this->table . "
                      WHERE user_id = :user_id
                      ORDER BY created_at DESC
                      LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getUserNotifications: " . $e->getMessage());
            return [];
        }
    }
    

    /**
     * Obtener cantidad de notificaciones no leídas
     */
    public function getUnreadCount($userId) {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table . "
                      WHERE user_id = :user_id AND leido = 0";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($result['total'] ?? 0);
        } catch (Exception $e) {
            error_log("Error en getUnreadCount: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtener todas las notificaciones (para administrador)
     */
    public function getAllNotifications($limit = 100, $offset = 0) {
        try {
            $query = "SELECT n.*, u.nombre as user_nombre, u.email as user_email
                      FROM " . $this->table . " n
                      INNER JOIN users u ON n.user_id = u.id
                      ORDER BY n.created_at DESC
                      LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getAllNotifications: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Eliminar notificaciones antiguas (mayores a X días)
     */
    public function deleteOld($days = 30) {
        try {
            $query = "DELETE FROM " . $this->table . "
                      WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':days', $days, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en deleteOld: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar una notificación específica
     */
    public function deleteNotification($id) {
        try {
            return $this->delete($id);
        } catch (Exception $e) {
            error_log("Error en deleteNotification: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar todas las notificaciones de un usuario
     */
    public function deleteUserNotifications($userId) {
        try {
            $query = "DELETE FROM " . $this->table . "
                      WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en deleteUserNotifications: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener notificaciones por tipo
     */
    public function getByType($userId, $type, $limit = 20) {
        try {
            $query = "SELECT * FROM " . $this->table . "
                      WHERE user_id = :user_id AND tipo = :type
                      ORDER BY created_at DESC
                      LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getByType: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener notificaciones recientes (últimas N)
     */
    public function getRecent($userId, $limit = 10) {
        try {
            $query = "SELECT * FROM " . $this->table . "
                      WHERE user_id = :user_id
                      ORDER BY created_at DESC
                      LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getRecent: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Enviar notificación masiva a múltiples usuarios
     */
    public function sendMassNotification($userIds, $title, $message, $type = 'info', $link = null) {
        $success = 0;
        $errors = 0;
        
        foreach ($userIds as $userId) {
            try {
                $this->createNotification($userId, $title, $message, $type, $link);
                $success++;
            } catch (Exception $e) {
                error_log("Error enviando notificación a usuario $userId: " . $e->getMessage());
                $errors++;
            }
        }
        
        return [
            'success' => $success,
            'errors' => $errors,
            'total' => count($userIds)
        ];
    }
    
    /**
     * Enviar notificación a todos los ejecutivos de una OTEC
     */
    public function sendToOtec($otecId, $title, $message, $type = 'info', $link = null) {
        try {
            // Obtener todos los ejecutivos de la OTEC
            $userModel = new User();
            $executives = $userModel->getByOtec($otecId);
            
            $userIds = array_column($executives, 'id');
            
            if (empty($userIds)) {
                return ['success' => 0, 'message' => 'No hay ejecutivos en esta OTEC'];
            }
            
            return $this->sendMassNotification($userIds, $title, $message, $type, $link);
            
        } catch (Exception $e) {
            error_log("Error en sendToOtec: " . $e->getMessage());
            return ['success' => 0, 'errors' => 1, 'message' => $e->getMessage()];
        }
    }

    /**
     * Crear una notificación
     */
   public function crearNotificacion($userId, $title, $message, $type = 'info', $link = null) {
        $query = "INSERT INTO notifications (user_id, title, message, type, link, created_at) 
                  VALUES (:user_id, :title, :message, :type, :link, NOW())";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':user_id' => $userId,
            ':title' => $title,
            ':message' => $message,
            ':type' => $type,
            ':link' => $link
        ]);
    }
    
    /**
     * Obtener notificaciones no leídas de un usuario
     */
    public function getUnreadByUser($userId, $limit = 10) {
        $query = "SELECT * FROM notifications 
                  WHERE user_id = :user_id AND read_at IS NULL 
                  ORDER BY created_at DESC 
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Marcar notificación como leída
     */
    public function marcarComoLeida($notificationId) {
        $query = "UPDATE notifications SET read_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $notificationId]);
    }
    
    /**
     * Marcar todas las notificaciones de un usuario como leídas
     */
    public function marcarTodasComoLeidas($userId) {
        $query = "UPDATE notifications SET read_at = NOW() WHERE user_id = :user_id AND read_at IS NULL";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':user_id' => $userId]);
    }    

}
?>