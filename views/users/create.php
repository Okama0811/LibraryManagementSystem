    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?model=user&action=index">Quản lý tài khoản người dùng</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tạo người dùng mới</li>
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
                            <h5 class="card-title mb-0">Tạo người dùng mới</h5>
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
                        <form action="index.php?model=user&action=create" method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="username" class="form-label">Tên đăng nhập:</label>
                                    <input type="text" name="username" id="username" class="form-control" required 
                                        value="<?php echo isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" name="email" id="email" class="form-control" required
                                        value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="full_name" class="form-label">Họ và tên:</label>
                                    <input type="text" name="full_name" id="full_name" class="form-control" required
                                        value="<?php echo isset($_SESSION['form_data']['full_name']) ? htmlspecialchars($_SESSION['form_data']['full_name']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Số điện thoại:</label>
                                    <input type="tel" name="phone" id="phone" class="form-control"
                                        value="<?php echo isset($_SESSION['form_data']['phone']) ? htmlspecialchars($_SESSION['form_data']['phone']) : ''; ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ:</label>
                                <input type="text" name="address" id="address" class="form-control"
                                    value="<?php echo isset($_SESSION['form_data']['address']) ? htmlspecialchars($_SESSION['form_data']['address']) : ''; ?>">
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date_of_birth" class="form-label">Ngày sinh:</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
                                        value="<?php echo isset($_SESSION['form_data']['date_of_birth']) ? htmlspecialchars($_SESSION['form_data']['date_of_birth']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Giới tính:</label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value="male" <?php echo (isset($_SESSION['form_data']['gender']) && $_SESSION['form_data']['gender'] == 'male') ? 'selected' : ''; ?>>Nam</option>
                                        <option value="female" <?php echo (isset($_SESSION['form_data']['gender']) && $_SESSION['form_data']['gender'] == 'female') ? 'selected' : ''; ?>>Nữ</option>
                                        <option value="other" <?php echo (isset($_SESSION['form_data']['gender']) && $_SESSION['form_data']['gender'] == 'other') ? 'selected' : ''; ?>>Khác</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="member_type" class="form-label">Loại thành viên:</label>
                                    <input type="text" name="member_type" id="member_type" class="form-control" value="member" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="max_books" class="form-label">Số lượng sách tối đa được mượn:</label>
                                    <input type="number" name="max_books" id="max_books" class="form-control"
                                        value="<?php echo isset($_SESSION['form_data']['max_books']) ? htmlspecialchars($_SESSION['form_data']['max_books']) : '10'; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="expiry_date" class="form-label">Ngày hết hạn:</label>
                                    <input type="date" name="expiry_date" id="expiry_date" class="form-control"
                                        value="<?php echo isset($_SESSION['form_data']['expiry_date']) ? htmlspecialchars($_SESSION['form_data']['expiry_date']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Trạng thái tài khoản:</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="active" <?php echo (isset($_SESSION['form_data']['status']) && $_SESSION['form_data']['status'] == 'active') ? 'selected' : ''; ?>>Hoạt động</option>
                                        <option value="inactive" <?php echo (isset($_SESSION['form_data']['status']) && $_SESSION['form_data']['status'] == 'inactive') ? 'selected' : ''; ?>>Không hoạt động</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Ghi chú:</label>
                                <textarea name="note" id="note" class="form-control" rows="3"><?php echo isset($_SESSION['form_data']['note']) ? htmlspecialchars($_SESSION['form_data']['note']) : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu:</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control" required>
                                    <button type="button" class="btn btn-warning" onclick="generatePassword()">
                                        <i class="fa-solid fa-arrows-rotate px-1"></i>Tạo mật khẩu
                                    </button>
                                </div>
                                <small class="form-text text-muted">Mật khẩu phải có ít nhất 8 ký tự</small>
                            </div>

                            <div class="mb-3">
                                <label for="role_id" class="form-label">Vai trò:</label>
                                <select name="role_id" id="role_id" class="form-control" required>
                                    <option value="">Chọn vai trò</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?php echo $role['role_id']; ?>" 
                                            <?php echo (isset($_SESSION['form_data']['role_id']) && $_SESSION['form_data']['role_id'] == $role['role_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($role['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?model=user&action=index" class="btn btn-secondary"> 
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
        const roleSelect = document.getElementById('role_id');

        form.addEventListener('submit', function(event) {
            if (roleSelect.value === '') {
                event.preventDefault();
                alert('Vui lòng chọn vai trò cho người dùng.');
                return;
            }

            const password = document.getElementById('password').value;
            if (password.length < 8) {
                event.preventDefault();
                alert('Mật khẩu phải có ít nhất 8 ký tự.');
                return;
            }
        });
    });

    function generatePassword() {
        const length = 12;
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        let password = "";
        
        password += "ABCDEFGHIJKLMNOPQRSTUVWXYZ"[Math.floor(Math.random() * 26)];
        password += "abcdefghijklmnopqrstuvwxyz"[Math.floor(Math.random() * 26)];
        password += "0123456789"[Math.floor(Math.random() * 10)];
        password += "!@#$%^&*"[Math.floor(Math.random() * 8)];
        
        for (let i = password.length; i < length; i++) {
            password += charset[Math.floor(Math.random() * charset.length)];
        }
        
        password = password.split('').sort(() => Math.random() - 0.5).join('');
        
        document.getElementById('password').value = password;
        alert('Mật khẩu đã được tạo: ' + password + '\nVui lòng lưu lại mật khẩu này một cách an toàn.');
    }
    </script>