<?php
include_once 'models/User.php';
include_once 'models/Role.php';

class UserController extends Controller
{
    private $user;
    private $role;

    public function __construct()
    {
        $this->user = new User();
        $this->role = new Role();
    }

    public function index()
    {
        $users = $this->user->read();
        $roles = $this->role->read();
        $content = 'views/users/index.php';
        include('views/layouts/base.php');
    }
    public function member()
    {
        $users = $this->user->read();
        $content = 'views/members/index.php';
        include('views/layouts/base.php');
    }
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Lưu dữ liệu form vào session trước khi xử lý
                $_SESSION['form_data'] = $_POST;
                
                $this->user->status = 'inactive';
                $this->user->role_id = isset($_POST['role_id']) ? $_POST['role_id'] : 1;
                
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->user, $key) && $key !== 'password') {
                        $this->user->$key = strip_tags(trim($value));
                    }
                }
                
                if (!empty($_POST['password'])) {
                    $this->user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }
                
                if ($this->user->create()) {
                    $_SESSION['message'] = 'User created successfully!';
                    $_SESSION['message_type'] = 'success';
                    // Xóa dữ liệu form khi thành công
                    unset($_SESSION['form_data']);
                    header("Location: index.php?model=user&action=index");
                    exit();
                } else {
                    throw new Exception('Failed to create user');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = 'Trùng email hoặc tên đăng nhập. Vui lòng thử lại!';
                $_SESSION['message_type'] = 'danger';
                $roles = $this->role->read();
                $content = 'views/users/create.php';
                include('views/layouts/base.php');
                return;
            }
        }
        
        $roles = $this->role->read();
        $content = 'views/users/create.php';
        include('views/layouts/base.php');
    }


    public function edit($id) {
    
        $user = $this->user->readById($id);
        if (!$user) {
            $_SESSION['message'] = 'User not found!';
            $_SESSION['message_type'] = 'danger';
            header("Location: index.php?model=user&action=index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->user, $key) && $key !== 'password') {
                        $this->user->$key = strip_tags(trim($value));
                    }
                }

                if (!empty($_POST['password'])) {
                    $this->user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }
                
                if ($this->user->update($id)) {
                    $_SESSION['message'] = 'User updated successfully!';
                    $_SESSION['message_type'] = 'success';
                } else {
                    throw new Exception('Failed to update user');
                }
                
                header("Location: index.php?model=user&action=index");
                exit();
            } catch (Exception $e) {
                $_SESSION['message'] =  'Trùng email hoặc tên đăng nhập. Vui lòng thử lại!';
                $_SESSION['message_type'] = 'danger';
            }
        }

        $roles = $this->role->read();
        $content = 'views/users/edit.php';
        include('views/layouts/base.php');
    }

    public function delete($id) {
        try {
            if ($this->user->delete($id)) {
                $_SESSION['message'] = 'Xóa người dùng thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Xóa không thành công!');
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }
        
        header("Location: index.php?model=user&action=index");
        exit();
    }
    public function statistics()
    {
        // $data = $this->user->getUserStatistics();
        // $usersByRole = $data['usersByRole'];
        // $totalUsers = $data['totalUsers'];
        // $content = 'views/thongke/user.php';
        // include('views/layouts/base.php');
    }

    public function roleDetail($role_id)
    {
        // $users = $this->user->getUsersByRoleId($role_id);
        // include('views/user/role_detail.php');
    }
}