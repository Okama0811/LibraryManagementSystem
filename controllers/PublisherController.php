<?php
include_once 'models/Publisher.php';

class PublisherController extends Controller
{
    private $publisher;

    public function __construct()
    {
        $this->publisher = new Publisher();
    }

    public function index()
    {
        $publisher = $this->publisher->read();
        $content = 'views/publishers/index.php';
        include('views/layouts/base.php');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->publisher, $key)) {
                        $this->publisher->$key = strip_tags(trim($value));
                    }
                }

                if ($this->publisher->create()) {
                    $_SESSION['message'] = 'Nhà xuất bản được tạo thành công!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=publisher&action=index");
                    exit();
                } else {
                    throw new Exception('Thêm nhà xuất bản không thành công');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = 'Lỗi khi tạo nhà xuất bản! Vui lòng thử lại.';
                $_SESSION['message_type'] = 'danger';
            }
        }
        $content = 'views/publishers/create.php';
        include('views/layouts/base.php');
    }


    public function edit($id)
    {
        $this->publisher = new Publisher();
        $publisherData = $this->publisher->readById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Handle avatar upload
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                    // Xử lý upload ảnh
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
                        // Delete old avatar if exists
                        if (!empty($publisherData['avatar_url'])) {
                            $oldAvatarPath = $uploadDir . $publisherData['avatar_url'];
                            if (file_exists($oldAvatarPath)) {
                                unlink($oldAvatarPath);
                            }
                        }
                        
                        $this->publisher->avatar_url = $newFileName;
                        if($this->publisher->updateAvatar($id)){
                            header("Location: index.php?model=publisher&action=edit&id=$id");
                        } else {
                            throw new Exception('Không thể cập nhật thông tin!');
                        }
                    } else {
                        throw new Exception('Có lỗi khi upload ảnh!');
                    }
                    // ...
                } else {
                    $this->publisher->avatar_url = $publisherData['avatar_url'];
                }

                // Cập nhật các trường dữ liệu khác
                $allowedFields = ['name', 'email', 'phone', 'address', 'website'];
                foreach ($allowedFields as $field) {
                    if (isset($_POST[$field])) {
                        $this->publisher->$field = strip_tags(trim($_POST[$field]));
                    } else {
                        $this->publisher->$field = $publisherData[$field];
                    }
                }

                $this->publisher->publisher_id = $publisherData['publisher_id'];

                if ($this->publisher->update($id)) {
                    $_SESSION['message'] = 'Cập nhật thông tin nhà xuất bản thành công!';
                    $_SESSION['message_type'] = 'success';
                } else {
                    throw new Exception('Không thể cập nhật thông tin!');
                }

                // Chuyển hướng cuối cùng sau khi cập nhật
                header("Location: index.php?model=publisher&action=edit&id=$id");
                exit();

            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }

        $publisher = $publisherData;
        $content = 'views/publishers/edit.php';
        include('views/layouts/base.php');
    }

    public function delete($id)
    {
        try {
            if ($this->publisher->delete($id)) {
                $_SESSION['message'] = 'Xóa nhà xuất bản thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Không thể xóa nhà xuất bản!');
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }
        header("Location: index.php?model=publisher&action=index");
        exit();
    }
}
