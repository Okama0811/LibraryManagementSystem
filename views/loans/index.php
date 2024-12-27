<!-- Phần content -->
<div class="card shadow mb-4">
    <div class="card-header py-2">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Quản lý phiếu</h5>
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <input type="search" id="searchInput" class="form-control" placeholder="Tìm kiếm...">
                </div>
                <div>
                <?php if ($userData['role_id'] == 3): ?>
                    <a href="index.php?model=loan&action=create" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i> Thêm Phiếu
                    </a>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-hover table-striped table-bordered">
                <thead class="table-dark text-center">
                    <tr>
                        <th class="align-middle">Mã Phiếu</th>
                        <th class="align-middle">Người Tạo</th>
                        <th class="align-middle">Thời Gian Tạo</th>
                        <th class="align-middle">Tình Trạng</th>
                        <th class="text-center align-middle"><i class="fas fa-cog"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loans as $loan): ?>
                        <tr>
                            <td class="text-center align-middle"><?= $loan['loan_id'] ?></td>
                            <td class="text-center align-middle"><?= htmlspecialchars($loan['user_name']) ?></td>
                            <td class="text-center align-middle"><?= htmlspecialchars((new DateTime($loan['created_at']))->format('d/m/Y')) ?></td>
                            <td class="text-center align-middle"><?= htmlspecialchars($loan['status']) ?></td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center">
                                    <a href="index.php?model=loan&action=show&id=<?= $loan['loan_id'] ?>" class="btn btn-sm btn-outline-info me-3" title="Xem Chi Tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>