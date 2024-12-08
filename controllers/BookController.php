<?php
include_once 'models/Book.php';
include_once 'models/Book_Author.php';
include_once 'models/Book_Category.php';
include_once 'models/Author.php';
include_once 'models/Category.php';
include_once 'models/Publisher.php';

class BookController extends Controller
{
    private $book;
    private $book_author;
    private $book_category;
    private $author;
    private $category;
    private $publisher;

    public function __construct()
    {
        $this->book = new Book();
        $this->book_author = new Book_Author();
        $this->book_category = new Book_Category();
        $this->author = new Author();
        $this->category = new Category();
        $this->publisher = new Publisher();
    }

    public function index()
    {
        $books = $this->book->read();
        $categories = $this->category->read();
        $publishers = $this->publisher->read();
        $authors = $this->author->read();
        $content = 'views/books/index.php';
        include('views/layouts/base.php');
    }


    public function create()
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                //    echo '<pre>';
                //     var_dump($_POST);
                //     var_dump($_FILES);
                //     echo '</pre>';
                //     die();

                    $authors = $_POST['authors']; // Mảng author_id
                    $categories = $_POST['categories']; // Mảng category_id

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

                    $book_id = $this->book->getLastInsertedId();
                    foreach ($authors as $author_id) {
                        $this->book_author->insertBookAuthor($book_id, $author_id);
                    }
            
                    // Thêm vào bảng book_category
                    foreach ($categories as $category_id) {
                        $this->book_category->insertBookCategory($book_id, $category_id);
                    }
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
        // var_dump($publishers);
        // var_dump($authors);
        // var_dump($categories);
        // die();

        $content = 'views/books/create.php';
        include('views/layouts/base.php');
    }

    public function edit($id)
    {
        $this->book = new Book();
        $bookData = $this->book->readById($id);
        // $bookId = $this->bookData->book_id;
        // $selectedAuthors = $this->book->getSelectedAuthorsByBookId($book_id);

            // echo '<pre>';
            // var_dump($bookData);
            // echo '</pre>';
            // die();
            // echo '<pre>';
            // var_dump($bookData);
            // var_dump($bookId);
            //             var_dump($authors);
            //             var_dump($categories);
            //             echo '</pre>';
            //             die();

        // $this->author = new Author();
        // $allAuthors = $authorModel->getAllAuthors();


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                    // echo '<pre>';
                    // var_dump($_POST);
                    // var_dump($_FILES);
                    // echo '</pre>';
                    // die();

                // $add_quantity = isset($_POST['add_quantity']) ? intval($_POST['add_quantity']) : 0;

                // Lấy số lượng hiện tại và số lượng sách đang được mượn
                // $current_quantity = $this->book->getCurrentQuantity($book_id); // Hàm lấy số lượng sách hiện có
                // $borrowed_quantity = $this->book->getBorrowedQuantity($book_id); // Hàm lấy số lượng sách đang được mượn
            
                // Tổng số lượng sau khi thêm
                // $new_total = $current_quantity + $add_quantity + $borrowed_quantity;
                // Update book details
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->book, $key)) {
                        $this->book->$key = strip_tags(trim($value));
                    }
                }

                 // Kiểm tra nếu tổng số lượng vượt quá số lượng hiện tại
                // if ($new_total > $max_quantity) {
                //     $error_message = "Tổng số lượng không thể vượt quá số lượng hiện tại ({$max_quantity}).";
                // } else {
                //     // Cập nhật số lượng sách có sẵn
                //     $updated_quantity = $current_quantity + $add_quantity;

                //     $result = updateAvailableQuantity($book_id, $updated_quantity);

                // }

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
                 
                    foreach ($authors as $author_id) {
                        $this->book_author->updateBookAuthor($book_id, $author_id);
                    
                    }
            
                    // Thêm vào bảng book_category
                    foreach ($categories as $category_id) {
                        $this->book_category->updateBookCategory($book_id, $category_id);
                    }

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
