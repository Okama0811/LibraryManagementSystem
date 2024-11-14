<?php

namespace App\Models\Abstracts;

use PDO;

abstract class Model
{
    protected $table;
    protected $connection;

    public function __construct()
    {
        $this->connection = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    }

    public function all()
    {
        $stmt = $this->connection->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $keys = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));
        $stmt = $this->connection->prepare("INSERT INTO {$this->table} ($keys) VALUES ($values)");
        $stmt->execute($data);
        return $this->connection->lastInsertId();
    }

    public function update(array $data, $id)
    {
        $updates = [];
        foreach ($data as $key => $value) {
            $updates[] = "$key = :$key";
        }
        $sql = "UPDATE {$this->table} SET " . implode(', ', $updates) . " WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array_merge($data, ['id' => $id]));
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}