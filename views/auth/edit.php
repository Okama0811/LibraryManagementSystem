<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa thông tin cá nhân</li>
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
                        <h5 class="card-title mb-0">Chỉnh sửa thông tin cá nhân</h5>
                        <div>
                            <button id="edit-profile-btn" class="btn btn-primary me-2">
                                <i class="bi bi-pencil"></i> Sửa
                            </button>
                            <button id="save-profile-btn" class="btn btn-success d-none">
                                <i class="bi bi-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="profile-form" action="index.php?model=auth&action=edit" method="POST" enctype="multipart/form-data">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="avatar-wrapper mb-3">
                                        <img id="avatar-preview" class="rounded-circle img-thumbnail" 
                                             src="<?php echo !empty($user['avatar_url']) ? 'uploads/avatars/' . $user['avatar_url'] : 'assets/images/default-avatar.png'; ?>" 
                                             alt="Avatar" style="width: 200px; height: 200px; object-fit: cover;">
                                    </div>
                                    <div class="mb-3">
                                        <label for="avatar" class="form-label">Thay đổi ảnh đại diện</label>
                                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" disabled>
                                        <small class="text-muted">Cho phép: JPG, JPEG, PNG. Tối đa 2MB</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row mb-3">
                                    <div class="col-md-6">  
                                        <label for="username" class="form-label">Tên đăng nhập:</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="email" name="email" id="email" class="form-control" 
                                            value="<?php echo htmlspecialchars($user['email']); ?>" required disabled>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="full_name" class="form-label">Họ tên:</label>
                                        <input type="text" name="full_name" id="full_name" class="form-control" 
                                            value="<?php echo htmlspecialchars($user['full_name']); ?>" required disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Số điện thoại:</label>
                                        <input type="tel" name="phone" id="phone" class="form-control" 
                                            value="<?php echo htmlspecialchars($user['phone']); ?>" disabled>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Địa chỉ:</label>
                                    <input type="text" name="address" id="address" class="form-control" 
                                        value="<?php echo htmlspecialchars($user['address']); ?>" disabled>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="date_of_birth" class="form-label">Ngày sinh:</label>
                                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" 
                                            value="<?php echo htmlspecialchars($user['date_of_birth']); ?>" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">Giới tính:</label>
                                        <select name="gender" id="gender" class="form-control" disabled>
                                            <option value="male" <?php echo $user['gender'] === 'male' ? 'selected' : ''; ?>>Nam</option>
                                            <option value="female" <?php echo $user['gender'] === 'female' ? 'selected' : ''; ?>>Nữ</option>
                                            <option value="other" <?php echo $user['gender'] === 'other' ? 'selected' : ''; ?>>Khác</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="current_password" class="form-label">Mật khẩu hiện tại:</label>
                                        <div class="input-group">
                                            <input type="password" name="current_password" id="current_password" class="form-control" disabled>
                                            <button type="button" id="change-password-btn" class="btn btn-outline-secondary">
                                                <i class="bi bi-key"></i> Đổi mật khẩu
                                            </button>
                                        </div>
                                        <small class="text-muted">Nhập mật khẩu hiện tại nếu bạn muốn đổi mật khẩu</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="new_password" class="form-label">Mật khẩu mới:</label>
                                        <input type="password" name="new_password" id="new_password" class="form-control" disabled>
                                        <small class="text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nút thoát và lưu -->
                        <div class="card-footer d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">Thoát</a>
                            <button type="submit" id="submit-btn" class="btn btn-success d-none">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Đổi mật khẩu -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Đổi mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="modal-current-password" class="form-label">Mật khẩu hiện tại</label>
                    <input type="password" class="form-control" id="modal-current-password" required>
                </div>
                <div class="mb-3">
                    <label for="modal-new-password" class="form-label">Mật khẩu mới</label>
                    <input type="password" class="form-control" id="modal-new-password" required>
                </div>
                <div class="mb-3">
                    <label for="modal-confirm-password" class="form-label">Xác nhận mật khẩu mới</label>
                    <input type="password" class="form-control" id="modal-confirm-password" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="save-password-btn" class="btn btn-primary">Lưu mật khẩu</button>
            </div>
        </div>
    </div>
</div>

<!-- Thư viện JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.getElementById('profile-form');
    const editProfileBtn = document.getElementById('edit-profile-btn');
    const saveProfileBtn = document.getElementById('save-profile-btn');
    const submitBtn = document.getElementById('submit-btn');
    const changePasswordBtn = document.getElementById('change-password-btn');
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');

    // Modal
    const changePasswordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
    const savePasswordBtn = document.getElementById('save-password-btn');

    // Chức năng chỉnh sửa hồ sơ
    editProfileBtn.addEventListener('click', function() {
        const formElements = profileForm.querySelectorAll('input, select, textarea');
        formElements.forEach(element => {
            if (element.id !== 'username') {
                element.disabled = false;
            }
        });

        avatarInput.disabled = false;
        
        editProfileBtn.classList.add('d-none');
        saveProfileBtn.classList.remove('d-none');
        submitBtn.classList.remove('d-none');
    });

    // Chức năng thay đổi mật khẩu
    changePasswordBtn.addEventListener('click', function() {
        changePasswordModal.show();
    });

    savePasswordBtn.addEventListener('click', function() {
        const currentPassword = document.getElementById('modal-current-password').value;
        const newPassword = document.getElementById('modal-new-password').value;
        const confirmPassword = document.getElementById('modal-confirm-password').value;

        if (newPassword !== confirmPassword) {
            alert('Mật khẩu mới và xác nhận mật khẩu không khớp!');
            return;
        }

        if (newPassword.length < 6) {
            alert('Mật khẩu mới phải dài ít nhất 6 ký tự!');
            return;
        }

        // Điền mật khẩu vào form chính
        document.getElementById('current_password').value = currentPassword;
        document.getElementById('new_password').value = newPassword;
        document.getElementById('new_password').disabled = false;

        changePasswordModal.hide();
        alert('Mật khẩu đã được chuẩn bị để thay đổi');
    });

    // Xem trước ảnh avatar
    avatarInput.addEventListener('change', function() {
        const file = this.files[0];
        
        if (file) {
            // Validate định dạng file
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Chỉ chấp nhận file ảnh định dạng JPG, JPEG hoặc PNG!');
                this.value = '';
                return;
            }
            
            // Validate kích thước file (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Kích thước file không được vượt quá 2MB!');
                this.value = '';
                return;
            }
            
            // Xem trước ảnh
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>