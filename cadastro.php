<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");
// Conexão com o banco de dados
$mysqli = new mysqli("localhost", "root", "", "crud");

// Verifica se houve erro na conexão
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

// Recebe os dados do formulário via POST
$name = $_POST['name'];
$cpf = $_POST['cpf'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$email = $_POST['email'];
if (empty($name) || empty($cpf) || empty($address) || empty($phone) || empty($email)) {
    echo json_encode(["message" => "Todos os campos precisam ser preenchidos!"]);
} else {
   


// Verifica se o CPF já está cadastrado
$str = "SELECT * FROM clientes WHERE cpf=?";
$stmt = $mysqli->prepare($str);
$stmt->bind_param('s', $cpf);
$stmt->execute();
$response = $stmt->get_result();

if ($response->num_rows > 0) {
    // Se o CPF já estiver cadastrado, retorna uma mensagem
    echo json_encode(["status" => "error", "message" => "Usuário já está cadastrado"]);
} else {
    // Caso contrário, insere os dados no banco de dados

    // Prepara a instrução SQL (com ? como placeholders)
    $sql = "INSERT INTO clientes (name, cpf, address, phone, email) VALUES (?, ?, ?, ?, ?)";

    // Prepara o statement
    $stmt = $mysqli->prepare($sql);

    // Verifica se o prepare deu certo
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . htmlspecialchars($mysqli->error));
    }

    // Faz o bind dos parâmetros (s: string, i: integer, etc.)
    $stmt->bind_param("sssss", $name, $cpf, $address, $phone, $email);

    // Executa o statement
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Usuário criado com sucesso!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao criar usuário: " . htmlspecialchars($stmt->error)]);
    }

    // Fecha o statement
    $stmt->close();
}
}

// Fecha a conexão
$mysqli->close();
?>

