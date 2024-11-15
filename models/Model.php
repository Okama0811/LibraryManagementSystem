<?php
abstract class Model
{
    protected $conn;
    protected $table_name;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection(); 
    }

    public function create()
    {
        $attributes = $this->getAttributes();
        unset($attributes['conn'], $attributes['table_name']);

        $columns = array_keys($attributes);
        $values = array_fill(0, count($columns), '?');

        $query = "INSERT INTO `" . $this->table_name . "` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ")";
        $stmt = $this->conn->prepare($query);

        $i = 1;
        foreach ($attributes as $value) {
            $stmt->bindValue($i++, $value);
        }

        return $stmt->execute();
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id)
    {
        $query = "UPDATE " . $this->table_name . " SET ";
        $set = [];
        foreach ($this->getAttributes() as $key => $value) {
            $set[] = $key . " = :" . $key;
        }
        $query .= implode(', ', $set) . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        foreach ($this->getAttributes() as $key => $value) {
            $stmt->bindParam(':' . $key, $this->$key);
        }
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    protected function getAttributes()
    {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
        $attributes = [];
        foreach ($properties as $property) {
            $attributes[$property->getName()] = $this->{$property->getName()};
        }
        return $attributes;
    }
}
