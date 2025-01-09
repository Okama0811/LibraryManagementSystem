<?php
include_once 'models/Fine.php';
include_once 'models/Loan.php';
include_once 'models/User.php';
include_once 'models/Fine_payment.php';

class FineController extends Controller
{
    private $fine;
    private $loan;
    private $user;
    private $fine_payment;

    public function __construct()
    {
        $this->fine = new Fine();
        $this->loan = new Loan();
        $this->user = new User();
        $this->fine_payment = new Fine_Payment();
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
                // Process POST data and assign values to properties
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->fine, $key)) {
                        $this->fine->$key = strip_tags(trim($value));
                    }
                }

                // Validate required fields
                if (empty($_POST['issued_date'])) {
                    throw new Exception('Ngày tạo phiếu không được để trống.');
                }

                $this->fine->issued_date = $_POST['issued_date'];
                $this->fine->due_date = $_POST['due_date'];
                $this->fine->notes = $_POST['notes'];
                $this->fine->status = $_POST['status'];
                $this->fine->loan_id = $_POST['loan_id'];
                $this->fine->user_id = $_POST['user_id'];   
                $this->fine->returned_to = $_POST['returned_to'] ?? NULL;

                // Create the fine record
                if ($this->fine->create()) {
                    // Get the id of the newly created fine
                    $fine_id = $this->fine->getLastInsertedId(); // Assuming this method exists

                    // Set payment_date to the current date if not provided
                    $payment_date = $_POST['payment_date'] ?? date('Y-m-d'); // Default to today's date if not provided
                    $amount = $_POST['amount'];
                    $payment_method = $_POST['payment_method'] ?? NULL;
                    $receive_by = $_POST['receive_by'] ?? NULL;
                    $notes = $_POST['payment_notes'] ?? NULL;

                    // SQL query to insert into fine_payment table
                    $sql = "INSERT INTO fine_payment (fine_id, amount, payment_date, payment_method, receive_by, notes) 
                            VALUES (:fine_id, :amount, :payment_date, :payment_method, :receive_by, :notes)";

                    $stmt = $this->conn->prepare($sql); 
                    $stmt->bindParam(':fine_id', $fine_id);
                    $stmt->bindParam(':amount', $amount);
                    $stmt->bindParam(':payment_date', $payment_date);
                    $stmt->bindParam(':payment_method', $payment_method);
                    $stmt->bindParam(':receive_by', $receive_by);
                    $stmt->bindParam(':notes', $notes);

                    // Execute the query to insert the fine_payment record
                    if ($stmt->execute()) {
                        $_SESSION['message'] = 'Thêm phiếu phạt  thành công!';
                        $_SESSION['message_type'] = 'success';
                        header("Location: index.php?model=fine&action=index");
                        exit();
                    } else {
                        throw new Exception('Thêm thông tin thanh toán phiếu phạt không thành công.');
                    }

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
        $fineData = $this->fine->readById($id);
    
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
                    // Nếu có thay đổi trạng thái, gọi hàm editStatus và cập nhật payment_date
                    $this->editStatus($id);  // Tích hợp hàm editStatus
    
                    // Cập nhật payment_date khi trạng thái thay đổi
                    if ($fineData['status'] === 'unpaid') {
                        // Nếu là trạng thái 'unpaid', chỉ cập nhật payment method và payment date
                        $this->fine->updatePaymentDate($id);
                    }
    
                    $_SESSION['message'] = 'Cập nhật trạng thái và payment_date thành công!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=fine&action=index");
                    exit();
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
        $payment = $this->fine->getPaymentData($id);
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
    public function export() {
        // Khởi tạo model Fine
        $fineModel = new Fine();
    
        // Lấy dữ liệu phiếu phạt
        $fines = $fineModel->read();
    
        // Cấu hình headers
        $headers = [
                'fine_id' => 'Mã phiếu phạt',
                'loan_id' => 'Mã mượn sách',
                'user_id' => 'Người mượn (ID)',
                'returned_to' => 'Người nhận lại (ID)',
                'confirmed_by' => 'Xác nhận bởi (ID)',
                'issued_date' => 'Ngày tạo phiếu',
                'due_date' => 'Ngày đến hạn',
                'returned_date' => 'Ngày trả',
                'status' => 'Trạng thái',
                'notes' => 'Ghi chú',
        ];
    
        // Khởi tạo ExcelExportService
        $excelService = new ExcelExportService();
    
        // Cấu hình xuất Excel
        $config = [
            'headers' => $headers,
            'data' => $fines,
            'filename' => 'danh_sach_phieu_phat.xlsx',
            'translations' => [
                // Dịch trạng thái
                'pending' => 'Chờ xử lý',
                'paid' => 'Đã thanh toán',
                'unpaid'=> 'Chưa thanh toán',
                'overdue' => 'Quá hạn',
            ],
            'translateColumns' => ['status'],
            'headerStyle' => [
                'font' => [
                    'bold' => true
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'E2E8F0'
                    ]
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ]
            ]
        ];
    
        // Xuất file Excel
        $excelService->exportWithConfig($config);
    }
}
