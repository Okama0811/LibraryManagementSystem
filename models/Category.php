<?php
include_once 'Model.php';
class Category extends Model
{
    protected $table_name = 'category';

    public $category_id;
    public $name;
    public $description;
    public $created_at;
    public $updated_at;

    public function __construct(){
        parent::__construct();
    }

    public function create()
    {
        return parent::create();
    }

    public function read() {
        $query = "SELECT * FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readById($id) {
        $query = "SELECT * FROM {$this->table_name} WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id)
    {
        return parent::update($id);
    }

    public function delete($id)
    {
        return parent::delete($id);
    }

    
    public function checkNameExists($name) {
        $sql = "SELECT COUNT(*) FROM {$this->table_name} WHERE name = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $name, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }
}