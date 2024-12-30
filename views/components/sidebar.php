<?php $current_model = isset($_GET['model']) ? $_GET['model'] : ''; $current_action = isset($_GET['action']) ? $_GET['action'] : ''; ?>  
<ul style="background-image: linear-gradient(to bottom, #dfe2f0, #b8cfe7); color: #423b8e;" class="navbar-nav sidebar sidebar-dark accordion position-sticky" id="accordionSidebar">
     <!-- Sidebar - Brand -->     
     <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php?model=default&action=admin_dashboard" style="color: #423b8e; margin-bottom: 10px; margin-top: 10px">         
         <div class="sidebar-brand-icon rotate-n-15" style="color: #423b8e; font-size: 1.8rem;">             
             <i class="fa-solid fa-book-open" style="color: #423b8e;"></i>         
         </div>         
         <div class="sidebar-brand-text mx-2" style="color: #423b8e; font-size: 1.2rem;"> E-LIBRARY</div>     
     </a>      
     
     <!-- Divider -->     
     <hr class="sidebar-divider my-0">      
     
     <!-- Nav Item - Dashboard -->     
     <li class="nav-item <?= ($current_model == '' && $current_action == '') ? 'active' : '' ?>">     
         <a class="nav-link" href="index.php?model=default&action=admin_dashboard"style="color: #423b8e; display: flex; align-items: center;">             
             <i class="fas fa-fw fa-tachometer-alt " style="color: #423b8e; font-size: 1.2rem; margin-right: 0.75rem;"></i>             
             <span class="sidebar-brand-text"style="color: #423b8e; font-size: 1rem;">Trang chủ</span>         
         </a>     
     </li>      
     
     <!-- Divider -->     
     <hr class="sidebar-divider">      
     
     <!-- Heading -->     
     <div class="sidebar-heading" style="color: #423b8e; font-size: 0.8rem; font-weight: bold;">         
         Công cụ     
     </div>      
     
     <?php     
     if (isset($_SESSION['permissions'])) {     
        $permissions = $_SESSION['permissions'];
        if (in_array('manage_users', $permissions)) {
            include 'views/components/manage_user_sidebar.php';
        }   
        if (in_array('manage_members', $permissions)) {
            include 'views/components/manage_member_sidebar.php';
        }  
        if (in_array('manage_publishers', $permissions)) {
            include 'views/components/manage_publisher_sidebar.php';
        } 
        if (in_array('manage_authors', $permissions)) {
            include 'views/components/manage_author_sidebar.php';
        }   
        if (in_array('manage_books', $permissions)) {
            include 'views/components/manage_book_sidebar.php';
        }   
        if (in_array('manage_categories', $permissions)) {
            include 'views/components/manage_category_sidebar.php';
        }  
         
        ?>
        <li class="nav-item ">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour" 
            style="color: #423b8e; position: relative;"
            data-custom-color="#423b8e">
                <i class="fa-solid fa-file" style="color: #423b8e; font-size: 1.1rem; margin-right: 0.75rem;"></i>
                <span style="color: #423b8e; font-size: 0.9rem; margin-right: 0.75rem; margin-left: 0.4rem">Quản lý phiếu</span>
            </a>
            <div id="collapseFour" class="collapse <?= ($current_model == 'loan' || $current_model == 'reservation' || $current_model == 'book_condition' || $current_model == 'fine') ? 'show' : '' ?>" aria-labelledby="headingTwo">
                <div class="bg-transparent py-2 collapse-inner rounded">
        <?php
        if (in_array('manage_loans', $permissions)) {
            include 'views/components/manage_loan_sidebar.php';
        }   
        if (in_array('manage_reservations', $permissions)) {
            include 'views/components/manage_reservation_sidebar.php';
        }   
        if (in_array('manage_bookConditions', $permissions)) {
            include 'views/components/manage_bookCondition_sidebar.php';
        }   
        if (in_array('manage_fines', $permissions)) {
            include 'views/components/manage_fines_sidebar.php';
        }   
        ?>
                </div>   
            </div>
        </li>
        <li class="nav-item <?= ($current_model == 'publisher' ) ? 'active' : '' ?>">       
        <a class="nav-link" href="route.php?model=statistic&action=index" >
                <i class="fa-solid fa-chart-simple"style="color: #423b8e; font-size: 1.1rem; margin-right: 0.75rem;"></i>
                <span style="color: #423b8e; font-size: 0.9rem;">Thống kê</span>
            </a>
        </li>
        <?php
     } else {         
         echo "<li class='nav-item' style='color: #423b8e; font-size: 1rem;'>Không có quyền truy cập</li>";     
     }    
      
     ?>      
     
     <!-- Divider -->     
     <hr class="sidebar-divider">      
     
     <!-- Heading -->     
     <div class="sidebar-heading" style="color: #423b8e; font-size: 0.8rem; font-weight: bold;">         
         Cá nhân    
     </div>     
     <li class="nav-item <?= ($current_model == 'auth' && $current_action == 'edit') ? 'active' : '' ?>">         
         <a class="nav-link" href="index.php?model=auth&action=edit" style="color: #423b8e; display: flex; align-items: center;">             
             <i class="fa-solid fa-user" style="color: #423b8e; font-size: 1.1rem; margin-right: 0.75rem;"></i>             
             <span style="color: #423b8e; font-size: 0.9rem;">Hồ sơ</span>         
         </a>     
     </li>     
     <hr class="sidebar-divider d-none d-md-block">      
     
     <!-- Sidebar Toggler (Sidebar) -->     
     <div class="text-center d-none d-md-inline position-relative w-100" style="height: 50px;">
        <button class="rounded-circle border-0 position-absolute" id="sidebarToggle" 
                style="
                    background-color: #423b8e; 
                    width: 40px; 
                    height: 40px; 
                    top: 50%; 
                    left: 50%;
                    transform: translate(-50%, -50%);
                    display: flex; 
                    justify-content: center; 
                    align-items: center;
                    color: white;
                ">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
</ul>