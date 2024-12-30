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
        $query = "SELECT * FROM reservation_detail WHERE reservation_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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