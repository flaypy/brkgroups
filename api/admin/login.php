<?php
require_once '../../config/database.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['usuario']) || !isset($data['senha'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário e senha são obrigatórios.']);
    exit;
}

$usuario = $data['usuario'];
$senha = $data['senha'];

// Usando queries parametrizadas do PostgreSQL para segurança
$stmt_name = "login_admin";
$sql = "SELECT id, senha_hash FROM administradores WHERE usuario = $1";
$prepare_result = pg_prepare($conn, $stmt_name, $sql);

if (!$prepare_result) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao preparar a consulta.']);
    exit;
}

$result = pg_execute($conn, $stmt_name, array($usuario));

if ($result && pg_num_rows($result) === 1) {
    $admin = pg_fetch_assoc($result);
    if (password_verify($senha, $admin['senha_hash'])) {
        // Senha correta, cria a sessão
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_usuario'] = $usuario;
        echo json_encode(['sucesso' => true, 'mensagem' => 'Login realizado com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário ou senha inválidos.']);
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário ou senha inválidos.']);
}

pg_close($conn);

