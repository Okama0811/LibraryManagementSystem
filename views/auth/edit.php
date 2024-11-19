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
                            <button type="button" id="change-password-btn" class="btn btn-outline-secondary me-2" title="Đổi mật khẩu">
                                <i class="fa-solid fa-key"></i>
                            </button>
                            <button id="edit-profile-btn" class="btn btn-outline-primary me-2" title="Sửa thông tin">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button id="save-profile-btn" class="btn btn-outline-success d-none" title="Lưu thay đổi">
                                <i class="fa-regular fa-floppy-disk"></i>
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
                                <?php 
                                // Xác định loại badge và text cho button
                                $btnClass = '';
                                $btnText = '';
                                $btnIcon = '';

                                if ($user['role_id'] == 3) { // Thành viên
                                    $btnClass = 'btn-outline-primary';
                                    // Chuyển đổi member_type sang tiếng Việt
                                    switch(strtolower($user['member_type'])) {
                                        case 'regular':
                                            $btnText = 'Thành viên Thường';
                                            $btnIcon = 'bi-person';
                                            break;
                                        case 'vip':
                                            $btnText = 'Thành viên VIP';
                                            $btnIcon = 'bi-star-fill';
                                            break;
                                        case 'premium':
                                            $btnText = 'Thành viên Premium';
                                            $btnIcon = 'bi-gem';
                                            break;
                                        default:
                                            $btnText = 'Thành viên';
                                            $btnIcon = 'bi-person';
                                    }
                                } else { // Nhân viên/Admin
                                    switch(strtolower($_SESSION['role_name'])) {
                                        case 'admin':
                                            $btnClass = 'btn-outline-danger';
                                            $btnText = 'Quản trị viên';
                                            $btnIcon = 'bi-shield-lock';
                                            break;
                                        case 'librarian':
                                            $btnClass = 'btn-outline-success';
                                            $btnText = 'Thủ thư';
                                            $btnIcon = 'bi-book';
                                            break;
                                        default:
                                            $btnClass = 'btn-outline-info';
                                            $btnText = ucfirst($user['role_name']);
                                            $btnIcon = 'bi-person-badge';
                                    }
                                }
                                ?>
                                <button type="button" 
                                        class="btn <?php echo $btnClass; ?> btn-sm shadow-sm position-relative mb-3 px-2 rounded-pill" 
                                        id="accountInfoBtn"
                                        data-bs-container="body"
                                        data-bs-toggle="popover" 
                                        data-bs-placement="bottom"
                                        style="min-width: 100px; border-width: 2px;">
                                    <i class="bi <?php echo $btnIcon; ?> me-2"></i>
                                    <?php echo htmlspecialchars($btnText); ?>
                                    <?php if ($user['role_id'] != 3 && $user['status'] === 'active'): ?>
                                        <span class="position-absolute top-0 start-100 translate-middle p-2 bg-success border border-light rounded-circle">
                                            <span class="visually-hidden">Đang hoạt động</span>
                                        </span>
                                    <?php endif; ?>
                                </button>
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
                                        <label for="full_name" class="form-label">Họ tên:</label>
                                        <input type="text" name="full_name" id="full_name" class="form-control" 
                                            value="<?php echo htmlspecialchars($user['full_name']); ?>" required disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="username" class="form-label">Tên đăng nhập:</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="email" name="email" id="email" class="form-control" 
                                            value="<?php echo htmlspecialchars($user['email']); ?>" required disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Số điện thoại:</label>
                                        <input type="tel" name="phone" id="phone" class="form-control" 
                                            value="<?php echo htmlspecialchars($user['phone']); ?>" disabled>
                                    </div>
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

                                <div class="mb-3">
                                    <label for="address" class="form-label">Địa chỉ:</label>
                                    <input type="text" name="address" id="address" class="form-control" 
                                        value="<?php echo htmlspecialchars($user['address']); ?>" disabled>
                                </div>

                                <div class="mb-3">
                                    <label for="note" class="form-label">Ghi chú:</label>
                                    <textarea name="note" id="note" class="form-control" rows="3" disabled><?php echo htmlspecialchars($user['note']); ?></textarea>
                                </div>
                            </div>
                        </div> 
                </div>
                <div class="card-footer d-flex justify-content-between">
                            <a href="index.php" class="btn btn-outline-secondary" title="Quay lại">
                                <i class="fa-solid fa-arrow-left"></i>
                            </a>
                            <button type="submit" id="submit-btn" class="btn btn-outline-success d-none" title="Lưu thay đổi">
                                <i class="fa-regular fa-floppy-disk"></i>
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="password-change-form" method="POST" action="index.php?model=auth&action=edit">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Đổi mật khẩu</h5>

                    <button type="button" class="btn btn-close close-modal-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-danger"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal-current-password" class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" id="modal-current-password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal-new-password" class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control" id="modal-new-password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal-confirm-password" class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" class="form-control" id="modal-confirm-password" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelPasswordBtn">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu mật khẩu</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.getElementById('profile-form');
    const editProfileBtn = document.getElementById('edit-profile-btn');
    const saveProfileBtn = document.getElementById('save-profile-btn');
    const submitBtn = document.getElementById('submit-btn');
    const changePasswordBtn = document.getElementById('change-password-btn');
    const passwordChangeForm = document.getElementById('password-change-form');
    const cancelPasswordBtn = document.getElementById('cancelPasswordBtn');
    const modalElement = document.getElementById('changePasswordModal');
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');
    const accountInfoBtn = document.getElementById('accountInfoBtn');

    const changePasswordModal = new bootstrap.Modal(modalElement);
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

    // Mở modal đổi mật khẩu
    changePasswordBtn.addEventListener('click', function() {
        passwordChangeForm.reset();
        changePasswordModal.show();
    });

    // Xử lý nút Hủy
    cancelPasswordBtn.addEventListener('click', function() {
        changePasswordModal.hide();
    });

    // Xử lý nút đóng (X)
    modalElement.querySelector('.btn-close').addEventListener('click', function() {
        changePasswordModal.hide();
    });

    // Xử lý click bên ngoài modal
    modalElement.addEventListener('click', function(e) {
        if (e.target === modalElement) {
            changePasswordModal.hide();
        }
    });

    passwordChangeForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const currentPassword = document.getElementById('modal-current-password').value;
        const newPassword = document.getElementById('modal-new-password').value;
        const confirmPassword = document.getElementById('modal-confirm-password').value;

        // Kiểm tra validation
        if (!currentPassword || !newPassword || !confirmPassword) {
            alert('Vui lòng điền đầy đủ thông tin!');
            return;
        }

        if (newPassword !== confirmPassword) {
            alert('Mật khẩu mới và xác nhận mật khẩu không khớp!');
            return;
        }

        if (newPassword.length < 6) {
            alert('Mật khẩu mới phải dài ít nhất 6 ký tự!');
            return;
        }

        try {
            const formData = new FormData(passwordChangeForm);
            const response = await fetch('index.php?model=auth&action=edit', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.status === 'success') {
                alert(result.message);
                changePasswordModal.hide();
                passwordChangeForm.reset();
            } else {
                alert(result.message);
            }
        } catch (error) {
            // Thử parse response để lấy thông báo lỗi chi tiết
            try {
                const errorText = await error.response?.text();
                const errorJson = JSON.parse(errorText);
                alert(errorJson.message || 'Có lỗi xảy ra khi gửi yêu cầu!');
            } catch (parseError) {
                alert('Có lỗi xảy ra khi gửi yêu cầu!');
            }
            console.error('Error:', error);
        }
    });


    // Reset form khi đóng modal
    modalElement.addEventListener('hidden.bs.modal', function () {
        passwordChangeForm.reset();
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
    const popoverContent = `
    <?php if ($user['role_id'] > 1): ?>
        <div class="p-2">
            <p class="mb-2"><strong>Chức vụ:</strong> <?php echo htmlspecialchars($user['role_name']); ?></p>
            <p class="mb-2"><strong>Mô tả:</strong> <?php echo htmlspecialchars($user['role_description']); ?></p>
            <p class="mb-0"><strong>Trạng thái:</strong> 
                <span class="badge <?php echo $user['status'] === 'active' ? 'bg-success' : 'bg-danger'; ?>">
                    <?php echo $user['status'] === 'active' ? 'Đang hoạt động' : 'Không hoạt động'; ?>
                </span>
            </p>
        </div>
    <?php else: ?>
        <div class="p-2">
            <?php if ($user['role_id'] === 3): ?>
                <p class="mb-2"><strong>Loại thành viên:</strong> <?php echo htmlspecialchars($user['member_type']); ?></p>
                <p class="mb-2"><strong>Ngày hết hạn:</strong> <?php echo htmlspecialchars($user['expiry_date']); ?></p>
                <p class="mb-2"><strong>Số sách tối đa:</strong> <?php echo htmlspecialchars($user['max_books']); ?></p>
            <?php endif; ?>
            <p class="mb-0"><strong>Trạng thái:</strong> 
                <span class="badge <?php echo $user['status'] === 'active' ? 'bg-success' : 'bg-danger'; ?>">
                    <?php echo $user['status'] === 'active' ? 'Đang hoạt động' : 'Không hoạt động'; ?>
                </span>
            </p>
        </div>
    <?php endif; ?>
    `;

    // Initialize Bootstrap popover
    const popover = new bootstrap.Popover(accountInfoBtn, {
        container: 'body',
        html: true,
        content: popoverContent,
        trigger: 'manual', // Change to manual trigger
        placement: 'bottom'
    });

    // Toggle popover on button click
    let isPopoverVisible = false;
    accountInfoBtn.addEventListener('click', function(event) {
        event.stopPropagation();
        if (isPopoverVisible) {
            popover.hide();
        } else {
            popover.show();
        }
        isPopoverVisible = !isPopoverVisible;
    });

    // Close popover when clicking outside
    document.addEventListener('click', function(event) {
        if (!accountInfoBtn.contains(event.target) && 
            !event.target.closest('.popover')) {
            popover.hide();
            isPopoverVisible = false;
        }
    });

    // Handle popover hidden event
    accountInfoBtn.addEventListener('hidden.bs.popover', function() {
        isPopoverVisible = false;
    });

    // Handle popover shown event
    accountInfoBtn.addEventListener('shown.bs.popover', function() {
        isPopoverVisible = true;
    });

    // Reinitialize popover if it gets destroyed
    accountInfoBtn.addEventListener('click', function() {
        const currentPopover = bootstrap.Popover.getInstance(accountInfoBtn);
        if (!currentPopover) {
            const newPopover = new bootstrap.Popover(accountInfoBtn, {
                container: 'body',
                html: true,
                content: popoverContent,
                trigger: 'manual',
                placement: 'bottom'
            });
            newPopover.show();
            isPopoverVisible = true;
        }
    });
    saveProfileBtn.addEventListener('click', function() {
        submitBtn.click();
    });
});
</script>