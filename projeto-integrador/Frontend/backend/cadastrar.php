<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conecta ao banco
$conn = new mysqli("localhost", "root", "", "barbearia");

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Recebe os dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

if (empty($nome) || empty($email) || empty($senha)) {
    die("Erro: Nome, email ou senha não enviados.");
}

// Criptografa a senha
$senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

// Prepara e executa o INSERT
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $email, $senhaCriptografada);

if ($stmt->execute()) {
    echo "sucesso";
} else {
    echo "Erro ao inserir: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
