<?php

namespace App\Models;

use App\Models\Abstracts\Model;

class User extends Model 
{
    protected $table = 'users';

    /**
     * Lấy danh sách user với phân trang
     */
    public function paginate($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->connection->prepare(
            "SELECT user_id, username, email, full_name, member_type, 
                    expiry_date, status, created_at 
            FROM {$this->table} 
            ORDER BY created_at DESC 
            LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Tạo người dùng mới
     */
    public function createUser(array $data)
    {
        // Validate dữ liệu đầu vào
        $requiredFields = ['username', 'password', 'email', 'full_name'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }

        // Xử lý dữ liệu
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['status'] = $data['status'] ?? 'active';
        $data['member_type'] = $data['member_type'] ?? 'standard';
        $data['max_books'] = $data['max_books'] ?? 5;
        $data['expiry_date'] = $data['expiry_date'] ?? date('Y-m-d', strtotime('+1 year'));

        return $this->create($data);
    }

    /**
     * Cập nhật thông tin người dùng
     */
    public function updateUser($userId, array $data)
    {
        // Nếu cập nhật mật khẩu
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        return $this->update($data, $userId);
    }

    /**
     * Gia hạn thẻ thư viện
     */
    public function renewMembership($userId, $months = 12)
    {
        $stmt = $this->connection->prepare(
            "UPDATE {$this->table} 
            SET expiry_date = DATE_ADD(CURDATE(), INTERVAL :months MONTH) 
            WHERE user_id = :user_id"
        );
        $stmt->bindParam(':months', $months, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    /**
     * Cập nhật số lượng sách được mượn tối đa
     */
    public function updateMaxBooks($userId, $maxBooks)
    {
        return $this->update(['max_books' => $maxBooks], $userId);
    }

    /**
     * Tìm kiếm người dùng
     */
    public function search($keyword)
    {
        $stmt = $this->connection->prepare(
            "SELECT user_id, username, email, full_name, member_type, 
                    expiry_date, status 
            FROM {$this->table} 
            WHERE username LIKE :keyword 
            OR email LIKE :keyword 
            OR full_name LIKE :keyword"
        );
        $searchTerm = "%{$keyword}%";
        $stmt->bindParam(':keyword', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin chi tiết người dùng
     */
    public function getUserDetails($userId)
    {
        $stmt = $this->connection->prepare(
            "SELECT u.*, r.role_name 
            FROM {$this->table} u 
            LEFT JOIN roles r ON u.role_id = r.role_id 
            WHERE u.user_id = :user_id"
        );
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Kích hoạt/Vô hiệu hóa tài khoản
     */
    public function updateStatus($userId, $status)
    {
        if (!in_array($status, ['active', 'inactive'])) {
            return false;
        }
        return $this->update(['status' => $status], $userId);
    }

    /**
     * Kiểm tra số sách đang mượn
     */
    public function getCurrentBorrowedBooks($userId)
    {
        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) FROM loans 
            WHERE user_id = :user_id 
            AND return_date IS NULL"
        );
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Kiểm tra có thể mượn thêm sách không
     */
    public function canBorrowMore($userId)
    {
        $user = $this->find($userId);
        $currentBorrowed = $this->getCurrentBorrowedBooks($userId);
        return $currentBorrowed < $user['max_books'];
    }
}