<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Abstracts\Controller;
use App\Models\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->model = new Auth();
    }

    /**
     * Hiển thị form đăng nhập
     */
    public function loginForm()
    {
        return $this->render('login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($this->model->login($username, $password)) {
            // Chuyển hướng đến trang dashboard sau khi đăng nhập thành công
            header('Location: /dashboard');
            exit;
        }

        // Nếu đăng nhập thất bại, quay lại form với thông báo lỗi
        $_SESSION['error'] = 'Invalid username or password';
        header('Location: /login');
        exit;
    }

    /**
     * Hiển thị form đăng ký
     */
    public function registerForm()
    {
        return $this->render('register');
    }

    /**
     * Xử lý đăng ký
     */
    public function register()
    {
        $data = [
            'username' => $_POST['username'] ?? '',
            'password' => $_POST['password'] ?? '',
            'email' => $_POST['email'] ?? '',
            'full_name' => $_POST['full_name'] ?? '',
            'date_of_birth' => $_POST['date_of_birth'] ?? null,
            'gender' => $_POST['gender'] ?? null,
            'phone' => $_POST['phone'] ?? null,
            'address' => $_POST['address'] ?? null
        ];

        // Validate dữ liệu
        if (!$this->validateRegistration($data)) {
            $_SESSION['error'] = 'Please fill in all required fields';
            header('Location: /register');
            exit;
        }

        if ($this->model->register($data)) {
            $_SESSION['success'] = 'Registration successful. Please login.';
            header('Location: /login');
            exit;
        }

        $_SESSION['error'] = 'Registration failed. Please try again.';
        header('Location: /register');
        exit;
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout()
    {
        $this->model->logout();
        header('Location: /login');
        exit;
    }

    /**
     * Validate dữ liệu đăng ký
     */
    private function validateRegistration($data)
    {
        $required = ['username', 'password', 'email', 'full_name'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }

        // Validate email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    /**
     * Override phương thức redirectToIndex
     */
    protected function redirectToIndex($model)
    {
        header('Location: /login');
        exit;
    }
}