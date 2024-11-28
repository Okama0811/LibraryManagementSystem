<?php
include_once 'models/Book.php';

class BookController extends Controller
{
    private $book;

    public function __construct()
    {
        $this->book = new Book();
    }

    public function index()
    {
        $books = $this->book->read();
        $content = 'views/books/index.php';
        include('views/layouts/base.php');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Set book properties from POST data
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->book, $key)) {
                        $this->book->$key = strip_tags(trim($value));
                    }
                }

                // Handle cover image upload
                if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['cover_image'];
                    $fileName = $file['name'];
                    $fileTmpName = $file['tmp_name'];
                    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $uploadDir = 'uploads/covers/';
                    
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $uploadPath = $uploadDir . $newFileName;
                    if (move_uploaded_file($fileTmpName, $uploadPath)) {
                        $this->book->cover_image = $newFileName;
                    } else {
                        throw new Exception('Không thể tải ảnh bìa!');
                    }
                }

                if ($this->book->create()) {
                    $_SESSION['message'] = 'Thêm sách mới thành công!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=book&action=index");
                    exit();
                } else {
                    throw new Exception('Thêm sách không thành công.');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }

        $publishers = $this->book->getPublishers();
        $authors = $this->book->getAuthors();
        $categories = $this->book->getCategories();
        $content = 'views/books/create.php';
        include('views/layouts/base.php');
    }

    public function edit($id)
    {
        $bookData = $this->book->readById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Update book details
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->book, $key)) {
                        $this->book->$key = strip_tags(trim($value));
                    }
                }

                // Handle cover image update
                if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['cover_image'];
                    $fileName = $file['name'];
                    $fileTmpName = $file['tmp_name'];
                    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $uploadDir = 'uploads/covers/';
                    
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $uploadPath = $uploadDir . $newFileName;
                    if (move_uploaded_file($fileTmpName, $uploadPath)) {
                        if (!empty($bookData['cover_image'])) {
                            $oldCoverPath = $uploadDir . $bookData['cover_image'];
                            if (file_exists($oldCoverPath)) {
                                unlink($oldCoverPath);
                            }
                        }
                        $this->book->cover_image = $newFileName;
                    } else {
                        throw new Exception('Không thể cập nhật ảnh bìa!');
                    }
                }

                if ($this->book->update($id)) {
                    $_SESSION['message'] = 'Cập nhật sách thành công!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=book&action=index");
                    exit();
                } else {
                    throw new Exception('Cập nhật sách không thành công.');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }

        $publishers = $this->book->getPublishers();
        $authors = $this->book->getAuthors();
        $categories = $this->book->getCategories();
        $book = $bookData;
        $content = 'views/books/edit.php';
        include('views/layouts/base.php');
    }

    public function delete($id)
    {
        try {
            if ($this->book->delete($id)) {
                $_SESSION['message'] = 'Xóa sách thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Xóa sách không thành công.');
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }
        header("Location: index.php?model=book&action=index");
        exit();
    }
   
}
?>
