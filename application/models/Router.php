<?php

namespace Models;

use Models\Session;
use Controllers\PagesController;

class Router {
    private Session $session;

    private string $url;

    private array $routes = [];
    private array $params = [];

    public function __construct($session) {
        $this->session = $session;

        $this->url = $_SERVER['REQUEST_URI'];
    }

    public function add($route, $function, $protected) {
        $this->routes[$route] = [
            'function' => $function,
            'protected' => $protected
        ];
    }

    public function check(): void {
        $routes = array_keys($this->routes);

        foreach ($routes as $route) {
            $paramKey = $this->checkParamKey($route);

            if (empty($paramKey) && $route !== $this->url) {
                continue;
            }

            if (empty($paramKey) && $route === $this->url) {
                $this->run($this->url);
                return;
            }

            $route = preg_replace("/(^\/)|(\/$)/", "", $route);
            $reqUri = preg_replace("/(^\/)|(\/$)/", "", $this->url);

            $uri = explode("/", $route);
            $reqUri = explode("/", $reqUri);

            $indexNum = [];

            foreach ($uri as $index => $param) {
                if (preg_match("/{.*}/", $param)) {
                    $indexNum[] = $index;
                }
            }

            foreach ($indexNum as $key => $index) {
                if (empty($reqUri[$index])) {
                    continue;
                }

                $this->params[$paramKey[$key]] = $reqUri[$index];

                $reqUri[$index] = "{.*}";
            }

            $pattern = '/' . str_replace("/", '\\/', implode("/", $reqUri)) . '/';

            $pregMatch = preg_match($pattern, $route);

            if (!$pregMatch) {
                continue;
            }

            $this->run('/' . $route);
            return;
        }

        $this->error();
    }

    private function run(string $route): void {
        $function = $this->routes[$route]['function'] ?? null;
        $protected = $this->routes[$route]['protected'] ?? null;

        $login = $this->session->get('login');

        if ($protected && !$login) {
            header('Location: /');
            exit;
        }

        call_user_func($function, $this, $this->session);
    }

    private function error(): void {
        call_user_func([PagesController::class, 'error'], $this);
    }

    private function checkParamKey(string $route): array {
        preg_match_all("/\{([^}]+)\}/", $route, $matches);
        return $matches[1];
    }

    public function getParam(string $key): ?string {
        return $this->params[$key] ?? null;
    }

    public function render(string $view, array $data = []): void {
        foreach ($data as $key => $value) {
            $$key = $value;
        }

        ob_start();
        require __DIR__ . "/../views/${view}.php";
        $content = ob_get_contents();
        ob_end_clean();

        require __DIR__ . '/../views/layout.php';
    }
}