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
  include '../layout_admin/navbar.php'
  ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php
  include '../layout_admin/sidebar.php';

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
              <div class="card-header">
                <h3 class="card-title">Daftar Data Kategori Rumah</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i>Tambah Data</button>
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-import"><i class="fas fa-file-excel"></i> Import Excel</button>
                <a href="export.php" class="btn btn-info mb-3"><i class="fas fa-file-download"></i> Export Excel</a>
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
                        <a href="hapus.php?id=<?= $d['id_kategori']; ?>" 
                        class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')"
                        ><i class="fas fa-trash"></i></a>
                        <button class="btn btn-warning btn-xs" type="submit" 
                        data-target="#modal-edit" 
                        data-id_kategori="<?= $d['id_kategori'] ?>" 
                        data-nama_kategori="<?= $d['nama_kategori']?>" 
                        data-luas_bangunan="<?= $d['luas_bangunan']?>"
                        data-luas_tanah="<?= $d['luas_tanah']?>"
                        data-jumlah_kamar="<?= $d['jumlah_kamar'] ?>"
                        data-harga="<?= $d['harga']?>"
                        data-deskripsi="<?= $d['deskripsi'] ?>"
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
            <div class="modal-header">
              <h4 class="modal-title">Tambah Data Kategori Rumah</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah.php" method="post">
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control" id="nama_kategori" placeholder="Masukan Nama Kategori Rumah" required>
                </div>
                <div class="form-group">
                    <label for="luas_bangunan">Luas Bangunan</label>
                    <input type="number" name="luas_bangunan" class="form-control" id="luas_bangunan" placeholder="Masukan Luas Bangunan" required>
                </div>
                <div class="form-group">
                    <label for="luas_tanah">Luas Tanah</label>
                    <input type="number" name="luas_tanah" class="form-control" id="luas_tanah" placeholder="Masukan Luas Bangunan" required>
                </div>
                <div class="form-group">
                    <label for="jumlah_kamar">Jumlah Kamar</label>
                    <input type="number" name="jumlah_kamar" class="form-control" id="jumlah_kamar" placeholder="Masukan Jumlah Kamar" required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" name="harga" class="form-control" id="harga" placeholder="Masukan Harga Rumah" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" id="deskripsi" placeholder="Masukan Deskripsi" required></textarea>
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
            <div class="modal-header">
              <h4 class="modal-title">Import Data Kategori Rumah</h4>
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
            <div class="modal-header">
              <h4 class="modal-title">Edit Data Kategori Rumah</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <form action="edit.php" method="post">
                <div class="form-group">
                    <input type="hidden" name="id_kategori" class="form-control" id="id_kategori" required>
                </div>
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control" id="nama_kategori" placeholder="Masukan Nama Kategori Rumah" required>
                </div>
                <div class="form-group">
                    <label for="luas_bangunan">Luas Bangunan</label>
                    <input type="number" name="luas_bangunan" class="form-control" id="luas_bangunan" placeholder="Masukan Luas Bangunan" required>
                </div>
                <div class="form-group">
                    <label for="luas_tanah">Luas Tanah</label>
                    <input type="number" name="luas_tanah" class="form-control" id="luas_tanah" placeholder="Masukan Luas Bangunan" required>
                </div>
                <div class="form-group">
                    <label for="jumlah_kamar">Jumlah Kamar</label>
                    <input type="number" name="jumlah_kamar" class="form-control" id="jumlah_kamar" placeholder="Masukan Jumlah Kamar" required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" name="harga" class="form-control" id="harga" placeholder="Masukan Harga Rumah" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" id="deskripsi" placeholder="Masukan Deskripsi" required></textarea>
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