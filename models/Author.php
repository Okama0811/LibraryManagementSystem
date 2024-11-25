<?php
include_once 'Model.php';
class Author extends Model
{
    protected $table_name = 'author';

    public $author_id;
    public $name;
    public $biography;
    public $birth_date;
    public $nationality;
    public $created_at;
    public $updated_at;
    public $avatar_url;

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
        $query = "SELECT * FROM {$this->table_name} WHERE author_id = :id";
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

    public function updateAvatar($id) {
        $query = "UPDATE `" . $this->table_name . "` SET avatar_url = ? WHERE author_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $this->avatar_url);
        $stmt->bindValue(2, $id);
        return $stmt->execute();
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