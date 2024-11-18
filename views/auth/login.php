<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng nhập - Thư viện UTT</title>
    <link href="./vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href=".\assets\css\mystyle.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #FAF9F6;
        }

        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        .card.o-hidden.border-0.shadow-lg {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        .login-title {
            font-size: 1.8rem;
            color: #2c3e50;
            font-weight: 500;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .login-title:after {
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

        /* Style cho form controls */
        .form-control-user {
            border-radius: 25px !important;
            padding: 1.2rem 1.1rem !important;
            font-size: 1.1rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        .form-control-user:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78,115,223,.25);
        }

        /* Style cho password field */
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

        /* Style cho checkbox */
        .custom-control-label {
            font-size: 0.85rem;
            color: #6c757d;
            cursor: pointer;
        }

        /* Style cho buttons */
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

        /* Style cho links */
        .small {
            font-size: 0.875rem;
            color: #858796;
            transition: color 0.2s;
        }

        .small:hover {
            color: #4e73df;
            text-decoration: none;
        }

        /* Background styling */
        .bg-gradient-primary {
            background: linear-gradient(145deg, #4e73df 0%, #224abe 100%);
        }

        /* Card styling */
        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .bg-login-image {
            background: url('./assets/images/img_login.jpg');
            background-position: center;
            background-size: cover;
        }
    </style>
</head>

<body class="background-color: #FAF9F6;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="login-title">Chào mừng trở lại!</h1>
                                    </div>
                                    <?php if (!empty($error_msg)): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $error_msg; ?>
                                        </div>
                                    <?php endif; ?>
                                    <form class="user" action="index.php?model=auth&action=login" method="POST">
                                        <div class="form-group">
                                            <input type="username" name="username" 
                                                class="form-control form-control-user" 
                                                id="exampleInputUsername" 
                                                placeholder="Nhập tên đăng nhập" 
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <div class="password-field">
                                                <input type="password" name="password" 
                                                    class="form-control form-control-user" 
                                                    id="exampleInputPassword" 
                                                    placeholder="Nhập mật khẩu" 
                                                    required>
                                                <i class="fas fa-eye toggle-password" data-target="exampleInputPassword"></i>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">
                                                    Ghi nhớ đăng nhập
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Đăng nhập
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="index.php?model=auth&action=register">
                                            Chưa có tài khoản? Đăng ký
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none d-lg-block bg-register-image">
                                <img src="./assets/images/img_login.jpg" 
                                    alt="Mô tả ảnh" 
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/1b233c9fdd.js" crossorigin="anonymous"></script>
    <script src="./vendor/jquery/jquery.min.js"></script>
    <script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./vendor/jquery-easing/jquery.easing.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý sự kiện hiện/ẩn mật khẩu
        const togglePassword = document.querySelector('.toggle-password');
        
        togglePassword.addEventListener('click', function() {
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
    </script>
</body>
</html>