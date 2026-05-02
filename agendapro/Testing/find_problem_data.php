<?php
// find_problem_data.php
require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

$tables = ['courses', 'otec', 'users', 'bookings'];

foreach ($tables as $table) {
    echo "<h2>Tabla: $table</h2>";
    $stmt = $conn->query("SELECT * FROM $table");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        foreach ($row as $key => $value) {
            if (is_string($value) && !mb_check_encoding($value, 'UTF-8')) {
                echo "<p style='color:red'>Problema en $table ID {$row['id']} - Campo: $key</p>";
                echo "<pre>" . bin2hex($value) . "</pre>";
            }
        }
    }
}
?>