<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=reservation&action=index">Quản lý đặt sách</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết phiếu đặt sách</li>
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
                        <h5 class="card-title mb-0">Chi tiết phiếu đặt sách</h5>
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

                    <form action="index.php?model=reservation&action=edit&id=<?= $reservation['reservation_id']; ?>" method="POST">
                        <!-- Thông tin người tạo -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Thông tin người tạo</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="full_name" class="form-label"><strong>Họ và tên:</strong></label> <?= htmlspecialchars($reservation['full_name']); ?>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="phone" class="form-label"><strong>Số điện thoại:</strong></label> <?= htmlspecialchars($reservation['phone']); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="email" class="form-label"><strong>Email:</strong></label> <?= htmlspecialchars($reservation['email']); ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="date_of_birth" class="form-label"><strong>Ngày sinh:</strong></label> <?= htmlspecialchars(date('d-m-Y', strtotime($reservation['date_of_birth']))); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="gender" class="form-label"><strong>Giới tính:</strong></label>
                                        <?php $gender = [
                                                'male' => 'Nam',
                                                'female' => 'Nữ',
                                            ];
                                            echo htmlspecialchars($gender[$reservation['gender']] ?? 'Khác');
                                        ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="address" class="form-label"><strong>Địa chỉ:</strong></label> <?= htmlspecialchars($reservation['address']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin phiếu đặt -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Thông tin phiếu đặt</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="reservation_date" class="form-label"><strong>Ngày đặt:</strong></label> <?= htmlspecialchars(date('d-m-Y', strtotime($reservation['reservation_date']))); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="form-label"><strong>Ngày hết hạn:</strong></label> <?= htmlspecialchars(date('d-m-Y', strtotime($reservation['expiry_date']))); ?>
                                    </div>
                                </div>
                                <div class="row-md-6">
                                    <label for="status" class="form-label"><strong>Tình trạng:</strong></label>
                                        <?php 
                                            $statusLabels = [
                                                'pending' => 'Đang xử lý',
                                                'confirmed' => 'Đã xác nhận',
                                                'fulfilled' => 'Hoàn thành',
                                                'expired' => 'Quá hạn',
                                                'canceled' => 'Bị hủy',
                                            ];
                                            echo htmlspecialchars($statusLabels[$reservation['status']] ?? 'Không xác định');
                                        ?>
                                </div>
                                <div class="row-md-6">
                                    <label for="notes" class="form-label" ><strong>Ghi chú:</strong></label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3" readonly><?= htmlspecialchars($reservation['notes']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Danh sách sách đặt -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Danh sách sách đặt</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th>Mã sách</th>
                                                <th>Tên sách</th>
                                                <th>Tác giả</th>
                                                <th>Tình trạng sách</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($reservationDetails as $detail): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($detail['book_id']); ?></td>
                                                    <td><?= htmlspecialchars($detail['title']); ?></td>
                                                    <td><?= htmlspecialchars($detail['authors']); ?></td>
                                                    <td>
                                                    <?php 
                                                        $statusLabels = [
                                                            'available' => 'Còn sách',
                                                            'unavailable' => 'Hết sách'
                                                        ];
                                                        echo htmlspecialchars($statusLabels[$detail['status']] ?? 'Không xác định');
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="index.php?model=reservation&action=index" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Quay lại
                        </a>
                        <input type="hidden" name="status" id="status" value="<?= $reservation['status']; ?>">

                        <?php if ($reservation['status'] === 'pending'): ?>
                            <button type="submit" onclick="document.getElementById('status').value = 'confirmed';" class="btn btn-warning">
                                Phê duyệt
                            </button>
                            <button type="submit" onclick="document.getElementById('status').value = 'canceled';" class="btn btn-danger">
                                Hủy phiếu
                            </button>
                        <?php endif; ?>

                        <?php if ($reservation['status'] === 'confirmed'): ?>
                            <button type="submit" onclick="document.getElementById('status').value = 'fulfilled';" class="btn btn-success">
                                Hoàn thành
                            </button>
                            <button type="submit" onclick="document.getElementById('status').value = 'canceled';" class="btn btn-danger">
                                Hủy phiếu
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>