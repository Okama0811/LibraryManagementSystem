<div class="container mt-4" style="margin-left:600px;margin-top:40px;margin-bottom:40px;"> 
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <?php if(isset($_SESSION['message'])): ?>
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
                    <form id="password-form" method="POST" action="index.php?model=member&action=change_password&id=<?php echo $_SESSION['user_id']?>">
                        <div class="form-group row">
                            <label for="current_password" class="col-sm-4 col-form-label text-right">Mật khẩu hiện tại:</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                                    <span class="password-toggle-icon">
                                        <i class="fas fa-eye toggle-password" data-target="current_password"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="new_password" class="col-sm-4 col-form-label text-right">Mật khẩu mới:</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                                    <span class="password-toggle-icon">
                                        <i class="fas fa-eye toggle-password" data-target="new_password"></i>
                                    </span>
                                </div>
                                <small class="form-text text-muted">Mật khẩu phải có ít nhất 6 ký tự</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="confirm_password" class="col-sm-4 col-form-label text-right">Xác nhận mật khẩu mới:</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                                    <span class="password-toggle-icon">
                                        <i class="fas fa-eye toggle-password" data-target="confirm_password"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Định dạng label và input trên cùng một dòng */
.form-group.row .col-form-label {
    display: flex;
    align-items: center;
    margin-bottom: 0;
}

.input-group {
    position: relative;
}

.password-toggle-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    cursor: pointer;
    color: #6c757d;
}

.toggle-password:hover {
    color: #4a4a4a;
}

.form-control {
    padding-right: 35px;
}

/* Căn giữa nút cập nhật mật khẩu */
.text-center button {
    margin: 0 auto;
    display: inline-block;
}
</style>
