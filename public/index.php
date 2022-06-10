<?php
require '../application/app.php';

use Models\Session;
use Models\Router;

use Controllers\PagesController;
use Controllers\UserController;
use Controllers\ChatController;

$session = new Session();
$router = new Router($session);

$router->add('/', [PagesController::class, 'login'], false);
$router->add('/logout', [PagesController::class, 'logout'], false);
$router->add('/registrar', [PagesController::class, 'signup'], false);
$router->add('/recuperar', [PagesController::class, 'recover'], false);
$router->add('/restablecer', [PagesController::class, 'restore'], false);
$router->add('/restablecer/{token}', [PagesController::class, 'restore'], false);
$router->add('/confirmar', [PagesController::class, 'confirm'], false);
$router->add('/confirmar/{token}', [PagesController::class, 'confirm'], false);

$router->add('/chat', [PagesController::class, 'chat'], true);

$router->add('/api/user/signup', [UserController::class, 'signup'], false);
$router->add('/api/user/login', [UserController::class, 'login'], false);
$router->add('/api/user/confirm', [UserController::class, 'confirm'], false);
$router->add('/api/user/recover', [UserController::class, 'recover'], false);
$router->add('/api/user/restore', [UserController::class, 'restore'], false);

$router->add('/api/user/get/id', [UserController::class, 'getId'], true);

$router->add('/api/chat/general/create', [GeneralMessageController::class, 'create'], true);
$router->add('/api/chat/general/read', [ChatController::class, 'read'], true);
$router->add('/api/chat/general/update', [GeneralMessageController::class, 'update'], true);
$router->add('/api/chat/general/delete', [GeneralMessageController::class, 'delete'], true);



$router->check();
