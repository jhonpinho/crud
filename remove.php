<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once('config.php');  // Inclua seu arquivo de configuração para a conexão ao banco de dados

$id = $_POST['id'] ?? '';  // Verifica se o ID foi enviado via POST

if (empty($id)) {
    echo json_encode(["message" => "ID não foi passado."]);
    exit;
}

// Usar prepared statement para evitar SQL Injection
$sql = "DELETE FROM clientes WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('i', $id);  // 'i' indica que o parâmetro é um inteiro

if ($stmt->execute()) {
    echo json_encode(["message" => "Usuário deletado com sucesso"]);
} else {
    echo json_encode(["message" => "Erro ao deletar usuário"]);
}

$stmt->close();
$connection->close();
?>
