<?php
include_once 'models/Book.php';
include_once 'models/Role.php';

class DefaultController extends Controller
{
    private $book;
    private $loan;
    private $member;

    private $user;
    private $role;

    public function __construct()
    {
        $this->book = new Book();
        $this->loan = new Loan();
        $this->member = new User();
    }
    public function index() {
        $book = new Book();
        $data_book = $book->read(); // Lấy tất cả sách với thông tin liên quan
        
        // Sắp xếp theo ngày tạo mới nhất
        usort($data_book, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        // var_dump($data);
        // exit();
        $content = 'views/default/index.php';
        include('views/layouts/application.php');
    }
    
    public function detail($id) {
        $book = new Book();
        $book_detail = $book->readById($id);
        // Return book detail view for modal
        include('views/default/book_detail.php');
    }
    
    public function admin_dashboard(){
        $totalBooks = $this->book->getTotalCount();
        $activeMembers = $this->member->getActiveCount();
        $activeLoans = $this->loan->getActiveLoanCount();
        $overdueLoans = $this->loan->getOverdueCount();

        // Thống kê theo tháng
        $monthlyStats = $this->loan->getMonthlyStats();

        // Thống kê theo danh mục
        $categoryStats = $this->book->getCategoryStats();

        // Sách phổ biến
        $popularBooks = $this->book->getMostPopularBooks(5);

        $content = 'views/default/adminDashboard.php';
        include('views/layouts/base.php');
    }
}