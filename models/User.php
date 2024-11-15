<?php
include_once 'Model.php';
class User extends Model
{
    protected $table_name = 'users';

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
    public function __construct(){
        parent::__construct();
    }
    public function create()
    {
        $this->status = 'inactive';
        $this->role_id= 1;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        // var_dump($this);
        // exit();
        return parent::create();
    }

    public function read()
    {
        return parent::read();
    }

    public function readById($id)
    {
        return parent::readById($id);
    }

    public function update($id)
    {
        return parent::update($id);
    }

    public function delete($id)
    {
        return parent::delete($id);
    }

    //Các phương thức xác thực User
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
        // $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($userData);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}