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
    $hal = 'kategori_rumah';
  ?>
  
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-flex">
<div class="wrapper">

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

  $data_kategori_rumah = mysqli_query($conn, "SELECT * FROM kategori_rumah
            ") or die(mysqli_error($conn));
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
      <div class="card">
              <div class="card-header" style="background-color: #001F3F; color: white;">
                <h3 class="card-title">DATA KATEGORI RUMAH</h3>
                <div class="card-tools">
                    <a href="export.php" class="btn btn-light btn-sm"><i class="fas fa-file-download"></i> Export Excel</a>
                 </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                
                
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Nama Kategori</th>
                    <th>Luas Bangunan</th>
                    <th>Luas Tanah</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                  $no = 1; 
                  while ($d = mysqli_fetch_array($data_kategori_rumah)) { ?>
                    <tr>
                      <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                      <td><?= $d['nama_kategori']; ?></td>
                      <td><?= $d['luas_bangunan']; ?></td>
                      <td><?= $d['luas_tanah']; ?></td>
                      <td>Rp <?= number_format($d['harga'], 0, ',', '.'); ?></td>
                      <td><?= $d['deskripsi']; ?></td>
                      <td class="text-center">
                        <a href="detail.php?id=<?= $d['id_kategori'] ?>" class="btn btn-success btn-xs"><i class="fas fa-eye"></i></a>
                      </td>
                    </tr>
                     <?php } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
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
  include '../layout_marketing/footer.php'
  ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php
include '../layout_marketing/js.php'
?>
<script type="text/javascript">
   $('#modal-edit').on('show.bs.modal', function(e) {

   var id_kategori = $(e.relatedTarget).data('id_kategori');
   var nama_kategori = $(e.relatedTarget).data('nama_kategori');
   var luas_bangunan = $(e.relatedTarget).data('luas_bangunan');
   var luas_tanah = $(e.relatedTarget).data('luas_tanah');
   var jumlah_kamar = $(e.relatedTarget).data('jumlah_kamar');
   var harga = $(e.relatedTarget).data('harga');
  var deskripsi = $(e.relatedTarget).data('deskripsi');
  var id_site_plan = $(e.relatedTarget).data('id_site_plan');
  

  
    $(e.currentTarget).find('input[name="id_kategori"]').val(id_kategori);
    $(e.currentTarget).find('input[name="nama_kategori"]').val(nama_kategori);
    $(e.currentTarget).find('input[name="luas_bangunan"]').val(luas_bangunan);
    $(e.currentTarget).find('input[name="luas_tanah"]').val(luas_tanah);
    $(e.currentTarget).find('input[name="jumlah_kamar"]').val(jumlah_kamar);
    $(e.currentTarget).find('input[name="harga"]').val(harga);
    $(e.currentTarget).find('textarea[name="deskripsi"]').val(deskripsi);
    $(e.currentTarget).find('select[name="id_site_plan"]').val(id_site_plan);
   });


  $('#modal-foto').on('show.bs.modal', function(e) {

   var id_karyawan = $(e.relatedTarget).data('id_karyawan');
  
   $(e.currentTarget).find('input[name="id_karyawan"]').val(id_karyawan);
  
  });
 
</script> 


</body>
</html>
<?php 
} ?>