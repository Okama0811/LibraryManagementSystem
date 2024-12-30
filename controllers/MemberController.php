<?php
include_once 'models/User.php';
include_once 'models/Role.php';

class MemberController extends Controller {

    private $userModel;
    private $fineModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->fineModel = new Fine();
    }

    public function edit($id) {

        // Lấy thông tin user hiện tại
        $userData = $this->userModel->readById($_SESSION['user_id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // var_dump("cáhdmsadm");
                // exit();
                // Xử lý upload avatar
                $this->handleAvatarUpload($userData);
                
                // Xử lý cập nhật thông tin cơ bản
                $this->handleBasicInfoUpdate($userData);
                
                // Xử lý đổi mật khẩu nếu có
                $this->handlePasswordChange($userData);
                
                // Thực hiện cập nhật
                if ($this->userModel->update($_SESSION['user_id'])) {
                    $this->updateSession();
                    
                    header("Location: index.php?model=member&action=edit");
                    exit();
                } else {
                    throw new Exception('Không thể cập nhật thông tin!');
                }

            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }

        // Hiển thị view
        $user = $userData;
        $content = 'views/member/edit.php';
        include('views/layouts/application.php');
    }

    private function handleAvatarUpload($userData) {
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['avatar'];
            
            // Validate file
            $this->validateAvatar($file);
            
            // Xử lý upload
            $uploadDir = 'uploads/avatars/';
            $newFileName = $this->processAvatarUpload($file, $uploadDir);
            
            // Xóa avatar cũ nếu có
            $this->removeOldAvatar($userData['avatar_url'], $uploadDir);
            
            // Cập nhật đường dẫn avatar mới
            $this->userModel->avatar_url = $newFileName;
        } else {
            $this->userModel->avatar_url = $userData['avatar_url'];
        }
    }

    private function validateAvatar($file) {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Chỉ chấp nhận file ảnh định dạng JPG, JPEG hoặc PNG!');
        }
        
        if ($file['size'] > $maxSize) {
            throw new Exception('Kích thước file không được vượt quá 2MB!');
        }
    }

    private function processAvatarUpload($file, $uploadDir) {
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $newFileName;
        
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            throw new Exception('Có lỗi khi upload ảnh!');
        }
        
        return $newFileName;
    }

    private function removeOldAvatar($oldAvatar, $uploadDir) {
        if (!empty($oldAvatar)) {
            $oldAvatarPath = $uploadDir . $oldAvatar;
            if (file_exists($oldAvatarPath)) {
                unlink($oldAvatarPath);
            }
        }
    }

    private function handleBasicInfoUpdate($userData) {
        $allowedFields = [
            'email', 'username', 'full_name', 'phone', 
            'address', 'date_of_birth', 'gender', 
            'expiry_date', 'member_type', 'max_books', 'note'
        ];
        
        foreach ($allowedFields as $field) {
            if (isset($_POST[$field])) {
                $this->userModel->$field = strip_tags(trim($_POST[$field]));
            } else {
                $this->userModel->$field = $userData[$field];
            }
        }

        // Giữ nguyên các trường không được phép thay đổi
        $this->userModel->user_id = $userData['user_id'];
        $this->userModel->role_id = $userData['role_id'];
        $this->userModel->status = $userData['status'];
        $this->userModel->created_at = $userData['created_at'];
        $this->userModel->updated_at = date('Y-m-d H:i:s');
    }

    private function handlePasswordChange($userData) {
        if (isset($_POST['current_password']) && 
            isset($_POST['new_password']) && 
            isset($_POST['confirm_password'])) {
            
            $this->validatePasswordChange(
                $_POST['current_password'],
                $_POST['new_password'],
                $_POST['confirm_password'],
                $userData['password']
            );
            
            $this->userModel->password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        } else {
            $this->userModel->password = $userData['password'];
        }
    }

    private function validatePasswordChange($currentPassword, $newPassword, $confirmPassword, $storedPassword) {
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            throw new Exception('Vui lòng điền đầy đủ thông tin mật khẩu!');
        }
        
        if (!password_verify($currentPassword, $storedPassword)) {
            throw new Exception('Mật khẩu hiện tại không đúng!');
        }
        
        if ($newPassword !== $confirmPassword) {
            throw new Exception('Mật khẩu mới và xác nhận mật khẩu không khớp!');
        }
        
        if (strlen($newPassword) < 6) {
            throw new Exception('Mật khẩu mới phải dài ít nhất 6 ký tự!');
        }
    }

    private function updateSession() {
        $_SESSION['user_id'] = $this->userModel->user_id;
        $_SESSION['username'] = $this->userModel->username;
        $_SESSION['role_id'] = $this->userModel->role_id;
        $_SESSION['full_name'] = $this->userModel->full_name;
        $_SESSION['avatar_url'] = $this->userModel->avatar_url;
    }
    public function change_password($id) {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?model=auth&action=login');
            exit();
        }
    
        // Kiểm tra xem id có khớp với người dùng hiện tại không
        if ($_SESSION['user_id'] != $id) {
            $_SESSION['message'] = 'Bạn không có quyền thực hiện hành động này!';
            $_SESSION['message_type'] = 'danger';
            header('Location: index.php?model=member&action=edit');
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Lấy dữ liệu từ form
                $currentPassword = $_POST['current_password'];
                $newPassword = $_POST['new_password'];
                $confirmPassword = $_POST['confirm_password'];
    
                // Lấy thông tin user hiện tại
                $userData = $this->userModel->readById($id);
    
                // Kiểm tra mật khẩu hiện tại
                if (!password_verify($currentPassword, $userData['password'])) {
                    throw new Exception('Mật khẩu hiện tại không đúng!');
                }
    
                // Kiểm tra mật khẩu mới và xác nhận mật khẩu
                if ($newPassword !== $confirmPassword) {
                    throw new Exception('Mật khẩu mới và xác nhận mật khẩu không khớp!');
                }
    
                // Kiểm tra độ dài mật khẩu mới
                if (strlen($newPassword) < 6) {
                    throw new Exception('Mật khẩu mới phải có ít nhất 6 ký tự!');
                }
    
                // Cập nhật mật khẩu mới
                $this->userModel->password = password_hash($newPassword, PASSWORD_DEFAULT);
                $this->userModel->updated_at = date('Y-m-d H:i:s');
    
                // Thực hiện cập nhật
                if ($this->userModel->updatePassword($id, $this->userModel->password)) {
                    $_SESSION['message'] = 'Đổi mật khẩu thành công!';
                    $_SESSION['message_type'] = 'success';
                    header('Location: index.php?model=member&action=edit');
                    exit();
                } else {
                    throw new Exception('Không thể cập nhật mật khẩu!');
                }
    
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }
    
        // Hiển thị form đổi mật khẩu
        $content = 'views/member/change-password.php';
        include('views/layouts/application.php');
    }

    public function fines($id) {
        $fines = $this->fineModel->getFinesByMemberId($id);

        $content = 'views/member/fines.php';
        include('views/layouts/application.php');
    }

    public function show($id) {
        $fine = $this->fineModel->readById($id);

        $content = 'views/member/show_fine.php';
        include('views/layouts/application.php');
    }

    public function pay() {
        // Kiểm tra xem người dùng đã gửi form thanh toán chưa
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_fines'])) {
            $selectedFines = $_POST['selected_fines']; // Các ID của hóa đơn được chọn
            
            if (isset($_FILES['payment_image']) && $_FILES['payment_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['payment_image'];
                $fileName = $file['name'];
                $fileTmpName = $file['tmp_name'];
                $fileSize = $file['size'];
                $fileType = $file['type'];
    
                // Kiểm tra định dạng và kích thước file
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                $maxSize = 2 * 1024 * 1024; // 2MB
    
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['alert'] = 'Chỉ chấp nhận file ảnh định dạng JPG, JPEG hoặc PNG!';
                    header('Location: index.php?model=member&action=pay');
                    exit;
                }
    
                if ($fileSize > $maxSize) {
                    $_SESSION['alert'] = 'Kích thước file không được vượt quá 2MB!';
                    header('Location: index.php?model=member&action=pay');
                    exit;
                }
    
                // Tạo tên file mới và đường dẫn upload
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadDir = 'uploads/payments/';
                
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $uploadFilePath = $uploadDir . $newFileName;
    
                if (move_uploaded_file($fileTmpName, $uploadFilePath)) {
                    // Lặp qua từng hóa đơn được chọn và cập nhật trạng thái
                    foreach ($selectedFines as $fineId) {
                        $this->fineModel->updateFineStatus($fineId, [
                            'status' => 'pending', // Cập nhật trạng thái thành 'chờ thanh toán'
                            'payment_method' => 'Chuyển khoản', // Cập nhật phương thức thành 'chuyển khoản'
                            'notes' => $newFileName, // Lưu đường dẫn ảnh vào notes
                            'returned_date' => date('Y-m-d') // Lưu ngày hiện tại vào returned_date
                        ]);
                    }
    
                    // Chuyển hướng người dùng sau khi thanh toán thành công
                    $_SESSION['message'] = 'Thanh toán đang chờ xử lý!';
                    $_SESSION['message_type'] = 'success';
                    header('Location: index.php?model=member&action=fines&id=' . $_SESSION['user_id']);
                    exit;
                } else {
                    $_SESSION['alert'] = 'Không thể tải ảnh lên. Vui lòng thử lại!';
                    header('Location: index.php?model=member&action=pay');
                    exit;
                }
            } else {
                $_SESSION['alert'] = 'Vui lòng tải ảnh xác nhận thanh toán!';
                header('Location: index.php?model=member&action=pay');
                exit;
            }
        }
    
        // Lấy danh sách phiếu phạt và thông tin người dùng để hiển thị
        $fines = $this->fineModel->getFinesByMemberId($_SESSION['user_id']);
        $user = $this->userModel->readById($_SESSION['user_id']);
        
        $content = 'views/member/pay.php';
        include('views/layouts/application.php');
    }
    
}
?>