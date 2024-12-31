<?php
include_once 'models/Book.php';
include_once 'models/Loan.php';

class CartController extends Controller
{
    private $cart;
    private $loan;

    public function __construct()
    {
        $this->cart = new Cart();
        $this->loan = new Loan();

    }

    public function index()
    {
        $card_detail = $this->cart->show($_SESSION['user_id']);
        $content = 'views/default/cart.php';
        include('views/layouts/application.php');
    }

    public function createDefaultCart($user_id)
{
    $existingCart = $this->cart->getCartByUserId($user_id);

    if (!$existingCart) {
        $this->cart->create(['user_id' => $user_id]);
    }
}

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $user_id = $_SESSION['user_id'];
                $book_id = $_POST['book_id'];  
                $quantity = $_POST['quantity']; 
                $existingCart = $this->cart->getCartByUserId($user_id);
                if (!$existingCart) {
                    $cart_id = $this->cart->create(['user_id' => $user_id]);
                } else {
                    $cart_id = $existingCart['cart_id'];
                }

                $existingItem = $this->cart->getCartItem($cart_id, $book_id);
                if ($existingItem) {
                    $newQuantity = $existingItem['quantity'] + $quantity;
                    $this->cart->updateCartItem($cart_id, $book_id, $newQuantity);
                } else {
                    $this->cart->addCartItem($cart_id, $book_id, $quantity);
                }
                header('Location: index.php?model=default&action=index');
                exit;

            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
                header('Location: index.php?model=default&action=index');
                exit;
            }
    }

        
    $content = 'views/default/index.php';
    include('views/layouts/application.php');
    }

    public function checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (!isset($_POST['selected_books']) || !is_array($_POST['selected_books']) || empty($_POST['selected_books'])) {
                    throw new Exception('Vui lòng chọn ít nhất một cuốn sách để mượn.');
                }
                
                $user_id = $_SESSION['user_id'];
                $issued_date = date('Y-m-d');
                $due_date = date('Y-m-d', strtotime('+14 days'));
                $this->loan->issued_by = $user_id;
                $this->loan->issued_date = $issued_date;
                $this->loan->due_date = $due_date;
                $this->loan->status = 'issued';
                $this->loan->notes = '';
                
                $books = [];
                foreach ($_POST['selected_books'] as $bookId) {
                    $book = $this->cart->getBookAvailability($bookId);
                    $requestedQuantity = $_POST['book_quantity'][$bookId];
                    
                    if ($book['available_quantity'] < $requestedQuantity) {
                        throw new Exception("Sách '{$book['title']}' chỉ còn {$book['available_quantity']} cuốn.");
                    }
                    
                    $books[] = [
                        'book_id' => $bookId,
                        'quantity' => $requestedQuantity
                    ];
                }
                
                // Tạo phiếu mượn và chi tiết
                $loanId = $this->loan->createLoan($books);
                
                if ($loanId) {
                    // Xóa sách đã mượn khỏi giỏ
                    foreach ($_POST['selected_books'] as $bookId) {
                        $this->cart->removeCartItem($user_id, $bookId);
                    }
                    
                    $_SESSION['message'] = 'Tạo phiếu mượn thành công!';
                    $_SESSION['message_type'] = 'success';
                    header('Location: index.php?model=cart&action=index');
                } else {
                    throw new Exception('Tạo phiếu mượn thất bại.');
                }
                
            } catch (Exception $e) {
                $_SESSION['message'] = $e->getMessage();
                $_SESSION['message_type'] = 'danger';
                header('Location: index.php?model=cart&action=index');
            }
            exit;
        }
    }

    public function delete_book($id)
    {
        try {
            $user_id = $_SESSION['user_id'];
            if ($this->cart->removeCartItem($user_id,$id)) {
                $_SESSION['message'] = 'Xóa sách thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Xóa sách thất bại ');
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        header("Location: index.php?model=cart&action=index");
        exit();
    }

}
?>
