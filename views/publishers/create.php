<div class="container-fluid">
        <div class="row mt-3">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?model=publisher&action=index">Quản lý nhà xuất bản</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm nhà xuất bản mới/li>
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
                            <h5 class="card-title mb-0">Thêm nhà xuất bản mới</h5>
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
                        <form action="index.php?model=publisher&action=create" method="POST" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Tên nhà xuất bản:</label>
                                    <input type="text" name="name" id="name" class="form-control" required 
                                        value="<?php echo isset($_SESSION['form_data']['name']) ? htmlspecialchars($_SESSION['form_data']['name']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" name="email" id="email" class="form-control" required
                                        value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
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

                            <div class="mb-3">
                                <label for="web" class="form-label">Địa chỉ Website:</label>
                                <textarea name="web" id="web" class="form-control" rows="3"><?php echo isset($_SESSION['form_data']['web']) ? htmlspecialchars($_SESSION['form_data']['web']) : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Ảnh nhà xuất bản:</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            </div>

                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?model=publisher&action=index" class="btn btn-secondary"> 
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

    </script>