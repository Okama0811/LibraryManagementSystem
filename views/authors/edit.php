<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=author&action=index">Quản lý tác giả</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa thông tin tác giả</li>
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
                        <h5 class="card-title mb-0">Chỉnh sửa thông tin tác giả</h5>
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

                    <form action="index.php?model=author&action=edit&id=<?= $author['author_id']; ?>" method="POST" enctype="multipart/form-data">
                        
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="avatar-wrapper mb-3">
                                    <img id="avatar-preview" class="rounded-circle img-thumbnail" 
                                        src="<?php echo !empty($author['avatar_url']) ? 'uploads/avatars/' . $author['avatar_url'] : 'assets/images/default-avatar.png'; ?>" 
                                        alt="Avatar" style="width: 200px; height: 200px; object-fit: cover;">
                                </div>
                            
                                <div class="mb-3">
                                    <label for="avatar" class="form-label">Thay đổi ảnh đại diện</label>
                                    <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" disabled>
                                    <small class="text-muted">Cho phép: JPG, JPEG, PNG. Tối đa 2MB</small>
                                </div>
                            </div>
                        
                            <div class="col-md-9">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Tên tác giả:</label>
                                        <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($author['name']); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nationality" class="form-label">Quốc tịch:</label>
                                        <input type="text" name="nationality" id="nationality" class="form-control" value="<?= htmlspecialchars($author['nationality']); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="birth_date" class="form-label">Ngày sinh:</label>
                                        <input type="date" name="birth_date" id="birth_date" class="form-control" value="<?= htmlspecialchars($author['birth_date']); ?>">
                                    </div>
                                </div>
                                <div class="row-md-3">
                                        <label for="biography" class="form-label">Tiểu sử:</label>
                                        <textarea name="biography" id="biography" class="form-control" rows="4"><?= htmlspecialchars($author['biography']); ?></textarea>
                                    </div>
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?model=author&action=index" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="button" id="toggleEdit" class="btn btn-primary">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button type="submit" id="saveChanges" class="btn btn-success" style="display: none;"> 
                                <i class="fa-regular fa-floppy-disk"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const roleSelect = document.getElementById('role_id');
    const toggleEditBtn = document.getElementById('toggleEdit');
    const saveChangesBtn = document.getElementById('saveChanges');
    const allInputs = document.querySelectorAll('input, select, textarea');
    
    // Disable all inputs initially
    allInputs.forEach(input => {
        input.disabled = true;
    });

    // Toggle edit mode
    toggleEditBtn.addEventListener('click', function() {
        // Thay đổi logic kiểm tra trạng thái
        const isDisabled = allInputs[0].disabled;
        
        if (isDisabled) {
            // Chuyển sang chế độ edit
            toggleEditBtn.style.display = 'none';
            saveChangesBtn.style.display = 'block';
            // Enable all inputs
            allInputs.forEach(input => {
                input.disabled = false;
            });
        } else {
            // Chuyển sang chế độ view
            toggleEditBtn.style.display = 'block';
            saveChangesBtn.style.display = 'none';
            // Disable all inputs
            allInputs.forEach(input => {
                input.disabled = true;
            });
        }
    });
});

</script>