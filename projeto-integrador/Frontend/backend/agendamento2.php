<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $servico = $_POST['servico'] ?? '';
    $data = $_POST['data'] ?? '';
    $hora = $_POST['hora'] ?? '';

    // Definir valor do serviço
    switch ($servico) {
        case 'cabelo':
            $valor = 20;
            break;
        case 'barba':
            $valor = 15;
            break;
        case 'combo':
            $valor = 35;
            break;
        case 'degrade':
    $valor = 25;
    break;
case 'corte_sobrancelha':
    $valor = 18; 
case 'corte_barba_sobrancelha':
    $valor = 38; 
    break;

        default:
            $valor = 0;
    }

    
    $conn = new mysqli("localhost", "root", "", "barbearia");
    if ($conn->connect_error) {
        die("Erro: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO agendamentos (nome_cliente, servico, valor, data_agendamento, hora_agendamento) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $nome, $servico, $valor, $data, $hora);

    if ($stmt->execute()) {
        header("Location: ../pages/barbeiro.php");
    } else {
        echo "Erro ao salvar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
