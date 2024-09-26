<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Conexão com o banco de dados
$mysqli = new mysqli("localhost", "root", "", "crud");

// Verifica se houve erro na conexão
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

// Recebe os dados via POST
$id = $_POST['id'];
$name = $_POST['name'];
$cpf = $_POST['cpf'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$email = $_POST['email'];

if (empty($id) || empty($name) || empty($cpf) || empty($address) || empty($phone) || empty($email)) {
    echo json_encode(["message" => "Todos os campos precisam ser preenchidos!"]);
    exit;
}

// Verifica se o CPF já existe em outro usuário (exceto o atual)
$query = "SELECT * FROM clientes WHERE cpf = ? AND id != ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('si', $cpf, $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["message" => "CPF já está cadastrado para outro usuário."]);
    exit;
}

// Prepara a query de atualização
$query = "UPDATE clientes SET name=?, cpf=?, address=?, phone=?, email=? WHERE id=?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("sssssi", $name, $cpf, $address, $phone, $email, $id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Usuário atualizado com sucesso"]);
} else {
    echo json_encode(["message" => "Erro ao atualizar usuário"]);
}

$stmt->close();
$mysqli->close();
?>

