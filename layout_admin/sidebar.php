<aside class="main-sidebar sidebar-light-teal elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="../assets/logo/visupro.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-teal"><b>Visu</b>Pro</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-2 pb-2 mb-2 d-flex">
        <div class="info">
          <h5 class="d-block"><center><b>DASHBOARD</b> Admin</center></h5>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="../admin_home/" class="nav-link <?=  ($hal == "home_admin") ? "active" : "" ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
           <li class="nav-item">
            <a href="../admin_site_plan/" class="nav-link <?=  ($hal == "site_plan") ? "active" : "" ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Site Plan</p>
            </a>
          </li>          
          <li class="nav-item">
            <a href="../admin_kategori_rumah/" class="nav-link <?=  ($hal == "kategori_rumah") ? "active" : "" ?>">
              <i class="nav-icon bi bi-house-gear-fill"></i>
              <p>Kategori Rumah</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../admin_rumah/" class="nav-link <?=  ($hal == "admin_rumah") ? "active" : "" ?>">
              <i class="nav-icon bi bi-house-gear-fill"></i>
              <p>Data Rumah</p>
            </a>
          </li>
          <!-- <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dropdown
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v1</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v2</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v3</p>
                </a>
              </li>
            </ul>
          </li> -->
          <li class="nav-item">
            <a href="../admin_users/" class="nav-link <?=  ($hal == "users") ? "active" : "" ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../admin_data_marketing/" class="nav-link <?=  ($hal == "marketing") ? "active" : "" ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>Tim Marketing</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../admin_setingan/" class="nav-link <?=  ($hal == "settings") ? "active" : "" ?>">
              <i class="nav-icon bi bi-gear-fill"></i>
              <p>Setingan Web</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../logout.php" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Log Out</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>