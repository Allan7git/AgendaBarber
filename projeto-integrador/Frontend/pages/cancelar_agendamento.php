<?php
session_start();

// Verifica se o usuário está logado e se é barbeiro
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'barbeiro') {
    header("Location: ../login.php");
    exit;
}

// Verifica se o ID do agendamento foi enviado
if (!isset($_POST['id_agendamento'])) {
    header("Location: barbeiro.php");
    exit;
}

$id_agendamento = intval($_POST['id_agendamento']);

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "barbearia");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Deleta o agendamento pelo ID
$stmt = $conn->prepare("DELETE FROM agendamentos WHERE id = ?");
$stmt->bind_param("i", $id_agendamento);
$stmt->execute();

$stmt->close();
$conn->close();

// Redireciona de volta para a área do barbeiro
header("Location: barbeiro.php");
exit;
?>
 