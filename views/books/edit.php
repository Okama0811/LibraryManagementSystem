<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=book&action=index">Quản lý sách</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa sách</li>
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
                    <h5 class="card-title mb-0">Chỉnh sửa sách</h5>
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
                    <form action="index.php?model=book&action=edit&id=<?php echo $book['book_id']; ?>" method="POST" enctype="multipart/form-data">

                        <div class="avatar-wrapper mb-3">
                                    <img id="image-preview" class="rounded-circle img-thumbnail" 
                                    src="<?php echo !empty($book['cover_image']) ? 'uploads/covers/' . $book['cover_image'] : 'assets/images/default-avatar.png'; ?>" 
                                        alt="Avatar" style="width: 200px; height: 200px; object-fit: cover;">
                                </div>
                                <div class="mb-3">
                                    <label for="cover_image" class="form-label">Hình ảnh bìa</label>
                                    <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*" >
                                    <small class="text-muted">Cho phép: JPG, JPEG, PNG. Tối đa 2MB</small>
                                </div>
                              
                        <div class="mb-3">
                            <label for="title" class="form-label">Tên sách:</label>
                            <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Danh sách tác giả đã chọn:</label>
                            <table id="authorsTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tác giả</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($authors)): ?>
                                        <?php foreach ($authors as $author): ?>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="authors[]" value="<?= htmlspecialchars($author['author_id']); ?>">
                                                    <?= htmlspecialchars($author['name']); ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeAuthor(this)">Xóa</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <select id="author_id" class="form-select">
                                <option value="">-- Chọn tác giả --</option>
                                <?php foreach ($authors as $author): ?>
                                    <option value="<?= htmlspecialchars($author['author_id']); ?>">
                                        <?= htmlspecialchars($author['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="new_author">+ Thêm tác giả mới</option>
                            </select>
                            <button type="button" class="btn btn-primary" onclick="addAuthor()">Thêm tác giả</button>
                        </div>

                        <div class="mb-3">
                            <label for="publisher_id" class="form-label">Nhà xuất bản:</label>
                            <select name="publisher_id" id="publisher_id" class="form-control" required>
                                <option value="">Chọn nhà xuất bản</option>
                                <?php if (empty($publishers)): ?>
                                    <option value="">Không có nhà xuất bản nào</option>
                                <?php else: ?>
                                    <?php foreach ($publishers as $publisher): ?>
                                        <option 
                                            value="<?= htmlspecialchars($publisher['publisher_id']); ?>"
                                            <?= $publisher['publisher_id'] == $book['publisher_id'] ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($publisher['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Danh sách thể loại đã chọn:</label>
                            <table id="categoriesTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Thể loại</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="categories[]" value="<?= htmlspecialchars($category['category_id']); ?>">
                                                    <?= htmlspecialchars($category['name']); ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeCategory(this)">Xóa</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <select id="category_id" class="form-select">
                                <option value="">-- Chọn thể loại --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['category_id']); ?>">
                                        <?= htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="new_category">+ Thêm thể loại mới</option>
                            </select>
                            <button type="button" class="btn btn-primary" onclick="addCategory()">Thêm thể loại</button>
                        </div>

                        <div class="mb-3">
                            <label for="publication_year" class="form-label">Năm xuất bản:</label>
                            <input type="number" name="publication_year" id="publication_year" class="form-control" value="<?php echo htmlspecialchars($book['publication_year']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="edition" class="form-label">Tái bản:</label>
                            <input type="text" name="edition" id="edition" class="form-control" value="<?php echo htmlspecialchars($book['edition']); ?>" maxlength="50" required>
                        </div>

                        <div class="mb-3">
                            <label for="pages" class="form-label">Số trang:</label>
                            <input type="number" name="pages" id="pages" class="form-control" value="<?php echo htmlspecialchars($book['pages']); ?>" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="language" class="form-label">Ngôn ngữ:</label>
                            <select name="language" id="language" class="form-control" required>
                                <option value="" disabled <?= empty($book['language']) ? 'selected' : '' ?>>Chọn ngôn ngữ</option>
                                <option value="English" <?= $book['language'] === 'English' ? 'selected' : '' ?>>Tiếng Anh</option>
                                <option value="Vietnamese" <?= $book['language'] === 'Vietnamese' ? 'selected' : '' ?>>Tiếng Việt</option>
                            </select>
                        </div>
                
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Số lượng:</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="<?php echo $book['quantity']; ?>"  required>
                        </div>

                        <div class="mb-3">
                            <label for="current_quantity" class="form-label">Số lượng sách hiện tại:</label>
                            <input type="number" 
                                id="current_quantity" 
                                class="form-control" 
                                value="<?= htmlspecialchars($book['available_quantity']); ?>" 
                                readonly>

                            <label for="add_quantity" class="form-label mt-3">Số lượng muốn cộng thêm:</label>
                            <input type="number" 
                                name="add_quantity" 
                                id="add_quantity" 
                                class="form-control" 
                                min="0" 
                                placeholder="Nhập số lượng muốn thêm" 
                                >

                            <p id="warning_message" class="text-danger mt-2" style="display: none;">
                                Tổng số lượng không thể vượt quá số lượng hiện tại!
                            </p>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Giá:</label>
                            <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" value="<?php echo htmlspecialchars($book['price']); ?>" required>
                        </div>

                        <div class="mb-3">
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả:</label>
                            <textarea name="description" id="description" class="form-control" rows="3"><?php echo htmlspecialchars($book['description']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái:</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="" disabled <?= empty($book['status']) ? 'selected' : '' ?>>Chọn trạng thái</option>
                                <option value="available" <?= $book['status'] === 'available' ? 'selected' : '' ?>>available</option>
                                <option value="unavailable" <?= $book['status'] === 'unavailable' ? 'selected' : '' ?>>unavailable</option>
                            </select>
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
      <div class="modal-header">s
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

<div class="modal fade" id="newCategoryModal" tabindex="-1" aria-labelledby="newCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newCategoryModalLabel">Thông báo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Bạn cần đăng ký thể loại trước. Bạn có muốn đến trang đăng ký thể loại không?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
        <a href="index.php?model=category&action=create" class="btn btn-primary">Có, đến trang đăng ký</a>
      </div>
    </div>
  </div>
</div>

<script>
      // Lấy năm hiện tại
      const currentYear = new Date().getFullYear();

// Gán giá trị max cho input
    document.getElementById('publication_year').setAttribute('max', currentYear);

    const quantityInput = document.getElementById('quantity');
    const availableQuantityInput = document.getElementById('available_quantity');

    // Lắng nghe sự kiện khi người dùng nhập số lượng
    quantityInput.addEventListener('input', function () {
        const quantity = parseInt(quantityInput.value) || 0; // Lấy giá trị hoặc gán mặc định là 0
        availableQuantityInput.value = quantity; // Gán số lượng có sẵn bằng số lượng
    });

    function addAuthor() {
    const authorsSelect = document.getElementById('author_id'); // Lấy dropdown danh sách tác giả
    const selectedOption = authorsSelect.options[authorsSelect.selectedIndex];

    if (!selectedOption || selectedOption.value === "") {
        alert("Vui lòng chọn tác giả hợp lệ!");
        return;
    }

    // Kiểm tra nếu tác giả đã tồn tại
    const existingAuthors = Array.from(document.querySelectorAll('#authorsTable tbody tr td:first-child'))
        .map(cell => cell.textContent.trim());

    if (existingAuthors.includes(selectedOption.text)) {
        alert("Tác giả này đã được chọn.");
        return;
    }

    // Thêm dòng mới vào bảng
    const tableBody = document.querySelector('#authorsTable tbody');
    const newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td>
            <input type="hidden" name="authors[]" value="${selectedOption.value}">
            ${selectedOption.text}
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeAuthor(this)">Xóa</button>
        </td>
    `;

    tableBody.appendChild(newRow);
}

function removeAuthor(button) {
    const row = button.closest('tr');
    row.parentNode.removeChild(row);
}

document.getElementById('author_id').addEventListener('change', function () {
    if (this.value === "new_author") {
        const newAuthorModal = new bootstrap.Modal(document.getElementById('newAuthorModal'));
        newAuthorModal.show();
        this.value = ""; // Reset lại dropdown
    }
});

function addCategory() {
    const categoriesSelect = document.getElementById('category_id'); // Lấy dropdown danh sách thể loại
    const selectedOption = categoriesSelect.options[categoriesSelect.selectedIndex];

    if (!selectedOption || selectedOption.value === "") {
        alert("Vui lòng chọn thể loại hợp lệ!");
        return;
    }

    // Kiểm tra nếu thể loại đã tồn tại
    const existingCategories = Array.from(document.querySelectorAll('#categoriesTable tbody tr td:first-child'))
        .map(cell => cell.textContent.trim());

    if (existingCategories.includes(selectedOption.text)) {
        alert("Thể loại này đã được chọn.");
        return;
    }

    // Thêm dòng mới vào bảng
    const tableBody = document.querySelector('#categoriesTable tbody');
    const newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td>
            <input type="hidden" name="categories[]" value="${selectedOption.value}">
            ${selectedOption.text}
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeCategory(this)">Xóa</button>
        </td>
    `;

    tableBody.appendChild(newRow);
}

function removeCategory(button) {
    const row = button.closest('tr');
    row.parentNode.removeChild(row);
}

document.getElementById('category_id').addEventListener('change', function () {
    if (this.value === "new_category") {
        const newCategoryModal = new bootstrap.Modal(document.getElementById('newCategoryModal'));
        newCategoryModal.show();
        this.value = ""; // Reset lại dropdown
    }
});


document.getElementById('cover_image').addEventListener('change', function () {
    const file = this.files[0];
    
    if (file) {
        // Validate định dạng file
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('Chỉ chấp nhận file ảnh định dạng JPG, JPEG hoặc PNG!');
            this.value = '';
            return;
        }
        
        // Validate kích thước file (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Kích thước file không được vượt quá 2MB!');
            this.value = '';
            return;
        }
        
        // Xem trước ảnh
        const reader = new FileReader();
        reader.onload = function (e) {
            const imagePreview = document.getElementById('image-preview');
            imagePreview.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});

</script>
