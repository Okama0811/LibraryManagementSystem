<div id="content">
    <!-- Carousel Section -->
    <div id="carousel-id" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" id="headerSlide">
            <?php 
            $featured_books = array_slice($data_book, 0, 5); 
            // var_dump($data_book);
            // exit();// Lấy 5 cuốn sách đầu tiên cho carousel
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
                                <h4><b><?php echo $book['title'] ?></b></h4>
                                <p>Tác giả: <?php echo $book['authors'] ?></p>
                                <p>Thể loại: <?php echo $book['categories'] ?></p>
                                <p>NXB: <?php echo $book['publisher_name'] ?></p>
                                <div class="status">
                                    <?php if($book['available_quantity'] > 0): ?>
                                        <span class="label label-success">Còn sách</span>
                                    <?php else: ?>
                                        <span class="label label-danger">Hết sách</span>
                                    <?php endif; ?>
                                </div>
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
                
                foreach($available_books as $book) { ?>
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
                                <h4><b><?php echo $book['title'] ?></b></h4>
                                <p>Tác giả: <?php echo $book['authors'] ?></p>
                                <p>Thể loại: <?php echo $book['categories'] ?></p>
                                <p>NXB: <?php echo $book['publisher_name'] ?></p>
                                <p>Số lượng có sẵn: <?php echo $book['available_quantity'] ?></p>
                                <div class="buy">
                                    <a href="loan/request/<?php echo $book['book_id'] ?>" 
                                       class="btn btn-primary">
                                        <i class="glyphicon glyphicon-book"></i> Mượn sách
                                    </a>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Book Detail Modal -->
    <div class="modal fade" id="modal-id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Chi tiết sách</h4>
                </div>
                <div class="modal-body">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>