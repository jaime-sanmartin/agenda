<?php
// models/User.php
require_once 'core/Model.php';

class User extends Model {
    protected $table = 'users';
    
    /**
     * Obtener todos los usuarios con detalles de OTEC
     */
    public function getAllWithDetails() {
        $query = "SELECT u.*, o.nombre as otec_nombre 
                  FROM " . $this->table . " u
                  LEFT JOIN otec o ON u.otec_id = o.id
                  ORDER BY u.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener usuarios por OTEC
     */
    public function getByOtec($otecId) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE otec_id = :otec_id AND activo = 1
                  ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':otec_id', $otecId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener todos los facilitadores activos (para administración)
     */
    public function getAllFacilitadores() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE rol = 'facilitador' AND activo = 1
                  ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Asignar facilitador a una OTEC
     */
    public function assignFacilitadorToOtec($otecId, $facilitadorId, $asignadoPor) {
        $query = "INSERT INTO otec_facilitadores (otec_id, facilitador_id, asignado_por) 
                  VALUES (:otec_id, :facilitador_id, :asignado_por)
                  ON DUPLICATE KEY UPDATE activo = 1, updated_at = NOW()";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':otec_id', $otecId);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->bindParam(':asignado_por', $asignadoPor);
        return $stmt->execute();
    }
    
    /**
     * Remover asignación de facilitador a OTEC
     */
    public function removeFacilitadorFromOtec($otecId, $facilitadorId) {
        $query = "DELETE FROM otec_facilitadores 
                  WHERE otec_id = :otec_id AND facilitador_id = :facilitador_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':otec_id', $otecId);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        return $stmt->execute();
    }
    
    /**
     * Obtener usuarios por rol
     */
    public function getByRol($rol) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE rol = :rol AND activo = 1
                  ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':rol', $rol);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener usuarios activos
     */
    public function getActiveUsers() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE activo = 1 
                  ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Validar email único
     */
    public function validateEmail($email, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        if ($excludeId) {
            $query .= " AND id != :id";
        }
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        if ($excludeId) {
            $stmt->bindParam(':id', $excludeId);
        }
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }
    
    /**
     * Validar RUT único
     */
    public function validateRut($rut, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table . " WHERE rut = :rut";
        if ($excludeId) {
            $query .= " AND id != :id";
        }
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':rut', $rut);
        if ($excludeId) {
            $stmt->bindParam(':id', $excludeId);
        }
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }
    
    /**
     * Crear usuario con contraseña encriptada
     */
    public function createUser($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->create($data);
    }
    
    /**
     * Actualizar usuario (con encriptación de contraseña si es necesario)
     */
    public function updateUser($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        return $this->update($id, $data);
    }

    /**
     * Obtener usuarios creados por un facilitador específico
     * Incluye al propio facilitador
     */
    public function getUsersByFacilitador($facilitadorId) {
        $query = "SELECT u.*, o.nombre as otec_nombre 
                  FROM " . $this->table . " u
                  LEFT JOIN otec o ON u.otec_id = o.id
                  WHERE u.created_by = :facilitador_id OR u.id = :facilitador_id
                  ORDER BY u.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verificar si un usuario fue creado por el facilitador actual
     */
    public function isOwnedByFacilitador($userId, $facilitadorId) {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE id = :user_id AND (created_by = :facilitador_id OR id = :facilitador_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }    

    /**
     * Actualizar contraseña del usuario
     */
    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($id, ['password' => $hashedPassword]);
    }
    
    /**
     * Contar usuarios creados por un facilitador específico
     */
    public function countByFacilitador($facilitadorId) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " 
                  WHERE created_by = :facilitador_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    /**
     * Obtener usuarios creados por un facilitador específico (incluye al propio facilitador)
     */
    public function getByFacilitador($facilitadorId) {
        $query = "SELECT u.*, o.nombre as otec_nombre 
                  FROM " . $this->table . " u
                  LEFT JOIN otec o ON u.otec_id = o.id
                  WHERE u.created_by = :facilitador_id OR u.id = :facilitador_id
                  ORDER BY u.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // models/User.php - Agregar estos métodos
    /**
     * Obtener facilitadores por OTEC
     */
    public function getFacilitadoresByOtec($otecId) {
        $query = "SELECT u.id, u.nombre, u.email, u.rol
                  FROM users u
                  WHERE u.otec_id = :otec_id 
                  AND u.rol = 'facilitador' 
                  AND u.activo = 1
                  ORDER BY u.nombre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':otec_id', $otecId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener facilitadores que pueden impartir un curso específico
     */
    public function getFacilitadoresByCurso($cursoId) {
        $query = "SELECT DISTINCT u.id, u.nombre, u.email, u.rol
                  FROM users u
                  INNER JOIN courses c ON c.created_by = u.id
                  WHERE c.id = :curso_id 
                  AND u.rol = 'facilitador' 
                  AND u.activo = 1
                  ORDER BY u.nombre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':curso_id', $cursoId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener OTEC del usuario
     */
    public function getOtecByUser($userId) {
        $query = "SELECT o.* 
                  FROM users u
                  LEFT JOIN otec o ON u.otec_id = o.id
                  WHERE u.id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener todos los usuarios (sin filtro - para administrador)
     */
    public function getAllUsersAdmin() {
        $query = "SELECT u.*, o.nombre as otec_nombre 
                  FROM " . $this->table . " u
                  LEFT JOIN otec o ON u.otec_id = o.id
                  ORDER BY u.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Contar usuarios por rol
     */
    public function countByRol($rol) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE rol = :rol";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':rol', $rol);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Buscar usuario por email
     * @param string $email
     * @return array|false
     */
    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verificar si un ejecutivo ya está asociado a una OTEC (y por tanto al facilitador)
     * @param int $ejecutivoId
     * @param int $otecId
     * @return bool
     */
    public function isEjecutivoAsociadoAOtec($ejecutivoId, $otecId) {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE id = :ejecutivo_id AND otec_id = :otec_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ejecutivo_id', $ejecutivoId);
        $stmt->bindParam(':otec_id', $otecId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Obtener ejecutivos asociados a las OTEC de un facilitador
     * @param int $facilitadorId
     * @return array
     */
    // models/User.php
    // Reemplazar el método getEjecutivosByFacilitador
    
    public function getEjecutivosByFacilitador($facilitadorId) {
        $query = "SELECT u.*, o.nombre as otec_nombre, o.imagen_otec as otec_imagen
                  FROM users u
                  JOIN otec o ON u.otec_id = o.id
                  WHERE u.rol = 'ejecutivo'
                  AND o.id IN (
                      SELECT ofa.otec_id FROM otec_facilitadores ofa 
                      WHERE ofa.facilitador_id = :facilitador_id AND ofa.activo = 1
                  )
                  ORDER BY u.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>