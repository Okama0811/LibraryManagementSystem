<!-- breadcrum -->
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=user&action=index">Quản lý tài khoản người dùng</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Thông báo lỗi -->
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
    <!-- Phần content -->
    <div class="card shadow mb-4">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Quản lý tài khoản người dùng</h5>
                <div>
                    <a id="toggleSearch" class="btn btn-secondary">Tìm kiếm</a>
                    <a href="index.php?model=user&action=create" class="btn btn-primary">Thêm mới</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Tìm kiếm -->
            <form id="searchForm" class="mb-3" style="display: none;">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <div class="d-flex align-items-center">
                            <label for="emailSearch" class="mr-2 mb-0" style="white-space: nowrap;">Email:&nbsp;&nbsp;&nbsp;</label>
                            <input type="text" id="emailSearch" class="form-control" placeholder="Tìm theo email">
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="d-flex align-items-center">
                            <label for="tenSearch" class="mr-2 mb-0" style="white-space: nowrap;">Tên:&nbsp;&nbsp;&nbsp;</label>
                            <input type="text" id="tenSearch" class="form-control" placeholder="Tìm theo tên">
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="d-flex align-items-center">
                            <label for="vaitroSearch" class="mr-2 mb-0" style="white-space: nowrap;">Vai trò:&nbsp;&nbsp;&nbsp;</label>
                            <select id="vaitroSearch" class="form-control">
                                <option value="">Chọn vai trò</option>
                                <option value="Cán bộ nhân viên nhà trường">Cán bộ nhân viên nhà trường</option>
                                <option value="Kỹ thuật viên">Kỹ thuật viên</option>
                                <option value="Nhân viên quản lý tài sản">Nhân viên quản lý tài sản</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Bảng -->
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-light text-black text-center">
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Vai Trò</th>
                            <th class="text-center"><i class="fas fa-cog"></i></th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="text-center"><?= $user['user_id'] ?></td>
                                <td><?= htmlspecialchars($user['full_name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['role_name']) ?></td>
                                <td class="text-center">
                                    <form action="index.php?model=user&action=delete&id=<?= $user['user_id'] ?>" method="POST" style="display: inline-block;" onsubmit="return confirmDelete();">
                                        <button type="submit" class="btn p-0 text-danger" style="background: none; border: none;" title="Xóa">
                                            <i class="fas fa-times" style="font-size: 1.3rem;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>    
$(document).ready(function() {
    var table = $('#dataTable').DataTable({
    dom: 'rtip',
    language: {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Vietnamese.json"
    },
    columnDefs: [
        {
            targets: -1, 
            orderable: false, 
            searchable: false 
        }
    ]
});

    $('#dataTable tbody').on('click', 'tr', function(e) {
        if ($(e.target).closest('button').length || $(e.target).closest('form').length) {
            return;
        }
        
        var userId = $(this).find('td:first').text().trim();
        
        window.location.href = 'index.php?model=user&action=edit&id=' + userId;
    });

    function filterTable() {
        var nameFilter = $('#tenSearch').val().toLowerCase();
        var emailFilter = $('#emailSearch').val().toLowerCase();
        var roleFilter = $('#vaitroSearch').val();

        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var email = data[1].toLowerCase();
            var name = data[2].toLowerCase();
            var role = data[3];

            var roleMatch = roleFilter === '' || role.includes(roleFilter);

            return name.includes(nameFilter) && email.includes(emailFilter) && roleMatch;
        });

        table.draw();

        $.fn.dataTable.ext.search.pop();
    }

    $('#tenSearch, #emailSearch').on('keyup', filterTable);
    $('#vaitroSearch').on('change', filterTable);

    $('#toggleSearch').on('click', function() {
        var searchForm = $('#searchForm');
        if (searchForm.is(':hidden')) {
            searchForm.show();
            $(this).text('Ẩn tìm kiếm');
        } else {
            searchForm.hide();
            $(this).text('Tìm kiếm');
        }
    });
});

function confirmDelete() {
    return confirm('Bạn có chắc muốn xóa người dùng này?');
}
</script>