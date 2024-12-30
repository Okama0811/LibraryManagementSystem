<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=reservation&action=index">Quản lý đặt sách</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tạo phiếu đặt sách</li>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Tạo phiếu đặt sách</h5>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form action="index.php?model=reservation&action=create" method="POST">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="user_id" class="form-label">Thông Tin Người Dùng:</label>
                                <select name="user_id" id="user_id" class="form-control" required>
                                    <?php foreach ($users as $user): ?>
                                        <?php if ($user['role_id'] == 3): ?>
                                            <option value="<?= $user['user_id'] ?>">
                                                <?= htmlspecialchars($user['full_name']) ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div id="books-section">
                            <label class="form-label">Thông Tin Sách:</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">STT</th>
                                        <th scope="col" class="text-center">Chọn Sách</th>
                                        <th scope="col" class="text-center">Ngày Dự Kiến</th>
                                    </tr>
                                </thead>
                                <tbody id="books-table-body">
                                    <tr class="book-row">
                                        <td class="stt text-center">1</td>
                                        <td>
                                            <select name="book_id[]" class="form-control book-select" required>
                                                <?php foreach ($books as $book): 
                                                    if ($book['expected_date'] != null): ?>
                                                    <option value="<?= $book['book_id'] ?>" data-expected-date="<?= $book['expected_date'] ?>">
                                                        <?= htmlspecialchars($book['title']) ?>
                                                    </option>
                                                <?php endif; endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="date" name="expected_date[]" class="form-control expected-date"
                                                value="<?= $books[0]['expected_date'] ?>" readonly>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end mb-3">
                            <button type="button" id="add-book" class="btn btn-primary">Thêm Sách</button>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <?php
                                    $defaultExpiryDate = date('Y-m-d', strtotime($books[0]['expected_date'] . ' +3 days'));
                                ?>
                                <label for="reservation_date" class="form-label">Ngày Đặt:</label>
                                <input type="date" name="reservation_date" id="reservation_date" class="form-control" 
                                       value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col">
                                <label for="expiry_date" class="form-label">Ngày Hết Hạn:</label>
                                <input type="date" name="expiry_date" id="expiry_date" class="form-control" 
                                    value="<?= $defaultExpiryDate ?>" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi Chú:</label>
                            <textarea name="notes" id="notes" class="form-control" required
                                      placeholder="Vui lòng điền đầy đủ: Họ và Tên, Lớp, Mã SV (Bắt buộc)"></textarea>
                        </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="index.php?model=reservation&action=index" class="btn btn-secondary"> 
                        <i class="fa-solid fa-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fa-regular fa-floppy-disk"></i>
                    </button>
                </div>
                    </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-book').addEventListener('click', function () {
        const booksTableBody = document.getElementById('books-table-body');
        const rowCount = booksTableBody.querySelectorAll('tr').length;

        const newRow = document.createElement('tr');
        newRow.className = 'book-row';

        let bookOptions = `
            <?php foreach ($books as $book):
                if ($book['expected_date'] != null):?>
                <option value="<?= $book['book_id'] ?>" data-expected-date="<?= $book['expected_date'] ?>">
                    <?= htmlspecialchars($book['title']) ?>
                </option>
            <?php endif; endforeach; ?>
        `;

        newRow.innerHTML = `
            <td class="stt text-center">${rowCount + 1}</td>
            <td>
                <select name="book_id[]" class="form-control book-select" required>
                    ${bookOptions}
                </select>
            </td>
            <td>
                <input type="date" name="expected_date[]" class="form-control expected-date"
                    value="<?= $books[0]['expected_date'] ?>" readonly>
            </td>
        `;

        booksTableBody.appendChild(newRow);

        // Cập nhật ngày dự kiến khi chọn sách
        const bookSelect = newRow.querySelector('.book-select');
        const expectedDateInput = newRow.querySelector('.expected-date');

        bookSelect.addEventListener('change', function () {
            const selectedOption = bookSelect.options[bookSelect.selectedIndex];
            expectedDateInput.value = selectedOption.getAttribute('data-expected-date');
            updateExpiryDate(); // Tính lại expiry_date khi thay đổi sách
        });

        updateExpiryDate(); // Tính lại expiry_date sau khi thêm sách
    });

    function updateExpiryDate() {
        const expectedDates = document.querySelectorAll('.expected-date');
        let maxDate = null;

        expectedDates.forEach(input => {
            const dateValue = input.value;
            if (dateValue) {
                const currentDate = new Date(dateValue);
                if (!maxDate || currentDate > maxDate) {
                    maxDate = currentDate;
                }
            }
        });

        if (maxDate) {
            maxDate.setDate(maxDate.getDate() + 3); // Cộng thêm 3 ngày
            document.getElementById('expiry_date').value = maxDate.toISOString().split('T')[0];
        }
    }
    
    document.querySelectorAll('.book-select').forEach(select => {
        select.addEventListener('change', function () {
            const expectedDateInput = select.closest('tr').querySelector('.expected-date');
            const selectedOption = select.options[select.selectedIndex];
            expectedDateInput.value = selectedOption.getAttribute('data-expected-date');
            updateExpiryDate(); // Tính lại expiry_date khi thay đổi sách
        });
    });
</script>
