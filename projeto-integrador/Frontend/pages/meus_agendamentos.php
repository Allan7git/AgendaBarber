<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado. Faça login primeiro.");
}

$conn = new mysqli("localhost", "root", "", "barbearia");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$usuario_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("SELECT id, nome_cliente, servico, data_agendamento, hora_agendamento, valor, observacoes FROM agendamentos WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Agenda Barber - Meus Agendamentos</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="meus_agendamentos.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
<header>
  <div class="logo">
    <a href="../index.html"><img src="../imagens/logo branco.png" alt="Logo"></a>
  </div>
  <nav class="navbar">
    <ul class="menu-lista">
       <li><a href="fazer_agendamento.php"><i class="fas fa-calendar-check"></i> Agendamento</a></li>
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
  <h1>Meus Agendamentos</h1>
  <p class="subtitle">Veja seus horários agendados</p>

  <div class="alerta">
    <i class="fas fa-info-circle"></i>
    <span>Atualize a página para ver atualizações.</span>
  </div>

  <div class="tabela-wrapper">
    <table class="tabela-agendamentos">
      <thead>
        <tr>
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
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                // Traduz o serviço, se necessário
                switch ($row['servico']) {
                    case 'cabelo':
                        $servico = 'Corte de Cabelo';
                        break;
                    case 'barba':
                        $servico = 'Barba';
                        break;
                    case 'combo':
                        $servico = 'Corte + Barba';
                        break;
                    case 'degrade':
                        $servico = 'Corte Degradê';
                        break;
                    case 'corte_sobrancelha':
                        $servico = 'Corte + Sobrancelha';
                        break;
                    case 'corte_barba_sobrancelha':
                        $servico = 'Corte + Barba + Sobrancelha';
                        break;
                    default:
                        $servico = ucfirst($row['servico']);
                }

                $valor = 'R$ ' . number_format($row['valor'], 2, ',', '.');
                $data = date('d/m/Y', strtotime($row['data_agendamento']));
                $hora = htmlspecialchars($row['hora_agendamento']);
                $observacao = nl2br(htmlspecialchars($row['observacoes']));

                echo "<tr>
                        <td>$servico</td>
                        <td style='white-space: nowrap;'>$valor</td>
                        <td>$data</td>
                        <td>$hora</td>
                        <td class='observacao'>$observacao</td>
                        <td>
                          <form method='POST' action='cancelar_cliente.php' onsubmit='return confirm(\"Deseja cancelar este agendamento?\");'>
                            <input type='hidden' name='id_agendamento' value='{$row['id']}' />
                            <button type='submit' class='btn-cancelar'>Cancelar</button>
                          </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center;'>Nenhum agendamento encontrado.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</main>

<script>
  function toggleMenu() {
    const menu = document.querySelector('.menu-lista');
    menu.classList.toggle('ativo');
  }

  function mostrarContato(event) {
    event.preventDefault();
    const box = document.getElementById('contato-info');
    box.classList.toggle('mostrar');
  }

  window.addEventListener('click', function (e) {
    const contatoWrapper = document.querySelector('.contato-wrapper');
    if (!contatoWrapper.contains(e.target)) {
      document.getElementById('contato-info').classList.remove('mostrar');
    }
  });
</script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
