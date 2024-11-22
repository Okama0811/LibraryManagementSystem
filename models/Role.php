<?php
include_once 'Model.php';

class Role extends Model
{
    protected $table_name = 'role';

    public $role_id;
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

    // Get all permissions for a role
    public function getPermissions() {
        $query = "SELECT p.* FROM permission p 
                 INNER JOIN role_permission rp ON p.permission_id = rp.permission_id 
                 WHERE rp.role_id = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $this->role_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Assign permission to role
    public function assignPermission($permission_id) {
        $query = "INSERT INTO role_permission (role_id, permission_id, created_at) 
                 VALUES (:role_id, :permission_id, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $this->role_id);
        $stmt->bindParam(':permission_id', $permission_id);
        return $stmt->execute();
    }

    // Remove permission from role
    public function removePermission($permission_id) {
        $query = "DELETE FROM role_permission 
                 WHERE role_id = :role_id AND permission_id = :permission_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $this->role_id);
        $stmt->bindParam(':permission_id', $permission_id);
        return $stmt->execute();
    }
}
