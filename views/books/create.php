<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=book&action=index">Quản lý sách</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thêm sách mới</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thêm sách mới</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['message'])): ?>
                        <div id="alert-message" class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                            <?= $_SESSION['message']; ?>
                        </div>
                        <?php
                        unset($_SESSION['message']);
                        unset($_SESSION['message_type']);
                        ?>
                    <?php endif; ?>
                    <form action="index.php?model=book&action=create" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tên sách:</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Tác giả:</label>
                            <select name="author[]" id="author" class="form-control" multiple required> <!-- Added multiple here -->
                                <option value="">Chọn tác giả</option>
                                <?php if (empty($authors)): ?>
                                    <option value="">Không có tác giả nào</option>
                                <?php else: ?>
                                    <?php foreach ($authors as $author): ?>
                                        <option value="<?= htmlspecialchars($author['author_id']); ?>">
                                            <?= htmlspecialchars($author['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <option value="new_author">Tác giả mới</option> <!-- New option for "New Author" -->
                            </select>    
                        </div>
                        <div class="mb-3">
                            <label for="publisher" class="form-label">Nhà xuất bản:</label>
                            <select name="publisher" id="publisher" class="form-control" required>
                                <option value="">Chọn nhà xuất bản</option>
                                 <?php if (empty($publishers)): ?>
                                    <option value="">Không có nhà xuất bản nào</option>
                                <?php else: ?>
                                    <?php foreach ($publishers as $publisher): ?>
                                        <option value="<?= htmlspecialchars($publisher['publisher_id']); ?>">
                                            <?= htmlspecialchars($publisher['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                        <label for="category" class="form-label">Thể loại:</label>
                            <select name="category[]" id="category" class="form-control" multiple required>
                                <?php if (empty($categories)): ?>
                                    <option value="">Không có thể loại nào</option>
                                <?php else: ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= htmlspecialchars($category['category_id']); ?>">
                                            <?= htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="publish_date" class="form-label">Ngày xuất bản:</label>
                            <input type="date" name="publish_date" id="publish_date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Hình ảnh bìa:</label>
                            <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả:</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?model=book&action=index" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Trở lại
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fa-regular fa-floppy-disk"></i> Lưu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal (for new author registration prompt) -->
<div class="modal fade" id="newAuthorModal" tabindex="-1" aria-labelledby="newAuthorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newAuthorModalLabel">Thông báo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Bạn cần đăng ký tác giả trước. Bạn có muốn đến trang đăng ký tác giả không?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
        <a href="index.php?model=author&action=create" class="btn btn-primary">Có, đến trang đăng ký</a>
      </div>
    </div>
  </div>
</div>

<script>
    // Check if the 'new_author' option is selected
    document.getElementById('author').addEventListener('change', function() {
        if (this.value === 'new_author') {
            // Show the modal
            var myModal = new bootstrap.Modal(document.getElementById('newAuthorModal'));
            myModal.show();
        }
    });
</script>
