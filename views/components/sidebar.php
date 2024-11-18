<?php $current_model = isset($_GET['model']) ? $_GET['model'] : ''; $current_action = isset($_GET['action']) ? $_GET['action'] : ''; ?>  
<ul style="background-image: linear-gradient(to bottom, #dfe2f0, #b8cfe7); color: #423b8e;" class="navbar-nav sidebar sidebar-dark accordion position-sticky" id="accordionSidebar">
     <!-- Sidebar - Brand -->     
     <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html" style="color: #423b8e; margin-bottom: 10px; margin-top: 10px">         
         <div class="sidebar-brand-icon rotate-n-15" style="color: #423b8e; font-size: 1.8rem;">             
             <i class="fa-solid fa-book-open" style="color: #423b8e;"></i>         
         </div>         
         <div class="sidebar-brand-text mx-2" style="color: #423b8e; font-size: 1.2rem;">UTT LIBRARY</div>     
     </a>      
     
     <!-- Divider -->     
     <hr class="sidebar-divider my-0">      
     
     <!-- Nav Item - Dashboard -->     
     <li class="nav-item <?= ($current_model == '' && $current_action == '') ? 'active' : '' ?>">         
         <a class="nav-link" href="index.html" style="color: #423b8e; display: flex; align-items: center;">             
             <i class="fas fa-fw fa-tachometer-alt" style="color: #423b8e; font-size: 1.2rem; margin-right: 0.75rem;"></i>             
             <span style="color: #423b8e; font-size: 1rem;">Trang chủ</span>         
         </a>     
     </li>      
     
     <!-- Divider -->     
     <hr class="sidebar-divider">      
     
     <!-- Heading -->     
     <div class="sidebar-heading" style="color: #423b8e; font-size: 0.9rem; font-weight: bold;">         
         Công cụ     
     </div>      
     
     <?php     
     if (isset($_SESSION['role_id'])) {         
         switch ($_SESSION['role_id']) {             
             case '1':                 
                 include 'views/components/user_management_sidebar.php';                 
                 break;             
             case 'NhanVien':                 
                 include 'views/components/nhanvien-side.php';                 
                 break;             
             case 'NhanVienQuanLy':                 
                 include 'views/components/nvql-side.php';                 
                 break;             
             case 'KyThuat':                 
                 include 'views/components/kythuat-side.php';                 
                 break;             
             default:                 
                 echo "<li class='nav-item' style='color: #423b8e; font-size: 1rem;'>Không có quyền truy cập</li>";                 
                 break;         
         }     
     } else {         
         echo "<li class='nav-item' style='color: #423b8e; font-size: 1rem;'>Không có quyền truy cập</li>";     
     }     
     ?>      
     
     <!-- Divider -->     
     <hr class="sidebar-divider">      
     
     <!-- Heading -->     
     <div class="sidebar-heading" style="color: #423b8e; font-size: 0.9rem; font-weight: bold;">         
         Cá nhân    
     </div>     
     <li class="nav-item <?= ($current_model == 'auth' && $current_action == 'edit') ? 'active' : '' ?>">         
         <a class="nav-link" href="index.php?model=auth&action=edit" style="color: #423b8e; display: flex; align-items: center;">             
             <i class="fa-solid fa-user" style="color: #423b8e; font-size: 1.2rem; margin-right: 0.75rem;"></i>             
             <span style="color: #423b8e; font-size: 1rem;">Hồ sơ</span>         
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