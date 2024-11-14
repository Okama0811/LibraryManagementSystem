<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Abstracts\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * Override phương thức index để thêm phân trang
     */
    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $perPage = 10;
        $users = $this->getModel()->paginate($page, $perPage);
        return $this->render('index', compact('users', 'page', 'perPage'));
    }

    /**
     * Override phương thức store để xử lý thêm user
     */
    public function store()
    {
        $data = $this->getPostData();

        if (!$this->validateUserData($data)) {
            $this->setSessionError('Please fill in all required fields');
            $this->redirect('/users/create');
        }

        if ($this->getModel()->create($data)) {
            $this->setSessionSuccess('User created successfully');
            return $this->redirectToIndex($this->model);
        }

        $this->setSessionError('Failed to create user');
        $this->redirect('/users/create');
    }

    /**
     * Override phương thức update để xử lý cập nhật user
     */
    public function update($id)
    {
        $data = $this->getPostData(true); // true để bỏ qua password nếu rỗng

        if ($this->getModel()->update($data, $id)) {
            $this->setSessionSuccess('User updated successfully');
            return $this->redirectToIndex($this->model);
        }

        $this->setSessionError('Failed to update user');
        $this->redirect("/users/edit/$id");
    }

    /**
     * Xử lý gia hạn thẻ thư viện
     */
    public function renewMembership($id)
    {
        $months = $_POST['months'] ?? 12;
        
        if ($this->getModel()->renewMembership($id, $months)) {
            $this->setSessionSuccess('Membership renewed successfully');
        } else {
            $this->setSessionError('Failed to renew membership');
        }

        $this->redirect("/users/show/$id");
    }

    /**
     * Xử lý cập nhật trạng thái user
     */
    public function updateStatus($id)
    {
        $status = $_POST['status'] ?? 'active';
        
        if ($this->getModel()->updateStatus($id, $status)) {
            $this->setSessionSuccess('Status updated successfully');
        } else {
            $this->setSessionError('Failed to update status');
        }

        $this->redirect("/users/show/$id");
    }

    /**
     * Tìm kiếm user
     */
    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';
        $users = $this->getModel()->search($keyword);
        return $this->render('index', compact('users', 'keyword'));
    }

    /**
     * Lấy dữ liệu từ $_POST
     */
    public function getPostData($isUpdate = false)
    {
        $data = [
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? '',
            'full_name' => $_POST['full_name'] ?? '',
            'date_of_birth' => $_POST['date_of_birth'] ?? null,
            'gender' => $_POST['gender'] ?? null,
            'phone' => $_POST['phone'] ?? null,
            'address' => $_POST['address'] ?? null,
            'member_type' => $_POST['member_type'] ?? 'standard',
            'max_books' => $_POST['max_books'] ?? 5,
            'role_id' => $_POST['role_id'] ?? null
        ];

        // Chỉ thêm password khi tạo mới hoặc có nhập password khi update
        if (!$isUpdate || !empty($_POST['password'])) {
            $data['password'] = $_POST['password'] ?? '';
        }

        return $data;
    }

    /**
     * Validate dữ liệu user
     */
    private function validateUserData($data)
    {
        $required = ['username', 'email', 'full_name'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    /**
     * Set session error
     */
    private function setSessionError($message)
    {
        $_SESSION['error'] = $message;
    }

    /**
     * Set session success
     */
    private function setSessionSuccess($message)
    {
        $_SESSION['success'] = $message;
    }

    /**
     * Redirect to url
     */
    private function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * Override phương thức redirectToIndex
     */
    protected function redirectToIndex($model)
    {
        $this->redirect('/users');
    }
}