<?php

namespace Models;

class Session {
    public function __construct() {
        session_start();
    }

    public function get(string $key) {
        return $_SESSION[$key] ?? null;
    }

    public function set(string $key, $value): void {
        $_SESSION[$key] = $value;
    }

    public function remove(string $key): void {
        unset($_SESSION[$key]);
    }

    public function destroy(): void {
        session_unset();
        session_destroy();
    }
}