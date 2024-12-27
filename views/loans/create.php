<div class="container">
    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tạo Phiếu Mượn</h5>
                </div>
                <div class="card-body">
                    <form action="index.php?model=loan&action=create" method="POST" id="loanForm">        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="user_id" class="form-label">Người mượn:</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($userData['username']) ?></p>
                                <input type="hidden" name="user_borrow_id" value="<?= $userData['user_id'] ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="issued_date" class="form-label">Ngày mượn:</label>
                                <input type="date" name="issued_date" id="issued_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="due_date" class="form-label">Hạn trả:</label>
                                <input type="date" name="due_date" id="due_date" class="form-control" 
                                       value="<?= date('Y-m-d', strtotime('+14 days')) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="notes" class="form-label">Ghi chú (tùy chọn):</label>
                                <input type="text" name="notes" id="notes" class="form-control">
                            </div>
                        </div>

                        <hr>
                        <h5>Chọn Sách Mượn</h5>
                        
                        <!-- Thanh tìm kiếm -->
                        <div class="mb-3">
                            <label for="bookSearch" class="form-label">Tìm kiếm sách:</label>
                            <input type="text" id="bookSearch" class="form-control" placeholder="Nhập mã sách hoặc tên sách...">
                        </div>

                        <!-- Bảng sách với cuộn -->
                        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-bordered" id="bookTable">
                                <thead>
                                    <tr>
                                        <th>Chọn</th>
                                        <th>Mã Sách</th>
                                        <th>Tên Sách</th>
                                        <th>Số Lượng Sẵn Có</th>
                                        <th>Số Lượng Mượn</th>
                                        <th>Ghi Chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach($availableBooks as $book): 
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_books[]" 
                                                   value="<?= $book['book_id'] ?>" 
                                                   class="form-check-input book-checkbox">
                                        </td>
                                        <td><?= $book['book_id'] ?></td>
                                        <td><?= $book['title'] ?></td>
                                        <td><?= $book['quantity'] ?></td>
                                        <td>
                                            <input type="number" 
                                                   name="book_quantity[<?= $book['book_id'] ?>]" 
                                                   min="1" 
                                                   max="<?= $book['quantity'] ?>" 
                                                   class="form-control book-quantity" 
                                                   disabled>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   name="book_notes[<?= $book['book_id'] ?>]" 
                                                   class="form-control book-note" 
                                                   disabled>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?model=loan&action=index" class="btn btn-secondary"> 
                                <i class="fa-solid fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fa-regular fa-floppy-disk"></i> Tạo Phiếu Mượn
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lọc sách dựa trên đầu vào tìm kiếm
    const bookSearch = document.getElementById('bookSearch');
    const bookTable = document.getElementById('bookTable').getElementsByTagName('tbody')[0];

    bookSearch.addEventListener('keyup', function() {
        const searchValue = bookSearch.value.toLowerCase();
        const rows = bookTable.getElementsByTagName('tr');
        
        for (const row of rows) {
            const bookId = row.cells[1].textContent.toLowerCase();
            const bookTitle = row.cells[2].textContent.toLowerCase();

            if (bookId.includes(searchValue) || bookTitle.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });

    // Kích hoạt/khóa số lượng và ghi chú khi chọn checkbox
    const checkboxes = document.querySelectorAll('.book-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const quantityInput = this.closest('tr').querySelector('.book-quantity');
            const noteInput = this.closest('tr').querySelector('.book-note');
            
            if (this.checked) {
                quantityInput.disabled = false;
                noteInput.disabled = false;
                quantityInput.value = 1; // Mặc định số lượng là 1
            } else {
                quantityInput.disabled = true;
                noteInput.disabled = true;
                quantityInput.value = '';
                noteInput.value = '';
            }
        });
    });
});
</script>
