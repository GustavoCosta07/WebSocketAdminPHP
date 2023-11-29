<?php
use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;

$server = new Server("0.0.0.0", 9502);

$server->on("start", function (Server $server) {
    echo "Swoole WebSocket Server is started at http://localhost:9502\n";
});

$server->on('open', function (Server $server, Request $request) {
    echo "connection open: {$request->fd}\n";
});

$server->on('message', function (Server $server, Frame $frame) {
    echo "received message: {$frame->data}\n";
    $userData = json_decode($frame->data, true);

    $servidor = 'db'; 
    $usuario = 'user';
    $senha = 'password';
    $dbname = 'mydb';
    $porta = 3306; 

    $conn = mysqli_connect($servidor, $usuario, $senha, $dbname, $porta);

    if (!$conn) {
        echo "Falha na conexão: " . mysqli_connect_error();
        exit;
    }
    
    mysqli_set_charset($conn, "utf8");

    if ($userData['type'] == 'cadastro') {
        $stmt = mysqli_prepare($conn, 'INSERT INTO usuarios (nome, telefone, username, idade, senha) VALUES (?, ?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'sssis', $userData['nome'], $userData['telefone'], $userData['username'], $userData['idade'], $userData['senha']);
        mysqli_stmt_execute($stmt);

        $response = [
            'greeting' => "Olá, {$userData['username']}, utilize seu usuário e senha para entrar no sistema.",
            'username' => "Seu usuário é {$userData['username']}.",
            'password' => "Sua senha é {$userData['senha']}.",
        ];

        foreach ($server->connections as $fd) {
            $server->push($fd, json_encode($response));
        }
    }

    if ($userData['type'] == 'atualizarIdade') {
        $stmt = mysqli_prepare($conn, 'UPDATE usuarios SET idade = ? WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'ii', $userData['novaIdade'], $userData['id']);
        mysqli_stmt_execute($stmt);

        foreach ($server->connections as $fd) {
            $server->push($fd, json_encode(['type' => 'novaIdade', 'idade' => $userData['novaIdade']]));
        }
    }
});

$server->on('close', function (Server $server, int $fd) {
    echo "connection close: {$fd}\n";
});

$server->start();
?>