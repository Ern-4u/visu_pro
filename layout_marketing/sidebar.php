<aside class="main-sidebar sidebar-light-navy elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="../assets/logo/<?= $web['logo'] ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-teal"><b>Visu</b>Pro</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-2 pb-2 mb-2 d-flex">
        <div class="info">
          <h5 class="d-block" style="color: #001F3F; text-align: center;"><center><b>DASHBOARD</b> ADMIN</center></h5>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="../marketing_home/" class="nav-link <?=  ($hal == "home_admin") ? "active" : "" ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-header"><b>PROPERTI</b></li>
          <li class="nav-item">
            <a href="../marketing_site_plan/" class="nav-link <?=  ($hal == "site_plan") ? "active" : "" ?>">
              <i class="nav-icon bi bi-pin-map-fill"></i>
              <p>Site Plan</p>
            </a>
          </li>          
          <li class="nav-item">
            <a href="../marketing_kategori_rumah/" class="nav-link <?=  ($hal == "kategori_rumah") ? "active" : "" ?>">
              <i class="nav-icon bi bi-house-gear-fill"></i>
              <p>Kategori Rumah</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../marketing_rumah/" class="nav-link <?=  ($hal == "marketing_rumah") ? "active" : "" ?>">
              <i class="nav-icon bi bi-house-fill"></i>
              <p>Data Rumah</p>
            </a>
          </li>
          <li class="nav-header"><b>KEUANGAN</b></li>
          <li class="nav-item">
            <a href="../marketing_transaksi/" class="nav-link <?=  ($hal == "transaksi") ? "active" : "" ?>">
              <i class="nav-icon fas bi bi-credit-card-fill"></i>
              <p>Transaksi</p>
            </a>
          </li>
          <li class="nav-header"><b>TOOLS</b>
          </li>
          <li class="nav-item">
            <a href="../marketing_pembeli/" class="nav-link <?=  ($hal == "pembeli") ? "active" : "" ?>">
              <i class="nav-icon fas fa-user"></i>
              <p>Data Pembeli</p>
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