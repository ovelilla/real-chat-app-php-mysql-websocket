<?php

namespace Models;

use mysqli;
use Exception;

class Database {
    private string $db_host;
    private string $db_user;
    private string $db_pass;
    private string $db_name;

    protected object $connection;

    protected array $errors = [];

    public function __construct() {
        $this->db_host = $_ENV['DB_HOST'];
        $this->db_user = $_ENV['DB_USER'];
        $this->db_pass = $_ENV['DB_PASS'];
        $this->db_name = $_ENV['DB_NAME'];
    }

    public function connect(): void {
        try {
            $this->connection = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function close(): void {
        $this->connection->close();
    }

    public function count() {
        $this->connect();
     
        $query = "SELECT COUNT(*) FROM " . $this->table;

        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        $count = 0;
        $stmt->bind_result($count);
        $stmt->fetch();

        $stmt->close();
        $this->close();

        return $count;
    }

    public function all($sort = [], $limit = []): ?array {
        $this->connect();
     
        $query = "SELECT * FROM " . $this->table;

        if (!empty($sort)) {
            $columns = array_keys($sort);
            $values = array_values($sort);
            
            $query .= " ORDER BY ";

            for ($i = 0; $i < count($sort); $i++) { 
                $query .= $i ? ', ' : '';
                $query .= "$columns[$i] $values[$i]";
            }
        }

        if (!empty($limit)) {   
            $query .= " LIMIT " . $limit['start'] . ", " .$limit['rows'];
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        $result = $this->fetchAll($result);

        $stmt->close();
        $this->close();

        return $result;
    }

    public function where(array $conditions, bool $limit = true): ?array {
        $this->connect();

        $columns = array_keys($conditions);
        $values = array_values($conditions);
        $types = str_repeat('s', count($values));

        $query = "SELECT * FROM " . $this->table . " WHERE ";

        foreach ($columns as $i => $column) {
            $query .= $i ? ' AND ' : '';
            $query .= "$column = ?";
        }

        if ($limit) {
            $query .= ' LIMIT 1';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();

        $result = $stmt->get_result();
        $result = $this->fetch($result, $limit);

        $stmt->close();
        $this->close();

        return $result;
    }

    public function save(): ?array {
        if (!is_null($this->id)) {
            $result = $this->update();
        } else {
            $result = $this->insert();
        }
        return $result;
    }

    public function insert(): array {
        $this->connect();

        $data = $this->getData();

        $columns = array_keys($data);
        $columns = array_map([$this, 'escapeMysqlIdentifier'], $columns);
        $columns = implode(', ', $columns);

        $values = array_values($data);

        $parameters = str_repeat('?,', count($values) - 1) . '?';
        $types = str_repeat('s', count($values));
        
        $query = "INSERT INTO " . $this->table . " ($columns) VALUES ($parameters)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();

        $insert_id = $stmt->insert_id;
        $affected_rows = $stmt->affected_rows;

        $stmt->close();
        $this->close();

        return [
            'insert_id' => $insert_id,
            'affected_rows' => $affected_rows
        ];
    }

    public function update(): array {
        $this->connect();

        $data = $this->getData();

        $columns = array_keys($data);
        $columns = array_map([$this, 'escapeMysqlIdentifier'], $columns);

        $values = array_values($data);
        $values[] = $this->id;

        $types = str_repeat('s', count($values));
        
        $query = "UPDATE " . $this->table . " SET ";
        foreach ($columns as $i => $column) {
            $query .= $i ? ', ' : '';
            $query .= "$column = ?";
        }
        
        $query .= " WHERE id = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();

        $affected_rows = $stmt->affected_rows;

        $stmt->close();
        $this->close();

        return [
            'affected_rows' => $affected_rows
        ];
    }

    public function updateAll(array $data, array $conditions): array {
        $this->connect();

        $data_columns = array_keys($data);
        $data_columns = array_map([$this, 'escapeMysqlIdentifier'], $data_columns);
        $data_values = array_values($data);

        $conditions_columns = array_keys($conditions);
        $conditions_columns = array_map([$this, 'escapeMysqlIdentifier'], $conditions_columns);
        $conditions_values = array_values($conditions);

        $values = array_merge($data_values, $conditions_values);

        $types = str_repeat('s', count($values));
        
        $query = "UPDATE " . $this->table . " SET ";
        foreach ($data_columns as $i => $column) {
            $query .= $i ? ', ' : '';
            $query .= "$column = ?";
        }

        $query .= " WHERE ";
        foreach ($conditions_columns as $i => $column) {
            $query .= $i ? ' AND ' : '';
            $query .= "$column = ?";
        }
        
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();

        $affected_rows = $stmt->affected_rows;

        $stmt->close();
        $this->close();

        return [
            'affected_rows' => $affected_rows
        ];
    }

    public function delete(array $conditions): array {
        $this->connect();

        $columns = array_keys($conditions);
        $values = array_values($conditions);
        $types = str_repeat('s', count($values));

        $query = "DELETE FROM " . $this->table . " WHERE ";

        foreach ($columns as $i => $column) {
            $query .= $i ? ' AND ' : '';
            $query .= "$column = ?";
        }

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();

        $affected_rows = $stmt->affected_rows;

        $stmt->close();
        $this->close();

        return [
            'affected_rows' => $affected_rows
        ];
    }

    public function fetch(object $result, bool $limit): ?array {
        if ($limit) {
            $result = $this->fetchOne($result);
        } else {
            $result = $this->fetchAll($result);
        }
        return $result;
    }

    public function fetchOne(object $result): ?array {
        return $result->fetch_assoc();
    }

    public function fetchAll(object $result): ?array {
        while ($row = $result->fetch_assoc()) {
            $array[] = $row;
        }
        return $array ?? [];
    }

    public function sync($args = []) {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    public function getData() {
        $data = [];
        foreach ($this->columns as $column) {
            if ($column === 'id') continue;
            $data[$column] = $this->$column;
        }
        return $data;
    }

    protected function createObject($row) {
        $object = new static;
        foreach ($row as $key => $value) {
            if (property_exists($object, $key)) {
                $object->$key = $value;
            }
        }
        return $object;
    }

    protected function escapeMysqlIdentifier($field) {
        return "`" . str_replace("`", "``", $field) . "`";
    }

    public function setError($type, $message): void {
        $this->errors[$type] = $message;
    }

    public function getError(): array {
        return $this->errors;
    }
}
