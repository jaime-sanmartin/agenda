<?php
// helpers/Email.php - Versión simplificada usando mail()
class Email {
    
    public function send($to, $subject, $body, $altBody = '') {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: AgendaPro Facilitador <no-reply@mascapacita2.com>\r\n";
        $headers .= "Reply-To: no-reply@mascapacita2.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        return mail($to, $subject, $body, $headers);
    }
    
    public function sendBookingNotification($booking, $status) {
        $userModel = new User();
        $executive = $userModel->find($booking['created_by']);
        
        if (!$executive) {
            return false;
        }
        
        $statusText = [
            'pendiente' => 'Pendiente de aprobación',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
            'confirmada' => 'Confirmada'
        ];
        
        $subject = "Actualización de reserva: " . $booking['curso_nombre'];
        $body = "<h2>Actualización de Reserva</h2>
                 <p>Estimado/a,</p>
                 <p>Su solicitud para el curso <strong>{$booking['curso_nombre']}</strong> ha sido <strong>{$statusText[$status]}</strong>.</p>
                 <p><strong>Detalles:</strong></p>
                 <ul>
                     <li>Fecha: " . date('d/m/Y H:i', strtotime($booking['fecha_inicio'])) . "</li>
                     <li>Duración: {$booking['duracion_horas']} horas</li>
                 </ul>
                 <p>Ingrese al sistema para ver los detalles: https://mascapacita2.com/agendapro/</p>";
        
        return $this->send($executive['email'], $subject, $body);
    }
    
    public function sendPasswordReset($user, $token) {
        $resetLink = "https://mascapacita2.com/agendapro/auth/reset?token=" . $token;
        $subject = "Recuperación de contraseña - AgendaPro";
        $body = "<h2>Recuperación de contraseña</h2>
                 <p>Hola <strong>{$user['nombre']}</strong>,</p>
                 <p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
                 <p><a href='{$resetLink}'>Restablecer Contraseña</a></p>
                 <p>Este enlace expirará en 1 hora.</p>";
        
        return $this->send($user['email'], $subject, $body);
    }
}
?>