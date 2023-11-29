<!DOCTYPE html>
<html>

<body>
    <h1>Retorno Cadastro de Usuário</h1>

    <div id="resultado"></div>

    <script>
        let ws;

        ws = new WebSocket('ws://localhost:9502');

        ws.onopen = function () {
            console.log('Conexão estabelecida');
        };

        ws.onclose = function () {
            console.log('Conexão fechada, reconectando...');
            setTimeout(connect, 5000);
        };

        ws.onmessage = function (event) {
            const response = JSON.parse(event.data);
            document.getElementById('resultado').innerHTML = `
          <p>${response.greeting}</p>
          <p>${response.username}</p>
          <p>${response.password}</p>
        `;
        };

    </script>
</body>

</html>