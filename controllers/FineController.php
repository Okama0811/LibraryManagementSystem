<?php
include_once 'models/Fine.php';
include_once 'models/Loan.php';
include_once 'models/User.php';

class FineController extends Controller
{
    private $fine;
    private $loan;
    private $user;

    public function __construct()
    {
        $this->fine = new Fine();
        $this->loan = new Loan();
        $this->user = new User();
    }

    public function index()
    {
        $fines = $this->fine->read();
        // echo '<pre>';
        //         var_dump($fines);
        //         echo '</pre>';
        //         die(); 
        $loans = $this->loan->read();
        $users = $this->user->read();
        $content = 'views/fines/index.php';
        include('views/layouts/base.php');
    }


    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
       
    
                // Set loan properties from POST data
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->loan, $key)) {
                        $this->loan->$key = strip_tags(trim($value));
                    }
                }
    
                // Kiểm tra nếu không có giá trị cho 'issued_date', gán giá trị mặc định
                if (empty($_POST['issued_date'])) {
                    throw new Exception('Ngày tạo phiếu không được để trống.');
                }
    
                // Gán giá trị cho đối tượng fine
                $this->fine->issued_date = $_POST['issued_date'];
                $this->fine->due_date = $_POST['due_date'];
                $this->fine->notes = $_POST['notes'];
                $this->fine->status = $_POST['status'];
                $this->fine->assessed_by = $_POST['assessed_by'];
                $this->fine->loan_id = $_POST['loan_id'];
                $this->fine->user_id = $_POST['user_id'];   
                $this->fine->returned_to = $_POST['returned_to'] ?? NULL; // Gán giá trị NULL nếu không có
    
                if ($this->fine->create()) {
                    $_SESSION['message'] = 'Thêm phiếu phạt thành công!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=fine&action=index");
                    exit();
                } else {
                    throw new Exception('Thêm phiếu phạt không thành công.');
                }
    
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }
    
        $loans = $this->fine->getLoaned();
    
        $content = 'views/fines/create.php';
        include('views/layouts/base.php');
    }
    

     public function edit($id)
     {
         $this->fine = new Fine();
         $fineData = $this->fine->readById($id);
        //   echo '<pre>';
        //         var_dump($fineData);
        //         echo '</pre>';
        //         die(); 
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              try {
                foreach ($_POST as $key => $value) {
                     if (property_exists($this->fine, $key)) {
                         $this->fine->$key = strip_tags(trim($value));
                     }
                 }

                 if ($this->fine->update($id)) {

                     $_SESSION['message'] = 'Cập nhật phiếu thành công!';
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
        $loans = $this->fine->getLoaned();
         $fine = $fineData;
         $content = 'views/fines/edit.php';
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
    public function detail($id)
    {
        include('views/books/book_detail.php');
    }
    
}
?>
