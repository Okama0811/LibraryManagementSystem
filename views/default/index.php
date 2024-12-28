<div id="content">
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
                <!-- Latest Books Section -->
                <h2 class="content-menu">Sách Mới
                    <span class="glyphicon glyphicon-menu-right" style="font-size: 18px"></span>
                </h2>
                
                <?php
                $latest_books = array_slice($data_book, 0, 8); // Lấy 8 cuốn sách mới nhất
                foreach($latest_books as $book) { ?>
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
                                <h3 style="word_color: white"><b><?php echo $book['title'] ?></b></h3>
                                <p>Tác giả: <?php echo $book['authors'] ?></p>
                                <p>Thể loại: <?php echo $book['categories'] ?></p>
                                <div class="buy">
                                    <?php if($book['available_quantity'] > 0): ?>
                                        <a href="loan/request/<?php echo $book['book_id'] ?>" 
                                           class="btn btn-primary">
                                            <i class="glyphicon glyphicon-book"></i> Mượn sách
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>

                <!-- Available Books Section -->
                <h2 class="content-menu">Sách Có Sẵn
                    <span class="glyphicon glyphicon-menu-right" style="font-size: 18px"></span>
                </h2>
                
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
                                <h3><b><?php echo $book['title'] ?></b></h3>
                                <p>Tác giả: <?php echo $book['authors'] ?></p>
                                <p>Thể loại: <?php echo $book['categories'] ?></p>
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