<!DOCTYPE html>
<html>
<body>
  <h1>Cadastro de Usuário</h1>
  <form id="cadastroForm">
    <input type="text" id="nome" placeholder="Nome">
    <input type="text" id="telefone" placeholder="Telefone">
    <input type="text" id="username" placeholder="Username">
    <input type="number" id="idade" placeholder="Idade">
    <input type="password" id="senha" placeholder="Senha">
    <input type="password" id="confirmacao" placeholder="Confirmação de Senha">
    <button type="button" onclick="cadastrar()">Cadastrar</button>
  </form>

  <div id="resultado"></div>

  <script>
    let ws;

    function connect() {
      ws = new WebSocket('ws://localhost:9502');

      ws.onopen = function() {
        console.log('Conexão estabelecida');
      };

      ws.onclose = function() {
        console.log('Conexão fechada, reconectando...');
        setTimeout(connect, 5000);
      };

      ws.onmessage = function(event) {
        const response = JSON.parse(event.data);
        document.getElementById('resultado').innerHTML = `
          <p>${response.greeting}</p>
          <p>${response.username}</p>
          <p>${response.password}</p>
        `;
      };
    }

    function cadastrar() {
      const nome = document.getElementById('nome').value;
      const telefone = document.getElementById('telefone').value;
      const username = document.getElementById('username').value;
      const idade = document.getElementById('idade').value;
      const senha = document.getElementById('senha').value;
      const confirmacao = document.getElementById('confirmacao').value;

      if (senha !== confirmacao) {
        alert('As senhas não coincidem.');
        return;
      }

      const userData = {
        nome,
        telefone,
        username,
        idade,
        senha,
        type: 'cadastro'
      };

      if (ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify(userData));
      } else {
        console.error('Não foi possível enviar a mensagem. O WebSocket não está aberto.');
      }
    }

    connect();
  </script>
</body>
</html>
