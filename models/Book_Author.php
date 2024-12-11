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
        $stmt = $this->conn     ->prepare($sql);
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
        $stmt->bindParam(':author_id', $authorId, PDO::PARAM_INT);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function getAuthorsByBookId($book_id) {
        $stmt = $this->conn->prepare("
            SELECT author.author_id, author.name 
            FROM book_author 
            JOIN author ON book_author.author_id = author.author_id
            WHERE book_author.book_id = :book_id
        ");
        $stmt->bindValue(':book_id', $book_id, PDO::PARAM_INT);
        $stmt->execute();
    
        $authors = $stmt->fetchAll(PDO::FETCH_ASSOC); // Lấy toàn bộ kết quả
        return $authors;
    }   

    public function updateBookAuthor($id, $author_id) {
         // Xóa tất cả các tác giả hiện tại trước khi thêm mới
    $sqlDelete = "DELETE FROM book_author WHERE book_id = :book_id";
    $stmtDelete = $this->conn->prepare($sqlDelete);
    $stmtDelete->bindParam(':book_id', $id, PDO::PARAM_INT);
    $stmtDelete->execute();

    // Thêm mới tác giả
    $sqlInsert = "INSERT INTO book_author (book_id, author_id) VALUES (:book_id, :author_id)";
    $stmtInsert = $this->conn->prepare($sqlInsert);
    $stmtInsert->bindParam(':book_id', $id, PDO::PARAM_INT);
    $stmtInsert->bindParam(':author_id', $authorId, PDO::PARAM_INT);
    return $stmtInsert->execute();
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
