<?php
include_once 'Model.php';
class Permission extends Model
{
    protected $table_name = 'permission';

    public $permission_id;
    public $name;
    public $description;
    public $created_at;
    public $updated_at;

    public function __construct() {
        parent::__construct();
    }

    public function create() {
        return parent::create();
    }

    public function read() {
        return parent::read();
    }

    public function readById($id) {
        return parent::readById($id);
    }

    public function update($id) {
        return parent::update($id);
    }

    public function delete($id) {
        return parent::delete($id);
    }

    // Get all roles that have this permission
    public function getRoles() {
        $query = "SELECT r.* FROM roles r 
                 INNER JOIN role_permissions rp ON r.role_id = rp.role_id 
                 WHERE rp.permission_id = :permission_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':permission_id', $this->permission_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function assignPermission($role_id,$permission_id): bool
    {
        $query = "INSERT INTO role_permission (role_id, permission_id) VALUES (:role_id, :permission_id)";
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':role_id', $role_id);
        $stmt->bindParam(':permission_id', $permission_id);
        
        // Thực thi câu lệnh
        return $stmt->execute();
    }
}