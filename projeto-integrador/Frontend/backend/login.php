<?php
session_start();

// Conecte ao banco de dados
$conn = new mysqli("localhost", "root", "", "barbearia");


if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}

$email = $_POST['email'];
$senha = $_POST['senha'];

// Consulta SQL para verificar o login
$sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $usuario = $result->fetch_assoc();

  // Salva os dados na sessão
  $_SESSION['usuario_id'] = $usuario['id'];
  $_SESSION['tipo'] = $usuario['tipo'];

  // Verifica se é barbeiro
  if ($usuario['tipo'] === 'barbeiro') {
    header("Location: /projeto-integrador/Frontend/pages/barbeiro.php");
  } else {
    header("Location: /projeto-integrador/Frontend/pages/fazer_agendamento.php");
  }

  exit();
}
 else {
  echo "<script>
    alert('Login ou senha inválidos!');
    window.location.href = '../login.html';
  </script>";
}

$conn->close();
?>
