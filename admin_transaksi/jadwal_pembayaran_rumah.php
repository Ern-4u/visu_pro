<?php
require_once '../database/config.php';
$authority = @$_SESSION['peran'];

if ($authority != 'A') {
  echo '<script> alert("Anda Tidak boleh masuk ke halaman ini!!!!");
  window.location.href="../logout.php" </script>';
}

else {
  $id_transaksi = @$_GET['id'];
  $rumah = 'rumah';

  $data_jadwal = mysqli_query($conn, "SELECT jadwal_pembayaran.*, transaksi.*
            FROM jadwal_pembayaran 
            LEFT JOIN transaksi ON jadwal_pembayaran.id_transaksi = transaksi.id_transaksi
            WHERE jadwal_pembayaran.id_transaksi = '$id_transaksi' AND jadwal_pembayaran.jenis_tagihan = '$rumah'
            ") or die(mysqli_error($conn));


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>VISU Pro | Dashboard admin</title>
  <?php
    include '../layout_admin/css.php';
    $hal = 'transaksi';
  ?>
  
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-flex">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble rounded-circle" src="../assets/logo/visupro.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <?php
  include '../layout_admin/navbar.php'
  ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php
  include '../layout_admin/sidebar.php'
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            Data Transaksi Rumah
          </h3>
        </div>
        <div class="card-body">
          <buttton class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i> Tambah Data</buttton>
          <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr class="text-center">
                    <th width="5%">No</th>
                    <th>Angsuran Ke-</th>
                    <th>Jatuh Tempo</th>
                    <th>Jumlah Tagihan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                  $no = 1; 
                  while ($d = mysqli_fetch_array($data_jadwal)) { ?>
                    <tr>
                      <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                      <td><?= $d['angsuran_ke']; ?></td>
                      <td><?= $d['jatuh_tempo']; ?></td>
                      <td><?= $d['jumlah_tagihan'] ?></td>
                      <td><?= $d['status'] ?></td>
                      <td class="text-center">
                       <a href="pembayaran.php" class="btn btn-info btn-xs">Bayar Tagihan</a>
                      </td>
                    </tr>
                     <?php } ?>
                  </tbody>
                </table>
        </div>
      </div>      

    </div>  
    <!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
   <?php
  include '../layout_admin/footer.php'
  ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php
include '../layout_admin/js.php'
?>
</body>
</html>

<?php
}
?>