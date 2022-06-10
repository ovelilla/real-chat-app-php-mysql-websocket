<?php

namespace Controllers;

use Models\Router;
use Models\Session;

use Models\User;
use Models\Email;

class UserController {
    public static function login(Router $router, Session $session) {
        $login = $session->get('login');

        if ($login) {
            $response = [
                'status' => 'error',
                'msg' => 'Already logged in'
            ];
            echo json_encode($response);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = [
                'status' => 'error',
                'msg' => 'Bad request method'
            ];
            echo json_encode($response);
            return;
        }

        $user = new User($_POST);

        $errors = $user->validateLogin();

        if (!empty($errors)) {
            $response = [
                'status' => 'error',
                'errors' => $errors
            ];

            echo json_encode($response);
            return;
        }

        $user_exists = $user->where(['email' => $user->getEmail()]);

        if (!$user_exists) {
            $user->setError('email', 'El usuario no existe');
            $errors = $user->getError();

            $response = [
                'status' => 'error',
                'errors' => $errors
            ];

            echo json_encode($response);
            return;
        }

        $user->sync($user_exists);

        if (!$user->checkPassword()) {
            $user->setError('password', 'Password incorrecto');
            $errors = $user->getError();

            $response = [
                'status' => 'error',
                'errors' => $errors
            ];

            echo json_encode($response);
            return;
        }

        if (!$user->getConfirmed()) {
            $user->setError('email', 'El usuario no esta confirmado');
            $errors = $user->getError();

            $response = [
                'status' => 'error',
                'errors' => $errors
            ];

            echo json_encode($response);
            return;
        }

        $session->set('id', $user->getId());
        $session->set('name', $user->getName());
        $session->set('email', $user->getEmail());
        $session->set('login', true);

        $response = [
            'status' => 'success',
            'errors' => $errors,
            'user' => [
                'name' => $user->getName()
            ]
        ];

        echo json_encode($response);
    }

    public static function signup(Router $router, Session $session) {
        $login = $session->get('login');

        if ($login) {
            $response = [
                'status' => 'error',
                'msg' => 'Already logged in'
            ];
            echo json_encode($response);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = [
                'status' => 'error',
                'msg' => 'Bad request method'
            ];
            echo json_encode($response);
            return;
        }

        $user = new User($_POST);

        $errors = $user->validateNewAcount();

        if (!empty($errors)) {
            $response = [
                'status' => 'error',
                'errors' => $errors
            ];

            echo json_encode($response);
            return;
        }

        $user_exists = $user->where(['email' => $user->getEmail()]);

        if ($user_exists) {
            $user->setError('email', 'El usuario ya está registrado');
            $errors = $user->getError();

            $response = [
                'status' => 'error',
                'errors' => $errors
            ];

            echo json_encode($response);
            return;
        }

        $user->hashPassword();
        $user->createToken();
        $user->save();

        $email = new Email($user);
        $email->sendAcountConfirmation();

        $response = [
            'status' => 'success',
            'errors' => $errors,
        ];

        echo json_encode($response);
    }

    public static function confirm(Router $router, Session $session) {
        $login = $session->get('login');

        if ($login) {
            $response = [
                'status' => 'error',
                'msg' => 'Already logged in'
            ];
            echo json_encode($response);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = [
                'status' => 'error',
                'msg' => 'Bad request method'
            ];
            echo json_encode($response);
            return;
        }
        
        $user = new User($_POST);

        $user_exists = $user->where(['token' => $user->getToken()]);

        if (!$user_exists) {
            $user->setError('token', 'Token no valido');
            $errors = $user->getError();

            $response = [
                'status' => 'error',
                'errors' => $errors
            ];

            echo json_encode($response);
            return;
        }

        $user->sync($user_exists);
        $user->setConfirmed(1);
        $user->setToken('');
        $user->save();

        $response = [
            'status' => 'success',
            'errors' => [],
            'msg' => '¡Cuenta confirmada correctamente!'
        ];

        echo json_encode($response);
    }

    public static function recover(Router $router, Session $session) {
        $login = $session->get('login');

        if ($login) {
            $response = [
                'status' => 'error',
                'msg' => 'Already logged in'
            ];
            echo json_encode($response);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = [
                'status' => 'error',
                'msg' => 'Bad request method'
            ];
            echo json_encode($response);
            return;
        }

        $user = new User($_POST);

        $errors = $user->validateEmail();

        if (!empty($errors)) {
            $response = [
                'status' => 'error',
                'errors' => $errors
            ];

            echo json_encode($response);
            return;
        }

        $user_exists = $user->where(['email' => $user->getEmail()]);

        if (!$user_exists) {
            $user->setError('email', 'El usuario no existe');
            $errors = $user->getError();

            $response = [
                'status' => 'error',
                'errors' => $errors
            ];

            echo json_encode($response);
            return;
        }

        $user->sync($user_exists);

        if (!$user->getConfirmed()) {
            $user->setError('email', 'El usuario no esta confirmado');
            $errors = $user->getError();

            $response = [
                'status' => 'error',
                'errors' => $errors
            ];

            echo json_encode($response);
            return;
        }

        $user->createToken();
        $user->save();

        $email = new Email($user);
        $email->sendResetPassword();

        $response = [
            'status' => 'success',
            'errors' => $errors,
        ];

        echo json_encode($response);
    }

    public static function restore(Router $router, Session $session) {
        $login = $session->get('login');

        if ($login) {
            $response = [
                'status' => 'error',
                'msg' => 'Already logged in'
            ];
            echo json_encode($response);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = [
                'status' => 'error',
                'msg' => 'Bad request method'
            ];
            echo json_encode($response);
            return;
        }

        $user = new User($_POST);

        //validar password

        $user_exists = $user->where(['token' => $user->getToken()]);

        if (!$user_exists) {
            $user->setError('token', 'Token no válido');
            $errors = $user->getError();

            $response = [
                'status' => 'error',
                'errors' => $errors
            ];

            echo json_encode($response);
            return;
        }

        $user->sync($user_exists);

        $errors = $user->validatePassword();

        if (!empty($errors)) {
            $response = [
                'status' => 'error',
                'errors' => $errors
            ];

            echo json_encode($response);
            return;
        }

        $user->hashPassword();
        $user->setToken('');
        $user->save();

        $response = [
            'status' => 'success',
            'errors' => $errors,
            'msg' => '¡Cuenta confirmada correctamente!',
        ];

        echo json_encode($response);
    }

    public static function getId(Router $router, Session $session) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = [
                'status' => 'error',
                'msg' => 'Bad request method'
            ];
            echo json_encode($response);
            return;
        }

        $id = $session->get('id');
       
        $response = $id;

        echo json_encode($response);
    }
}
