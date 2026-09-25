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
    $hal = 'users';
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
  include '../layout_admin/navbar.php'
  ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php
  include '../layout_admin/sidebar.php';

  $data_user = mysqli_query($conn, "SELECT * FROM users")or die(mysqli_error($conn));
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
                <h3 class="card-title">DATA USERS</h3>
                <div class="card-tools">
                  <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i>Tambah Data</button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Peran</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                  $no = 1; 
                  while ($admin = mysqli_fetch_array($data_user)) { ?>
                    <tr>
                      <td class="text-center"><?=  $no++ ; ?></td>
                      <td><?= $admin['username']; ?></td>
                      <td><?= $admin['nama']; ?></td>
                      <td>
                        <?php 
                        $peran = $admin['peran'];
                        if ($peran == 'A') {
                          echo 'Admin';
                        } elseif ($peran == 'M') {
                          echo 'Marketing';
                        } else {
                          echo 'Tidak ada Peran';
                        }
                        ?>
                      </td>
                      <td class="text-center">
                        <a href="hapus.php?user=<?= $admin['username']; ?>" 
                        class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')"
                        ><i class="fas fa-trash"></i></a>
                        <button class="btn btn-warning btn-xs" type="submit" data-target="#modal-edit" data-username="<?= $admin['username'] ?>" 
                        data-nama="<?= $admin['nama']?>" data-peran="<?= $admin['peran']?> " data-toggle="modal">
                        <i class="fas fa-edit"> </i>
                      </button>
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
   <!-- modal Tambah -->
      <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">TAMBAH DATA USER</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control" id="username" placeholder="Masukan username" required>
                </div>
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" class="form-control" id="nama" placeholder="Masukan Nama" required>
                </div>
                <div class="form-group">
                  <label for="peran">Role</label>
                  <select name="peran" class="form-control" id="peran">
                    <option value="A">Admin</option>
                  </select>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_tambah" class="btn btn-primary">Simpan</button>
              </div>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Tambah -->
       <!-- modal Edit -->
      <div class="modal fade" id="modal-edit" >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">EDIT DATA USER</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="edit.php" method="post" id="editForm">
                <div class="form-group">
                  <input type="text" name="username" hidden>
                </div>
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="edit-nama" name="nama" class="form-control"  placeholder="Masukan username">
                </div>
                
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_edit" class="btn btn-primary">Simpan</button>
               </div>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Edit -->

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