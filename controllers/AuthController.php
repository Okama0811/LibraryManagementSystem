<?php
include_once 'models/User.php';


class AuthController extends Controller {

    private $authModel;
    public function __construct() {
        $this->authModel = new User();
    }

    public function register() {
        $error_msg ='';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // var_dump($_POST);
            // exit();
            $this->authModel->email = $_POST['email'];
            $this->authModel->username= $_POST['username'];
            $this->authModel->password= password_hash($_POST['password'], PASSWORD_DEFAULT);
            $this->authModel->full_name= $_POST['full_name'];
            $this->authModel->date_of_birth= $_POST['date_of_birth'];
            $this->authModel->gender= $_POST['gender'];
            $this->authModel->phone= $_POST['phone'];
            $this->authModel->address=$_POST['address'];
            if ($this->authModel->create()) {
                header('Location: index.php?model=auth&action=login');
                exit();
            } else {
                $error_msg = "Đăng ký không thành công";
            }
        }
        include('views/auth/register.php');
    }

    public function login() {
        $error_msg ='';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $this->authModel = new User();
            if ($this->authModel->authenticate($email,$password)) {
                session_start();
                $_SESSION['user_id'] = $this->authModel->user_id;
                $_SESSION['email'] = $this->authModel->email;
                $_SESSION['role_id'] = $this->authModel->role_id;
                $_SESSION['full_name'] = $this->authModel->full_name;
                // $_SESSION['avatar'] = $this->authModel->;
                header('Location: dashboard.php');
                exit();
            } else {
                $error_msg = "Email hoặc mật khẩu không chính xác.";
            }
        }
        include('views/auth/login.php');
    }
    public function logout() {
        session_destroy();
        header('Location: index.php?model=auth&action=login');
        exit();
    }
    // public function profile() {
    //     if (!isset($_SESSION['email'])) {
    //         // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    //         header('Location: index.php?model=auth&action=login');
    //         exit();
    //     }

    //     // Lấy thông tin người dùng từ CSDL dựa trên $_SESSION['user_id']
    //     $user = $this->authModel->getUserByEmail($_SESSION['email']);

    //     // Nếu không tìm thấy người dùng, xử lý lỗi hoặc thông báo không tìm thấy
    //     if (!$user) {
    //         echo "Không tìm thấy thông tin người dùng.";
    //         exit();
    //     }

    //     // Include view để hiển thị thông tin người dùng
    //     $content = 'views/auth/profile.php';
    //     include('views/layouts/base.php');
    // }
    // public function edit($id) {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $email = $_POST['email'];
    //         $ten = $_POST['ten'];
    //         $avatarPath = $_SESSION['avatar'];
    //         $password = null;
    //         $current_password = $_POST['current_password'];
            
            
    //         if (!empty($_POST['new_password'])) {
    //             if (!$this->authModel->checkCurrentPassword($id, $current_password)) {
    //                 $_SESSION['message'] = 'Mật khẩu không chính xác.';
    //                 $_SESSION['message_type'] = 'danger';
    //                 header("Location: index.php?model=auth&action=profile");
    //                 exit();
    //             }
    //             $password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    //         }
    
    //         if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                
    //             $uploadDir = 'uploads/avatars/';
    //             $uploadFile = $uploadDir . basename($_FILES['avatar']['name']);
                
    //             if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile)) {
    //                 $avatarPath = $uploadFile;
    //             } else {
    //                 echo "Không thể tải lên tệp. Vui lòng kiểm tra quyền thư mục.";
    //                 return;
    //             }
    //         }
    //         $email= $_SESSION['email'];
    //         if ($this->authModel->updateUserProfile($id, $email, $ten, $avatarPath, $password)) {
    //             $_SESSION['ten'] = $ten;
    //             if ($avatarPath) {
    //                 $_SESSION['avatar'] = $avatarPath;
    //             }
    //             if ($password) {
    //                 $_SESSION['password'] = $password;
    //             }
    //             $_SESSION['message'] = 'Cập nhật thông tin thành công.';
    //             $_SESSION['message_type'] = 'success';
    //             header("Location: index.php?model=auth&action=profile");
    //             exit();
    //         } else {
    //             $_SESSION['message'] = 'Cập nhật thông tin không thành công.';
    //             $_SESSION['message_type'] = 'danger';
    //             header("Location: index.php?model=auth&action=profile");
    //         }
    //     } else {
    //         $user = $this->authModel->getUserByID($id);
    //         if (!$user) {
    //             echo "Không tìm thấy thông tin người dùng.";
    //             exit();
    //         }
    
    //         $content = 'views/auth/edit_profile.php';
    //         include('views/layouts/base.php');
    //     }
    // }
    
}
?>