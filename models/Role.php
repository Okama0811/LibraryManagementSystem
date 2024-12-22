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
    
    public function __construct() 
    {
        parent::__construct();
    }
    
    // Kiểm tra xem role có user nào không trước khi xóa
    public function hasUsers($role_id) 
    {
        $query = "SELECT COUNT(*) as count FROM user WHERE role_id = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function create() 
    {
        $query = "INSERT INTO role (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        
        if($stmt->execute()) {
            $this->role_id = $this->conn->lastInsertId(); // Lưu ID ngay sau khi insert
            return true;
        }
        return false;
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
        // Xóa tất cả permission associations trước
        $this->removeAllPermissions($id);
        return parent::delete($id);
    }
    
    // Lấy tất cả permissions của role
    public function getPermissionIDs($role_id) 
    {
        $query = "SELECT permission_id FROM role_permission p  
                  WHERE p.role_id = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $role_id);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getPermissions() 
    {
        $query = "SELECT p.* FROM permission p 
                  INNER JOIN role_permission rp ON p.permission_id = rp.permission_id 
                  WHERE rp.role_id = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $this->role_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Gán permission cho role
    public function assignPermission($permission_id) 
    {
        $query = "INSERT INTO role_permission (role_id, permission_id, created_at) 
                  VALUES (:role_id, :permission_id, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $this->role_id);
        $stmt->bindParam(':permission_id', $permission_id);
        return $stmt->execute();
    }
    
    // Xóa permission khỏi role
    public function removePermission($permission_id) 
    {
        $query = "DELETE FROM role_permission 
                  WHERE role_id = :role_id AND permission_id = :permission_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $this->role_id);
        $stmt->bindParam(':permission_id', $permission_id);
        return $stmt->execute();
    }

    // Xóa tất cả permissions của role
    public function removeAllPermissions($role_id) 
    {
        $query = "DELETE FROM role_permission WHERE role_id = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $role_id);
        return $stmt->execute();
    }

    // Cập nhật permissions của role
    public function updatePermissions($permissions) 
    {
        $this->removeAllPermissions($this->role_id);
        
        if (!empty($permissions)) {
            foreach ($permissions as $permission_id) {
                $this->assignPermission($permission_id);
            }
        }
        return true;
    }
}