<div class="container-fluid">
    <div class="row my-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 py-2 bg-light rounded">
                    <li class="breadcrumb-item">
                        <a href="index.php?" class="text-decoration-none text-primary">Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="index.php?model=loan&action=index" class="text-decoration-none text-primary">Quản lý phiếu</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết phiếu mượn</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Thông báo lỗi -->
    <?php if (isset($_SESSION['message'])): ?>
        <div id="alert-message" class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['message']; ?>
        </div>
        <?php
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
        <script>
            setTimeout(function() {
                var alert = document.getElementById('alert-message');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 150); 
                }
            }, 2000);
        </script>
    <?php endif; ?>

    <?php if (!empty($_SESSION['insufficient_books'])): ?>
    <div class="alert alert-warning">
        <strong>Cảnh báo!</strong> Một số sách không đủ số lượng:
        <ul>
            <?php foreach ($_SESSION['insufficient_books'] as $book): ?>
                <li><?= $book['title'] ?>: Yêu cầu <?= $book['requested'] ?>, còn lại <?= $book['remaining'] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php  endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Chi tiết phiếu mượn</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
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
                        <span class="badge <?= $loan['status'] === 'issued' ? 'bg-warning' : ($loan['status'] === 'returned' ? 'bg-success' : 'bg-danger'); ?>">
                            <?php
                                $displayStatus = $loan['status'];
                                if ($loan['status'] === 'issued') {
                                    $displayStatus = 'Đã phê duyệt';
                                } elseif ($loan['status'] === 'overdue') {
                                    $displayStatus = 'Quá hạn';
                                } elseif ($loan['status'] === 'returned') {
                                    $displayStatus = 'Đã trả';
                                }
                                echo htmlspecialchars($displayStatus);
                            ?>
                        </span>
                    </p>
                    <p><strong>Ghi Chú:</strong> <?= htmlspecialchars($loan['notes']); ?></p>
                </div>
            </div>
            <div class="table-responsive">
                <table id="dataTable" class="table table-hover table-striped table-bordered">
                    <thead class="table-dark text-center">
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
                                <td class="text-center align-middle"><?= htmlspecialchars($book_detail['book_title']); ?></td>
                                <td class="text-center align-middle"><?= htmlspecialchars($book_detail['loan_detail_quantity']); ?></td>
                                <td class="text-center align-middle"><?= htmlspecialchars($book_detail['book_quantity']); ?></td>
                                <td class="text-center align-middle">
                                    <?php if ($loan['status'] != 'issued' && $loan['status'] != 'overdue' && $loan['status'] != 'returned'): ?>
                                        <input type="checkbox" name="books[<?= $book_detail['book_id']; ?>]" value="1" class="form-check-input">
                                    <?php endif; ?>
                                    
                                    <?php if ($loan['status'] === 'issued'): ?>
                                        <div class="form-group">
                                            <select class="form-select" name="book_status[<?= $book_detail['book_id'] ?>]" id="book_status_<?= $book_detail['book_id']; ?>">
                                                <option value="returned" <?= $book_detail['status'] === 'returned' ? 'selected' : '' ?>>Đã trả</option>
                                                <option value="lost" <?= $book_detail['status'] === 'lost' ? 'selected' : '' ?>>Mất</option>
                                                <option value="damaged" <?= $book_detail['status'] === 'damaged' ? 'selected' : '' ?>>Hư hỏng</option>
                                            </select>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-4 mt-3">
            <?php if ($loan['status'] != 'issued' && $loan['status'] != 'overdue' && $loan['status'] != 'returned' && $userData['role_id'] != 3): ?>
                <button class="btn btn-warning btn-lg" onclick="handleAction('issued', <?= $loan['loan_id']; ?>)">Phê duyệt</button>
            <?php endif; ?>
            <?php if ($loan['status'] === 'issued' && $userData['role_id'] != 3): ?>
                <button class="btn btn-success btn-lg" onclick="handleAction('returned', <?= $loan['loan_id']; ?>)">Đã trả</button>
                <button class="btn btn-danger btn-lg" onclick="handleAction('overdue', <?= $loan['loan_id']; ?>)">Quá hạn</button>
            <?php endif; ?>
        </div>
    </div>

   <!-- Modal thông báo -->
   <div id="insufficientBooksModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Sách không đủ số lượng</h5>
            <button type="button" class="btn-close" onclick="closeModal()"></button>
        </div>
        <div class="modal-body">
            <ul class="list-group">
                <?php if (isset($_SESSION['insufficient_books'])): ?>
                    <?php foreach ($_SESSION['insufficient_books'] as $book): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($book['title']) ?>: Yêu cầu <?= $book['requested'] ?>      Còn lại <?= $book['remaining'] ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <p class="mt-3">Bạn có muốn chuyển những sách này sang phiếu hẹn không?</p>
        </div>
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
    .breadcrumb a {
        font-weight: 500;
    }

    .card {
        border-radius: 10px;
    }

    .btn {
        font-size: 0.9rem; 
        font-weight: bold; 
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        filter: brightness(0.9);
    }


    .d-flex {
    justify-content: center;
    gap: 20px; 
    flex-wrap: wrap;
}

.d-flex button {
    font-size: 0.9rem; 
    font-weight: bold; 
    transition: background-color 0.3s ease;
    margin: 10px; 
}

.btn:hover {
    filter: brightness(0.9);
}

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

.list-group-item {
    padding: 10px;
}

.form-check-input {
    width: 20px;
    height: 20px;
    cursor: pointer;
    transition: transform 0.2s ease-in-out;
}

.form-check-input:checked {
    transform: scale(1.2);
}


.form-select {
    background-color: #f8f9fa;
    border-radius: 5px;
    padding: 5px 10px;
    font-size: 1rem;
    font-family: Arial, sans-serif;

    font-weight: 500;
    border: 1px solid #ced4da;
    transition: border-color 0.2s ease-in-out;
}

.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(38, 143, 255, 0.5);
}


.form-select option {
    padding: 10px;
}

select:focus {
    outline: none;                 
    border-color: #007bff;         
    background-color: #fff;        
    font-weight: bold;              
}


select option {
    font-family: Arial, sans-serif;
    font-size: 16px;
    color: #333;
}


.form-group {
    margin: 10px 0;
}


.badge {
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 20px;
    padding: 6px 12px;
}


.badge:hover {
    opacity: 0.8;
}


@media (max-width: 768px) {
    .d-flex {
        flex-direction: column; 
        gap: 10px; 
    }

    .d-flex button {
        width: 100%; 
        margin-left: 0; 
    }
}

</style>
