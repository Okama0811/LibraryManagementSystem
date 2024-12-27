<?php
include_once 'models/Loan.php';

class LoanController extends Controller
{
    private $loan;
    private $authModel;

    public function __construct()
    {
        $this->loan = new Loan();
        $this->authModel = new User();
    }

    // Hiển thị danh sách phiếu mượn
    public function index()
    {
        // $loans = $this->loan->getAllLoans(
        //     $_SESSION['user']['user_id'], 
        //     $_SESSION['user']['role_id']
        // );
        $userData = $this->authModel->readById($_SESSION['user_id']);
        $loans = $this->loan->getAllandUserName();
        $content = 'views/loans/index.php';
        include('views/layouts/base.php');
    }

    // Hiển thị chi tiết phiếu mượn
    public function show($id)
    {
        if (!isset($_GET['id'])) {
            $_SESSION['message'] = 'Không tìm thấy mã phiếu!';
            $_SESSION['message_type'] = 'danger';
            header('Location: index.php?model=loan&action=index');
            exit;
        }

        $loanId = $_GET['id'];
        $loan = $this->loan->getById($loanId);
        $books = $this->loan->getBooksByLoanId($loanId);

        if (!$loan) {
            $_SESSION['message'] = 'Phiếu mượn không tồn tại!';
            $_SESSION['message_type'] = 'danger';
            header('Location: index.php?model=loan&action=index');
            exit;
        }
        $userData = $this->authModel->readById($_SESSION['user_id']);
        $content = 'views/loans/show.php';
        include('views/layouts/base.php');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Xử lý dữ liệu từ form
                $this->loan->issued_by = null;
                $this->loan->user_id =  $_POST['user_borrow_id'];
                $this->loan->issued_date = $_POST['issued_date'];
                $this->loan->due_date = $_POST['due_date'];
                $this->loan->status = 'pending';
                $this->loan->notes = $_POST['notes'] ?? '';
                $this->loan->books = [];
    
                // Xử lý các sách được chọn
                if (isset($_POST['selected_books']) && is_array($_POST['selected_books'])) {
                    foreach ($_POST['selected_books'] as $bookId) {
                        $this->loan->books[] = [
                            'book_id' => $bookId,
                            'quantity' => $_POST['book_quantity'][$bookId],
                            'status' => 'pending',
                            'notes' => $_POST['book_notes'][$bookId] ?? ''
                        ];
                    }
                }
    
                // Gọi phương thức tạo phiếu mượn
                $loanId = $this->loan->createLoan();
    
                if ($loanId) {
                    $_SESSION['message'] = 'Tạo phiếu mượn thành công!';
                    $_SESSION['message_type'] = 'success';
                    header('Location: index.php?model=loan&action=show&id=' . $loanId);
                } else {
                    throw new Exception('Tạo phiếu mượn thất bại');
                }
                exit;
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
                header('Location: index.php?model=loan&action=create');
                exit;
            }
        }

        $availableBooks = $this->loan->getAvailableBooks();
        $userData = $this->authModel->readById($_SESSION['user_id']);
        $content = 'views/loans/create.php';
        include('views/layouts/base.php');
    }

public function update_status($status = null, $returnDate = null)
{
    if (!isset($_GET['id'])) {
        $_SESSION['message'] = 'Không tìm thấy mã phiếu!';
        $_SESSION['message_type'] = 'danger';
        header('Location: index.php?model=loan&action=index');
        exit;
    }

    $loanId = $_GET['id'];

    if (isset($_GET['status'])) {
        $status = $_GET['status'];
    } else {
        $status = null; 
    }

    if ($status === null) {
        $_SESSION['message'] = 'Trạng thái không hợp lệ!';
        $_SESSION['message_type'] = 'danger';
        header('Location: index.php?model=loan&action=index');
        exit;
    }

    // Xử lý khi trạng thái là null (phê duyệt)
    if ($status != 'issued' and $status != 'overdue' and $status != 'returned') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->loan->getBooksByLoanId($loanId);
            $allBooks = array_column($stmt, 'book_id');
    
            foreach ($allBooks as $bookId) {
                try {
                    if (isset($_POST['books'][$bookId])) {
                        // Nếu sách được chọn, cập nhật trạng thái sách
                        $this->loan->updateBookStatusInLoanDetail($loanId, $bookId, 'issued');
                    } else {
                        // Nếu sách không được chọn, chuyển sang bảng hẹn và xóa khỏi phiếu mượn
                        $this->loan->reserveBook($loanId, $bookId);
                        $this->loan->deleteBookFromLoan($loanId, $bookId);
                    }
                } catch (Exception $e) {
                    $_SESSION['message'] = 'Lỗi khi cập nhật: ' . $e->getMessage();
                    $_SESSION['message_type'] = 'danger';
                    header('Location: index.php?model=loan&action=show&id=' . $loanId);
                    exit;
                }
            }
        }
    }

    // Xử lý khi trạng thái là returned (trả sách)
    if ($status === 'returned') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->loan->getBooksByLoanId($loanId);
            $allBooks = array_column($stmt, 'book_id');
    
            $returnDate = date('Y-m-d H:i:s'); // Ngày trả là ngày hiện tại

            foreach ($allBooks as $bookId) {
                try {
                    if (isset($_POST['books'][$bookId])) {
                        // Lấy trạng thái sách từ POST
                        $bookStatus = $_POST['books'][$bookId];
                        
                        // Cập nhật trạng thái sách trong loan_detail
                        $this->loan->updateBookStatusInLoanDetail($loanId, $bookId, $bookStatus);
                        
                        // Cập nhật lại trạng thái sách trong bảng book
                        $this->loan->updateBookAvailability($bookId, $bookStatus);
                    }
                } catch (Exception $e) {
                    $_SESSION['message'] = 'Lỗi khi cập nhật: ' . $e->getMessage();
                    $_SESSION['message_type'] = 'danger';
                    header('Location: index.php?model=loan&action=show&id=' . $loanId);
                    exit;
                }
            }
        }
    }

    // Cập nhật trạng thái phiếu mượn
    $result = $this->loan->updateStatus($loanId, $status, $returnDate);

    if ($result) {
        $_SESSION['message'] = 'Cập nhật trạng thái phiếu mượn thành công!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Cập nhật trạng thái thất bại!';
        $_SESSION['message_type'] = 'danger';
    }

    header('Location: index.php?model=loan&action=index');
    exit;
}
    public function updateBookStatus($loanId, $bookId, $status)
    {
        $result = $this->loan->updateBookStatusInLoanDetail($loanId, $bookId, $status);
        if ($result) {
            echo "Trạng thái sách đã được cập nhật thành công!";
        } else {
            echo "Cập nhật trạng thái sách thất bại!";
        }
    }
}
?>