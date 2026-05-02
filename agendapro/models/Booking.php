<?php
// models/Booking.php
require_once 'core/Model.php';

class Booking extends Model {
    protected $table = 'bookings';
    
    // =====================================================
    // CONSTANTES DE ESTADOS UNIFICADOS
    // =====================================================
    // Estados de Reservas
    const STATUS_PENDING = 'pendiente';
    const STATUS_APPROVED = 'aprobada';
    const STATUS_REJECTED = 'rechazada';
    const STATUS_CANCELLED = 'anulada';
    
    // Estados de Sesiones
    const SESSION_PENDING = 'pendiente';
    const SESSION_COMPLETED = 'realizada';
    const SESSION_SUSPENDED = 'suspendida';
    const SESSION_DELETED = 'eliminada';  // Borrado lógico
    
    // Acciones de log
    const LOG_ACTION_CREATE = 'crear';
    const LOG_ACTION_SUSPEND = 'suspender';
    const LOG_ACTION_REAGENDAR = 'reagendar';
    const LOG_ACTION_DELETE = 'eliminar';
    const LOG_ACTION_REACTIVATE = 'reactivar';
    
    /**
     * Obtener eventos en rango con filtros (solo NO eliminadas)
     */
    public function getEventsInRange($start, $end, $userRol = null, $otecId = null, $facilitadorId = null) {
        $query = "SELECT b.*, 
                         c.nombre as curso_nombre, 
                         c.modalidad as curso_modalidad,
                         c.duracion_horas,
                         o.nombre as otec_nombre,
                         u.nombre as created_by_nombre,
                         f.nombre as facilitador_nombre
                  FROM " . $this->table . " b
                  LEFT JOIN courses c ON b.curso_id = c.id
                  LEFT JOIN otec o ON b.otec_id = o.id
                  LEFT JOIN users u ON b.created_by = u.id
                  LEFT JOIN users f ON b.facilitador_id = f.id
                  WHERE b.fecha_inicio <= :end AND b.fecha_fin >= :start";
        
        $params = [':start' => $start, ':end' => $end];
        
        if ($facilitadorId) {
            $query .= " AND b.facilitador_id = :facilitador_id";
            $params[':facilitador_id'] = $facilitadorId;
        }
        elseif ($userRol === 'ejecutivo' && $otecId) {
            $query .= " AND b.otec_id = :otec_id";
            $params[':otec_id'] = $otecId;
        }
        elseif ($userRol === 'facilitador' && isset($_SESSION['user_id'])) {
            $query .= " AND b.facilitador_id = :facilitador_user_id";
            $params[':facilitador_user_id'] = $_SESSION['user_id'];
        }
        
        // Solo mostrar pendiente + aprobada en calendario
        $query .= " AND b.estado IN (:estado_pendiente, :estado_aprobada)";
        $params[':estado_pendiente'] = self::STATUS_PENDING;
        $params[':estado_aprobada'] = self::STATUS_APPROVED;
        
        $query .= " ORDER BY b.fecha_inicio ASC";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($results as &$row) {
            $row['es_mi_otec'] = ($row['otec_id'] == $otecId);
        }
        
        return $results;
    }
    
    /**
     * Obtener todas las sesiones (eventos) para el calendario
     * Excluye sesiones eliminadas lógicamente
     */
    public function getSessionEventsInRange($start, $end, $userRol = null, $otecId = null, $facilitadorId = null) {
        $query = "SELECT s.*, 
                         b.id as booking_id,
                         b.curso_id,
                         b.otec_id,
                         b.estado as booking_estado,
                         b.valor_acordado,
                         b.notas as booking_notas,
                         b.tipo_calendario,
                         c.nombre as curso_nombre,
                         c.modalidad as curso_modalidad,
                         c.duracion_horas as curso_duracion,
                         o.nombre as otec_nombre,
                         u.nombre as created_by_nombre,
                         f.nombre as facilitador_nombre
                  FROM sessions s
                  INNER JOIN bookings b ON s.booking_id = b.id
                  LEFT JOIN courses c ON b.curso_id = c.id
                  LEFT JOIN otec o ON b.otec_id = o.id
                  LEFT JOIN users u ON b.created_by = u.id
                  LEFT JOIN users f ON b.facilitador_id = f.id
                  WHERE s.fecha_inicio <= :end AND s.fecha_fin >= :start
                  AND s.eliminada = 0";  // Excluir eliminadas lógicamente
        
        $params = [':start' => $start, ':end' => $end];
        
        if ($facilitadorId) {
            $query .= " AND b.facilitador_id = :facilitador_id";
            $params[':facilitador_id'] = $facilitadorId;
        }
        elseif ($userRol === 'ejecutivo' && $otecId) {
            $query .= " AND b.otec_id = :otec_id";
            $params[':otec_id'] = $otecId;
        }
        
        // Excluir sesiones de reservas rechazadas o anuladas
        $query .= " AND b.estado NOT IN (:estado_rechazada, :estado_anulada)";
        $params[':estado_rechazada'] = self::STATUS_REJECTED;
        $params[':estado_anulada'] = self::STATUS_CANCELLED;
        
        $query .= " ORDER BY s.fecha_inicio ASC";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crear reserva
     */
    public function create($data) {
        $fields = array_keys($data);
        $placeholders = ':' . implode(', :', $fields);
        
        $query = "INSERT INTO " . $this->table . "
                  (" . implode(',', $fields) . ")
                  VALUES (" . $placeholders . ")";
        
        $stmt = $this->db->prepare($query);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        error_log("Error al crear reserva: " . print_r($stmt->errorInfo(), true));
        return false;
    }
    
    /**
     * Generar sesiones para un curso continuo (una sesión por cada día)
     */
    public function generarSesionesContinuas($fechaInicio, $fechaFin) {
        $sessions = [];
        
        $startDateTime = new DateTime($fechaInicio);
        $endDateTime = new DateTime($fechaFin);
        
        if ($endDateTime <= $startDateTime) {
            error_log("Error: fecha_fin debe ser posterior a fecha_inicio");
            return [];
        }
        
        $horaInicio = $startDateTime->format('H:i:s');
        $horaFin = $endDateTime->format('H:i:s');
        
        $currentDate = clone $startDateTime;
        $currentDate->setTime(0, 0, 0);
        
        $endDate = clone $endDateTime;
        $endDate->setTime(0, 0, 0);
        
        $numeroSesion = 1;
        
        while ($currentDate <= $endDate) {
            $sesionInicio = clone $currentDate;
            $sesionInicio->setTime(
                (int)$startDateTime->format('H'),
                (int)$startDateTime->format('i'),
                (int)$startDateTime->format('s')
            );
            
            $sesionFin = clone $currentDate;
            $sesionFin->setTime(
                (int)$endDateTime->format('H'),
                (int)$endDateTime->format('i'),
                (int)$endDateTime->format('s')
            );
            
            $sessions[] = [
                'fecha_inicio' => $sesionInicio->format('Y-m-d H:i:s'),
                'fecha_fin' => $sesionFin->format('Y-m-d H:i:s'),
                'numero_sesion' => $numeroSesion
            ];
            
            $numeroSesion++;
            $currentDate->modify('+1 day');
        }
        
        return $sessions;
    }
    
    /**
     * Generar sesiones para un curso por días específicos
     */
    public function generarSesionesPorDias($fechaInicioCurso, $totalHoras, $diasSemana, $horaInicio, $duracionSesion, $fechaFinOpcional = null) {
        $sessions = [];
        $horasRestantes = $totalHoras;
        $currentDate = new DateTime($fechaInicioCurso);
        $numeroSesion = 1;
        
        if ($fechaFinOpcional) {
            $endDate = new DateTime($fechaFinOpcional);
        } else {
            $sesionesPorSemana = count($diasSemana);
            $semanasNecesarias = ceil($totalHoras / ($sesionesPorSemana * $duracionSesion));
            $endDate = clone $currentDate;
            $endDate->modify("+{$semanasNecesarias} weeks");
        }
        
        while ($horasRestantes > 0 && $currentDate <= $endDate) {
            $diaSemana = (int)$currentDate->format('N');
            
            if (in_array($diaSemana, $diasSemana)) {
                list($hora, $minuto) = explode(':', $horaInicio);
                $fechaInicioSession = clone $currentDate;
                $fechaInicioSession->setTime((int)$hora, (int)$minuto);
                
                $fechaFinSession = clone $fechaInicioSession;
                $fechaFinSession->modify("+{$duracionSesion} hours");
                
                $sessions[] = [
                    'fecha_inicio' => $fechaInicioSession->format('Y-m-d H:i:s'),
                    'fecha_fin' => $fechaFinSession->format('Y-m-d H:i:s'),
                    'numero_sesion' => $numeroSesion
                ];
                
                $horasRestantes -= $duracionSesion;
                $numeroSesion++;
            }
            $currentDate->modify('+1 day');
        }
        
        return $sessions;
    }
    
    /**
     * Crear sesiones para una reserva (con log)
     */
    public function createSessions($bookingId, $sessions) {
        if (empty($sessions)) {
            error_log("No hay sesiones para crear en booking_id: " . $bookingId);
            return false;
        }
        
        $query = "INSERT INTO sessions (booking_id, fecha_inicio, fecha_fin, estado, numero_sesion, eliminada) 
                  VALUES (:booking_id, :fecha_inicio, :fecha_fin, :estado_pendiente, :numero_sesion, 0)";
        $stmt = $this->db->prepare($query);
        
        foreach ($sessions as $session) {
            $numero = $session['numero_sesion'] ?? 1;
            
            $stmt->bindParam(':booking_id', $bookingId);
            $stmt->bindParam(':fecha_inicio', $session['fecha_inicio']);
            $stmt->bindParam(':fecha_fin', $session['fecha_fin']);
            $stmt->bindValue(':estado_pendiente', self::SESSION_PENDING);
            $stmt->bindParam(':numero_sesion', $numero);
            $stmt->execute();
            
            $sessionId = $this->db->lastInsertId();
            $this->registrarLogSesion($sessionId, $_SESSION['user_id'], self::LOG_ACTION_CREATE, null, json_encode($session), "Sesiones creadas al generar reserva");
        }
        
        return true;
    }
    
    /**
     * Obtener todas las sesiones de una reserva (no eliminadas)
     */
    public function getSessions($bookingId) {
        $query = "SELECT * FROM sessions WHERE booking_id = :booking_id AND eliminada = 0 ORDER BY fecha_inicio ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':booking_id', $bookingId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener una sesión por ID
     */
    public function getSessionById($sessionId) {
        $query = "SELECT s.*, b.curso_id, b.otec_id, b.estado as booking_estado,
                         c.nombre as curso_nombre, o.nombre as otec_nombre,
                         u.nombre as facilitador_nombre
                  FROM sessions s
                  LEFT JOIN bookings b ON s.booking_id = b.id
                  LEFT JOIN courses c ON b.curso_id = c.id
                  LEFT JOIN otec o ON b.otec_id = o.id
                  LEFT JOIN users u ON b.facilitador_id = u.id
                  WHERE s.id = :id AND s.eliminada = 0";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $sessionId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Suspender una sesión (con log)
     */
    public function suspenderSesion($sessionId, $motivo = null) {
        // Obtener estado anterior
        $sessionActual = $this->getSessionById($sessionId);
        if (!$sessionActual) {
            return false;
        }
        
        $valorAnterior = json_encode([
            'estado' => $sessionActual['estado'],
            'fecha_inicio' => $sessionActual['fecha_inicio'],
            'fecha_fin' => $sessionActual['fecha_fin']
        ]);
        
        $query = "UPDATE sessions 
                  SET estado = :estado, 
                      updated_at = NOW()
                  WHERE id = :id AND eliminada = 0";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':estado' => self::SESSION_SUSPENDED,
            ':id' => $sessionId
        ]);
        
        if ($result) {
            $valorNuevo = json_encode(['estado' => self::SESSION_SUSPENDED]);
            $this->registrarLogSesion($sessionId, $_SESSION['user_id'], self::LOG_ACTION_SUSPEND, $valorAnterior, $valorNuevo, $motivo);
        }
        
        return $result;
    }
    
    /**
     * Reagendar una sesión (con log)
     */
    public function reagendarSesion($sessionId, $nuevaFechaInicio, $nuevaFechaFin, $motivo = null) {
        // Obtener estado anterior
        $sessionActual = $this->getSessionById($sessionId);
        if (!$sessionActual) {
            return false;
        }
        
        $valorAnterior = json_encode([
            'fecha_inicio' => $sessionActual['fecha_inicio'],
            'fecha_fin' => $sessionActual['fecha_fin'],
            'estado' => $sessionActual['estado']
        ]);
        
        $query = "UPDATE sessions 
                  SET fecha_inicio = :fecha_inicio, 
                      fecha_fin = :fecha_fin, 
                      estado = :estado,
                      updated_at = NOW()
                  WHERE id = :id AND eliminada = 0";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':fecha_inicio' => $nuevaFechaInicio,
            ':fecha_fin' => $nuevaFechaFin,
            ':estado' => self::SESSION_PENDING,
            ':id' => $sessionId
        ]);
        
        if ($result) {
            $valorNuevo = json_encode([
                'fecha_inicio' => $nuevaFechaInicio,
                'fecha_fin' => $nuevaFechaFin,
                'estado' => self::SESSION_PENDING
            ]);
            $this->registrarLogSesion($sessionId, $_SESSION['user_id'], self::LOG_ACTION_REAGENDAR, $valorAnterior, $valorNuevo, $motivo);
        }
        
        return $result;
    }
    
    /**
     * Eliminar lógicamente sesiones de una reserva (por rechazo o anulación)
     */
    public function eliminarSesionesByBookingId($bookingId, $motivo = null, $soloFuturas = false) {
        $fechaActual = date('Y-m-d H:i:s');
        
        if ($soloFuturas) {
            // Solo sesiones con fecha futura
            $query = "UPDATE sessions 
                      SET eliminada = 1, 
                          estado = :estado_eliminada,
                          updated_at = NOW()
                      WHERE booking_id = :booking_id 
                      AND fecha_inicio > :fecha_actual
                      AND eliminada = 0";
            $params = [
                ':estado_eliminada' => self::SESSION_DELETED,
                ':booking_id' => $bookingId,
                ':fecha_actual' => $fechaActual
            ];
        } else {
            // Todas las sesiones
            $query = "UPDATE sessions 
                      SET eliminada = 1, 
                          estado = :estado_eliminada,
                          updated_at = NOW()
                      WHERE booking_id = :booking_id AND eliminada = 0";
            $params = [
                ':estado_eliminada' => self::SESSION_DELETED,
                ':booking_id' => $bookingId
            ];
        }
        
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute($params);
        
        if ($result) {
            // Registrar log para cada sesión afectada
            $sesiones = $this->getSessions($bookingId); // Esto solo trae no eliminadas
            foreach ($sesiones as $sesion) {
                if ($soloFuturas && strtotime($sesion['fecha_inicio']) <= strtotime($fechaActual)) {
                    continue;
                }
                $this->registrarLogSesion($sesion['id'], $_SESSION['user_id'], self::LOG_ACTION_DELETE, 
                    json_encode(['estado' => $sesion['estado']]), 
                    json_encode(['estado' => self::SESSION_DELETED]), 
                    $motivo);
            }
        }
        
        return $result;
    }
    
    /**
     * Registrar log de sesión
     */
    private function registrarLogSesion($sesionId, $usuarioId, $accion, $valorAnterior, $valorNuevo, $motivo = null) {
        $query = "INSERT INTO sesiones_log (sesion_id, usuario_id, accion, valor_anterior, valor_nuevo, motivo) 
                  VALUES (:sesion_id, :usuario_id, :accion, :valor_anterior, :valor_nuevo, :motivo)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':sesion_id' => $sesionId,
            ':usuario_id' => $usuarioId,
            ':accion' => $accion,
            ':valor_anterior' => $valorAnterior,
            ':valor_nuevo' => $valorNuevo,
            ':motivo' => $motivo
        ]);
    }
    
    /**
     * Actualizar estado de reserva
     */
    public function updateReservaStatus($reservaId, $nuevoEstado) {
        $estadosValidos = [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
            self::STATUS_CANCELLED
        ];
        
        if (!in_array($nuevoEstado, $estadosValidos)) {
            return false;
        }
        
        $query = "UPDATE " . $this->table . " 
                  SET estado = :estado, updated_at = NOW() 
                  WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':estado' => $nuevoEstado,
            ':id' => $reservaId
        ]);
    }
    
    /**
     * Obtener conteo de reservas por estado
     */
    public function getReservasCountByStatus($otecId = null, $facilitadorId = null) {
        $query = "SELECT estado, COUNT(*) as total FROM " . $this->table . " WHERE 1=1";
        $params = [];
        
        if ($otecId) {
            $query .= " AND otec_id = :otec_id";
            $params[':otec_id'] = $otecId;
        }
        
        if ($facilitadorId) {
            $query .= " AND facilitador_id = :facilitador_id";
            $params[':facilitador_id'] = $facilitadorId;
        }
        
        $query .= " GROUP BY estado";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        $counts = [
            self::STATUS_PENDING => 0,
            self::STATUS_APPROVED => 0,
            self::STATUS_REJECTED => 0,
            self::STATUS_CANCELLED => 0
        ];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (isset($counts[$row['estado']])) {
                $counts[$row['estado']] = (int)$row['total'];
            }
        }
        
        return $counts;
    }
    
    /**
     * Obtener reservas por estado con detalles
     */
    public function getReservasByStatusWithDetails($estado, $otecId = null, $facilitadorId = null) {
        $query = "SELECT b.*, 
                         c.nombre as curso_nombre, 
                         c.modalidad as curso_modalidad,
                         c.duracion_horas,
                         o.nombre as otec_nombre,
                         u.nombre as created_by_nombre,
                         u.email as created_by_email,
                         f.nombre as facilitador_nombre
                  FROM " . $this->table . " b
                  LEFT JOIN courses c ON b.curso_id = c.id
                  LEFT JOIN otec o ON b.otec_id = o.id
                  LEFT JOIN users u ON b.created_by = u.id
                  LEFT JOIN users f ON b.facilitador_id = f.id
                  WHERE b.estado = :estado";
        
        $params = [':estado' => $estado];
        
        if ($otecId) {
            $query .= " AND b.otec_id = :otec_id";
            $params[':otec_id'] = $otecId;
        }
        
        if ($facilitadorId) {
            $query .= " AND b.facilitador_id = :facilitador_id";
            $params[':facilitador_id'] = $facilitadorId;
        }
        
        $query .= " ORDER BY b.fecha_inicio DESC";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener todas las reservas con detalles
     */
    public function getAllWithDetails($filters = []) {
        $query = "SELECT b.*, 
                         c.nombre as curso_nombre, 
                         c.modalidad as curso_modalidad,
                         c.duracion_horas,
                         o.nombre as otec_nombre,
                         u.nombre as created_by_nombre,
                         f.nombre as facilitador_nombre
                  FROM " . $this->table . " b
                  LEFT JOIN courses c ON b.curso_id = c.id
                  LEFT JOIN otec o ON b.otec_id = o.id
                  LEFT JOIN users u ON b.created_by = u.id
                  LEFT JOIN users f ON b.facilitador_id = f.id
                  WHERE 1=1";
        
        $params = [];
        
        if (isset($filters['otec_id']) && $filters['otec_id']) {
            $query .= " AND b.otec_id = :otec_id";
            $params[':otec_id'] = $filters['otec_id'];
        }
        
        if (isset($filters['estado']) && $filters['estado']) {
            $query .= " AND b.estado = :estado";
            $params[':estado'] = $filters['estado'];
        }
        
        if (isset($filters['facilitador_id']) && $filters['facilitador_id']) {
            $query .= " AND b.facilitador_id = :facilitador_id";
            $params[':facilitador_id'] = $filters['facilitador_id'];
        }
        
        $query .= " ORDER BY b.fecha_inicio DESC";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener eventos por facilitador
     */
    public function getEventsByFacilitador($start, $end, $facilitadorId) {
        $query = "SELECT b.*, 
                         c.nombre as curso_nombre, 
                         c.modalidad as curso_modalidad,
                         c.duracion_horas,
                         o.nombre as otec_nombre,
                         u.nombre as created_by_nombre,
                         f.nombre as facilitador_nombre
                  FROM " . $this->table . " b
                  LEFT JOIN courses c ON b.curso_id = c.id
                  LEFT JOIN otec o ON b.otec_id = o.id
                  LEFT JOIN users u ON b.created_by = u.id
                  LEFT JOIN users f ON b.facilitador_id = f.id
                  WHERE b.fecha_inicio <= :end 
                    AND b.fecha_fin >= :start
                    AND b.facilitador_id = :facilitador_id
                  ORDER BY b.fecha_inicio ASC";
        
        $params = [
            ':start' => $start,
            ':end' => $end,
            ':facilitador_id' => $facilitadorId
        ];
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($results as &$row) {
            $row['es_mi_otec'] = true;
        }
        
        return $results;
    }
    
    /**
     * Encontrar una reserva por ID
     */
    public function find($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Actualizar una reserva
     */
    public function update($id, $data) {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        
        $query = "UPDATE " . $this->table . " 
                  SET " . implode(', ', $fields) . ", updated_at = NOW()
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $data[':id'] = $id;
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Actualizar estado de una reserva (alias)
     */
    public function updateStatus($id, $status, $facilitadorId = null) {
        $data = ['estado' => $status];
        if ($facilitadorId !== null) {
            $data['facilitador_id'] = $facilitadorId;
        }
        return $this->update($id, $data);
    }
    
    /**
     * Obtener reservas de cursos creados por un facilitador
     */
    public function getByFacilitadorCourses($facilitadorId, $filtros = []) {
        $query = "SELECT b.*, 
                         c.nombre as curso_nombre,
                         c.modalidad as curso_modalidad,
                         o.nombre as otec_nombre,
                         u.nombre as created_by_nombre
                  FROM " . $this->table . " b
                  INNER JOIN courses c ON b.curso_id = c.id
                  LEFT JOIN otec o ON b.otec_id = o.id
                  LEFT JOIN users u ON b.created_by = u.id
                  WHERE c.created_by = :facilitador_id";
        
        $params = [':facilitador_id' => $facilitadorId];
        
        if (!empty($filtros['estado'])) {
            $query .= " AND b.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }
        
        if (!empty($filtros['otec_id'])) {
            $query .= " AND b.otec_id = :otec_id";
            $params[':otec_id'] = $filtros['otec_id'];
        }
        
        $query .= " ORDER BY b.fecha_inicio DESC";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Contar reservas de cursos creados por un facilitador
     */
    public function countByFacilitadorCourses($facilitadorId, $estado = null) {
        $query = "SELECT COUNT(*) as total 
                  FROM " . $this->table . " b
                  INNER JOIN courses c ON b.curso_id = c.id
                  WHERE c.created_by = :facilitador_id";
        $params = [':facilitador_id' => $facilitadorId];
        
        if ($estado) {
            $query .= " AND b.estado = :estado";
            $params[':estado'] = $estado;
        }
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    

    /**
     * Contar reservas pendientes de cursos del facilitador
     */
    public function countPendingByFacilitador($facilitadorId) {
        return $this->countByFacilitadorCourses($facilitadorId, 'pendiente');
    }

    /**
     * Obtener OTEC con las que trabaja un facilitador
     */
    public function getOtecByFacilitador($facilitadorId) {
        $query = "SELECT DISTINCT o.id, o.nombre, o.rut,
                         COUNT(b.id) as total_reservas
                  FROM " . $this->table . " b
                  INNER JOIN otec o ON b.otec_id = o.id
                  WHERE b.facilitador_id = :facilitador_id
                  GROUP BY o.id
                  ORDER BY total_reservas DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener cursos que imparte un facilitador
     */
    public function getCoursesByFacilitador($facilitadorId) {
        $query = "SELECT DISTINCT c.id, c.nombre, c.modalidad, c.duracion_horas,
                         COUNT(b.id) as total_veces
                  FROM " . $this->table . " b
                  INNER JOIN courses c ON b.curso_id = c.id
                  WHERE b.facilitador_id = :facilitador_id
                  GROUP BY c.id
                  ORDER BY total_veces DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Estadísticas por período para un facilitador específico
     */
    public function getStatsByPeriodForFacilitador($facilitadorId, $period = 'month') {
        if ($period == 'month') {
            $query = "SELECT 
                        DATE_FORMAT(b.fecha_inicio, '%Y-%m') as periodo,
                        COUNT(*) as total
                      FROM " . $this->table . " b
                      WHERE b.facilitador_id = :facilitador_id
                        AND b.fecha_inicio >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                      GROUP BY DATE_FORMAT(b.fecha_inicio, '%Y-%m')
                      ORDER BY periodo ASC";
        } else {
            $query = "SELECT 
                        DATE(b.fecha_inicio) as periodo,
                        COUNT(*) as total
                      FROM " . $this->table . " b
                      WHERE b.facilitador_id = :facilitador_id
                        AND b.fecha_inicio >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                      GROUP BY DATE(b.fecha_inicio)
                      ORDER BY periodo ASC";
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Próximas reservas de un facilitador
     */
    public function getUpcomingByFacilitador($facilitadorId, $limit = 10) {
        $query = "SELECT b.*, 
                         c.nombre as curso_nombre,
                         o.nombre as otec_nombre,
                         u.nombre as created_by_nombre
                  FROM " . $this->table . " b
                  LEFT JOIN courses c ON b.curso_id = c.id
                  LEFT JOIN otec o ON b.otec_id = o.id
                  LEFT JOIN users u ON b.created_by = u.id
                  WHERE b.facilitador_id = :facilitador_id
                    AND b.fecha_inicio >= CURDATE()
                    AND b.estado IN ('confirmada', 'aprobada', 'pendiente')
                  ORDER BY b.fecha_inicio ASC
                  LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Reservas recientes de un facilitador
     */
    public function getRecentByFacilitador($facilitadorId, $limit = 10) {
        $query = "SELECT b.*, 
                         c.nombre as curso_nombre,
                         o.nombre as otec_nombre,
                         u.nombre as created_by_nombre
                  FROM " . $this->table . " b
                  LEFT JOIN courses c ON b.curso_id = c.id
                  LEFT JOIN otec o ON b.otec_id = o.id
                  LEFT JOIN users u ON b.created_by = u.id
                  WHERE b.facilitador_id = :facilitador_id
                  ORDER BY b.created_at DESC
                  LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':facilitador_id', $facilitadorId);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener todas las reservas (sin filtro - para administrador)
     */
    public function getAllBookingsAdmin($filtros = []) {
        $query = "SELECT b.*, 
                         c.nombre as curso_nombre, 
                         c.modalidad as curso_modalidad,
                         c.duracion_horas,
                         o.nombre as otec_nombre,
                         u.nombre as created_by_nombre,
                         f.nombre as facilitador_nombre
                  FROM " . $this->table . " b
                  LEFT JOIN courses c ON b.curso_id = c.id
                  LEFT JOIN otec o ON b.otec_id = o.id
                  LEFT JOIN users u ON b.created_by = u.id
                  LEFT JOIN users f ON b.facilitador_id = f.id
                  WHERE 1=1";
        
        $params = [];
        
        if (!empty($filtros['estado'])) {
            $query .= " AND b.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }
        
        if (!empty($filtros['otec_id'])) {
            $query .= " AND b.otec_id = :otec_id";
            $params[':otec_id'] = $filtros['otec_id'];
        }
        
        $query .= " ORDER BY b.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>