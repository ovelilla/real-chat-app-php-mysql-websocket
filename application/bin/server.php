<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

use Controllers\ChatController;

require dirname(__DIR__) . '\..\vendor\autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatController()
        )
    ),
    8080
);

$server->run();
