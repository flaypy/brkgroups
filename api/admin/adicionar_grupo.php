<?php
require_once '../../config/database.php';

// Proteção: Verifica se o admin está logado
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado. Faça login como administrador.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Validação simples dos dados recebidos
if (empty($data['nome']) || empty($data['link_convite']) || empty($data['categoria_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nome, link e categoria são obrigatórios.']);
    exit;
}

$nome = $data['nome'];
$descricao = $data['descricao'] ?? ''; // Descrição é opcional
$link_convite = $data['link_convite'];
$imagem_perfil = $data['imagem_perfil'] ?? 'https://placehold.co/100x100/25D366/FFFFFF?text=' . strtoupper(substr($nome, 0, 1));
$categoria_id = (int)$data['categoria_id'];

$stmt_name = "add_group";
$sql = "INSERT INTO grupos (nome, descricao, link_convite, imagem_perfil, categoria_id) VALUES ($1, $2, $3, $4, $5)";

$prepare_result = pg_prepare($conn, $stmt_name, $sql);
if(!$prepare_result){
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao preparar a inserção no banco de dados.']);
    exit;
}

$execute_result = pg_execute($conn, $stmt_name, array($nome, $descricao, $link_convite, $imagem_perfil, $categoria_id));

if ($execute_result) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Grupo adicionado com sucesso!']);
} else {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao adicionar o grupo. O link de convite já pode existir.']);
}

pg_close($conn);

