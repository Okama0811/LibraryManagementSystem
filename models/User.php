<?php
include_once 'Model.php';
class User extends Model
{
    protected $table_name = 'user';

    public $user_id;
    public $role_id;
    public $username;
    public $password;
    public $email;
    public $full_name;
    public $date_of_birth;
    public $gender;
    public $phone;
    public $address;
    public $member_type;
    public $expiry_date;
    public $max_books;
    public $status;
    public $note;
    public $created_at;
    public $updated_at;
    private $role;
    public function __construct(){
        parent::__construct();
    }
    public function create()
    {
        $this->status = 'inactive';
        $this->role_id= 1;
        return parent::create();
    }

    public function read() {
        $query = "SELECT u.*, r.name as role_name, r.description as role_description 
                 FROM {$this->table_name} u 
                 LEFT JOIN role r ON u.role_id = r.role_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readById($id) {
        $query = "SELECT u.*, r.name as role_name, r.description as role_description 
                 FROM {$this->table_name} u 
                 LEFT JOIN role r ON u.role_id = r.role_id 
                 WHERE u.user_id = :id";
        
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

    public function authenticate($email, $password)
    {
        $user = $this->readByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
        // if ($user && $password === $user['password']) {
            foreach ($user as $key => $value) {
                if (property_exists($this, $key)) { 
                    $this->$key = $value; 
                }
            }
            return true;
        }
        return false;
    }

    protected function readByEmail($email)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}