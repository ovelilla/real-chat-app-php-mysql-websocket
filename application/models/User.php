<?php

namespace Models;

class User extends Database {
    protected string $table = 'users';
    protected array $columns = ['id', 'name', 'email', 'password', 'token', 'confirmed', 'status', 'connection_id', 'date'];

    protected ?int $id;
    protected string $name;
    protected string $email;
    protected string $password;
    protected string $password_repeat;
    protected string $password_current;
    protected string $token;
    protected int $confirmed;
    protected string $status;
    protected string $connection_id;
    protected string $date;

    public function __construct($args = []) {
        parent::__construct();

        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password_repeat = $args['password_repeat'] ?? '';
        $this->password_current = $args['password_current'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmed = $args['confirmed'] ?? 0;
        $this->status = $args['status'] ?? 'logout';
        $this->connection_id = $args['connection_id'] ?? '';
        $this->date = $args['date'] ?? date('Y-m-d H:i:s');
    }

    public function getId(): string {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function getToken(): string {
        return $this->token;
    }

    public function setToken(string $token): void {
        $this->token = $token;
    }

    public function getConfirmed(): string {
        return $this->confirmed;
    }

    public function setConfirmed(int $confirmed): void {
        $this->confirmed = $confirmed;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setStatus(int $status): void {
        $this->status = $status;
    }

    public function getConnectionId(): string {
        return $this->connection_id;
    }

    public function setConnectionId(int $connection_id): void {
        $this->connection_id = $connection_id;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function setDate(int $date): void {
        $this->date = $date;
    }

    public function validateLogin(): array {
        if (!$this->email) {
            $this->errors['email'] = 'El email es obligatorio';
        }

        if ($this->email && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'El email no es válido';
        }

        if (!$this->password_current) {
            $this->errors['password'] = 'El password es obligatorio';
        }

        if ($this->password_current && strlen($this->password_current) < 6) {
            $this->errors['password'] = 'El password debe contener al menos 6 caracteres';
        }

        return $this->errors;
    }

    public function validateNewAcount(): array {
        if (!$this->name) {
            $this->errors['name'] = 'El nombre es obligatorio';
        }

        if (!$this->email) {
            $this->errors['email'] = 'El email es obligatorio';
        }

        if ($this->email && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'El email no es válido';
        }

        if (!$this->password_current) {
            $this->errors['password'] = 'El password es obligatorio';
        }

        if ($this->password_current && strlen($this->password_current) < 6) {
            $this->errors['password'] = 'El password debe contener al menos 6 caracteres';
        }

        if (!$this->password_repeat) {
            $this->errors['password_repeat'] = 'El password de verificación es obligatorio';
        }

        if (($this->password_current && $this->password_repeat) && ($this->password_current !== $this->password_repeat)) {
            $this->errors['password_repeat'] = 'Los passwords no coinciden';
        }

        return $this->errors;
    }

    public function validateEmail(): array {
        if (!$this->email) {
            $this->errors['email'] = 'El email es obligatorio';
        }

        if ($this->email && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'El email no es válido';
        }

        return $this->errors;
    }

    public function validatePassword(): array {
        if (!$this->password) {
            $this->errors['password'] = 'El password es obligatorio';
        }

        if ($this->password && strlen($this->password) < 6) {
            $this->errors['password'] = 'El password debe contener al menos 6 caracteres';
        }

        return $this->errors;
    }

    public function validateNewPassword(): array {
        if (!$this->password_current) {
            $this->errors['password'] = 'El password es obligatorio';
        }

        if ($this->password_current && strlen($this->password_current) < 6) {
            $this->errors['password'] = 'El password debe contener al menos 6 caracteres';
        }

        return $this->errors;
    }

    public function checkPassword(): bool {
        return password_verify($this->password_current, $this->password);
    }

    public function hashPassword(): void {
        $this->password = password_hash($this->password_current, PASSWORD_BCRYPT);
    }

    public function createToken(): void {
        $this->token = randomId(64);
    }
}
