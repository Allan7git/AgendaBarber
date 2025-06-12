<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "root", "", "barbearia");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($nome) || empty($email) || empty($senha)) {
    die("Erro: Nome, email ou senha não enviados.");
}

// Validação básica no PHP
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Erro: Email inválido.");
}

if (strlen($senha) < 8 || !preg_match('/[A-Za-z]/', $senha) || !preg_match('/\d/', $senha)) {
    die("Erro: A senha deve ter no mínimo 8 caracteres, contendo ao menos uma letra e um número.");
}

// Verifica se email já existe
$sql_check = "SELECT id FROM usuarios WHERE email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    die("Erro: Email já cadastrado.");
}

$stmt_check->close();

// INSERE a senha sem hash
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $email, $senha);

if ($stmt->execute()) {
    echo "sucesso";
} else {
    echo "Erro ao inserir: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
