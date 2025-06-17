<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Agenda Barber - Agendamento</title>
  <link rel="stylesheet" href="fazer_agendamento.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
  <header>
    <div class="logo">
      <a href="../index.html"> <img src="../imagens/logo branco.png" alt="Logo"></a>
    </div>
    <nav class="navbar">
      <ul class="menu-lista">
          <li><a href="meus_agendamentos.php"><i class="fas fa-list-alt"></i> Meus Agendamentos</a></li>
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
    <h1>Bem-vindo!</h1>
    <p class="subtitle">Agende seu horário de forma rápida e fácil!</p>

    <div class="alerta">
      <i class="fas fa-info-circle"></i>
      <span>Verifique se os dados estão corretos antes de confirmar.</span>
    </div>

    <form class="formulario" action="../backend/agendamento.php" method="POST">
      <div class="campo">
        <i class="fas fa-user"></i>
        <input type="text" name="nome" maxlength="50" placeholder="Seu nome completo" required />
      </div>

      <div class="campo">
        <i class="fas fa-cut"></i>

<select id="servico" name="servico" required>
  <option value="" disabled selected>— Escolha um serviço —</option>
  <option value="cabelo">Corte de Cabelo (R$20,00)</option>
  <option value="barba">Barba (R$15,00)</option>
  <option value="combo">Corte + Barba (R$35,00)</option>
  <option value="degrade">Corte Degradê (R$25,00)</option>
  <option value="corte_sobrancelha">Corte + Sobrancelha (R$18,00)</option>
  <option value="corte_barba_sobrancelha">Corte + Barba + Sobrancelha (R$38,00)</option>
</select>

      </div>

     <?php
  $hoje = date('Y-m-d');
  $umAnoDepois = date('Y-m-d', strtotime('+1 year'));
?>
<div class="campo">
  <i class="fas fa-calendar-alt"></i>
  <input type="date" name="data" min="<?= $hoje ?>" max="<?= $umAnoDepois ?>" required />
</div>

      <div class="campo">
        <i class="fas fa-clock"></i>
       <input type="time" name="hora" min="09:00" max="19:00" required />

      </div>
      
      <div class="descricao">
        
        <textarea name="observacoes" id="observacoes" rows="3" maxlength="50" placeholder="Observações"></textarea>
      </div>

      <button type="submit">Confirmar Agendamento</button>
    </form>
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


  
  document.addEventListener("DOMContentLoaded", function () {
    const dataInput = document.querySelector('input[type="date"]');

    dataInput.addEventListener("input", function () {
      const dataSelecionada = new Date(this.value);
      const diaSemana = dataSelecionada.getUTCDay(); // 0 = Domingo

      if (diaSemana === 0) {
        alert("Domingos não estão disponíveis para agendamento.");
        this.value = ""; // Limpa a data inválida
      }
    });
  });

  </script>
</body>
</html>
