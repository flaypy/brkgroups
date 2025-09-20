<?php
// Não precisa do database.php, apenas da sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

if (isset($_SESSION['admin_id'])) {
    echo json_encode(['logado' => true, 'usuario' => $_SESSION['admin_usuario']]);
} else {
    echo json_encode(['logado' => false]);
}
