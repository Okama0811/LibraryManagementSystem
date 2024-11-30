<?php
include_once 'Model.php';
class Book_Author extends Model
{
    protected $table_name = 'book_author';

    public $book_id;
    public $author_id;
    public $role;
    public $created_at;
    public $updated_at;

    public function __construct() {
        parent::__construct();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
        SET book_id=:book_id, author_id=:author_id, role=:role";

        $stmt = $this->conn->prepare($query);

        $this->book_id = htmlspecialchars(strip_tags($this->book_id));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->role = htmlspecialchars(strip_tags($this->role))?: 'author';

        $stmt->bindParam(':book_id', $this->book_id);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':role', $this->role);

        $stmt->execute()
    }

    public function read() {
        $query = "SELECT * FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readByBookId($id) {
        $query = "SELECT * FROM {$this->table_name} WHERE book_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function readByAuthorId($id) {
        $query = "SELECT * FROM {$this->table_name} WHERE author_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id) {
        return parent::update($id);
    }

    public function delete($id) {
        return parent::delete($id);
    }
  
}
