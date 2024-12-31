<div id="content">
    <?php if (isset($_SESSION['message'])): ?>
        <div id="alert-message" class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['message']; ?>
        </div>
        <?php
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
        <script>
            setTimeout(function() {
                var alert = document.getElementById('alert-message');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 150);
                }
            }, 2000);
        </script>
    <?php endif; ?>
    <!-- Carousel Section -->
    <div id="carousel-id" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" id="headerSlide">
            <?php 
            $featured_books = array_slice($data_book, 0, 5); 
            foreach($featured_books as $index => $book) { ?>
                <div class="item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <a data-toggle="modal" href="#" data-target="#modal-id" onclick="Display_BookDetail('<?php echo $book['book_id'] ?>')">
                        <?php if($book['cover_image']): ?>
                            <img src="uploads/covers/<?php echo $book['cover_image'] ?>" alt="<?php echo $book['title'] ?>">
                        <?php else: ?>
                            <img src="uploads/covers/default-book.jpg" alt="Default Cover">
                        <?php endif; ?>
                    </a>
                </div>
            <?php } ?>
        </div>
        <a class="left carousel-control" href="#carousel-id" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="right carousel-control" href="#carousel-id" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </div>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 all-product">
                <a href="index.php?model=book&action=list&id=Newest">
					<h2 title="Những sách mới tuần qua" class="content-menu">Sách mới 
						<span class="glyphicon glyphicon-menu-right" style="font-size: 18px"></span>
					</h2>
				</a>
                <?php
                $latest_books = array_slice($data_book, 0, 8); // Lấy 8 cuốn sách mới nhất
                foreach($latest_books as $book) { ?>
                   <div class="product-container" onclick="Display_BookDetail('<?php echo $book['book_id'] ?>')">
                        <a data-toggle="modal" href="#" data-target="#modal-id">
                            <div class="product-img text-center">
                                <?php if($book['cover_image']): ?>
                                    <img src="uploads/covers/<?php echo $book['cover_image'] ?>" alt="<?php echo $book['title'] ?>">
                                <?php else: ?>
                                    <img src="uploads/covers/default_book.jpg" alt="Default Cover">
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3 style="color: white;"><b><?php echo $book['title'] ?></b></h3>
                                <h5 style="color: white;">Tác giả: <?php echo $book['authors'] ?></h5>
                                <div class='buy'>
									<a class='btn btn-primary btn-md cart-container <?php
                                        if(isset($_SESSION['cart'])){
                                            if(array_search($book['book_id'], $_SESSION['cart']) !== false){
                                                echo 'cart-ordered';
                                            }
                                        } ?>' data-masp='<?php echo $book['book_id'] ?>' >
                                        <i title='Thêm vào giỏ hàng' class='glyphicon glyphicon-shopping-cart cart-item'></i>
                                    </a>
                                    <a href="client/buynow/<?php echo $book['book_id'] ?>" class="snip0050">
                                        <span>Mượn sách</span><i class="glyphicon glyphicon-ok"></i>
                                    </a>
							    </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>

                <!-- Available Books Section -->
                <a href="index.php?model=book&action=list&id=TopWeek">
					<h2 title="Top sách trong tuần" class="content-menu">Top sách trong tuần
						<span class="glyphicon glyphicon-menu-right" style="font-size: 18px"></span>
					</h2>
				</a>
                <?php
                $available_books = array_filter($data_book, function($book) {
                    return $book['available_quantity'] > 0;
                });
                $available_books = array_slice($available_books, 0, 8);
                
                foreach($available_books as $book) { 
                    if($book['available_quantity'] > 0): ?>
                    <div class="product-container" onclick="Display_BookDetail('<?php echo $book['book_id'] ?>')">
                        <a data-toggle="modal" href="#" data-target="#modal-id">
                            <div class="product-img text-center">
                                <?php if($book['cover_image']): ?>
                                    <img src="uploads/covers/<?php echo $book['cover_image'] ?>" alt="<?php echo $book['title'] ?>">
                                <?php else: ?>
                                    <img src="uploads/covers/default-book.jpg" alt="Default Cover">
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3 style="color: white;"><b><?php echo $book['title'] ?></b></h3>
                                <h5 style="color: white;">Tác giả: <?php echo $book['authors'] ?></h5>
                                <div class="buy">
                                    <a href="loan/request/<?php echo $book['book_id'] ?>" 
                                       class="btn btn-primary">
                                        <i class="fa-solid fa-basket-shopping"></i></i> Mượn sách
                                    </a>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif;} ?>
            </div>
        </div>
    </div>
</div>

<script>
function Display_BookDetail(book_id){
    $('#modal-id').attr('data-remote','index.php?model=book&action=show&id='+book_id);
    $('#modal-book').empty();

    $.ajax({
        url : "index.php?model=book&action=show&id="+book_id,
        type : "post",
        dataType:"text",
        data : {
        book_id
        },
        success : function (result){
        $('#modal-book').html(result);
        }
    });
}
</script>