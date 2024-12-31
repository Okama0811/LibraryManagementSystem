<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light shadow-sm p-2 rounded">
                    <li class="breadcrumb-item"><a href="index.php?model=loan&action=index">Quản lý phiếu mượn</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết phiếu mượn</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container">
    <!-- Thông báo lỗi -->
    <?php if (isset($_SESSION['message'])): ?>
        <div id="alert-message" class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show shadow-sm" role="alert">
            <?= $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header ">
            <h5 class=" mb-0">Chi tiết phiếu mượn</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Mã Phiếu:</strong> <?= htmlspecialchars($loan['loan_id']); ?></p>
                    <p><strong>Người Mượn:</strong> <?= htmlspecialchars($loan['borrower_name']); ?></p>
                    <p><strong>Ngày Mượn:</strong> <?= htmlspecialchars((new DateTime($loan['issued_date']))->format('d/m/Y')); ?></p>
                    <p><strong>Ngày Đến Hạn:</strong> <?= htmlspecialchars((new DateTime($loan['due_date']))->format('d/m/Y')); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Ngày Trả (nếu có):</strong> <?= $loan['returned_date'] ? htmlspecialchars((new DateTime($loan['returned_date']))->format('d/m/Y')) : 'Chưa trả'; ?></p>
                    <p><strong>Người Trả (nếu có):</strong> <?= $loan['returned_to'] ? htmlspecialchars($loan['returned_to']) : 'Chưa trả'; ?></p>
                    <p><strong>Trạng Thái:</strong>
                        <span class="badge <?= $loan['status'] === 'issued' ? 'bg-primary' : ($loan['status'] === 'returned' ? 'bg-success' : 'bg-danger'); ?>" style = "color:white; font-size: 14px">
                        <?php
                            $displayStatus = $loan['status'];
                            if ($loan['status'] === 'issued') {
                                $displayStatus = 'Đã phê duyệt';
                            } elseif ($loan['status'] === 'overdue') {
                                $displayStatus = 'Quá hạn';
                            } elseif ($loan['status'] === 'returned') {
                                $displayStatus = 'Đã trả';
                            }
                        ?>
                        <?= htmlspecialchars($displayStatus); ?>
                        </span>
                    </p>
                    <p><strong>Ghi Chú:</strong> <?= htmlspecialchars($loan['notes']); ?></p>
                </div>
            </div>
            <div class="table-responsive">
            <form action="index.php?model=loan&action=update_status&id=<?= $loan['loan_id']?>" method="POST">
                <table id="dataTable" class="table table-hover table-striped table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Tên sách</th>
                            <th>Số lượng mượn</th>
                            <th>Số lượng hiện có</th>
                            <th>Trạng thái sách</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book_detail): ?>
                            <tr>
                                <td><?= htmlspecialchars($book_detail['book_title']); ?></td>
                                <td><?= htmlspecialchars($book_detail['loan_detail_quantity']); ?></td>
                                <td><?= htmlspecialchars($book_detail['book_quantity']); ?></td>
                                <td>
                                    <?php if ($loan['status'] === 'issued'): ?>
                                        <select class="form-select form-select-sm" name="book_status[<?= $book_detail['book_id'] ?>]" id="book_status_<?= $book_detail['book_id']; ?>">
                                            <option value="returned" <?= $book_detail['status'] === 'returned' ? 'selected' : '' ?>>Đã trả</option>
                                            <option value="lost" <?= $book_detail['status'] === 'lost' ? 'selected' : '' ?>>Mất</option>
                                            <option value="damaged" <?= $book_detail['status'] === 'damaged' ? 'selected' : '' ?>>Hư hỏng</option>
                                        </select>
                                    <?php endif; ?>
                                    <?php if ( $loan['status'] === 'overdue'||$loan['status'] === 'returned' ): ?>
                                        <?php
                                            echo $book_detail['status'] === 'lost' ? 'Mất' : 
                                                ($book_detail['status'] === 'damaged' ? 'Hỏng' : 
                                                ($book_detail['status'] === 'returned' ? 'Đã trả' : 
                                                ($book_detail['status'] === 'overdue' ? 'Quá hạn' : 'Chờ xử lý')));
                                        ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <input type="hidden" name="book_detail_id[]" value="<?= htmlspecialchars($book_detail['book_id']); ?>">
                        <?php endforeach; ?>
                        <input type="hidden" name="loan_id" value="<?= htmlspecialchars($loan['loan_id']); ?>">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="index.php?model=loan&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> 
            </a>
            <?php if ($loan['status'] === 'issued' && $userData['role_id'] != 3): ?>
                <input type="hidden" name="action_status" value="returned">
                <button  type = "submit" class="btn btn-success">Đã trả</button>
            <?php endif; ?>   
            <?php if ($loan['status'] === NULL && $userData['role_id'] != 3): ?>
                <input type="hidden" name="action_status" value="issued">
                <button type = "submit" class="btn btn-primary" >Phê duyệt</button>

                <input type="hidden" name="action_status" value="overdue">
                <button  type = "submit" class="btn btn-danger" >Quá hạn</button>
            <?php endif; ?>
        </div>
            </form>
    </div>
</div>


<div id="insufficientBooksModal" class="modal" tabindex="-1">
    <div class="modal-dialog" style="max-width: 90%; width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: bold;">Sách không đủ số lượng</h2>
                <button type="button" class="btn-close" onclick="closeModal()"></button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered text-center">
                    <thead style="background-color: #ced4da;">
                        <tr>
                            <th>Tên sách</th>
                            <th>Yêu cầu</th>
                            <th>Còn lại</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($_SESSION['insufficient_books'])): ?>
                            <?php foreach ($_SESSION['insufficient_books'] as $book): ?>
                                <tr>
                                    <td><?= htmlspecialchars($book['title']) ?></td>
                                    <td><?= $book['requested'] ?></td>
                                    <td><?= $book['remaining'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">Không có sách nào thiếu.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <p class="mt-3" style="font-style:italic ;">Đề nghị: Chuyển sách sang phiếu hẹn !</p>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Hủy</button>
                <form action="index.php?model=loan&action=handle_reservation" method="POST">
                    <input type="hidden" name="loan_id" value="<?= $_SESSION['loan_id'] ?? '' ?>">
                    <?php if (isset($_SESSION['insufficient_books'])): ?>
                        <?php foreach ($_SESSION['insufficient_books'] as $book): ?>
                            <input type="hidden" name="book_ids[]" value="<?= $book['book_id'] ?>">
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary">Chuyển sang phiếu hẹn</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
   document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($_SESSION['insufficient_books'])): ?>
        // Kiểm tra xem có tồn tại dữ liệu không
        console.log(<?php echo json_encode($_SESSION['insufficient_books']); ?>);
        
        // Mở modal khi có sách không đủ số lượng
        var modal = document.getElementById('insufficientBooksModal');
        modal.style.display = 'flex';

        <?php
        unset($_SESSION['insufficient_books']);
        ?>
    <?php endif; ?>
});

function closeModal() {
    var modal = document.getElementById('insufficientBooksModal');
    modal.style.display = 'none';
}

  function handleAction(action, id) {
    if (confirm('Bạn có chắc chắn muốn thực hiện hành động này?')) {

        var form = document.createElement('form');
        form.method = 'POST';
        form.action = `index.php?model=loan&action=update_status&status=${action}&id=${id}`;

        var elements = document.querySelectorAll('input[type="checkbox"][name^="books"], select[name^="books"]');
        elements.forEach(function(element) {
            if (element.type === 'checkbox') {
                if (element.checked) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = element.name;
                    input.value = element.value;
                    form.appendChild(input);
                }
            } else if (element.tagName === 'SELECT') {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = element.name;
                input.value = element.value;
                form.appendChild(input);
            }
        });

        document.body.appendChild(form);
        form.submit();
    }
}


</script>

<style>
.modal {
    display: none; 
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    
}

.modal-content {
    background-color: white;
    border-radius: 10px;
    padding: 20px;
    width: 80%;
    max-width: 500px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header .btn-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
}
</style>
