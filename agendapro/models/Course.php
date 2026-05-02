<?php
// models/Course.php
require_once 'core/Model.php';

class Course extends Model {
    protected $table = 'courses';
    
    public function getAllWithDetails() {
        $query = "SELECT c.*, 
                         COUNT(b.id) as total_capacitaciones
                  FROM " . $this->table . " c
                  LEFT JOIN bookings b ON c.id = b.curso_id
                  GROUP BY c.id
                  ORDER BY c.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getActiveCourses() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE activo = 1 
                  ORDER BY nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Método para el dashboard del ejecutivo - Cursos públicos
    
    public function getPublicCourses($limit = 10) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE publico = 1 AND activo = 1 
                  ORDER BY nombre 
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Método para el dashboard - Cursos más populares
    public function getPopularCourses($limit = 5) {
        $query = "SELECT c.id, c.nombre, c.modalidad, c.duracion_horas,
                         COUNT(b.id) as total_reservas
                  FROM " . $this->table . " c
                  LEFT JOIN bookings b ON c.id = b.curso_id
                  WHERE b.estado IN ('confirmada', 'aprobada', 'finalizada')
                  GROUP BY c.id
                  ORDER BY total_reservas DESC
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
    
    // Método para el dashboard - Estadísticas de cursos
    public function getCourseStats() {
        $query = "SELECT 
                    COUNT(*) as total_cursos,
                    SUM(CASE WHEN modalidad = 'online' THEN 1 ELSE 0 END) as cursos_online,
                    SUM(CASE WHEN modalidad = 'presencial' THEN 1 ELSE 0 END) as cursos_presenciales,
                    SUM(CASE WHEN activo = 1 THEN 1 ELSE 0 END) as cursos_activos
                  FROM " . $this->table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function validateNombre($nombre, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table . " WHERE nombre = :nombre";
        if ($excludeId) {
            $query .= " AND id != :id";
        }
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        if ($excludeId) {
            $stmt->bindParam(':id', $excludeId);
        }
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }
    
    public function getById($id) {
        return $this->find($id);
    }

    /**
     * Obtener cursos para el catálogo del ejecutivo
     * Muestra cursos de facilitadores que trabajan con su OTEC
     */
    public function getCoursesForEjecutivo($otecId, $filtros = []) {
        $query = "SELECT DISTINCT 
                    c.id, 
                    c.nombre, 
                    c.descripcion, 
                    c.modalidad, 
                    c.duracion_horas,
                    c.publico,
                    c.activo,
                    c.created_by,
                    c.imagen,
                    c.descriptor_pdf,
                    u.nombre as facilitador_nombre,
                    COUNT(b.id) as total_reservas
                  FROM courses c
                  LEFT JOIN users u ON c.created_by = u.id
                  LEFT JOIN bookings b ON c.id = b.curso_id
                  WHERE c.activo = 1
                  AND -- Cursos públicos
                      c.publico = 1
                  AND -- Cursos de facilitadores que trabajan con esta OTEC (con o sin reservas)
                      EXISTS (
                          SELECT 1 FROM otec_facilitadores ofa
                          WHERE ofa.facilitador_id = c.created_by 
                          AND ofa.otec_id = :otec_id
                          AND ofa.activo = 1
                      )
                  ";
        
        $params = [':otec_id' => $otecId];
        
        // Aplicar filtros
        if (!empty($filtros['facilitador_id'])) {
            $query .= " AND c.created_by = :facilitador_id";
            $params[':facilitador_id'] = $filtros['facilitador_id'];
        }
        
        if (!empty($filtros['modalidad'])) {
            $query .= " AND c.modalidad = :modalidad";
            $params[':modalidad'] = $filtros['modalidad'];
        }
        
        if (!empty($filtros['buscar'])) {
            $query .= " AND c.nombre LIKE :buscar";
            $params[':buscar'] = '%' . $filtros['buscar'] . '%';
        }
        
        $query .= " GROUP BY c.id ORDER BY c.nombre ASC";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Depuración temporal (eliminar después)
        error_log("=== getCoursesForEjecutivo ===");
        error_log("OTEC ID: " . $otecId);
        error_log("Cursos encontrados: " . count($results));
        
        return $results;
    }
    
    /**
     * Obtener facilitadores que trabajan con una OTEC específica
     */
    public function getFacilitadoresByOtecForCatalog($otecId) {
        $query = "SELECT DISTINCT u.id, u.nombre
                  FROM users u
                  JOIN bookings b ON u.id = b.facilitador_id
                  WHERE b.otec_id = :otec_id AND u.rol = 'facilitador' AND u.activo = 1
                  ORDER BY u.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':otec_id', $otecId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Contar cursos creados por un facilitador específico
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
     * Obtener cursos creados por un facilitador específico
     */
    public function getByFacilitador($facilitadorId, $filtros = []) {
        $query = "SELECT c.*, 
                         COUNT(b.id) as total_reservas
                  FROM " . $this->table . " c
                  LEFT JOIN bookings b ON c.id = b.curso_id
                  WHERE c.created_by = :facilitador_id";
        
        $params = [':facilitador_id' => $facilitadorId];
        
        if (!empty($filtros['activo'])) {
            $query .= " AND c.activo = :activo";
            $params[':activo'] = $filtros['activo'];
        }
        
        if (!empty($filtros['buscar'])) {
            $query .= " AND c.nombre LIKE :buscar";
            $params[':buscar'] = '%' . $filtros['buscar'] . '%';
        }
        
        $query .= " GROUP BY c.id ORDER BY c.nombre ASC";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener cursos más populares de un facilitador específico
     */
    public function getPopularByFacilitador($facilitadorId, $limit = 5) {
        $query = "SELECT c.id, c.nombre, c.modalidad, c.duracion_horas,
                         COUNT(b.id) as total_reservas
                  FROM " . $this->table . " c
                  LEFT JOIN bookings b ON c.id = b.curso_id
                  WHERE c.created_by = :facilitador_id
                    AND b.estado IN ('confirmada', 'aprobada', 'finalizada')
                  GROUP BY c.id
                  ORDER BY total_reservas DESC
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verificar si un curso pertenece a un facilitador
     */
    public function isOwnedByFacilitador($cursoId, $facilitadorId) {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE id = :curso_id AND created_by = :facilitador_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':curso_id', $cursoId);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Crear curso y automáticamente asociar al facilitador creador
     * 
     * @param array $data Datos del curso
     * @param int $facilitadorId ID del facilitador que lo crea
     * @return int|false ID del curso creado o false si falla
     */
    public function createWithFacilitador($data, $facilitadorId) {
        $data['created_by'] = $facilitadorId;
        return $this->create($data);
    }
    
    /**
     * Obtener cursos de un facilitador (los que creó)
     * 
     * @param int $facilitadorId ID del facilitador
     * @return array
     */
    public function getByFacilitadorCompleto($facilitadorId) {
        $query = "SELECT c.*, 
                         COUNT(b.id) as total_reservas
                  FROM " . $this->table . " c
                  LEFT JOIN bookings b ON c.id = b.curso_id
                  WHERE c.created_by = :facilitador_id
                  GROUP BY c.id
                  ORDER BY c.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener cursos creados por un facilitador
     * 
     * @param int $facilitadorId ID del facilitador
     * @param bool $soloActivos Si solo cursos activos
     * @return array
     */
     /*
    public function getCoursesForFacilitador($facilitadorId, $soloActivos = true) {
        $query = "SELECT c.id, c.nombre, c.descripcion, c.modalidad, 
                         c.duracion_horas, c.publico, c.activo
                  FROM courses c
                  WHERE c.created_by = :facilitador_id";
        
        if ($soloActivos) {
            $query .= " AND c.activo = 1";
        }
        
        $query .= " ORDER BY c.nombre";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    */
    // models/Course.php - Agregar estos métodos
    /**
     * Obtener cursos por OTEC
     */
    public function getCoursesByOtec($otecId) {
        $query = "SELECT c.*, o.nombre as otec_nombre
                  FROM courses c
                  LEFT JOIN otec o ON c.otec_id = o.id
                  WHERE c.otec_id = :otec_id AND c.activo = 1
                  ORDER BY c.nombre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':otec_id', $otecId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener curso por ID con detalles de OTEC
     */
    public function getCourseWithDetails($id) {
        $query = "SELECT c.*, o.nombre as otec_nombre, o.id as otec_id
                  FROM courses c
                  LEFT JOIN otec o ON c.otec_id = o.id
                  WHERE c.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener cursos públicos (para ejecutivos)
     */
     /*
    public function getPublicCourses() {
        $query = "SELECT c.*, o.nombre as otec_nombre
                  FROM courses c
                  LEFT JOIN otec o ON c.otec_id = o.id
                  WHERE c.activo = 1
                  ORDER BY c.nombre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    */
    /**
     * Obtener cursos que puede impartir un facilitador
     */
    public function getCoursesForFacilitador($facilitadorId) {
        $query = "SELECT DISTINCT c.*, o.nombre as otec_nombre
                  FROM courses c
                  JOIN otec_facilitadores ofa ON c.created_by = ofa.facilitador_id
                  JOIN otec o ON ofa.otec_id = o.id
                  WHERE c.created_by = :facilitador_id AND c.activo = 1
                  ORDER BY c.nombre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener todos los cursos (sin filtro - para administrador)
     */
    public function getAllCoursesAdmin() {
        $query = "SELECT c.*, 
                         u.nombre as creador_nombre,
                         COUNT(b.id) as total_reservas
                  FROM " . $this->table . " c
                  LEFT JOIN users u ON c.created_by = u.id
                  LEFT JOIN bookings b ON c.id = b.curso_id
                  GROUP BY c.id
                  ORDER BY c.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 

    /**
     * Contar cursos por modalidad
     */
    public function countByModalidad($modalidad) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE modalidad = :modalidad";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':modalidad', $modalidad);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // models/Course.php
    // Agregar este método al final de la clase
    
    /**
     * Obtener cursos activos con el nombre del facilitador que los creó
     * @return array
     */
    public function getActiveCoursesWithFacilitador() {
        $query = "SELECT c.*, u.nombre as facilitador_nombre 
                  FROM " . $this->table . " c
                  LEFT JOIN users u ON c.created_by = u.id
                  WHERE c.activo = 1 
                  ORDER BY c.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener cursos públicos con nombre del facilitador
     * @return array
     */
    public function getPublicCoursesWithFacilitador() {
        $query = "SELECT c.*, u.nombre as facilitador_nombre 
                  FROM " . $this->table . " c
                  LEFT JOIN users u ON c.created_by = u.id
                  WHERE c.activo = 1 AND c.publico = 1
                  ORDER BY c.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>