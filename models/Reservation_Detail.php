<?php
include_once 'Model.php';
class Reservation_Detail extends Model
{
    protected $table_name = 'reservation_detail';
    public $reservation_id;
    public $book_id;
    public $quantity;

    public function __construct(){
        parent::__construct();
    }

    public function create()
    {
        return parent::create();
    }

    public function read() {
        $query = "SELECT * FROM reservation_detail";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readById($id) {
        $query = "SELECT rd.reservation_id, rd.book_id, b.book_id, b.title, b.status,
                  GROUP_CONCAT(DISTINCT a.name SEPARATOR ', ') AS authors,
                  GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') AS categories 
                  FROM reservation_detail rd
                  JOIN book b ON b.book_id = rd.book_id
                  LEFT JOIN book_author ba ON b.book_id = ba.book_id
                  LEFT JOIN author a ON ba.author_id = a.author_id
                  LEFT JOIN book_category bc ON b.book_id = bc.book_id
                  LEFT JOIN category c ON bc.category_id = c.category_id
                  WHERE rd.reservation_id = :id
                  GROUP BY b.book_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id) {
        $query = "UPDATE reservation_detail SET book_id = :book_id, quantity = :quantity WHERE reservation_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':book_id', $this->book_id);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM reservation_detail WHERE reservation_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>