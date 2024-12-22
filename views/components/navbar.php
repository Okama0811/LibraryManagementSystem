<nav class="navbar navbar-expand navbar-dark topbar mb-4 static-top shadow" style="background-color: #474952;">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link text-white d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Tìm kiếm..." aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-secondary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Tìm kiếm..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">3+</span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in bg-dark" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header bg-dark text-white">
                    Thông báo
                </h6>
                <a class="dropdown-item d-flex align-items-center text-white" href="#">
                    <div class="mr-3">
                        <i class="fas fa-file-alt text-primary"></i>
                    </div>
                    <div>
                        <div class="small text-white-50">December 12, 2019</div>
                        Báo cáo mới đã được tạo
                    </div>
                </a>
                <!-- Thêm các thông báo khác ở đây -->
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-white small">
                    <?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Guest'; ?>
                </span>
                <?php
                $avatar = isset($_SESSION['avatar_url']) ? 'uploads/avatars/'.$_SESSION['avatar_url'] : 'assets/images/default-avatar.png';
                ?>
                <img class="img-profile rounded-circle" src="<?php echo $avatar; ?>" style="width: 40px; height: 40px; object-fit: cover;">
            </a>

            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow-lg animated--grow-in bg-dark" aria-labelledby="userDropdown">
            <a class="dropdown-item text-white" href="index.php">
                    <i class="fas fa-house fa-sm fa-fw mr-2 text-white-50"></i>
                    Trang chủ
                </a>    
            <a class="dropdown-item text-white" href="index.php?model=auth&action=edit">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-white-50"></i>
                    Hồ sơ cá nhân
                </a>
                
                <div class="dropdown-divider border-secondary"></div>

                <form id="logoutForm" action="index.php?model=auth&action=logout" method="post">
                    <button type="submit" class="dropdown-item text-white">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-white-50"></i>
                        Đăng xuất
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>