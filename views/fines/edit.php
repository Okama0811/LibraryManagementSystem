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
                    <li class="breadcrumb-item"><a href="index.php?model=fine&action=index">Quản lý phiếu phạt</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa phiếu phạt</li>
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
                    <h5 class="card-title mb-0">Chỉnh sửa phiếu phạt</h5>
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
                            <label for="user_id" class="form-label">Người thanh toán:</label>
                            <input type="text" name="user_id_display" id="user_id" class="form-control" value="<?= htmlspecialchars($fine['user_name']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="returned_to" class="form-label">Người phụ trách:</label>
                            <input type="text" name="returned_to" id="returned_to" class="form-control" value="<?= htmlspecialchars($fine['returned_to']); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="issued_date" class="form-label">Ngày tạo phiếu:</label>
                            <input class="form-control" type="date" id="issued_date" name="issued_date" value="<?= htmlspecialchars($fine['issued_date']); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="due_date" class="form-label">Hạn thanh toán:</label>
                            <input class="form-control" type="date" id="due_date" name="due_date" 
                                value="<?= htmlspecialchars($fine['due_date']); ?>" <?= $fine['status'] === 'paid' ? 'readonly' : '' ?>>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú:</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" 
                                    <?= $fine['status'] === 'paid' ? 'readonly' : '' ?>><?= htmlspecialchars($fine['notes']); ?></textarea>
                        </div>

                        <?php if ($fine['status'] == 'pending') : ?>
                            <div class="avatar-wrapper mb-3">
                                <img id="image-preview" class="img-fluid img-thumbnail" 
                                src="<?php echo !empty($payment['notes']) ? 'uploads/payments/' . $payment['notes'] : 'assets/images/default-avatar.png'; ?>" 
                                alt="Avatar" style="width: 30%; height: 350px; object-fit: cover;">
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái:</label>
                            <input class="form-control" type="text" id="status" name="status" 
                            value="<?php 
                                if ($fine['status'] === 'paid') {
                                    echo 'Đã hoàn thành';
                                } elseif ($fine['status'] === 'unpaid') {
                                    echo 'Chưa hoàn thành';
                                } elseif ($fine['status'] === 'pending') {
                                    echo 'Chờ xét duyệt';
                                } 
                            ?>" readonly>
                        </div>

                        <?php if ($fine['status'] == 'pending') : ?>
                            <form action="index.php?model=fine&action=edit&id=<?= $fine['fine_id'] ?>" method="POST">
                                <div class="d-flex justify-content-start">
                                    <button type="submit" class="btn btn-success">Xét duyệt</button>
                                </div>
                                <input type="hidden" name="status" value="paid">
                                <input type="hidden" name="receive_by" value="<?= htmlspecialchars($_SESSION['user_id']); ?>">
                                <input type="hidden" name="online_paid" value="<?= $payment['notes'] ?>">
                                <input type="hidden" name="payment_method" value="Chuyển khoản">
                                <input type="hidden"  name="amount"  value="<?= $payment['amount'] ?>" >
                            </form>
                       <?php endif; ?>

                        <?php if ($fine['status'] == 'unpaid') : ?>
                            <form action="index.php?model=fine&action=edit&id=<?= $fine['fine_id'] ?>" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Phương thức thanh toán:</label>
                                    <select class="form-control" id="payment_method" name="payment_method" onchange="toggleTransferInput(this.value)" required>
                                        <option value="">-- Chọn phương thức thanh toán --</option>
                                        <option value="Tiền mặt">Tiền mặt</option>
                                        <option value="Chuyển khoản">Chuyển khoản</option>
                                    </select>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6" id="transfer_proof" style="display: none;">
                                        <label for="proof_image" class="form-label">Tải lên ảnh chuyển khoản:</label>
                                        <input type="file" class="form-control" id="proof_image" name="proof_image" accept="image/*">
                                    </div>

                                    <div class="col-md-6"   id="transfer_amount" style="display: none;">
                                        <label for="amount" class="form-label">Số tiền chuyển khoản:</label>
                                        <input type="number" class="form-control" id="amount" name="amount"  value="<?= $payment['amount'] ?>" readonly>
                                    </div>
                                </div>
                                <input type="hidden" name="status" value="paid">
                                <input type="hidden" name="receive_by" value="<?= htmlspecialchars($_SESSION['user_id']); ?>">
                                <div class="d-flex justify-content-start">
                                    <button type="submit" class="btn btn-success">Xét duyệt</button>
                                </div>
                            </form>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="returned_date" class="form-label">Ngày hoàn thành:</label>
                            <input class="form-control" type="date" id="returned_date" name="returned_date" value="<?= htmlspecialchars($fine['returned_date']); ?>" required
                            min="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="confirmed_by" class="form-label">Người xác nhận:</label>
                            <input class="form-control" type="text" id="confirmed_by" name="confirmed_by" value="<?= htmlspecialchars($fine['confirmed_by']); ?>" readonly>
                        </div>

                        <input type="hidden" name="confirmed_by" value="<?= htmlspecialchars($_SESSION['user_id']); ?>">

                </div> <!--chân thẻ body-->
                <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?model=fine&action=index" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Quay lại
                            </a>
                            <?php if ($fine['status'] !== 'paid') : ?>
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <?php endif; ?>
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
    const statusInput = document.getElementById('status');
    const returnedDateInput = document.getElementById('returned_date');
    issuedDateInput.value = "<?= htmlspecialchars($fine['issued_date']); ?>";
    dueDateInput.value = "<?= htmlspecialchars($fine['due_date']); ?>";

    dueDateInput.min = issuedDateInput.value;

    if (statusInput.value.toLowerCase() === 'đã hoàn thành' || statusInput.value === 'paid') {
        const today = new Date().toISOString().split('T')[0];
        returnedDateInput.value = today;
        returnedDateInput.setAttribute('readonly', true);
    }

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

function toggleTransferInput(paymentMethod) {
    const transferProof = document.getElementById('transfer_proof');
    const transferAmount = document.getElementById('transfer_amount');
    
    if (paymentMethod === 'Chuyển khoản') {
        transferProof.style.display = 'block';
        transferAmount.style.display = 'block';
    } else if (paymentMethod === 'Tiền mặt') {
        transferProof.style.display = 'none';
        transferAmount.style.display = 'block';
    } else {
        transferProof.style.display = 'none';
        transferAmount.style.display = 'none';
    }
}
</script>