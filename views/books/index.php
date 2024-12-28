<!-- Breadcrumb -->
<div class="container-fluid">
    <div class="row my-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 py-2 bg-light">
                    <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quản lý sách</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Thông báo lỗi -->
<?php if (isset($_SESSION['message'])): ?>
        <div id="alert-message" class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show"
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

<!-- Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Quản lý sách</h5>
                <div class="d-flex align-items-center">
                    <input type="search" id="searchInput" class="form-control me-3" placeholder="Tìm kiếm sách...">
                    <a href="index.php?model=book&action=create" class="btn btn-primary ml-3">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Tác giả</th>
                            <th>Thể loại</th>
                            <th>Nhà xuất bản</th>
                            <th>Trạng thái</th>
                            <th>Số lượng có sẵn</th>
                            <th class="text-center align-middle"><i class="fas fa-cog"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td><?= $book['book_id'] ?></td>
                                <td><?= htmlspecialchars($book['title']) ?></td>
                                <td><?= htmlspecialchars($book['authors']) ?></td>
                                <td><?= htmlspecialchars($book['categories']) ?></td>
                                <td><?= htmlspecialchars($book['publisher_name']) ?></td>
                                <td><?= htmlspecialchars($book['status']) ?></td>
                                <td><?= $book['available_quantity'] ?> </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="index.php?model=book&action=edit&id=<?= $book['book_id'] ?>" class="btn btn-sm btn-outline-primary me-2" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="index.php?model=book&action=delete&id=<?= $book['book_id'] ?>" method="POST" class="d-inline" onsubmit="return confirmDelete();">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
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
        dom: 'rtp',
        language: {
            processing: "Đang xử lý...",
            search:'<i class="fas fa-search"></i>',
            lengthMenu: "Hiển thị _MENU_ dòng",
            info: "Đang hiển thị _START_ đến _END_ của _TOTAL_ bản ghi",
            infoEmpty: "Không có dữ liệu",
            infoFiltered: "(Được lọc từ _MAX_ bản ghi)",
            infoPostFix: "",
            loadingRecords: "Đang tải...",
            zeroRecords: "Không tìm thấy bản ghi nào",
            emptyTable: "Không có dữ liệu trong bảng",
            paginate: {
                first: "Đầu",
                previous: "Trước",
                next: "Tiếp",
                last: "Cuối"
            },
            aria: {
                sortAscending: ": Sắp xếp tăng dần",
                sortDescending: ": Sắp xếp giảm dần"
            }
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
        
        var book_id = $(this).find('td:first').text().trim();
        
        window.location.href = 'index.php?model=book&action=edit&id=' + book_id;
    });

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#dataTable tbody').on('click', 'tr', function(e) {
        if ($(e.target).closest('button').length || $(e.target).closest('form').length) {
            return;
        }
        
        var book_id = $(this).find('td:first').text().trim();
        
        window.location.href = 'index.php?model=book&action=edit&id=' + book_id;
    });
});

function confirmDelete() {
    return confirm('Bạn có chắc muốn xóa sách này?');
}
</script>
