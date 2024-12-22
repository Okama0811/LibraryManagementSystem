<style>
.avatar-wrapper {
    padding: 10px;
}

.avatar-container {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin: 0 auto;
    overflow: hidden;
    border: 3px solid #f8f9fa;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    position: relative;
}

.avatar-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.avatar-container:hover img {
    transform: scale(1.1);
}

.avatar-upload {
    margin-top: 15px;
}

.avatar-upload label {
    cursor: pointer;
    transition: all 0.3s ease;
}

.avatar-upload label:hover {
    background-color: #007bff;
    color: white;
}
</style>
<div class="container mt-4" style="margin-top:40px;margin-bottom:40px;">
    <div class="row">
        <div class="col-md-12 offset-md-2">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show">
                    <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                    ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>

            <div class="card">
            
                <div class="card-body">
                    <form id="profile-form" method="POST" action="index.php?model=member&action=edit&id=<?php echo $_SESSION['user_id']?>"
                        enctype="multipart/form-data">
                        <div class="row">
                            <!-- Avatar Section -->
                            <div class="col-md-4 text-center mb-4">
                                <div class="avatar-wrapper position-relative">
                                    <div class="avatar-container">
                                        <img src="<?php echo !empty($_SESSION['avatar_url']) ? 'uploads/avatars/' . $_SESSION['avatar_url'] : 'assets/images/default-avatar.png'; ?>"
                                            class="img-fluid"
                                            alt="Avatar">
                                    </div>
                                    <div class="avatar-upload mt-2">
                                        <label for="avatar" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-camera"></i> Đổi ảnh đại diện
                                        </label>
                                        <input type="file" id="avatar" name="avatar" class="d-none" accept="image/jpeg,image/png">
                                    </div>
                                </div>
                            </div>

                            <!-- Basic Info Section -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label><strong>Tên tài khoản:</strong></label>
                                    <input type="text" class="form-control"
                                        value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Họ và tên:</label>
                                    <input type="text" name="full_name" class="form-control"
                                        value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Email:</label>
                                    <input type="email" name="email" class="form-control"
                                        value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Số điện thoại:</label>
                                    <input type="tel" name="phone" class="form-control"
                                        value="<?php echo htmlspecialchars($user['phone']); ?>">
                                </div>

                                <div class="form-group">
                                    <label>Địa chỉ:</label>
                                    <textarea name="address" class="form-control"
                                        rows="2"><?php echo htmlspecialchars($user['address']); ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ngày sinh:</label>
                                            <input type="date" name="date_of_birth" class="form-control"
                                                value="<?php echo htmlspecialchars($user['date_of_birth']); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Giới tính:</label>
                                            <select name="gender" class="form-control">
                                                <option value="male" <?php echo $user['gender'] == 'male' ? 'selected' : ''; ?>>Nam</option>
                                                <option value="female" <?php echo $user['gender'] == 'female' ? 'selected' : ''; ?>>Nữ</option>
                                                <option value="other" <?php echo $user['gender'] == 'other' ? 'selected' : ''; ?>>Khác</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Loại thành viên:</label>
                                    <input type="text" class="form-control"
                                        value="<?php echo htmlspecialchars($user['member_type']); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        // Preview avatar before upload
        $("#avatar").change(function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.avatar-wrapper img').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>