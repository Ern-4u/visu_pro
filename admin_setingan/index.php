<?php
require_once '../database/config.php';
$authority = @$_SESSION['peran'];

if ($authority != 'A') {
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
    include '../layout_admin/css.php';
    $hal = 'settings';
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
  include '../layout_admin/sidebar.php';
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
     <?php 
     $query = mysqli_query($conn, "SELECT * FROM web WHERE id = 1")or die(mysqli_error($conn));
     $result = mysqli_fetch_array($query);

     ?>
    
    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
      <div class="card">
              <div class="card-header">
                <h3 class="card-title">Settingan Web</h3>
              </div>
              <!-- /.card-header -->
               <form action="" method="post">
              <div class="card-body"> 
                  <div class="form-group">
                    <label for="">Nama Proyek</label>
                    <input type="text" value="<?= $result['nama_proyek'] ?>" name="nama_proyek" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="">Alamat (Sesuai Google Mpas)</label>
                    <input type="text" name="alamat" value="<?= $result['alamat'] ?>" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="">Contact Person</label>
                    <input type="text" name="cp" value="<?= $result['cp'] ?>" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="">Instagram</label>
                    <input type="text" name="instagram" value="<?= $result['instagram'] ?>" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="">Tiktok</label>
                    <input type="text" name="tiktok" value="<?= $result['tiktok'] ?>" class="form-control">
                  </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary mb-3" name="btn_edit">Simpan Data</button>
              </div>
              </form>
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
<?php
  if (isset($_POST['btn_edit'])) {

  $id = 1;
  $nama_proyek = trim(mysqli_real_escape_string($conn, $_POST['nama_proyek']));
  $alamat = trim(mysqli_real_escape_string($conn, $_POST['alamat']));
  $cp = trim(mysqli_real_escape_string($conn, $_POST['cp']));
  $instagram = trim(mysqli_real_escape_string($conn, $_POST['instagram']));
  $tiktok = trim(mysqli_real_escape_string($conn, $_POST['tiktok']));
  

  $query_edit = "UPDATE web SET  nama_proyek='$nama_proyek', alamat='$alamat', cp= '$cp', instagram='$instagram', tiktok='$tiktok' WHERE id='$id'";
  $result_edit = mysqli_query($conn, $query_edit);

  if ($result_edit) {
    echo "<script>alert('Data Web berhasil diperbarui.'); window.location.href='../admin_setingan/';</script>";
  } else {
    echo "<script>alert('Terjadi kesalahan saat memperbarui data Web.'); window.location.href='../admin_setingan/';</script>";
  }
}
?>
      

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

<script type="text/javascript">
   $('#modal-edit').on('show.bs.modal', function(e) {

   var username = $(e.relatedTarget).data('username');
   var pin = $(e.relatedTarget).data('nama');
  

  
  $(e.currentTarget).find('input[name="username"]').val(username);
  $(e.currentTarget).find('input[name="nama"]').val(pin);
   
   });
 
</script> 
</body>
</html>
<?php 
} ?>