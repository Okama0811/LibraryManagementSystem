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

                    <div class="row mb-4">
                        <div class="col-md-5">

                            <div class="avatar-wrapper mb-3">
                                <img id="image-preview" class="img-fluid img-thumbnail" 
                                src="assets/images/eror.jpg" 
                                alt="Avatar" style="width: 100%; height: 600px; object-fit: cover;">
                            </div>
                            
                            <div class="mb-3">
                                <label for="cover_image" class="form-label">Hình ảnh bìa</label>
                                <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*" >
                                <small class="text-muted">Cho phép: JPG, JPEG, PNG. Tối đa 2MB</small>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="title" class="form-label">Tên sách:</label>
                                    <input type="text" name="title" id="title" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="publication_year" class="form-label">Năm xuất bản:</label>
                                    <input type="number" name="publication_year" id="publication_year" class="form-control" required   max="<?php echo date('Y'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="edition" class="form-label">Tái bản:</label>
                                    <input type="text" name="edition" id="edition" class="form-control" maxlength="50" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="pages" class="form-label">Số trang:</label>
                                    <input type="number" name="pages" id="pages" class="form-control" min="1" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="quantity" class="form-label">Số lượng:</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" 
                                         required>
                                </div>
                                <div class="col-md-6">
                                    <label for="available_quantity" class="form-label">Số lượng có sẵn:</label>
                                    <input type="number" name="available_quantity" id="available_quantity" class="form-control" min="0" value="0"readonly>
                                </div>
                                <div class="col-md-6">
                                <label for="language" class="form-label">Ngôn ngữ:</label>
                                    <select name="language" id="language" class="form-control" required>
                                        <option value="" disabled selected>Chọn ngôn ngữ</option>
                                        <option value="English">Tiếng Anh</option>
                                        <option value="Vietnamese">Tiếng Việt</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                <label for="publisher_id" class="form-label">Nhà xuất bản:</label>
                                    <select name="publisher_id" id="publisher_id" class="form-control" required>
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

                                <div class="col-md-6">
                                <label class="form-label">Danh sách tác giả :</label>
                                    <div class="table-responsive" style="max-height: 160px; overflow-y: auto;">
                                        <table id="authorsTable" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Tác giả</th>
                                                    <th>Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($selectedauthors)): ?>
                                                    <?php foreach ($selectedauthors as $author): ?>
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
                                    </div>
                                
                                    <div class="d-flex align-items-center">
                                    <select id="author_id" class="form-control" onchange="handleAuthorChange(this)">
                                        <option value="">-- Chọn tác giả --</option>
                                        <?php foreach ($authors as $author): ?>
                                            <option value="<?= htmlspecialchars($author['author_id']); ?>">
                                                <?= htmlspecialchars($author['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option value="new_author">+ Thêm tác giả mới</option>
                                    </select>

                                    <button type="button" class="btn btn-primary ml-2 d-flex align-items-center" onclick="addAuthor()">
                                        <i class="bi bi-plus-lg mr-1"></i> +
                                    </button>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Danh sách thể loại đã chọn:</label>
                                    <div class="table-responsive" style="max-height: 160px; overflow-y: auto;">
                                        <table id="categoriesTable" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Thể loại</th>
                                                    <th>Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($selectedcategories)): ?>
                                                    <?php foreach ($selectedcategories as $category): ?>
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
                                    </div>
                                
                                    <div class="d-flex align-items-center">
                                    <select id="category_id" class="form-control" onchange="handleCategoryChange(this)">
                                        <option value="">-- Chọn thể loại --</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= htmlspecialchars($category['category_id']); ?>">
                                                <?= htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option value="new_category">+ Thêm thể loại mới</option>
                                    </select>

                                    <button type="button" class="btn btn-primary ml-2 d-flex align-items-center" onclick="addCategory()">
                                        <i class="bi bi-plus-lg mr-1"></i> +
                                    </button>
                                    </div>

                                </div>     
                                
                                <div class="col-md-12">
                                    <label for="description" class="form-label">Mô tả:</label>
                                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                                </div>     

                                <div class="col-md-6">
                                    <input type="text" name="status" id="status"  value="available" class="form-control" hidden>
                                </div>
                            </div>

                        </div>
                    </div> 

                </div> <!--Chân thẻ body-->
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

    // Reset select về giá trị mặc định
    authorsSelect.value = "";
}

function removeAuthor(button) {
    const row = button.closest('tr');
    row.parentNode.removeChild(row);
}

function handleCategoryChange(select) {
    if (select.value === 'new_category') {
        var confirmRedirect = confirm("Bạn có muốn đến trang đăng ký thể loại mới không?");
        if (confirmRedirect) {
            window.location.href = 'index.php?model=category&action=create';
        } else {
            // Đặt lại select box về lựa chọn đầu tiên
            select.value = "";
        }
    }
}

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

    // Reset select về giá trị mặc định
    categoriesSelect.value = "";
}

function removeCategory(button) {
    const row = button.closest('tr');
    row.parentNode.removeChild(row);
}

function handleAuthorChange(select) {
    if (select.value === 'new_author') {
        var confirmRedirect = confirm("Bạn có muốn đến trang đăng ký tác giả mới không?");
        if (confirmRedirect) {
            window.location.href = 'index.php?model=author&action=create';
        } else {
            // Đặt lại select box về lựa chọn đầu tiên
            select.value = "";
        }
    }
}

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
