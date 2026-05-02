<?php
// models/Otec.php
require_once 'core/Model.php';

class Otec extends Model {
    protected $table = 'otec';
    
    public function getAllWithStats() {
        $query = "SELECT o.*, 
                         COUNT(DISTINCT u.id) as total_ejecutivos,
                         COUNT(b.id) as total_capacitaciones,
                         SUM(CASE WHEN b.estado = 'confirmada' THEN 1 ELSE 0 END) as capacitaciones_activas
                  FROM " . $this->table . " o
                  LEFT JOIN users u ON o.id = u.otec_id
                  LEFT JOIN bookings b ON o.id = b.otec_id
                  GROUP BY o.id
                  ORDER BY o.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getActiveOtec() {
        $query = "SELECT * FROM " . $this->table . " WHERE activo = 1 ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function validateRut($rut) {
        $query = "SELECT id FROM " . $this->table . " WHERE rut = :rut";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':rut', $rut);
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }

    /**
     * Contar OTEC creadas por un facilitador específico
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
     * Obtener OTEC creadas por un facilitador específico
     */
    public function getByAllFacilitador() {
        $query = "SELECT o.*, 
                         COUNT(DISTINCT u.id) as total_ejecutivos,
                         COUNT(b.id) as total_capacitaciones
                  FROM " . $this->table . " o
                  LEFT JOIN users u ON o.id = u.otec_id
                  LEFT JOIN bookings b ON o.id = b.otec_id
                  GROUP BY o.id
                  ORDER BY o.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener OTEC creadas por un facilitador específico
     */
    public function getByFacilitador($facilitadorId) {
        $query = "SELECT o.*, 
                         COUNT(DISTINCT u.id) as total_ejecutivos,
                         COUNT(b.id) as total_capacitaciones
                  FROM " . $this->table . " o
                  LEFT JOIN users u ON o.id = u.otec_id
                  LEFT JOIN bookings b ON o.id = b.otec_id
                  WHERE o.created_by = :facilitador_id
                  GROUP BY o.id
                  ORDER BY o.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener estadísticas de OTEC por facilitador
     */
    public function getStatsByFacilitador($facilitadorId) {
        $query = "SELECT 
                    COUNT(*) as total_otec,
                    SUM(CASE WHEN activo = 1 THEN 1 ELSE 0 END) as otec_activas,
                    SUM(CASE WHEN activo = 0 THEN 1 ELSE 0 END) as otec_inactivas
                  FROM " . $this->table . "
                  WHERE created_by = :facilitador_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verificar si una OTEC pertenece a un facilitador
     */
    public function isOwnedByFacilitador($otecId, $facilitadorId) {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE id = :otec_id AND created_by = :facilitador_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':otec_id', $otecId);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Crear OTEC y automáticamente asignar el facilitador creador
     * 
     * @param array $data Datos de la OTEC
     * @param int $facilitadorId ID del facilitador que la crea
     * @return int|false ID de la OTEC creada o false si falla
     */
    public function createWithFacilitador($data, $facilitadorId) {
        // Iniciar transacción
        $this->db->beginTransaction();
        
        try {
            // 1. Crear la OTEC
            $otecId = $this->create($data);
            
            if (!$otecId) {
                throw new Exception("Error al crear la OTEC");
            }
            
            // 2. Asignar el facilitador a la OTEC
            $query = "INSERT INTO otec_facilitadores (otec_id, facilitador_id, asignado_por, activo) 
                      VALUES (:otec_id, :facilitador_id, :asignado_por, 1)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':otec_id', $otecId);
            $stmt->bindParam(':facilitador_id', $facilitadorId);
            $stmt->bindParam(':asignado_por', $facilitadorId);
            $stmt->execute();
            
            // 3. Confirmar transacción
            $this->db->commit();
            
            return $otecId;
            
        } catch (Exception $e) {
            // Revertir en caso de error
            $this->db->rollBack();
            error_log("Error en createWithFacilitador: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Asignar un facilitador a una OTEC existente
     * 
     * @param int $otecId ID de la OTEC
     * @param int $facilitadorId ID del facilitador
     * @param int $asignadoPor ID de quien asigna
     * @return bool
     */
    public function asignarFacilitador($otecId, $facilitadorId, $asignadoPor) {
        $query = "INSERT INTO otec_facilitadores (otec_id, facilitador_id, asignado_por, activo) 
                  VALUES (:otec_id, :facilitador_id, :asignado_por, 1)
                  ON DUPLICATE KEY UPDATE activo = 1, updated_at = NOW()";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':otec_id', $otecId);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->bindParam(':asignado_por', $asignadoPor);
        return $stmt->execute();
    }
    
    /**
     * Obtener todas las OTEC de un facilitador (incluyendo las que creó)
     * 
     * @param int $facilitadorId ID del facilitador
     * @return array
     */
    public function getOtecByFacilitadorCompleto($facilitadorId) {
        $query = "SELECT o.*, 
                         of.activo as relacion_activa,
                         CASE WHEN o.created_by = :facilitador_id THEN 1 ELSE 0 END as es_creador
                  FROM otec o
                  LEFT JOIN otec_facilitadores ofa ON o.id = ofa.otec_id AND ofa.facilitador_id = :facilitador_id
                  WHERE o.created_by = :facilitador_id OR ofa.facilitador_id = :facilitador_id
                  GROUP BY o.id
                  ORDER BY o.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener OTEC donde un facilitador está asignado o que ha creado
     */
    public function getOtecForFacilitador($facilitadorId) {
        $query = "SELECT DISTINCT o.id, o.nombre, o.rut, o.activo, o.created_by,
                         CASE WHEN o.created_by = :facilitador_id THEN 1 ELSE 0 END as es_creador
                  FROM otec o
                  WHERE o.created_by = :facilitador_id
                  OR EXISTS (
                      SELECT 1 FROM otec_facilitadores ofa 
                      WHERE ofa.otec_id = o.id 
                      AND ofa.facilitador_id = :facilitador_id
                      AND ofa.activo = 1
                  )
                  ORDER BY o.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener todas las OTEC (sin filtro - para administrador)
     */
    public function getAllOtecAdmin() {
        $query = "SELECT o.*, u.nombre as creador_nombre
                  FROM otec o
                  LEFT JOIN users u ON o.created_by = u.id
                  ORDER BY o.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("getAllOtecAdmin() - Registros encontrados: " . count($result));
        return $result;
    }

    /**
     * Buscar OTEC por RUT
     * @param string $rut
     * @return array|false
     */
    public function findByRut($rut) {
        $query = "SELECT * FROM " . $this->table . " WHERE rut = :rut LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':rut', $rut);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verificar si un facilitador ya está asociado a una OTEC
     * @param int $otecId
     * @param int $facilitadorId
     * @return bool
     */
    public function isFacilitadorAsociado($otecId, $facilitadorId) {
        $query = "SELECT id FROM otec_facilitadores 
                  WHERE otec_id = :otec_id AND facilitador_id = :facilitador_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':otec_id', $otecId);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Asociar un facilitador a una OTEC existente
     * @param int $otecId
     * @param int $facilitadorId
     * @return bool
     */
    public function asociarFacilitador($otecId, $facilitadorId) {
        // Evitar duplicados
        if ($this->isFacilitadorAsociado($otecId, $facilitadorId)) {
            return true; // Ya estaba asociado
        }
        $query = "INSERT INTO otec_facilitadores (otec_id, facilitador_id, asignado_por, activo) 
                  VALUES (:otec_id, :facilitador_id, :asignado_por, 1)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':otec_id', $otecId);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->bindParam(':asignado_por', $facilitadorId);
        return $stmt->execute();
    }

    /**
     * Contar OTEC a las que un facilitador está asociado (activo)
     * @param int $facilitadorId
     * @return int
     */
    public function countAssociatedByFacilitador($facilitadorId) {
        $query = "SELECT COUNT(DISTINCT ofa.otec_id) as total 
                  FROM otec_facilitadores ofa
                  WHERE ofa.facilitador_id = :facilitador_id AND ofa.activo = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }
    
}
?>