<?php

namespace App\Models;

use App\Models\Abstracts\Model;

class Auth extends Model 
{
    protected $table = 'users';

    /**
     * Đăng nhập người dùng
     */
    public function login($username, $password)
    {
        $stmt = $this->connection->prepare(
            "SELECT user_id, username, email, full_name, role_id, status 
            FROM {$this->table} 
            WHERE (username = :username OR email = :username) 
            AND status = 'active'"
        );
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Kiểm tra tài khoản có đang active không
            if ($user['status'] !== 'active') {
                return false;
            }

            // Kiểm tra thẻ thư viện còn hạn không
            if (!$this->isValidMembership($user['user_id'])) {
                return false;
            }

            // Lưu thông tin vào session
            session_start();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role_id'] = $user['role_id'];
            return true;
        }
        return false;
    }

    /**
     * Đăng ký tài khoản mới
     */
    public function register(array $data)
    {
        // Kiểm tra username đã tồn tại
        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) FROM {$this->table} 
            WHERE username = :username OR email = :email"
        );
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        // Xử lý dữ liệu trước khi tạo
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['status'] = 'active';
        $data['member_type'] = $data['member_type'] ?? 'standard';
        $data['max_books'] = $data['max_books'] ?? 5;
        
        // Tính ngày hết hạn thẻ (ví dụ: 1 năm)
        $data['expiry_date'] = date('Y-m-d', strtotime('+1 year'));
        
        return $this->create($data);
    }

    /**
     * Kiểm tra thẻ thư viện còn hạn không
     */
    private function isValidMembership($userId)
    {
        $stmt = $this->connection->prepare(
            "SELECT expiry_date FROM {$this->table} 
            WHERE user_id = :user_id"
        );
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return strtotime($result['expiry_date']) >= time();
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        session_start();
        session_destroy();
        return true;
    }

    /**
     * Kiểm tra đăng nhập
     */
    public function isLoggedIn()
    {
        session_start();
        return isset($_SESSION['user_id']);
    }

    /**
     * Lấy thông tin người dùng hiện tại
     */
    public function getCurrentUser()
    {
        if ($this->isLoggedIn()) {
            return [
                'user_id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'full_name' => $_SESSION['full_name'],
                'role_id' => $_SESSION['role_id']
            ];
        }
        return null;
    }
}