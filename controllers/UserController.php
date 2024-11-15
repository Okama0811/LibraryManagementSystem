<?php

class UserController extends Controller
{
    private $db;
    private $user;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function index()
    {
        $users = $this->user->read();
        $content = 'views/users/index.php';
        include('views/layouts/base.php');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->username = $_POST['username'];
            $this->user->email = $_POST['email'];
            $this->user->role_id = $_POST['role_id'];
            $this->user->password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            if ($this->user->create()) {
                $_SESSION['message'] = 'User created successfully!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to create user!';
                $_SESSION['message_type'] = 'danger';
            }
            header("Location: index.php?model=user&action=index");
            exit();
        }

        $content = 'views/users/create.php';
        include('views/layouts/base.php');
    }

    public function edit($id)
    {
        $user = $this->user->readById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->user_id = $id;
            $this->user->username = $_POST['username'];
            $this->user->email = $_POST['email'];
            $this->user->role_id = $_POST['role_id'];
            if (!empty($_POST['password'])) {
                $this->user->password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            }

            if ($this->user->update($id)) {
                $_SESSION['message'] = 'User updated successfully!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to update user!';
                $_SESSION['message_type'] = 'danger';
            }
            header("Location: index.php?model=user&action=index");
            exit();
        }

        $content = 'views/users/edit.php';
        include('views/layouts/base.php');
    }

    public function delete($id)
    {
        if ($this->user->delete($id)) {
            $_SESSION['message'] = 'User deleted successfully!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Failed to delete user!';
            $_SESSION['message_type'] = 'danger';
        }
        header("Location: index.php?model=user&action=index");
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