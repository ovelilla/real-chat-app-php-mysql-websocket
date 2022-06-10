<?php

namespace Models;

class GeneralMessage extends Database {
    protected string $table = 'general_messages';
    protected array $columns = ['id', 'id_user', 'message', 'date'];

    protected ?int $id;
    protected string $id_user;
    protected string $message;
    protected string $date;

    public function __construct($args = []) {
        parent::__construct();

        $this->id = $args['id'] ?? null;
        $this->id_user = $args['id_user'] ?? '';
        $this->message = $args['message'] ?? '';
        $this->date = $args['date'] ?? date('Y-m-d H:i:s');
    }

    public function getId(): string {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getIdUser(): string {
        return $this->id_user;
    }

    public function setIdUser(string $id_user): void {
        $this->id_user = $id_user;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function setMessage(string $message): void {
        $this->message = $message;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function setDate(int $date): void {
        $this->date = $date;
    }

    public function validateMessage(): array {
        if (!$this->message) {
            $this->errors['message'] = 'El mensaje es obligatorio';
        }

        if ($this->email && strlen($this->message) > 255) {
            $this->errors['email'] = 'El mensaje es muy largo';
        }

        return $this->errors;
    }

    public function getAll() {
        $this->connect();

        $query = "SELECT m.id, m.id_user, m.message, m.date, u.name FROM general_messages AS m INNER JOIN users AS u ON m.id_user = u.id";

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        $stmt->close();
        $this->close();

        return $messages;
    }
}
