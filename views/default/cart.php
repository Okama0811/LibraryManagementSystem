
<div class="container-fluid form" style="padding: 20px;">
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
            <h4>
                <!-- <i class="pull-right">
                    <a href="index.php?model=books&action=index">Quay lại mua sắm tiếp!</a>
                </i> -->
            </h4>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <form action="index.php?model=cart&action=checkout" method="POST" id="checkoutForm">
                    <div class="cart-items">
                    <?php 
                    if (is_array($card_detail) && count($card_detail) > 0) {
                        foreach ($card_detail as $item) {
                    ?>
                        <div class="cart-item" id="cart-item-<?php echo $item['book_id']; ?>">
                            <div class="cart-item-actions">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="selected_books[]" value="<?php echo $item['book_id']; ?>">
                                </div>
                            </div>
							<div class=" text-center">
                                <?php if($item['cover_image']): ?>
                                    <img src="uploads/covers/<?php echo $item['cover_image'] ?>" alt="<?php echo $item['title'] ?>">
                                <?php else: ?>
                                    <img src="uploads/covers/default-book.jpg" alt="Default Cover">
                                <?php endif; ?>
                            </div>
                            <div class="cart-item-details">
                                <h5 class="cart-item-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                            </div>
                            <div>
                                <input type="number" 
                                       name="book_quantity[<?php echo $item['book_id']; ?>]" 
                                       value="<?php echo htmlspecialchars($item['borrow_quantity']); ?>" 
                                       class="form-control" 
                                       style="width: 60px;" 
                                       min="1">
                            </div>
                            <div class="cart-item-actions">
								<a href="index.php?model=cart&action=delete_book&id=<?= $item['book_id'] ?>" 
								class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" 
								title="Xóa" 
								onclick="return confirmDelete();">
								<i class="fas fa-trash-alt"></i>
								</a>
							</div>
                        </div>
                        <hr>
                    <?php } ?>
                        <div class="cart-total d-flex">
                            <button type = "submit " onclick="confirmChoose();" class="btn btn-success" >Đặt mượn sách</button>
                        </div>
                    <?php } else { ?>
                        <p>Giỏ hàng của bạn trống.</p>
                    <?php } ?>
                    </div>
                </form>
            <?php } else { ?>
                <p>Bạn cần đăng nhập để mượn sách.</p>
            <?php } ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const selectedBooks = document.querySelectorAll('input[name="selected_books[]"]:checked');
        if (selectedBooks.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một cuốn sách để mượn.');
        }
    });
});

function confirmDelete() {
    return confirm("Bạn có chắc chắn muốn xóa sách này khỏi giỏ hàng?");
}
function confirmChoose() {
    return confirm("Bạn có chắc chắn muốn đặt sách?");
}
</script>

<style>
.cart-item {
    display: flex;
    align-items: center;
    padding: 15px;
    margin-bottom: 15px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
}

.cart-item img {
    max-width: 80px; 
    max-height: 100px; 
    object-fit: cover; 
    border-radius: 4px; 
}

.cart-item-title {
    font-size: 14px;
    color: #808080;
    margin: 0;
    font-weight: 500;
	padding-left: 100px;
	text-shadow: none;
}

.cart-item-details {
    flex: 2;
    padding: 0 15px;
}

.cart-item-actions {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
	text-shadow: none;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
    border: none;
}

.btn-danger:hover {
    background-color: #c82333;
}

.form-control {
    border: 1px solid #ddd;
}

hr {
    margin: 15px 0;
    border-color: #eee;
}
</style>