<?php
session_start();
$conn = new mysqli("localhost", "root", "", "barbearia");

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    // Se estiver usando password_hash() no cadastro, use password_verify aqui
    if ($senha === $usuario['senha']) {
        $_SESSION['usuario'] = $usuario['nome'];
        $_SESSION['tipo'] = $usuario['tipo'];

      if ($usuario['tipo'] === 'barbeiro') {
    header("Location: ../Frontend/pages/barbeiro.php");
    exit();
} else {
    header("Location: ../Frontend/pages/agendamento.php");
    exit();
}

    } else {
        echo "Senha incorreta.";
    }
} else {
    echo "Usuário não encontrado.";
}

$stmt->close();
$conn->close();
?>
