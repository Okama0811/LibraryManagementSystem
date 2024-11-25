    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?model=author&action=index">Quản lý tác giả</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tạo tác giả mới</li>
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
                            <h5 class="card-title mb-0">Tạo tác giả mới</h5>
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
                        <form action="index.php?model=author&action=create" method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Tên tác giả:</label>
                                    <input type="text" name="name" id="name" class="form-control" required 
                                        value="<?php echo isset($_SESSION['form_data']['name']) ? htmlspecialchars($_SESSION['form_data']['name']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="nationality" class="form-label">Quốc tịch:</label>
                                    <input type="text" name="nationality" id="nationality" class="form-control" 
                                        value="<?php echo isset($_SESSION['form_data']['nationality']) ? htmlspecialchars($_SESSION['form_data']['nationality']) : ''; ?>">
                                </div>
                            </div>

                            <div class="row-md-6">
                                <label for="birth_date" class="form-label">Ngày sinh:</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control" required
                                    value="<?php echo isset($_SESSION['form_data']['birth_date']) ? htmlspecialchars($_SESSION['form_data']['birth_date']) : ''; ?>" >
                            </div>

                            <div class="row-md-6" style="margin-top:20px">
                                <label for="biography" class="form-label">Tiểu sử:</label>
                                <textarea name="biography" id="biography" class="form-control" rows="4" 
                                    value="<?php echo isset($_SESSION['form_data']['biography']) ? htmlspecialchars($_SESSION['form_data']['biography']) : ''; ?>"></textarea>
                            </div>
                        
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?model=author&action=index" class="btn btn-secondary"> 
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