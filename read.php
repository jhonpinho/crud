<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Conexão com o banco de dados
$mysqli = new mysqli("localhost", "root", "", "crud");

// Verifica se houve erro na conexão
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

// Busca todos os clientes
$query = "SELECT * FROM clientes";
$result = $mysqli->query($query);

// Verifica se há resultados
if ($result->num_rows > 0) {
    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;  // Adiciona cada cliente ao array
    }
    echo json_encode($clientes);  // Retorna todos os clientes como JSON
} else {
    echo json_encode(["message" => "Nenhum cliente encontrado"]);
}

$mysqli->close();
?>
