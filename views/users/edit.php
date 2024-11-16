<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=user&action=index">Quản lý tài khoản người dùng</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa thông tin tài khoản người dùng</li>
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
                        <h5 class="card-title mb-0">Chỉnh sửa thông tin tài khoản người dùng</h5>
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

                    <form action="index.php?model=user&action=edit&id=<?php echo $user['user_id']; ?>" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Tên đăng nhập:</label>
                                <input type="text" name="username" id="username" class="form-control" 
                                    value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" name="email" id="email" class="form-control" 
                                    value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label">Họ tên:</label>
                                <input type="text" name="full_name" id="full_name" class="form-control" 
                                    value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Số điện thoại:</label>
                                <input type="tel" name="phone" id="phone" class="form-control" 
                                    value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ:</label>
                            <input type="text" name="address" id="address" class="form-control" 
                                value="<?php echo htmlspecialchars($user['address']); ?>">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">Ngày sinh:</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" 
                                    value="<?php echo htmlspecialchars($user['date_of_birth']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Giới tính:</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="male" <?php echo $user['gender'] === 'male' ? 'selected' : ''; ?>>Nam</option>
                                    <option value="female" <?php echo $user['gender'] === 'female' ? 'selected' : ''; ?>>Nữ</option>
                                    <option value="other" <?php echo $user['gender'] === 'other' ? 'selected' : ''; ?>>Khác</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="member_type" class="form-label">Loại thành viên:</label>
                                <input type="text" name="member_type" id="member_type" class="form-control" 
                                    value="<?php echo htmlspecialchars($user['member_type']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="max_books" class="form-label">Số lượng sách tối đa được mượn:</label>
                                <input type="number" name="max_books" id="max_books" class="form-control" 
                                    value="<?php echo htmlspecialchars($user['max_books']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="expiry_date" class="form-label">Ngày hết hạn:</label>
                                <input type="date" name="expiry_date" id="expiry_date" class="form-control" 
                                    value="<?php echo htmlspecialchars($user['expiry_date']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Trạng thái tài khoản:</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Hoạt động</option>
                                    <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Không hoạt động</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú:</label>
                            <textarea name="note" id="note" class="form-control" rows="3"><?php echo htmlspecialchars($user['note']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu:</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Để trống nếu không thay đổi">
                                <button type="button" class="btn btn-warning" onclick="resetPassword()">
                                    <i class="fa-solid fa-arrows-rotate px-1"></i>Reset Password
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role_id" class="form-label">Vai trò:</label>
                            <select name="role_id" id="role_id" class="form-control" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?php echo $role['role_id']; ?>" 
                                        <?php echo $user['role_id'] == $role['role_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($role['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="index.php?model=user&action=index" class="btn btn-secondary">Thoát</a>
                    <button type="button" id="toggleEdit" class="btn btn-primary">Chỉnh sửa</button>
                    <button type="submit" id="saveChanges" class="btn btn-success" style="display: none;">Lưu thay đổi</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const roleSelect = document.getElementById('role_id');
    const toggleEditBtn = document.getElementById('toggleEdit');
    const saveChangesBtn = document.getElementById('saveChanges');
    const allInputs = document.querySelectorAll('input, select, textarea');
    
    // Disable all inputs initially
    allInputs.forEach(input => {
        input.disabled = true;
    });

    // Toggle edit mode
    toggleEditBtn.addEventListener('click', function() {
        const isEditMode = toggleEditBtn.textContent === 'Edit';
        
        // Toggle button text and visibility
        if (isEditMode) {
            toggleEditBtn.style.display = 'none';
            saveChangesBtn.style.display = 'block';
            // Enable all inputs
            allInputs.forEach(input => {
                input.disabled = false;
            });
        } else {
            toggleEditBtn.style.display = 'block';
            saveChangesBtn.style.display = 'none';
            // Disable all inputs
            allInputs.forEach(input => {
                input.disabled = true;
            });
        }
    });

    // Form validation
    form.addEventListener('submit', function(event) {
        if (roleSelect.value === '') {
            event.preventDefault();
            alert('Please select a role for the user.');
        }
    });
});

function resetPassword() {
    const defaultPassword = 'DefaultPass@123';
    const passwordInput = document.getElementById('password');
    if (!passwordInput.disabled) {
        passwordInput.value = defaultPassword;
        alert('Password has been reset to: ' + defaultPassword);
    }
}
</script>