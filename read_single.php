<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Conexão com o banco de dados
$mysqli = new mysqli("localhost", "root", "", "crud");

// Verifica se houve erro na conexão
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

// Verifica se o ID foi enviado via GET
$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo json_encode(["message" => "ID não fornecido"]);
    exit;
}

// Busca o cliente pelo ID
$query = "SELECT * FROM clientes WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $id);  // 'i' indica que o parâmetro é inteiro

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);  // Retorna os dados do usuário em formato JSON
    } else {
        echo json_encode(["message" => "Usuário não encontrado"]);
    }
} else {
    echo json_encode(["message" => "Erro ao buscar usuário"]);
}

$stmt->close();
$mysqli->close();
?>
