<?php
// Kiểm tra session
?>
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=book_condition&action=index">Quản lý phiếu phạt</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tạo phiếu mới</li>
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
                    <h5 class="card-title mb-0">Tạo phiếu mới</h5>
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
                    <form action="index.php?model=fine&action=create" method="POST">

                        <div class="mb-3">
                            <label for="loan_id" class="form-label">Phiếu mượn:</label>
                            <select name="loan_id" id="loan_id" class="form-control" required>
                                <option value="">Chọn phiếu mượn</option>
                                <?php foreach ($loans as $loan): ?>
                                    <option value="<?= htmlspecialchars($loan['loan_id']); ?>">
                                        <?= htmlspecialchars($loan['loan_id']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Tên người dùng:</label>
                            <input type="text" name="user_id_display" id="user_id" class="form-control" readonly>
                            <input type="hidden" name="user_id" id="user_id_hidden">
                        </div>

                        <div class="mb-3">
                            <label for="issued_date" class="form-label">Ngày tạo phiếu:</label>
                            <input class="form-control" type="date" id="issued_date" name="issued_date" required
                            min="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="due_date" class="form-label">Hạn trả:</label>
                            <input class="form-control" type="date" id="due_date" name="due_date" required>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú:</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                        </div>

                        <input type="hidden" name="returned_to" value="<?= htmlspecialchars($_SESSION['user_id']); ?>">
                        <input type="hidden" name="status" value="unpaid">
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

<?php
$userLoanMap = [];
foreach ($loans as $loan) {
    $userLoanMap[$loan['loan_id']] = $loan['username'];
    
}

// Truyền dữ liệu sang JavaScript
echo '<script>';
echo 'const userLoanMap = ' . json_encode($userLoanMap) . ';';
echo '</script>';
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const issuedDateInput = document.getElementById('issued_date');
    const dueDateInput = document.getElementById('due_date');
    issuedDateInput.value = new Date().toISOString().split('T')[0];
    dueDateInput.value = issuedDateInput.value;
    dueDateInput.min = issuedDateInput.value;

    issuedDateInput.addEventListener('change', function () {
        dueDateInput.min = issuedDateInput.value;
        if (dueDateInput.value < issuedDateInput.value) {
            dueDateInput.value = issuedDateInput.value;
        }
    });

    document.getElementById('loan_id').addEventListener('change', function () {
        const selectedLoanId = this.value;
        const userNameField = document.getElementById('user_id');
        const userIdField = document.getElementById('user_id_hidden'); // Thêm một trường ẩn cho user_id

        // Cập nhật giá trị của userNameField và userIdField
        userNameField.value = userLoanMap[selectedLoanId] || ''; // Hiển thị username
        userIdField.value = selectedLoanId; // Truyền user_id vào trường ẩn
    });

    document.querySelector('form').addEventListener('submit', function (event) {
        if (dueDateInput.value < issuedDateInput.value) {
            alert('Hạn trả không thể nhỏ hơn ngày tạo phiếu!');
            event.preventDefault();
        }
    });
});
</script>
