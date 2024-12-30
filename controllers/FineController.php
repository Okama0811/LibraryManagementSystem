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
        $loans = $this->loan->read();
        $users = $this->user->read();
        $content = 'views/fines/index.php';
        include('views/layouts/base.php');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->loan, $key)) {
                        $this->loan->$key = strip_tags(trim($value));
                    }
                }

                if (empty($_POST['issued_date'])) {
                    throw new Exception('Ngày tạo phiếu không được để trống.');
                }

                $this->fine->issued_date = $_POST['issued_date'];
                $this->fine->due_date = $_POST['due_date'];
                $this->fine->notes = $_POST['notes'];
                $this->fine->status = $_POST['status'];
                $this->fine->assessed_by = $_POST['assessed_by'];
                $this->fine->loan_id = $_POST['loan_id'];
                $this->fine->user_id = $_POST['user_id'];   
                $this->fine->returned_to = $_POST['returned_to'] ?? NULL;

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
    // Lấy dữ liệu phiếu phạt theo ID
    $fineData = $this->fine->readById($id);
    
    // Kiểm tra nếu form được gửi
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Duyệt qua tất cả các trường dữ liệu trong form
            foreach ($_POST as $key => $value) {
                if (property_exists($this->fine, $key)) {
                    // Loại bỏ các thẻ HTML và trim dữ liệu
                    $this->fine->$key = strip_tags(trim($value));
                }
            }
            
            // Kiểm tra xem có thay đổi trạng thái hay không
            if (isset($_POST['status']) && $_POST['status'] !== $fineData['status']) {
                // Nếu có thay đổi trạng thái, gọi hàm editStatus
                $this->editStatus($id);
            } else {
                // Nếu không có thay đổi trạng thái, gọi hàm update thông thường
                if ($this->fine->update($id)) {
                    $_SESSION['message'] = 'Cập nhật phiếu thành công!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=fine&action=index");
                    exit();
                } else {
                    throw new Exception('Cập nhật phiếu không thành công.');
                }
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }
    }
    
    // Lấy dữ liệu các khoản vay (loans)
    $loans = $this->fine->getLoaned();
    
    // Gán dữ liệu phiếu phạt vào biến để truyền ra view
    $fine = $fineData;
    $content = 'views/fines/edit.php';
    include('views/layouts/base.php');
}

    public function delete($id)
    {
        try {
            if ($this->fine->delete($id) ) {
                $_SESSION['message'] = 'Xóa phiếu phạt thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Xóa phiếu phạt không thành công.');
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }
        header("Location: index.php?model=fine&action=index");
        exit();
    }

    public function editStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'status' => 'paid',
                    'returned_date' => date('Y-m-d')
                ];

                $this->fine->updateFineStatus($id, $data);

                $_SESSION['message'] = 'Phiếu phạt đã được xét duyệt!';
                $_SESSION['message_type'] = 'success';
                header("Location: index.php?model=fine&action=index");
                exit();
            } catch (Exception $e) {
                $_SESSION['message'] = 'Lỗi xét duyệt: ' . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
                header("Location: index.php?model=fine&action=index");
                exit();
            }
        }
    }
}
