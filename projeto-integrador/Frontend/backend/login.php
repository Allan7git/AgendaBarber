<?php
session_start();

// Conecta ao banco de dados
$conn = new mysqli("localhost", "root", "", "barbearia");
if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}

$email = $_POST['email'];
$senha = $_POST['senha'];

// Prepare a consulta só pelo email
$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    // Usa password_verify para comparar senha digitada com hash do banco
    if (password_verify($senha, $usuario['senha'])) {
        // Login OK
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['tipo'] = $usuario['tipo'];

        if ($usuario['tipo'] === 'barbeiro') {
            header("Location: /projeto-integrador/Frontend/pages/barbeiro.php");
        } else {
            header("Location: /projeto-integrador/Frontend/pages/fazer_agendamento.php");
        }
        exit();
    } else {
        // Senha incorreta
        echo "<script>
          alert('Login ou senha inválidos!');
          window.location.href = '../index.html';
        </script>";
    }
} else {
    // Email não encontrado
    echo "<script>
      alert('Login ou senha inválidos!');
      window.location.href = '../index.html';
    </script>";
}
