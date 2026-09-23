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
    $hal = 'marketing';
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

  $data_marketing = mysqli_query($conn, "SELECT * FROM marketing")or die(mysqli_error($conn));
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
                <h3 class="card-title">DATA MARKETING</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i>Tambah Data</button>
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-import"><i class="fas fa-file-excel"></i> Import Excel</button>
                <a href="export.php" class="btn btn-info mb-3"><i class="fas fa-file-download"></i> Export Excel</a>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th>ID Karyawan</th>
                    <th>Nama Karyawan</th>
                    <th>Kontak</th>
                    <th>Email</th>
                    <th>Kelamin</th>
                    <th class="text-center">Foto</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                  $no = 1; 
                  while ($d = mysqli_fetch_array($data_marketing)) { ?>
                    <tr>
                      <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                      <td><?= $d['id_karyawan']; ?></td>
                      <td><?= $d['nama']; ?></td>
                      <td><a href="https://wa.me/<?= $d['kontak']; ?>?text=Hai, <?= $d['nama']; ?> ?>" target="_blank" type="button" class="btn btn-success btn-xs">
                           <i class="bi bi-whatsapp"></i> <?= $d['kontak'];?>                           
                      </a></td>
                      <td><?= $d['email']; ?></td>
                      <td>
                        <?php 
                        $peran = $d['kelamin'];
                        if ($peran == 'P') {
                          echo 'Perempuan';
                        } elseif ($peran == 'L') {
                          echo 'Laki-laki';
                        } else {
                          echo 'Tidak ada Kelamin';
                        }
                        ?>
                      </td>
                      <td class="text-center">
                      <?php
                      if ($d['foto'] == '') {    
                        if ($d['kelamin'] == 'L') { ?>
                            <button type="button"  data-toggle="modal" data-target="#modal-foto" data-id_karyawan="<?= $d['id_karyawan'] ?>">
                            <img src='../assets/foto_marketing/cowo.png' class='img-fluid' width='50px' height='50px'>
                            </button>
                      <?php    } else { ?>
                            <button type="button"  data-toggle="modal" data-target="#modal-foto" data-id_karyawan="<?= $d['id_karyawan'] ?>">
                            <img src='../assets/foto_marketing/cewe.png' class='img-fluid' width='50px' height='50px'>
                            </button>
                       <?php    }
                      } else { ?>
                            <button type="button"  data-toggle="modal" data-target="#modal-foto" data-id_karyawan="<?= $d['id_karyawan'] ?>">
                            <img src="../assets/foto_marketing/<?=$d['foto'] ?>" width="50px" height="50px">
                            </button>
                      <?php }
                      ?>
                      </td>
                      <td class="text-center">
                        <a href="hapus.php?id=<?= $d['id_karyawan']; ?>" 
                        class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')"
                        ><i class="fas fa-trash"></i></a>
                        <button class="btn btn-warning btn-xs" type="submit" 
                        data-target="#modal-edit" 
                        data-id_karyawan="<?= $d['id_karyawan'] ?>" 
                        data-nama="<?= $d['nama']?>" 
                        data-kontak="<?= $d['kontak']?>"
                        data-email="<?= $d['email']?>"
                        data-kelamin="<?= $d['kelamin']?>"
                        data-toggle="modal">
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
              <h4 class="modal-title">TAMBAH DATA MARKETING</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah.php" method="post">
                <div class="form-group">
                    <label for="id_karyawan">ID Karyawan</label>
                    <input type="text" name="id_karyawan" class="form-control" id="id_karyawan" placeholder="Masukan ID Karyawan" required>
                </div>
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" class="form-control" id="nama" placeholder="Masukan Nama Karyawan" required>
                </div>
                <div class="form-group">
                    <label for="kontak">Kontak</label>
                    <input type="text" name="kontak" class="form-control" id="kontak" placeholder="Masukan Kontak" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Masukan Email" required>
                </div>
                <div class="form-group">
                  <label for="kelamin">Jenis Kelamin</label>
                  <select name="kelamin" class="form-control" id="kelamin">
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
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

      <!-- modal Import -->
      <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">IMPORT DATA MARKRTING</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="import.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file_excel">Pilih File Excel (.xls, .xlsx, .csv)</label>
                    <input type="file" name="file_excel" class="form-control" id="file_excel" required accept=".xls, .xlsx, .csv">
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_import" class="btn btn-success">Import Data</button>
              </div>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Import -->

      <!-- modal Edit -->
      <div class="modal fade" id="modal-edit" >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">EDIT DATA MARKETING</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="edit.php" method="post">
                <div class="form-group">
                    <label for="id_karyawan">ID Karyawan</label>
                    <input type="text" name="id_karyawan" class="form-control" id="id_karyawan" placeholder="Masukan ID Karyawan" required>
                </div>
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" class="form-control" id="nama" placeholder="Masukan Nama Karyawan" required>
                </div>
                <div class="form-group">
                    <label for="kontak">Kontak</label>
                    <input type="text" name="kontak" class="form-control" id="kontak" placeholder="Masukan Kontak" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Masukan Email" required>
                </div>
                <div class="form-group">
                  <label for="kelamin">Jenis Kelamin</label>
                  <select name="kelamin" class="form-control" id="kelamin">
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                  </select>
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

  <!-- modal Foto -->
      <div class="modal fade" id="modal-foto">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">FOTO MARKETING</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="foto.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <input type="text" class="form-control" name="id_karyawan"  readonly>
                </div>
                <div class="form-group">
                    <label for="foto">Upload Foto</label>
                    <input type="file" accept=".jpg,.jpeg,.png" name="foto" class="form-control" id="foto" placeholder="Upload File Excel" required>
                </div>
                
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_foto" class="btn btn-primary">Simpan</button>
              </div>
              </form>
            </div>
            
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Foto -->

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

   var id_karyawan = $(e.relatedTarget).data('id_karyawan');
   var nama = $(e.relatedTarget).data('nama');
   var kontak = $(e.relatedTarget).data('kontak');
   var email = $(e.relatedTarget).data('email');
   var kelamin = $(e.relatedTarget).data('kelamin');
  

  
  $(e.currentTarget).find('input[name="id_karyawan"]').val(id_karyawan);
  $(e.currentTarget).find('input[name="nama"]').val(nama);
  $(e.currentTarget).find('input[name="kontak"]').val(kontak);
  $(e.currentTarget).find('input[name="email"]').val(email);
  $(e.currentTarget).find('select[name="kelamin"]').val(kelamin);
   
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