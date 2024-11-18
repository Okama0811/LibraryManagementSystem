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
        $attributes = $this->getAttributes();
        unset(
            $attributes['conn'], 
            $attributes['table_name'],
            $attributes['id'],
            $attributes['avatar_url'],
            $attributes[$this->table_name . '_id'] 
        );          

        $set = [];         
        foreach ($attributes as $key => $value) {             
            $set[] = "`$key` = ?";         
        }         
        $query = "UPDATE `" . $this->table_name . "` SET " . implode(', ', $set) . " WHERE " . $this->table_name . "_id = ?";
        
        $stmt = $this->conn->prepare($query);                  
        
        $i = 1;         
        foreach ($attributes as $value) {             
            $stmt->bindValue($i++, $value);         
        }         
        $stmt->bindValue($i, $id);                  
        
        return $stmt->execute();     
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE ".$this->table_name ."_id = :id";
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
