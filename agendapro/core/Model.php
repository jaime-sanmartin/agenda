<?php
// core/Model.php
abstract class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
        
        // Asegurar UTF-8 en la conexiиоn
        $this->db->exec("SET NAMES utf8mb4");
        $this->db->exec("SET CHARACTER SET utf8mb4");
    }

    /**
     * Asegurar que los strings sean UTF-8 vивlido (sin eliminar acentos)
     */
    protected function ensureUtf8($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->ensureUtf8($value);
            }
            return $data;
        }
        
        if (is_string($data) && !mb_check_encoding($data, 'UTF-8')) {
            // Solo convertir si no es UTF-8 vивlido
            $data = mb_convert_encoding($data, 'UTF-8', 'auto');
        }
        
        return $data;
    }

    public function all($orderBy = null, $limit = null) {
        $query = "SELECT * FROM " . $this->table;
        if ($orderBy) $query .= " ORDER BY " . $orderBy;
        if ($limit) $query .= " LIMIT " . (int)$limit;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $this->ensureUtf8($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function find($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE " . $this->primaryKey . " = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $this->ensureUtf8($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public function where($conditions, $orderBy = null, $limit = null) {
        $query = "SELECT * FROM " . $this->table;
        $where = [];
        $params = [];

        foreach ($conditions as $key => $value) {
            $where[] = $key . " = :" . $key;
            $params[':' . $key] = $value;
        }

        if (!empty($where)) $query .= " WHERE " . implode(' AND ', $where);
        if ($orderBy) $query .= " ORDER BY " . $orderBy;
        if ($limit) $query .= " LIMIT " . (int)$limit;

        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) $stmt->bindValue($key, $value);
        $stmt->execute();
        return $this->ensureUtf8($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function create($data) {
        $fields = array_keys($data);
        $placeholders = ':' . implode(', :', $fields);
        $query = "INSERT INTO " . $this->table . " (" . implode(',', $fields) . ") VALUES (" . $placeholders . ")";
        $stmt = $this->db->prepare($query);
        foreach ($data as $key => $value) $stmt->bindValue(':' . $key, $value);
        return $stmt->execute() ? $this->db->lastInsertId() : false;
    }

    public function update($id, $data) {
        $fields = [];
        foreach ($data as $key => $value) $fields[] = $key . " = :" . $key;
        $query = "UPDATE " . $this->table . " SET " . implode(',', $fields) . " WHERE " . $this->primaryKey . " = :id";
        $stmt = $this->db->prepare($query);
        foreach ($data as $key => $value) $stmt->bindValue(':' . $key, $value);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE " . $this->primaryKey . " = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function count($conditions = []) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) $where[] = $key . " = :" . $key;
            $query .= " WHERE " . implode(' AND ', $where);
        }
        $stmt = $this->db->prepare($query);
        foreach ($conditions as $key => $value) $stmt->bindValue(':' . $key, $value);
        $stmt->execute();
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>