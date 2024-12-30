<?php
include_once 'models/Reservation.php';
include_once 'models/Reservation_Detail.php';
include_once 'models/Book.php';

class ReservationController extends Controller
{
    private $reservation;
    private $reservationDetail;
    private $book;
    private $user;

    public function __construct()
    {
        $this->reservation = new Reservation();
        $this->reservationDetail = new Reservation_Detail();
        $this->book = new Book();
        $this->user = new User();
    }

    public function index()
    {
        $reservations = $this->reservation->read();
        $content = 'views/reservations/index.php';
        include('views/layouts/base.php');
    }

    public function create() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Lưu dữ liệu form vào session trước khi xử lý
                $_SESSION['form_data'] = $_POST;

                $bookIds = $_POST['book_id'];
                $expiryDate = $_POST['expiry_date'];
                $userId = $_POST['user_id'];
                $reservationDate = $_POST['reservation_date'];
                $notes = $_POST['notes'];
                $status = 'pending';

                // Kiểm tra dữ liệu đầu vào
                if (empty($bookIds)) {
                    throw new Exception('Dữ liệu không hợp lệ. Vui lòng kiểm tra lại thông tin.');
                }

                // Tạo một reservation chính
                $this->reservation->user_id = strip_tags(trim($userId));
                $this->reservation->reservation_date = strip_tags(trim($reservationDate));
                $this->reservation->notes = strip_tags(trim($notes));
                $this->reservation->status = $status;
                $this->reservation->expiry_date = strip_tags(trim($expiryDate));
                // Lưu reservation chính
                if (!$this->reservation->create()) {
                    throw new Exception("Không thể tạo phiếu đặt sách");
                }
                
                // Lấy ID của reservation vừa tạo
                $reservationId = $this->reservation->getLastInsertId();
                
                // Lặp qua từng sách để tạo reservation_detail
                foreach ($bookIds as $index => $bookId) {
                    $this->reservationDetail->reservation_id = $reservationId;
                    $this->reservationDetail->book_id = strip_tags(trim($bookId));
    
                    // Lưu từng reservation_detail
                    if (!$this->reservationDetail->create()) {
                        throw new Exception("Không thể tạo chi tiết phiếu đặt cho sách ID: $bookId");
                    }
                }

                $_SESSION['message'] = 'Tạo phiếu đặt sách thành công!';
                $_SESSION['message_type'] = 'success';
                unset($_SESSION['form_data']); // Xóa dữ liệu form sau khi thành công
                header("Location: index.php?model=reservation&action=index");
                exit();
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }

        // Lấy danh sách người dùng và sách
        $users = $this->user->read();
        $books = $this->book->readWithExpectedDate();
        $content = 'views/reservations/create.php';
        include('views/layouts/base.php');
    }

    public function edit($id)
    {
        $reservationData = $this->reservation->readById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->reservation, $key)) {
                        $this->reservation->$key = strip_tags(trim($value));
                    }
                }

                $this->reservation->user_id = $_SESSION['user_id'];
                $this->reservation->reservation_date = $_POST['reservation_date'];
                $this->reservation->expiry_date = date('Y-m-d', strtotime($_POST['reservation_date'] . ' +3 days'));
                $this->reservation->notes = $_POST['notes'];
                $this->reservation->status = $_POST['status'];

                if ($this->reservation->update($id)) {
                    $_SESSION['message'] = 'Cập nhật phiếu đặt sách thành công!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=reservation&action=index");
                    exit();
                } else {
                    throw new Exception('Không thể cập nhật phiếu đặt sách');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }

        $reservation = $reservationData;
        $content = 'views/reservations/edit.php';
        include('views/layouts/base.php');
    }

    public function delete($id)
    {
        try {
            if ($this->reservation->delete($id)) {
                $_SESSION['message'] = 'Xóa phiếu đặt sách thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Không thể xóa phiếu đặt sách');
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        header("Location: index.php?model=reservation&action=index");
        exit();
    }

    public function statistics()
    {
        // Ví dụ logic thống kê: Lấy tổng số đặt chỗ
        try {
            $reservations = $this->reservation->read();
            $totalReservations = count($reservations);
            $content = 'views/reservations/statistics.php';
            include('views/layouts/base.php');
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
            header("Location: index.php?model=reservation&action=index");
        }
    }
    public function member_create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Save form data to session before processing
                $_SESSION['form_data'] = $_POST;
    
                $bookIds = $_POST['book_id'];
                $userId = $_SESSION['user_id'];
                $notes = $_POST['notes'];
                $status = 'pending';
    
                // Validate input data
                if (empty($bookIds)) {
                    throw new Exception('Dữ liệu không hợp lệ. Vui lòng kiểm tra lại thông tin.');
                }
    
                // Lấy expected_date lớn nhất từ bảng books
                $maxExpectedDate = null;
                foreach ($bookIds as $bookId) {
                    $book = $this->book->readById($bookId);
                    if ($book && (!$maxExpectedDate || $book['expected_date'] > $maxExpectedDate)) {
                        $maxExpectedDate = $book['expected_date'];
                    }
                }
    
                if (!$maxExpectedDate) {
                    throw new Exception('Không thể xác định ngày dự kiến.');
                }
    
                $expiryDate = date('Y-m-d', strtotime($maxExpectedDate . ' +3 days'));
    
                // Create main reservation
                $this->reservation->user_id = strip_tags(trim($userId));
                $this->reservation->reservation_date = date('Y-m-d'); // Current date
                $this->reservation->notes = strip_tags(trim($notes));
                $this->reservation->status = $status;
                $this->reservation->expiry_date = $expiryDate;
    
                // Save main reservation
                if (!$this->reservation->create()) {
                    throw new Exception("Không thể tạo phiếu đặt sách");
                }
    
                // Get ID of the newly created reservation
                $reservationId = $this->reservation->getLastInsertId();
    
                // Loop through each book to create reservation_detail
                foreach ($bookIds as $bookId) {
                    $this->reservationDetail->reservation_id = $reservationId;
                    $this->reservationDetail->book_id = strip_tags(trim($bookId));
    
                    // Save each reservation_detail
                    if (!$this->reservationDetail->create()) {
                        throw new Exception("Không thể tạo chi tiết phiếu đặt cho sách ID: $bookId");
                    }
                }
    
                $_SESSION['message'] = 'Tạo phiếu đặt sách thành công!';
                $_SESSION['message_type'] = 'success';
                unset($_SESSION['form_data']); // Clear form data after success
                header("Location: index.php?model=default&action=index");
                exit();
    
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }
    
        $user = $this->user->readById($_SESSION['user_id']);
        $booksWithExpectedDate = $this->book->readWithExpectedDate();
    
        $content = 'views/reservations/member_create.php';
        include('views/layouts/application.php');
    }
}
?>
