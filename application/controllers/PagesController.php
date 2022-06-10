<?php

namespace Controllers;

use Models\Router;
use Models\Session;

class PagesController {
    public static function login(Router $router, Session $session) {
        $login = $session->get('login');

        if ($login) {
            header('Location: /');
            return;
        }

        $router->render('auth/login', [
            'title' => 'Iniciar sesión',
        ]);
    }

    public static function logout(Router $router, Session $session) {
        $session->destroy();

        header('Location: /');
    }

    public static function signup(Router $router, Session $session) {
        $login = $session->get('login');

        if ($login) {
            header('Location: /');
            return;
        }

        $router->render('auth/signup', [
            'title' => 'Crear cuenta',
        ]);
    }

    public static function confirm(Router $router, Session $session) {
        $login = $session->get('login');

        if ($login) {
            header('Location: /');
            return;
        }

        $token = $router->getParam('token');

        if (!$token) {
            header('Location: /');
            return;
        }

        $router->render('auth/confirm', [
            'title' => 'Confirmar tu cuenta'
        ]);
    }

    public static function recover(Router $router, Session $session) {
        $login = $session->get('login');

        if ($login) {
            header('Location: /');
            return;
        }

        $router->render('auth/recover', [
            'title' => 'Recuperar cuenta',
        ]);
    }

    public static function restore(Router $router, Session $session) {
        $login = $session->get('login');

        if ($login) {
            header('Location: /');
            return;
        }

        $token = $router->getParam('token');

        if (!$token) {
            header('Location: /');
            return;
        } 

        $router->render('auth/restore', [
            'title' => 'Restablecer password'
        ]);
    }

    public static function chat(Router $router) {
        $router->render('pages/chat', [
            'title' => 'Chat'
        ]);
    }

    public static function error(Router $router) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = [
                'post' => 'error',
            ];

            echo json_encode($response);
            return;
        }

        $router->render('pages/error', [
            'title' => 'Página no encontrada',
        ]);
    }
}