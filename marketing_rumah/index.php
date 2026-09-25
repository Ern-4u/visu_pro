<?php
require_once '../database/config.php';
$authority = @$_SESSION['peran'];

if ($authority != 'M') {
  echo '<script> alert("Anda Tidak boleh masuk ke halaman ini!!!!");
  window.location.href="../logout.php" </script>';
}

else {


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>VISU Pro | Dashboard admin</title>
  <?php
    include '../layout_marketing/css.php';
    $hal = 'rumah';
  ?>
  
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-flex">
<div class="wrapper">

  <!-- Preloader -->
  <?php
  $query_web = mysqli_query($conn, "SELECT * FROM web WHERE id = '1'")or die(mysqli_error($conn));
  $web = mysqli_fetch_array($query_web); 
  ?>
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble rounded-circle" src="../assets/logo/<?= $web['logo'] ?>" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <?php
  include '../layout_marketing/navbar.php'
  ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php
  include '../layout_marketing/sidebar.php';

  
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
      <form action="" method="post">
                <div class="row">
                    <div class="col-3">
                        <?php 
                        $panggil_data_site_plan = mysqli_query($conn, "SELECT * FROM site_plan" )or die($conn);
                        ?>
                        <div class="form-group">                    
                            <select class="form-control" name="id_site_plan" id="">
                              <option value="">-- Masukan Lokasi Perumahan --</option>
                                <?php 
                                while ($dt_st = mysqli_fetch_array($panggil_data_site_plan)){?>
                                <option value="<?= $dt_st['id_site_plan']; ?>"><?= $dt_st['nama_site_plan'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-2.5">
                        <button type="submit" name="btn_cari" class="btn" style="color: white; background-color: #001F3F;"><i class="fas fa-search"></i> Tampilkan Data</button>
                    </div>
                    
                </div>
            </form>
            <?php 
            if (isset($_POST['btn_cari'])){
            $id_site_plan = trim(mysqli_real_escape_string($conn, $_POST['id_site_plan']));

            $query_rumah = mysqli_query($conn, "SELECT rumah.*, kategori_rumah.*, site_plan.*
            FROM rumah 
            LEFT JOIN kategori_rumah ON rumah.id_kategori = kategori_rumah.id_kategori
            LEFT JOIN site_plan ON rumah.id_site_plan = site_plan.id_site_plan
            where rumah.id_site_plan = '$id_site_plan'
            ") or die(mysqli_error($conn));
            $nm_st_pln = mysqli_fetch_array($query_rumah);
            ?>

            <div class="card">
              <div class="card-header" style="background-color: #001F3F; color: white;">
                <h3 class="card-title">DAFTAR RUMAH KOMPLEK <?= strtoupper($nm_st_pln['nama_site_plan']) ?></h3>
                <div class="card-tools">
                  <a href="export.php" class="btn btn-light btn-sm"><i class="fas fa-file-download"></i> Export Excel</a>  
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="col-6">
                  
                </div>
                
                    
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Kode Blok</th>
                    <th>Kategori</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                  $no = 1; 
                  mysqli_data_seek($query_rumah, 0);
                  while ($d = mysqli_fetch_array($query_rumah)) { ?>
                    <tr>
                      <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                      <td><?= $d['kode_blok']; ?></td>
                      <td><?= $d['nama_kategori']; ?></td>
                      <td><?php
                      if ($d['status'] == 0) {
                        echo 'Tersedia';
                      } elseif ($d['status'] == 1) {
                        echo 'Terjual Cash';
                      } elseif ($d['status'] == 2) {
                        echo 'Terjual Cash Tempo';
                      } elseif ($d['status'] == 3) {
                        echo 'Terjual Kredit';
                      }
                      ?>
                      </td>
                    </tr>
                     <?php } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <?php 
            } 
            ?>


    </div>  
    <!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php 
  $ambil_data_kategori = mysqli_query($conn, "SELECT id_kategori,nama_kategori FROM kategori_rumah")or die(mysqli_error($conn));
  $ambil_data_site_plan = mysqli_query($conn, "SELECT id_site_plan,nama_site_plan FROM site_plan")or die(mysqli_error($conn));

  
  ?>
  
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
      

  <!-- Main Footer -->
   <?php
  include '../layout_marketing/footer.php'
  ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php
include '../layout_marketing/js.php'
?>


</body>
</html>
<?php 
} ?>