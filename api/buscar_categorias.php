<?php
require_once '../config/database.php';

$result = $conn->query("SELECT id, nome FROM categorias ORDER BY nome ASC");
$categorias = [];
while($row = $result->fetch_assoc()) {
    $categorias[] = $row;
}
echo json_encode(['sucesso' => true, 'dados' => $categorias]);
$conn->close();
