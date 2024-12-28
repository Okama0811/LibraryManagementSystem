<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=role&action=index">Quản lý chức vụ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sửa chức vụ</li>
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
                        <h5 class="card-title mb-0">Sửa chức vụ</h5>
                    </div>
                </div>
                <form action="index.php?model=role&action=edit&id=<?php echo $_SESSION['form_data']['role_id']; ?>" method="POST">
                    <div class="card-body">
                        <?php if (isset($_SESSION['message'])): ?>
                            <div id="alert-message"
                                class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show"
                                role="alert">
                                <?= $_SESSION['message']; ?>
                            </div>
                            <?php
                            unset($_SESSION['message']);
                            unset($_SESSION['message_type']);
                            ?>
                            <script>
                                setTimeout(function () {
                                    var alert = document.getElementById('alert-message');
                                    if (alert) {
                                        alert.classList.remove('show');
                                        alert.classList.add('fade');
                                        setTimeout(function () {
                                            alert.style.display = 'none';
                                        }, 150);
                                    }
                                }, 2000);
                            </script>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên chức vụ:</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                value="<?php echo isset($_SESSION['form_data']['name']) ? htmlspecialchars($_SESSION['form_data']['name']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả:</label>
                            <textarea name="description" id="description" class="form-control"
                                rows="3"><?php echo isset($_SESSION['form_data']['description']) ? htmlspecialchars($_SESSION['form_data']['description']) : ''; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quyền hạn:</label>
                            <div class="border rounded p-3">
                                <div class="mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" id="select-all" class="form-check-input">
                                        <label class="form-check-label" for="select-all">Chọn tất cả</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                    $permissions = $data->fetchAll(PDO::FETCH_ASSOC);
                                    ?>
                                    <?php if (!empty($permissions) && is_array($permissions)): ?>
                                        <?php foreach ($permissions as $permission): ?>
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" 
                                                           class="form-check-input permission-checkbox" 
                                                           name="permissions[]" 
                                                           value="<?php echo $permission['permission_id']; ?>"
                                                           id="permission_<?php echo $permission['permission_id']; ?>"
                                                           <?php 
                                                           if(isset($_SESSION['form_data']['permissions'])) {
                                                            $permission_ids = array_map(function($p) {
                                                                return $p['permission_id'];
                                                            }, $_SESSION['form_data']['permissions']);
                                                            
                                                            if(in_array($permission['permission_id'], $permission_ids)) {
                                                                echo 'checked';
                                                            }
                                                        }
                                                           ?>>
                                                    <label class="form-check-label" 
                                                           for="permission_<?php echo $permission['permission_id']; ?>">
                                                        <?php echo htmlspecialchars($permission['description']); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="index.php?model=role&action=index" class="btn btn-secondary">
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
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all');
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

        selectAllCheckbox.addEventListener('change', function () {
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        function updateSelectAllCheckbox() {
            const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
            const totalCount = permissionCheckboxes.length;
            selectAllCheckbox.checked = checkedCount === totalCount;
        }

        permissionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectAllCheckbox);
        });

        const form = document.querySelector('form');
        form.addEventListener('submit', function (event) {
            const name = document.getElementById('name').value.trim();
            if (name === '') {
                event.preventDefault();
                alert('Vui lòng nhập tên chức vụ.');
                return;
            }

            const checkedPermissions = document.querySelectorAll('.permission-checkbox:checked');
            if (checkedPermissions.length === 0) {
                event.preventDefault();
                alert('Vui lòng chọn ít nhất một quyền hạn.');
                return;
            }
        });
    });
</script>