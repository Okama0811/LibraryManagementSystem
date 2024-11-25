<?php
include_once 'models/Author.php';

class AuthorController extends Controller
{
    private $author;

    public function __construct()
    {
        $this->author = new Author();
    }

    public function index()
    {
        $authors = $this->author->read();
        $content = 'views/authors/index.php';
        include('views/layouts/base.php');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Store form data in session before processing
                $_SESSION['form_data'] = $_POST;

                foreach ($_POST as $key => $value) {
                    if (property_exists($this->author, $key)) {
                        $this->author->$key = strip_tags(trim($value));
                    }
                }

                if ($this->author->create()) {
                    $_SESSION['message'] = 'Author created successfully!';
                    $_SESSION['message_type'] = 'success';
                    unset($_SESSION['form_data']); // Clear form data on success
                    header("Location: index.php?model=author&action=index");
                    exit();
                } else {
                    throw new Exception('Failed to create author');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = 'Error creating author. Please try again!';
                $_SESSION['message_type'] = 'danger';
                $content = 'views/authors/create.php';
                include('views/layouts/base.php');
                return;
            }
        }

        $content = 'views/authors/create.php';
        include('views/layouts/base.php');
    }

    public function edit($id)
    {
        
        $this->author = new Author();
        $authorData = $this->author->readById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Handle avatar upload
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
                    
                    $uploadDir = 'uploads/author_avatars/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $uploadPath = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($fileTmpName, $uploadPath)) {
                        // Delete old avatar if exists
                        if (!empty($authorData['avatar_url'])) {
                            $oldAvatarPath = $uploadDir . $authorData['avatar_url'];
                            if (file_exists($oldAvatarPath)) {
                                unlink($oldAvatarPath);
                            }
                        }
                        
                        $this->author->avatar_url = $newFileName;
                        if($this->author->updateAvatar($id)){
                            header("Location: index.php?model=author&action=edit&id=$id");
                            exit();
                        } else {
                            throw new Exception('Không thể cập nhật thông tin!');
                        }
                    } else {
                        throw new Exception('Có lỗi khi upload ảnh!');
                    }
                } else {
                    $this->author->avatar_url = $authorData['avatar_url'];
                }

                // Update other fields
                $allowedFields = ['name', 'nationality', 'birth_date', 'biography'];
                foreach ($allowedFields as $field) {
                    if (isset($_POST[$field])) {
                        $this->author->$field = strip_tags(trim($_POST[$field]));
                    } else {
                        $this->author->$field = $authorData[$field];
                    }
                }

                $this->author->author_id = $authorData['author_id'];

                if ($this->author->update($id)) {
                    $_SESSION['message'] = 'Cập nhật thông tin tác giả thành công!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=author&action=edit&id=$id");
                    exit();
                } else {
                    throw new Exception('Không thể cập nhật thông tin!');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }

        $author = $authorData;
        $content = 'views/authors/edit.php';
        include('views/layouts/base.php');
    }

    public function delete($id)
    {
        try {
            if ($this->author->delete($id)) {
                $_SESSION['message'] = 'Author deleted successfully!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Failed to delete author');
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        header("Location: index.php?model=author&action=index");
        exit();
    }

    public function statistics()
    {
        // Add statistics logic here if needed
    }
}