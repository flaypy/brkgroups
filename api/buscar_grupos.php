<?php
require_once '../config/database.php';

$sql = "SELECT g.id, g.nome, g.descricao, g.link_convite, g.imagem_perfil, c.nome as categoria_nome 
        FROM grupos g 
        JOIN categorias c ON g.categoria_id = c.id 
        ORDER BY g.data_criacao DESC";

$result = pg_query($conn, $sql);
$grupos = [];

if ($result) {
    while($row = pg_fetch_assoc($result)) {
        $grupos[] = $row;
    }
    echo json_encode(['sucesso' => true, 'dados' => $grupos]);
} else {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao buscar grupos do banco de dados.']);
}

pg_close($conn);
