<?php
// Inicia a sessão em todas as páginas que utilizarem o banco de dados
// para que o login do admin persista.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// --- CONFIGURAÇÕES PARA POSTGRESQL ---
$host = "localhost";
$port = "5432"; // Porta padrão do PostgreSQL
$dbname = "grupos_whatsapp"; // Altere para o nome do seu banco
$user = "postgres"; // Altere para seu usuário PostgreSQL
$password = ""; // Altere para sua senha

// String de conexão para PostgreSQL
$conn_str = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";

// Tenta estabelecer a conexão com PostgreSQL
$conn = pg_connect($conn_str);

if (!$conn) {
    // Em caso de erro, retorna uma resposta JSON
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro de conexão com o banco de dados PostgreSQL.']);
    die();
}
// O charset é geralmente tratado pela configuração do cliente/banco no PostgreSQL.
