<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado. Faça login primeiro.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id_agendamento']) || empty($_POST['id_agendamento'])) {
        die("ID do agendamento não informado.");
    }

    $usuario_id = $_SESSION['usuario_id'];
    $id_agendamento = intval($_POST['id_agendamento']);

    // Conexão com o banco
    $conn = new mysqli("localhost", "root", "", "barbearia");
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // Verifica se o agendamento pertence ao usuário
    $stmt = $conn->prepare("SELECT id FROM agendamentos WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $id_agendamento, $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        $stmt->close();
        $conn->close();
        die("Agendamento não encontrado ou não pertence a você.");
    }
    $stmt->close();

    // Deleta o agendamento
    $stmt = $conn->prepare("DELETE FROM agendamentos WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $id_agendamento, $usuario_id);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // Redireciona para a página de agendamentos com mensagem de sucesso
        header("Location: meus_agendamentos.php?msg=cancelado");
        exit;
    } else {
        $stmt->close();
        $conn->close();
        die("Erro ao cancelar o agendamento.");
    }
} else {
    die("Método inválido.");
}
