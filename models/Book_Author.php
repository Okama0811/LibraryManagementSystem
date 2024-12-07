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

    public function insertBookAuthor($bookId, $authorId, $role = 'Author') {
        $sql = "INSERT INTO book_author (book_id, author_id, role) VALUES (:book_id, :author_id, :role)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
        $stmt->bindParam(':author_id', $authorId, PDO::PARAM_INT);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        return $stmt->execute();
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
