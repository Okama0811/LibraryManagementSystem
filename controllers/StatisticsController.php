<?php
include_once 'models/Book.php';
include_once 'models/Loan.php';
include_once 'models/Fine.php';

class StatisticsController extends Controller
{
    private $book;
    private $loan;
    private $fine;

    public function __construct()
    {
        $this->book = new Book();
        $this->loan = new Loan();
        // $this->fine = new Fine();
    }

    public function index()
    {
        // Lấy dữ liệu thống kê
        $totalBooks = $this->book->getTotalCount();
        $activeLoans = $this->loan->getActiveLoanCount();
        $overdueLoans = $this->loan->getOverdueCount();
        $monthlyStats = $this->loan->getMonthlyStats();
        $categoryStats = $this->book->getCategoryStats();
        $popularBooks = $this->book->getMostPopularBooks();
        // $recentFines = $this->fine->getRecentFines();

        // Load view
        $content = 'views/statistics/index.php';
        include('views/layouts/base.php');
    }
}