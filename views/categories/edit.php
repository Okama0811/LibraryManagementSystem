<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=category&action=index">Quản lý thể loại</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa thông tin thể loại</li>
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
                        <h5 class="card-title mb-0">Chỉnh sửa thông tin thể loại</h5>
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

                    <form action="index.php?model=category&action=edit&id=<?= $category['category_id']; ?>" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Tên thể loại:</label>
                             <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($category['name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="description" class="form-label">Mô tả:</label>
                            <textarea name="description" id="description" class="form-control" rows="3"><?= htmlspecialchars($category['description']); ?></textarea>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="index.php?model=category&action=index" class="btn btn-secondary">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleEditBtn = document.getElementById('toggleEdit');
    const saveChangesBtn = document.getElementById('saveChanges');
    const allInputs = document.querySelectorAll('input, select, textarea');

    // Disable tất cả các input ban đầu
    allInputs.forEach(input => {
        input.disabled = true;
    });

    // Toggle chế độ chỉnh sửa
    toggleEditBtn.addEventListener('click', function() {
        const isDisabled = allInputs[0].disabled; // Kiểm tra trạng thái của ô đầu tiên
        
        if (isDisabled) {
            // Chuyển sang chế độ chỉnh sửa
            toggleEditBtn.style.display = 'none';
            saveChangesBtn.style.display = 'inline-block'; // Hiển thị nút lưu
            allInputs.forEach(input => {
                input.disabled = false; // Bật chỉnh sửa
            });
        }
    });
});

</script>