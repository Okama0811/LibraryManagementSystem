<?php
include_once 'Model.php';
class Fine_Payment extends Model
{
    protected $table_name = 'fine_payment';

    public $payment_id;
    public $fine_id;
    public $payment_date;
    public $amount;
    public $payment_method;
    public $created_at;
    public $receive_by;
    public $notes;

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
        $query = "SELECT * FROM {$this->table_name} WHERE payment_id = :id";
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

    public function updatePaymentDate($id) {
        try {
            // Cập nhật payment_date trong bảng fine_payment
            $sql = "UPDATE fine_payment SET payment_date = :payment_date WHERE fine_id = :fine_id";

            $stmt = $this->conn->prepare($sql);
            $payment_date = date('Y-m-d'); // Ngày hiện tại

            $stmt->bindParam(':payment_date', $payment_date);
            $stmt->bindParam(':fine_id', $id);

            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception('Không thể cập nhật payment_date.');
            }
        } catch (Exception $e) {
            throw new Exception('Lỗi khi cập nhật payment_date: ' . $e->getMessage());
        }
    }
}