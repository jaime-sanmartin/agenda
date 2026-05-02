<?php
// controllers/NotificationController.php
require_once 'core/Controller.php';
require_once 'helpers/Auth.php';
require_once 'models/Notification.php';

class NotificationController extends Controller {
    
    private $notificationModel;
    
    public function __construct() {
        Auth::checkLogin();
        $this->notificationModel = new Notification();
    }
    
    /**
     * Obtener notificaciones no leídas (para AJAX)
     */
    public function unread() {
        header('Content-Type: application/json; charset=utf-8');
        
        $user = Auth::user();
        $userId = $user['id'] ?? null;
        
        if (!$userId) {
            echo json_encode(['count' => 0, 'notifications' => []]);
            return;
        }
        
        $notifications = $this->notificationModel->getUnread($userId);
        $count = count($notifications);
        
        // Formatear para JSON
        $formatted = [];
        foreach ($notifications as $notif) {
            $formatted[] = [
                'id' => $notif['id'],
                'titulo' => html_entity_decode($notif['titulo'], ENT_QUOTES, 'UTF-8'),
                'mensaje' => html_entity_decode($notif['mensaje'], ENT_QUOTES, 'UTF-8'),
                'tipo' => $notif['tipo'],
                //'leido' => (bool)$notif['leido'],
                'link' => $notif['link'],
                'created_at' => $notif['created_at']
            ];
        }
        
        echo json_encode([
            'count' => $count,
            'notifications' => $formatted
        ], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Marcar una notificación como leída
     */
    public function markRead($id) {
        header('Content-Type: application/json; charset=utf-8');
        
        $user = Auth::user();
        $userId = $user['id'] ?? null;
        
        if (!$userId) {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        $result = $this->notificationModel->markAsRead($id);
        echo json_encode(['success' => $result]);
    }
    
    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllRead() {
        header('Content-Type: application/json; charset=utf-8');
        
        $user = Auth::user();
        $userId = $user['id'] ?? null;
        
        if (!$userId) {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        $result = $this->notificationModel->markAllAsRead($userId);
        echo json_encode(['success' => $result]);
    }
    
    /**
     * Listar todas las notificaciones
     */
    public function index() {
        $user = Auth::user();
        $userId = $user['id'] ?? null;
        
        if (!$userId) {
            $this->redirect('/auth/login');
            return;
        }
        
        $notifications = $this->notificationModel->getUserNotifications($userId);
        $unreadCount = $this->notificationModel->getUnreadCount($userId);
        
        $data = [
            'title' => 'Mis Notificaciones',
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ];
        
        $this->view('notifications/index', $data);
    }
}
?>