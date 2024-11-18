<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng ký - Thư viện UTT</title>
    <link href="./vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href=".\assets\css\mystyle.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <style>
        .form-control-user {
            border-radius: 25px !important;
            padding: 1.2rem 1.1rem !important;
            font-size: 1.1rem;
        }
        
        /* Style cho title đăng ký */
        .register-title {
            font-size: 2rem;
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .register-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: #4e73df;
            border-radius: 2px;
        }

        /* Style cho điều khoản sử dụng */
        .custom-control-label {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .custom-control-label a {
            color: #4e73df;
            text-decoration: none;
            font-weight: 500;
        }

        .custom-control-label a:hover {
            text-decoration: underline;
        }

        /* Style cho modal điều khoản */
        #termsModal .modal-dialog {
            max-width: 400px;
        }

        #termsModal .modal-title {
            font-size: 1.25rem;
            color: #2c3e50;
            font-weight: 600;
        }

        #termsModal .modal-body {
            font-size: 0.9rem;
            color: #5a6570;
            padding: 1rem 1.5rem;
        }

        #termsModal .modal-body p {
            margin-bottom: 0.75rem;
            line-height: 1.5;
        }

        .password-field {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #858796;
        }
        
        .form-control.gender-select {
            border-radius: 25px !important;
            height: calc(1.5em + 1rem) !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.9rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background: #fff url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23858796' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E") no-repeat right .75rem center/8px 10px;
        }
        
        .birth-gender-row {
            margin-left: -5px;
            margin-right: -5px;
        }
        
        .birth-gender-row > div {
            padding-left: 5px;
            padding-right: 5px;
        }
        
        .birth-gender-row .form-control {
            height: calc(1.5em + 1rem) !important;
        }

        /* Additional styling for form elements */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-control {
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78,115,223,.25);
        }

        .btn-user {
            font-size: 0.9rem;
            border-radius: 25px;
            padding: 0.75rem 1rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.2s;
        }

        .btn-primary.btn-user {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .btn-primary.btn-user:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
            transform: translateY(-1px);
        }

        .small {
            font-size: 0.875rem;
            color: #858796;
        }

        .small:hover {
            color: #4e73df;
            text-decoration: none;
        }

        textarea.form-control-user {
            min-height: 100px;
            resize: vertical;
        }

        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body style="background-color: #FAF9F6;">  
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-register-image">
                                <img src="./assets/images/img_register.jpg" 
                                    alt="Mô tả ảnh" 
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="register-title">Đăng ký tài khoản</h1>
                                    </div>
                                    <?php if (!empty($error_msg)): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $error_msg; ?>
                                        </div>
                                    <?php endif; ?>
                                    <form class="user" action="index.php?model=auth&action=register" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control form-control-user" 
                                                placeholder="Tên người dùng" required 
                                                value="<?php echo htmlspecialchars($formData['username'] ?? ''); ?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control form-control-user" 
                                                placeholder="Email" required
                                                value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
                                        </div>
                                        <div class="form-group">
                                            <div class="password-field">
                                                <input type="password" name="password" class="form-control form-control-user" 
                                                    id="password" placeholder="Mật khẩu (ít nhất 6 ký tự)" required
                                                    minlength="6">
                                                <i class="fas fa-eye toggle-password" data-target="password"></i>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="password-field">
                                                <input type="password" name="confirm_password" class="form-control form-control-user" 
                                                    id="confirm_password" placeholder="Xác nhận mật khẩu" required
                                                    minlength="6">
                                                <i class="fas fa-eye toggle-password" data-target="confirm_password"></i>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="full_name" class="form-control form-control-user" 
                                                placeholder="Họ và tên" required
                                                value="<?php echo htmlspecialchars($formData['full_name'] ?? ''); ?>">
                                        </div>
                                        <div class="form-group">
                                            <div class="row birth-gender-row">
                                                <div class="col-7">
                                                    <input type="date" name="date_of_birth" class="form-control form-control-user" 
                                                        placeholder="Ngày sinh"
                                                        value="<?php echo htmlspecialchars($formData['date_of_birth'] ?? ''); ?>">
                                                </div>
                                                <div class="col-5">
                                                    <select name="gender" class="form-control gender-select">
                                                        <option value="">Giới tính</option>
                                                        <option value="male" <?php echo (isset($formData['gender']) && $formData['gender'] === 'male') ? 'selected' : ''; ?>>Nam</option>
                                                        <option value="female" <?php echo (isset($formData['gender']) && $formData['gender'] === 'female') ? 'selected' : ''; ?>>Nữ</option>
                                                        <option value="other" <?php echo (isset($formData['gender']) && $formData['gender'] === 'other') ? 'selected' : ''; ?>>Khác</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="phone" class="form-control form-control-user" 
                                                placeholder="Số điện thoại"
                                                value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>">
                                        </div>
                                        <div class="form-group">
                                            <textarea name="address" class="form-control form-control-user" 
                                                placeholder="Địa chỉ"><?php echo htmlspecialchars($formData['address'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="form-group mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="terms" value="1" class="custom-control-input" id="terms" required>
                                                <label class="custom-control-label" for="terms">
                                                    Tôi đồng ý với <a href="#" data-toggle="modal" data-target="#termsModal">điều khoản sử dụng</a>
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Đăng ký</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="index.php?model=auth&action=login">Đã có tài khoản? Đăng nhập!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Điều khoản sử dụng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>1. Người dùng phải cung cấp thông tin chính xác.</p>
                    <p>2. Thông tin cá nhân sẽ được bảo mật.</p>
                    <p>3. Người dùng phải tuân thủ nội quy thư viện.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://kit.fontawesome.com/1b233c9fdd.js" crossorigin="anonymous"></script>
    <script src="./vendor/jquery/jquery.min.js"></script>
    <script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./vendor/jquery-easing/jquery.easing.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý sự kiện hiện/ẩn mật khẩu
        const togglePassword = document.querySelectorAll('.toggle-password');
        
        togglePassword.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                
                if (input.type === "password") {
                    input.type = "text";
                    this.classList.remove("fa-eye");
                    this.classList.add("fa-eye-slash");
                } else {
                    input.type = "password";
                    this.classList.remove("fa-eye-slash");
                    this.classList.add("fa-eye");
                }
            });
        });
    });
    </script>
</body>
</html>