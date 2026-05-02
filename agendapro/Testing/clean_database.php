<?php
// clean_database.php - Ejecutar UNA SOLA VEZ para limpiar datos corruptos
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';

echo "<h1>Limpiando base de datos...</h1>";

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Función para limpiar texto
    function cleanText($text) {
        if ($text === null) return '';
        if (!is_string($text)) return $text;
        
        // Eliminar caracteres nulos
        $text = str_replace("\0", '', $text);
        // Eliminar caracteres de control no deseados
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        // Convertir a UTF-8 válido
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        // Eliminar caracteres inválidos
        $text = preg_replace('/[^\P{C}]+/u', '', $text);
        
        return trim($text);
    }
    
    // Limpiar cursos
    $courses = $conn->query("SELECT id, nombre, descripcion FROM courses");
    while ($row = $courses->fetch(PDO::FETCH_ASSOC)) {
        $cleanNombre = cleanText($row['nombre']);
        $cleanDescripcion = cleanText($row['descripcion']);
        
        if ($cleanNombre != $row['nombre'] || $cleanDescripcion != $row['descripcion']) {
            $stmt = $conn->prepare("UPDATE courses SET nombre = :nombre, descripcion = :descripcion WHERE id = :id");
            $stmt->execute([
                ':nombre' => $cleanNombre,
                ':descripcion' => $cleanDescripcion,
                ':id' => $row['id']
            ]);
            echo "Curso ID {$row['id']} limpiado<br>";
        }
    }
    
    // Limpiar OTEC
    $otecs = $conn->query("SELECT id, nombre, direccion, contacto FROM otec");
    while ($row = $otecs->fetch(PDO::FETCH_ASSOC)) {
        $cleanNombre = cleanText($row['nombre']);
        $cleanDireccion = cleanText($row['direccion']);
        $cleanContacto = cleanText($row['contacto']);
        
        if ($cleanNombre != $row['nombre'] || $cleanDireccion != $row['direccion'] || $cleanContacto != $row['contacto']) {
            $stmt = $conn->prepare("UPDATE otec SET nombre = :nombre, direccion = :direccion, contacto = :contacto WHERE id = :id");
            $stmt->execute([
                ':nombre' => $cleanNombre,
                ':direccion' => $cleanDireccion,
                ':contacto' => $cleanContacto,
                ':id' => $row['id']
            ]);
            echo "OTEC ID {$row['id']} limpiado<br>";
        }
    }
    
    // Limpiar usuarios
    $users = $conn->query("SELECT id, nombre FROM users");
    while ($row = $users->fetch(PDO::FETCH_ASSOC)) {
        $cleanNombre = cleanText($row['nombre']);
        
        if ($cleanNombre != $row['nombre']) {
            $stmt = $conn->prepare("UPDATE users SET nombre = :nombre WHERE id = :id");
            $stmt->execute([
                ':nombre' => $cleanNombre,
                ':id' => $row['id']
            ]);
            echo "Usuario ID {$row['id']} limpiado<br>";
        }
    }
    
    // Limpiar notas de bookings
    $bookings = $conn->query("SELECT id, notas FROM bookings WHERE notas IS NOT NULL");
    while ($row = $bookings->fetch(PDO::FETCH_ASSOC)) {
        $cleanNotas = cleanText($row['notas']);
        
        if ($cleanNotas != $row['notas']) {
            $stmt = $conn->prepare("UPDATE bookings SET notas = :notas WHERE id = :id");
            $stmt->execute([
                ':notas' => $cleanNotas,
                ':id' => $row['id']
            ]);
            echo "Booking ID {$row['id']} notas limpiadas<br>";
        }
    }
    
    echo "<h2>¡Limpieza completada!</h2>";
    echo "<p>Ahora puedes cerrar esta página y probar el calendario nuevamente.</p>";
    
} catch (Exception $e) {
    echo "<h2>Error: " . $e->getMessage() . "</h2>";
}
?>