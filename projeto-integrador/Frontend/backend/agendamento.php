<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado. Faça login primeiro.");
}

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "barbearia");

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION["usuario_id"];
    $nome = $_POST["nome"];
    $servico = $_POST["servico"];
    $data = $_POST["data"];
    $hora = $_POST["hora"];
    $observacao = $_POST["observacoes"];

    // Determina o valor com base no serviço
    switch ($servico) {
        case 'cabelo': $valor = 20.00; break;
        case 'barba': $valor = 15.00; break;
        case 'combo': $valor = 35.00; break;
        case 'degrade': $valor = 25.00; break;
        case 'corte_sobrancelha': $valor = 18.00; break;
        case 'corte_barba_sobrancelha': $valor = 38.00; break;
        default: $valor = 0.00;
    }

    // 🔒 VERIFICA SE O USUÁRIO JÁ TEM UM AGENDAMENTO FUTURO E ATIVO
    $verificaUsuario = $conn->prepare("SELECT id FROM agendamentos WHERE usuario_id = ? AND data_agendamento >= CURDATE() AND status = 'ativo'");
    $verificaUsuario->bind_param("i", $usuario_id);
    $verificaUsuario->execute();
    $verificaUsuario->store_result();

    if ($verificaUsuario->num_rows > 0) {
        echo "<script>
                alert('Você já possui um agendamento ativo. Cancele o agendamento atual antes de fazer outro.');
                window.location.href = '../pages/meus_agendamentos.php';
              </script>";
        $verificaUsuario->close();
        $conn->close();
        exit;
    }
    $verificaUsuario->close();

    // INSERE o agendamento
    $stmt = $conn->prepare("INSERT INTO agendamentos (usuario_id, nome_cliente, servico, data_agendamento, hora_agendamento, valor, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssds", $usuario_id, $nome, $servico, $data, $hora, $valor, $observacao);

    if ($stmt->execute()) {
        echo "<script>
                alert('Agendamento realizado com sucesso!');
                window.location.href = '../pages/meus_agendamentos.php';
              </script>";
    } else {
        echo "Erro ao agendar: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
