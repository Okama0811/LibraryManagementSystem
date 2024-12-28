<?php
include_once 'models/Category.php';

class CategoryController extends Controller
{
    private $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    public function index()
    {
        $categories = $this->category->read();
        $content = 'views/categories/index.php';
        include('views/layouts/base.php');
    }
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Store form data in session before processing
                $_SESSION['form_data'] = $_POST;

                foreach ($_POST as $key => $value) {
                    if (property_exists($this->category, $key)) {
                        $this->category->$key = strip_tags(trim($value));
                    }
                }

                if ($this->category->create()) {
                    $_SESSION['message'] = 'category created successfully!';
                    $_SESSION['message_type'] = 'success';
                    unset($_SESSION['form_data']); // Clear form data on success
                    header("Location: index.php?model=category&action=index");
                    exit();
                } else {
                    throw new Exception('Failed to create category');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = 'Error creating category. Please try again!';
                $_SESSION['message_type'] = 'danger';
                $content = 'views/categories/create.php';
                include('views/layouts/base.php');
                return;
            }
        }

        $content = 'views/categories/create.php';
        include('views/layouts/base.php');
    }

    public function edit($id)
    {
        $this->category = new Category();
        $categoryData = $this->category->readById($id);
    
        // Kiểm tra xem dữ liệu có tồn tại không
        if (!$categoryData) {
            $_SESSION['message'] = 'Danh mục không tồn tại!';
            $_SESSION['message_type'] = 'danger';
            header("Location: index.php?model=category&action=index");
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Chỉ xử lý khi là POST
            try {
                // Chỉ cập nhật các trường được phép
                $allowedFields = ['name', 'description'];
                foreach ($allowedFields as $field) {
                    if (isset($_POST[$field]) && !empty($_POST[$field])) {
                        $this->category->$field = strip_tags(trim($_POST[$field]));
                    } else {
                        $this->category->$field = $categoryData[$field]; // Giữ giá trị cũ nếu không có dữ liệu mới
                    }
                }
    
                $this->category->category_id = $categoryData['category_id'];
    
                // Thực hiện cập nhật
                if ($this->category->update($id)) {
                    $_SESSION['message'] = 'Cập nhật thông tin danh mục thành công!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=category&action=edit&id=$id");
                    exit();
                } else {
                    throw new Exception('Không thể cập nhật thông tin!');
                }
            } catch (Exception $e) {
                // Lưu thông báo lỗi vào session để hiển thị
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }
    
        // Hiển thị dữ liệu cho giao diện chỉnh sửa
        $category = $categoryData;
        $content = 'views/categories/edit.php';
        include('views/layouts/base.php');
    }

    public function delete($id)
    {
        try {
            if ($this->category->delete($id)) {
                $_SESSION['message'] = 'category deleted successfully!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Failed to delete category');
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        header("Location: index.php?model=category&action=index");
        exit();
    }

    public function statistics()
    {
        // Add statistics logic here if needed
    }
}