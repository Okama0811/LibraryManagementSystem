<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng ký - Thư viện UTT</title>
    <link href="./vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href=".\assets\css\mystyle.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
</head>

<body class="bg-gradient-primary">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-register-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Tạo tài khoản mới</h1>
                                </div>
                                <?php if (!empty($error_msg)): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $error_msg; ?>
                                    </div>
                                <?php endif; ?>
                                <form class="user" action="index.php?model=auth&action=register" method="POST">
                                    <div class="form-group">
                                        <input type="text" name="username" class="form-control form-control-user" placeholder="Tên người dùng" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control form-control-user" placeholder="Email" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control form-control-user" placeholder="Mật khẩu" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="full_name" class="form-control form-control-user" placeholder="Họ và tên" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="date" name="date_of_birth" class="form-control form-control-user" placeholder="Ngày sinh">
                                    </div>
                                    <div class="form-group">
                                        <select name="gender" class="form-control">
                                            <option value="">Giới tính</option>
                                            <option value="male">Nam</option>
                                            <option value="female">Nữ</option>
                                            <option value="other">Khác</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="phone" class="form-control form-control-user" placeholder="Số điện thoại">
                                    </div>
                                    <div class="form-group">
                                        <textarea name="address" class="form-control form-control-user" placeholder="Địa chỉ"></textarea>
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

<script src="https://kit.fontawesome.com/1b233c9fdd.js" crossorigin="anonymous"></script>
<script src="./vendor/jquery/jquery.min.js"></script>
<script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="./vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="./assets/js/myscript.js"></script>
</body>
</html>
