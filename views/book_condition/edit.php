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
                    <li class="breadcrumb-item"><a href="index.php?model=book_condition&action=index">Quản lý phiếu kiểm tra sách</a></li>
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
                    <form action="index.php?model=book_condition&action=edit&id=<?= htmlspecialchars($book_condition['condition_id']); ?>" method="POST" >
                              
                    <div class="mb-3">
                        <label for="book_id" class="form-label">Tiêu đề sách:</label>
                        <select name="book_id" id="book_id" class="form-control" value="<?= htmlspecialchars($book['book_id']); ?>" disable>
                            <option value="">Chọn sách</option>
                            <?php foreach ($books as $book): ?>
                                <option value="<?= htmlspecialchars($book['book_id']); ?>" <?= $book['book_id'] == $book_condition['book_id'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($book['book_title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="loan_id" class="form-label">ID phiếu mượn:</label>
                        <select name="loan_id" id="loan_id" class="form-control" required>
                            <option value="">Chọn phiếu mượn</option>
                        </select>
                    </div>

                        <div class="mb-3">
                            <label for="condition_before" class="form-label">Tình trạng sách trước khi mượn:</label>
                            <select name="condition_before" id="condition_before" class="form-control" required>
                                <option value="Perfect" <?= $bookCondition['condition_before'] == 'Perfect' ? 'selected' : ''; ?>>Perfect</option>
                                <option value="Damaged" <?= $bookCondition['condition_before'] == 'Damaged' ? 'selected' : ''; ?>>Damaged</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="condition_after" class="form-label">Tình trạng sách sau khi mượn:</label>
                            <select name="condition_after" id="condition_after" class="form-control" required onchange="toggleDamageDescription()">
                                <option value="Intact" <?= $bookCondition['condition_after'] == 'Intact' ? 'selected' : ''; ?>>Intact</option>
                                <option value="Damaged" <?= $bookCondition['condition_after'] == 'Damaged' ? 'selected' : ''; ?>>Damaged</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="damage_description" class="form-label">Chi tiết hư hại:</label>
                            <textarea name="damage_description" id="damage_description" class="form-control" rows="3" <?= $bookCondition['condition_after'] != 'Damaged' ? 'disabled' : ''; ?>><?= htmlspecialchars($bookCondition['damage_description']); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="assessed_date" class="form-label">Ngày kiểm tra:</label>
                            <input class="form-control" 
                                type="date" 
                                id="assessed_date" 
                                name="assessed_date" 
                                value="<?= htmlspecialchars($bookCondition['assessed_date']); ?>" 
                            >
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú:</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"><?= htmlspecialchars($bookCondition['notes']); ?></textarea>
                        </div>

                            <div class="mb-3">
                                <label for="assessed_by" class="form-label">Người kiểm tra:</label>
                                <input name="assessed_by" id="assessed_by" class="form-control" 
                                    value="<?= htmlspecialchars($_SESSION['full_name'] ?? 'Chưa đăng nhập'); ?>" 
                                    readonly>
                                <input type="hidden" name="assessed_by" value="<?= htmlspecialchars($_SESSION['user_id']); ?>">
                            </div>

                            <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?model=book_condition&action=index" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Trở lại
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
</div>

<?php
// Chuẩn bị danh sách liên kết book_id -> loan_id
$bookLoanMap = [];
foreach ($loans as $loan) {
    $bookId = $loan['book_id'];
    $loanId = $loan['loan_id'];
    if (!isset($bookLoanMap[$bookId])) {
        $bookLoanMap[$bookId] = [];
    }
    $bookLoanMap[$bookId][] = $loanId;
}

// Truyền dữ liệu sang JavaScript
echo '<script>';
echo 'const bookLoanMap = ' . json_encode($bookLoanMap) . ';';
echo 'const selectedLoanId = ' . json_encode($bookCondition['loan_id']) . ';';
echo '</script>';
?>

<script>
// Tải dữ liệu loan_id tương ứng khi chỉnh sửa
const loanSelect = document.getElementById('loan_id');
const selectedBookId = document.getElementById('book_id').value;

if (bookLoanMap[selectedBookId]) {
    bookLoanMap[selectedBookId].forEach(loanId => {
        const option = document.createElement('option');
        option.value = loanId;
        option.textContent = loanId;
        if (loanId == selectedLoanId) {
            option.selected = true;
        }
        loanSelect.appendChild(option);
    });
}

function toggleDamageDescription() {
    const conditionAfter = document.getElementById("condition_after").value;
    const damageDescription = document.getElementById("damage_description");

    if (conditionAfter === "Damaged") {
        damageDescription.disabled = false;
        damageDescription.required = true;
    } else {
        damageDescription.disabled = true;
        damageDescription.required = false;
        damageDescription.value = "";
    }
}
</script>
