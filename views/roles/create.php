<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=role&action=index">Quản lý vai trò</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tạo vai trò mới</li>
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
                        <h5 class="card-title mb-0">Tạo vai trò mới</h5>
                    </div>
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

                    <form action="index.php?model=role&action=create" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên vai trò:</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                value="<?php echo isset($_SESSION['form_data']['name']) ? htmlspecialchars($_SESSION['form_data']['name']) : ''; ?>">
                            <small class="form-text text-muted">Tên vai trò phải là duy nhất trong hệ thống</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả:</label>
                            <textarea name="description" id="description" class="form-control" rows="3"><?php echo isset($_SESSION['form_data']['description']) ? htmlspecialchars($_SESSION['form_data']['description']) : ''; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quyền hạn:</label>
                            <div class="row">
                                <?php foreach ($permissions as $permission): ?>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                            name="permissions[]" 
                                            value="<?php echo $permission['permission_id']; ?>" 
                                            id="permission_<?php echo $permission['permission_id']; ?>"
                                            <?php echo (isset($_SESSION['form_data']['permissions']) && 
                                                    in_array($permission['permission_id'], $_SESSION['form_data']['permissions'])) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="permission_<?php echo $permission['permission_id']; ?>">
                                            <?php echo htmlspecialchars($permission['name']); ?>
                                            <small class="d-block text-muted"><?php echo htmlspecialchars($permission['description']); ?></small>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                                <label class="form-check-label" for="selectAll">Chọn tất cả quyền</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="index.php?model=role&action=index" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i>
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const nameInput = document.getElementById('name');
    const selectAllCheckbox = document.getElementById('selectAll');
    const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');

    // Xử lý chọn tất cả quyền
    selectAllCheckbox.addEventListener('change', function() {
        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Kiểm tra nếu tất cả các checkbox đã được chọn
    function updateSelectAll() {
        const allChecked = Array.from(permissionCheckboxes).every(checkbox => checkbox.checked);
        selectAllCheckbox.checked = allChecked;
    }

    permissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectAll);
    });

    // Validate form trước khi submit
    form.addEventListener('submit', function(event) {
        if (nameInput.value.trim() === '') {
            event.preventDefault();
            alert('Vui lòng nhập tên vai trò.');
            nameInput.focus();
            return;
        }

        // Kiểm tra xem có ít nhất một quyền được chọn
        const hasPermission = Array.from(permissionCheckboxes).some(checkbox => checkbox.checked);
        if (!hasPermission) {
            event.preventDefault();
            alert('Vui lòng chọn ít nhất một quyền cho vai trò.');
            return;
        }
    });
});
</script>