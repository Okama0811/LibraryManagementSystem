<?php
include_once 'models/User.php';
include_once 'models/Role.php';

class AuthController extends Controller {

    private $authModel;
    public function __construct() {
        $this->authModel = new User();
    }

    public function register() {
        $error_msg = '';
        $formData = [
            'email' => '',
            'username' => '',
            'full_name' => '',
            'date_of_birth' => '',
            'gender' => '',
            'phone' => '',
            'address' => ''
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = [
                'email' => $_POST['email'] ?? '',
                'username' => $_POST['username'] ?? '',
                'full_name' => $_POST['full_name'] ?? '',
                'date_of_birth' => $_POST['date_of_birth'] ?? '',
                'gender' => $_POST['gender'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? ''
            ];
            
            // Kiểm tra điều khoản
            if (!isset($_POST['terms']) || $_POST['terms'] != '1') {
                $error_msg = "Vui lòng đồng ý với điều khoản sử dụng";
            }
            // Kiểm tra độ dài mật khẩu
            else if (strlen($_POST['password']) < 6) {
                $error_msg = "Mật khẩu phải có ít nhất 6 ký tự";
            }
            // Kiểm tra mật khẩu xác nhận
            else if ($_POST['password'] !== $_POST['confirm_password']) {
                $error_msg = "Mật khẩu xác nhận không khớp";
            }
            else {
                // Kiểm tra email tồn tại
                if ($this->authModel->checkEmailExists($_POST['email'])) {
                    $error_msg = "Email đã được sử dụng, vui lòng sử dụng email khác";
                }
                // Kiểm tra số điện thoại tồn tại
                else if ($this->authModel->checkPhoneExists($_POST['phone'])) {
                    $error_msg = "Số điện thoại đã được sử dụng, vui lòng sử dụng số khác";
                }
                // Kiểm tra username tồn tại
                else if ($this->authModel->checkUsernameExists($_POST['username'])) {
                    $error_msg = "Tên đăng nhập đã được sử dụng, vui lòng chọn tên khác";
                }
                else {
                    $this->authModel->email = $_POST['email'];
                    $this->authModel->username = $_POST['username'];
                    $this->authModel->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $this->authModel->full_name = $_POST['full_name'];
                    $this->authModel->date_of_birth = $_POST['date_of_birth'];
                    $this->authModel->gender = $_POST['gender'];
                    $this->authModel->phone = $_POST['phone'];
                    $this->authModel->address = $_POST['address'];
                    
                    try {
                        if ($this->authModel->create()) {
                            header('Location: index.php?model=auth&action=register_success');
                            exit();
                        } else {
                            $error_msg = "Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại sau!";
                        }
                    } catch (Exception $e) {
                        $error_msg = "Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại sau!";
                    }
                }
            }
        }
        
        include('views/auth/register.php');
    }
    public function register_success() {
        include('views/auth/register_success.php');
    }    public function login() {
        $error_msg ='';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $this->authModel = new User();
            $role = new Role();
            if ($this->authModel->authenticate($username,$password)) {
                session_start();
                $_SESSION['user_id'] = $this->authModel->user_id;
                $_SESSION['username'] = $this->authModel->username;
                $_SESSION['role_id'] = $this->authModel->role_id;
                $_SESSION['full_name'] = $this->authModel->full_name;
                $_SESSION['avatar_url'] = $this->authModel->avatar_url;
                $_SESSION['role_id']=$this->authModel->role_id;
                $role->readById($this->authModel->role_id);
                $_SESSION['role_name']=$role->name;
                header('Location: dashboard.php');
                exit();
            } else {
                $error_msg = "Tên đăng nhập hoặc mật khẩu không chính xác.";
            }
        }
        include('views/auth/login.php');
    }
    public function logout() {
        session_destroy();
        header('Location: index.php?model=auth&action=login');
        exit();
    }
    public function edit($user_id) {
        $this->authModel = new User();
        $userData = $this->authModel->readById($_SESSION['user_id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['avatar'];
                    $fileName = $file['name'];
                    $fileTmpName = $file['tmp_name'];
                    $fileSize = $file['size'];
                    $fileType = $file['type'];
                    
                    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    $maxSize = 2 * 1024 * 1024;
                    
                    if (!in_array($fileType, $allowedTypes)) {
                        throw new Exception('Chỉ chấp nhận file ảnh định dạng JPG, JPEG hoặc PNG!');
                    }
                    
                    if ($fileSize > $maxSize) {
                        throw new Exception('Kích thước file không được vượt quá 2MB!');
                    }
    
                    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $newFileName = uniqid() . '.' . $fileExtension;
                    
                    $uploadDir = 'uploads/avatars/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $uploadPath = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($fileTmpName, $uploadPath)) {
                        if (!empty($userData['avatar'])) {
                            $oldAvatarPath = $uploadDir . $userData['avatar'];
                            if (file_exists($oldAvatarPath)) {
                                unlink($oldAvatarPath);
                            }
                        }
                        
                        $this->authModel->avatar_url = $newFileName;
                        if($this->authModel->updateAvatar($_SESSION['user_id'])){
                            $_SESSION['avatar_url'] = $this->authModel->avatar_url;
                            header("Location: index.php?model=auth&action=edit");
                            exit();
                        } else {
                            throw new Exception('Không thể cập nhật thông tin!');
                        }
                    } else {
                        throw new Exception('Có lỗi khi upload ảnh!');
                    }
                } else {
                    $this->authModel->avatar_url = $userData['avatar_url'];
                }
    
              
                $allowedFields = ['email', 'username', 'full_name', 'phone', 'address','date_of_birth','gender','expiry_date','member_type','max_books','note']; 
                foreach ($allowedFields as $field) {
                    if (isset($_POST[$field])) {
                        $this->authModel->$field = strip_tags(trim($_POST[$field]));
                    } else {
                        $this->authModel->$field = $userData[$field];
                    }
                }
    
                $this->authModel->user_id = $userData['user_id'];
                $this->authModel->role_id = $userData['role_id'];
                $this->authModel->status = $userData['status'];
                $this->authModel->created_at = $userData['created_at'];
                $this->authModel->updated_at = date('Y-m-d H:i:s');
    
                if (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
                    if (empty($_POST['current_password']) || empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
                        echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin!']);
                        exit();
                    }
                    
                    if (!password_verify($_POST['current_password'], $userData['password'])) {
                        echo json_encode(['status' => 'error', 'message' => 'Mật khẩu hiện tại không đúng!']);
                        exit();
                    }
                    
                    if ($_POST['new_password'] !== $_POST['confirm_password']) {
                        echo json_encode(['status' => 'error', 'message' => 'Mật khẩu mới và xác nhận mật khẩu không khớp!']);
                        exit();
                    }
                    
                    if (strlen($_POST['new_password']) < 6) {
                        echo json_encode(['status' => 'error', 'message' => 'Mật khẩu mới phải dài ít nhất 6 ký tự!']);
                        exit();
                    }
                    
                    $this->authModel->password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    $this->authModel->user_id = $_SESSION['user_id'];
                    
                    if ($this->authModel->update($_SESSION['user_id'])) {
                        $_SESSION['message'] = 'Cập nhật thông tin thành công!';
                        $_SESSION['message_type'] = 'success';
                        $_SESSION['user_id'] = $this->authModel->user_id;
                        $_SESSION['username'] = $this->authModel->username;
                        $_SESSION['role_id'] = $this->authModel->role_id;
                        $_SESSION['full_name'] = $this->authModel->full_name;
                        $_SESSION['avatar_url'] = $this->authModel->avatar_url;
                        echo json_encode(['status' => 'success', 'message' => 'Đổi mật khẩu thành công!']);
                        exit();
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Không thể cập nhật mật khẩu!']);
                        exit();
                    }
                }
                else {
                    $this->authModel->password = $userData['password'];
                }
                if ($this->authModel->update($_SESSION['user_id'])) {
                    $_SESSION['message'] = 'Cập nhật thông tin thành công!';
                    $_SESSION['message_type'] = 'success';
                    $_SESSION['user_id'] = $this->authModel->user_id;
                    $_SESSION['username'] = $this->authModel->username;
                    $_SESSION['role_id'] = $this->authModel->role_id;
                    $_SESSION['full_name'] = $this->authModel->full_name;
                    $_SESSION['avatar_url'] = $this->authModel->avatar_url;
                    
                    header("Location: index.php?model=auth&action=edit");
                    exit();
                } else {
                    throw new Exception('Không thể cập nhật thông tin!');
                }
    
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }
    
        $user = $userData;
        $content = 'views/auth/edit.php';
        include('views/layouts/base.php');
    }
}
?>