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
    public $avatar_url;

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

    public function authenticate($username, $password)
    {
        $user = $this->readByUsername($username);
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

    protected function readByUsername($username)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function updateAvatar($id) {
        $query = "UPDATE `" . $this->table_name . "` SET avatar_url = ? WHERE " . $this->table_name . "_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $this->avatar_url);
        $stmt->bindValue(2, $id);
        return $stmt->execute();
    }

}