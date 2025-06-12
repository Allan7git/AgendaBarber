<?php
session_start();

// Verifica se o usuário está logado e se é barbeiro
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'barbeiro') {
    header("Location: ../login.php");
    exit;
}

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "barbearia");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Consulta os agendamentos do dia com valor incluído
$hoje = date('Y-m-d');
$sql = "SELECT id, nome_cliente, servico, valor, data_agendamento, hora_agendamento, observacoes FROM agendamentos 
        ORDER BY data_agendamento ASC";

$result = $conn->query($sql);


?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Agenda Barber - Área do Barbeiro</title>
  <link rel="stylesheet" href="barbeiro.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    .btn-cancelar {
      background-color: #e74c3c;
      border: none;
      color: white;
      padding: 6px 12px;
      cursor: pointer;
      border-radius: 4px;
      font-size: 0.9rem;
    }
    .btn-cancelar:hover {
      background-color: #c0392b;
    }
    form {
      margin: 0;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo">
      <a href="../index.html"><img src="../imagens/logo branco.png" alt="Logo"></a>
    </div>
    <nav class="navbar">
      <ul class="menu-lista">
         <li><a href="agendamento.html"><i class="fas fa-calendar-check"></i> Agendamento</a></li>
        <li><a href="../pages/sobrenós.html"><i class="fas fa-info-circle"></i> Sobre Nós</a></li>
        <li class="contato-wrapper">
          <a href="#" onclick="mostrarContato(event)"><i class="fas fa-phone-alt"></i> Contato</a>
          <div id="contato-info" class="contato-box">
            <h2>Informações de Contato</h2>
            <p><i class="fas fa-phone-alt"></i> (11) 99999-9999</p>
            <p><i class="fas fa-envelope"></i> contato@barber.com</p>
            <p><i class="fas fa-map-marker-alt"></i> Rua Exemplo, 123 - São Paulo, SP</p>
          </div>
        </li>
        <li><a href="../index.html"><i class="fas fa-door-open"></i> Sair</a></li>
      </ul>
      <div class="menu-toggle" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
      </div>
    </nav>
  </header>
<main class="content">
  <h1>Área do Barbeiro</h1>
  <p class="subtitle">Visualize os agendamentos do dia</p>

  <div class="alerta">
    <i class="fas fa-info-circle"></i>
    <span>Atualize a página para ver novos agendamentos.</span>
  </div>

  <div class="tabela-wrapper">
    <table class="tabela-agendamentos">
      <thead>
        <tr>
          <th>Cliente</th>
          <th>Serviço</th>
          <th>Valor</th>
          <th>Data</th>
          <th>Hora</th>
          <th>Observacões</th>
          <th>Ação</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $dataFormatada = date('d/m/Y', strtotime($row['data_agendamento']));

            switch ($row['servico']) {
              case 'cabelo':
                $servicoTexto = 'Corte de Cabelo';
                break;
              case 'barba':
                $servicoTexto = 'Barba';
                break;
              case 'combo':
                $servicoTexto = 'Corte + Barba';
                break;
              case 'degrade':
                $servicoTexto = 'Corte Degradê';
                break;
              case 'corte_sobrancelha':
                $servicoTexto = 'Corte + Sobrancelha';
                break;
              case 'corte_barba_sobrancelha':
                $servicoTexto = 'Corte + Barba + Sobrancelha';
                break;
              default:
                $servicoTexto = $row['servico'];
            }

            $valorFormatado = 'R$' . number_format($row['valor'], 2, ',', '.');

            echo "<tr>
              <td data-label='Cliente'>" . htmlspecialchars($row['nome_cliente']) . "</td>
              <td data-label='Serviço'>" . htmlspecialchars($servicoTexto) . "</td>
              <td data-label='Valor'>" . $valorFormatado . "</td>
              <td data-label='Data'>" . $dataFormatada . "</td>
              <td data-label='Hora'>" . htmlspecialchars($row['hora_agendamento']) . "</td>
              <td data-label='Observações' class='observacao'>" . nl2br(htmlspecialchars($row['observacoes'])) . "</td>
              <td data-label='Ação'>
                <form method='POST' action='cancelar_agendamento.php' onsubmit='return confirm(\"Deseja realmente cancelar este agendamento?\");'>
                  <input type='hidden' name='id_agendamento' value='" . $row['id'] . "' />
                  <button type='submit' class='btn-cancelar'>Cancelar</button>
                </form>
              </td>
            </tr>";
          }
        } else {
          echo '<tr><td colspan="7" style="text-align:center;">Nenhum agendamento para hoje.</td></tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
</main>

  <script>
    function toggleMenu() {
      document.querySelector('.menu-lista').classList.toggle('ativo');
    }

    function mostrarContato(event) {
      event.preventDefault();
      document.getElementById('contato-info').classList.toggle('mostrar');
    }

    window.addEventListener('click', function (e) {
      if (!document.querySelector('.contato-wrapper').contains(e.target)) {
        document.getElementById('contato-info').classList.remove('mostrar');
      }
    });
  </script>
</body>
</html>
<?php

?>
