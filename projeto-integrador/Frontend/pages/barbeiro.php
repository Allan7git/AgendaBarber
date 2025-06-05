<?php
session_start();

// Verifica se o usuário está logado e se é barbeiro
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'barbeiro') {
    header("Location: ../login.php"); // Redireciona para o login
    exit;
}
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
</head>
<body>
  <header>
    <div class="logo">
      <a href="../index.html"><img src="../imagens/logo branco.png" alt="Logo"></a>
    </div>
    <nav class="navbar">
      <ul class="menu-lista">
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

    <table class="tabela-agendamentos">
      <thead>
        <tr>
          <th>Cliente</th>
          <th>Serviço</th>
          <th>Data</th>
          <th>Hora</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>João Silva</td>
          <td>Corte de Cabelo</td>
          <td>05/06/2025</td>
          <td>14:00</td>
        </tr>
        <tr>
          <td>Maria Souza</td>
          <td>Barba</td>
          <td>05/06/2025</td>
          <td>15:30</td>
        </tr>
        <tr>
          <td>Carlos Pereira</td>
          <td>Cabelo e Barba</td>
          <td>05/06/2025</td>
          <td>16:45</td>
        </tr>
      </tbody>
    </table>
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
