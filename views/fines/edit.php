<?php
// Kiểm tra session
//echo '<pre>';
//var_dump($_SESSION);
//echo '</pre>';
?>
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=book_condition&action=index">Quản lý phiếu phạt</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa phiếu</li>
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
                    <h5 class="card-title mb-0">Chỉnh sửa phiếu kiểm tra</h5>
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
                    <form action="index.php?model=fine&action=edit&id=<?= htmlspecialchars($fine['fine_id']); ?>" method="POST">

                        <div class="mb-3">
                            <label for="loan_id" class="form-label">Phiếu mượn:</label>
                            <input type="text" name="loan_id" id="loan_id" class="form-control" value = "<?= htmlspecialchars($fine['loan_id']); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Tên người dùng:</label>
                            <input type="text" name="user_id_display" id="user_id" class="form-control" value="<?= htmlspecialchars($fine['user_name']); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="issued_date" class="form-label">Ngày tạo phiếu:</label>
                            <input class="form-control" type="date" id="issued_date" name="issued_date" value="<?= htmlspecialchars($fine['issued_date']); ?>" required
                           >
                        </div>

                        <div class="mb-3">
                            <label for="due_date" class="form-label">Hạn trả:</label>
                            <input class="form-control" type="date" id="due_date" name="due_date" value="<?= htmlspecialchars($fine['due_date']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú:</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"><?= htmlspecialchars($fine['notes']); ?></textarea>
                        </div>

                        <input type="hidden" name="returned_to" value="<?= htmlspecialchars($_SESSION['user_id']); ?>">
                        <input type="hidden" name="status" value="unpaid">

                </div> <!--chân thẻ body-->
                <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?model=book_condition&action=index" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fa-regular fa-floppy-disk"></i> Cập nhật
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
    issuedDateInput.value = "<?= htmlspecialchars($fine['issued_date']); ?>";
    dueDateInput.value = "<?= htmlspecialchars($fine['due_date']); ?>";

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