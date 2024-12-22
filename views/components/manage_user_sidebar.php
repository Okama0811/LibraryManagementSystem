
<li class="nav-item ">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" 
    style="color: #423b8e; position: relative;"
    data-custom-color="#423b8e">
        <i class="fa-solid fa-user-gear" style="color: #423b8e; font-size: 1.1rem; margin-right: 0.75rem;"></i>
        <span style="color: #423b8e; font-size: 0.9rem; margin-right: 0.75rem;">Quản lý nhân viên</span>
    </a>
    <div id="collapseTwo" class="collapse <?= ($current_model == 'role' || $current_model == 'permission' || ($current_model == 'user'&& $current_action == 'index') ) ? 'show' : '' ?>" aria-labelledby="headingTwo">
        <div class="bg-transparent py-2 collapse-inner rounded">
            <a class="collapse-item <?= ($current_model == 'user'&& $current_action == 'index') ? 'active' : '' ?>" 
               href="route.php?model=user&action=index" 
               style="color: #423b8e; font-size: 0.9rem;">Tài khoản nhân viên</a>
            <a class="collapse-item <?= ($current_model == 'role') ? 'active' : '' ?>" 
               href="route.php?model=role&action=index" 
               style="color: #423b8e; font-size: 0.9rem;">Chức vụ</a>
        </div>
        
    </div>
</li>

