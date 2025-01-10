<?php
include_once 'Model.php';
class Fine extends Model
{
    protected $table_name = 'fine';

    public $fine_id;
    public $loan_id;
    public $user_id;
    public $returned_to;
    public $confirmed_by;
    public $issued_date;
    public $due_date;
    public $returned_date;
    public $status;
    public $notes;
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
        $sql = "SELECT f.fine_id,
        l.loan_id,
        u.username AS user_name,
        r.username AS returned_to,
        c.username AS confirmed_by,
        f.issued_date,
        f.due_date,
        f.returned_date,
        f.status,
        f.notes
        FROM fine f
        LEFT JOIN user u ON f.user_id = u.user_id
        LEFT JOIN user r ON f.returned_to = r.user_id
        LEFT JOIN user c ON f.confirmed_by = c.user_id
        LEFT JOIN loan l ON f.loan_id = l.loan_id";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function readById($id) {
        $query = "SELECT 
            f.fine_id,
            f.loan_id,
            u.full_name AS user_name,
            r.full_name AS returned_to,
            c.username AS confirmed_by,
            f.issued_date,
            f.due_date,
            f.returned_date,
            f.status,
            f.notes
        FROM fine f
        LEFT JOIN user u ON f.user_id = u.user_id
        LEFT JOIN user r ON f.returned_to = r.user_id
        LEFT JOIN user c ON f.confirmed_by = c.user_id
        WHERE f.fine_id = :id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
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

    public function getLoaned(){
        $sql = "SELECT u.username, l.loan_id
        FROM loan l
        JOIN user u ON l.issued_by = u.user_id
        WHERE l.status = 'overdue'
        AND NOT EXISTS (
            SELECT 1
            FROM fine f
            WHERE f.loan_id = l.loan_id
        );";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getPaymentData($id){
        $sql = "SELECT notes,amount
        FROM fine_payment
        WHERE fine_payment.fine_id = :id";

       
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFinesByMemberId($id){
        $sql = "SELECT f.fine_id,
                p.amount,
                l.loan_id,
                u.username AS user_name,
                r.username AS returned_to,
                f.issued_date,
                f.due_date,
                f.returned_date,
                f.status,
                f.notes
                FROM fine f
                LEFT JOIN user u ON f.user_id = u.user_id
                LEFT JOIN user r ON f.returned_to = r.user_id
                LEFT JOIN loan l ON f.loan_id = l.loan_id
                LEFT JOIN fine_payment p ON f.fine_id = p.fine_id
                WHERE f.user_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateFineStatus($fineId, $data) {
        $sql = "UPDATE fine SET 
                status = :status,
                notes = :notes,
                returned_date = :returned_date 
                WHERE fine_id = :fine_id";
        
        // -- UPDATE fine_payment SET 
        //         -- payment_method = :payment_method
        //         -- WHERE fine_id = :fine_id;
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':notes', $data['notes']);
        $stmt->bindParam(':returned_date', $data['returned_date']);
        // $stmt->bindParam(':payment_method', $data['payment_method']);
        $stmt->bindParam(':fine_id', $fineId);
    
        $stmt->execute();

        if (isset($data['confirmed_by'])) {
            $sqlConfirmed = "UPDATE fine SET 
                                confirmed_by = :confirmed_by 
                             WHERE fine_id = :fine_id;";
            $stmtConfirmed = $this->conn->prepare($sqlConfirmed);
            $stmtConfirmed->bindParam(':confirmed_by', $data['confirmed_by']);
            $stmtConfirmed->bindParam(':fine_id', $fineId);
            $stmtConfirmed->execute();
        }
}

    public function getLastInsertedId()
    {
        return $this->conn->lastInsertId(); 
    }

    public function updatePayment($id, $amount, $payment_date, $payment_method, $receive_by, $payment_notes) {
        try {
            $sql = "UPDATE fine_payment 
                    SET amount = :amount, 
                        payment_date = :payment_date, 
                        payment_method = :payment_method, 
                        receive_by = :receive_by, 
                        notes = :notes 
                    WHERE fine_id = :fine_id";
    
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':fine_id', $id);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':payment_date', $payment_date);
            $stmt->bindParam(':payment_method', $payment_method);
            $stmt->bindParam(':receive_by', $receive_by);
            $stmt->bindParam(':notes', $payment_notes);
            $stmt->execute();
    
            return true;
        } catch (Exception $e) {
            throw new Exception("Cập nhật thông tin thanh toán thất bại: " . $e->getMessage());
        }
    }
    
}