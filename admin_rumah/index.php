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
    $hal = 'admin_rumah';
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
                        <button type="submit" name="btn_cari" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan Data</button>
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
              <div class="card-header">
                <h3 class="card-title">Daftar Data Rumah Komplek <?= $nm_st_pln['nama_site_plan'] ?></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="col-6">
                  <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i>Tambah Data</button>
                  <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-import"><i class="fas fa-file-excel"></i> Import Excel</button>
                  <a href="export.php" class="btn btn-info mb-3"><i class="fas fa-file-download"></i> Export Excel</a>  
                </div>
                
                    
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Kode Blok</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Aksi</th>
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
                      <td class="text-center">
                        <a href="hapus.php?id=<?= $d['id_rumah']; ?>" 
                        class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')"
                        ><i class="fas fa-trash"></i></a>
                        <button class="btn btn-warning btn-xs" type="submit" 
                        data-target="#modal-edit" 
                        data-id_rumah="<?= $d['id_rumah'] ?>" 
                        data-kode_blok="<?= $d['kode_blok']?>"
                        data-status="<?= $d['status'] ?>" 
                        data-id_kategori="<?= $d['id_kategori'] ?>"
                        data-id_site_plan="<?= $d['id_site_plan'] ?>"
                        data-toggle="modal">
                        <i class="fas fa-edit"> </i>
                        </button>
                        <a href="detail.php?id=<?= $d['id_rumah']; ?>" 
                        class="btn btn-success btn-xs" 
                        ><i class="fas fa-eye"></i></a>
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
  <!-- modal Tambah -->
      <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Data Rumah</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah.php" method="post">
                <div class="form-group">
                    <label for="kode_blok">Kode Blok</label>
                    <input type="text" name="kode_blok" class="form-control" id="kode_blok" placeholder="Masukan Kode Blok Rumah" required>
                </div>
                <div class="form-group">
                  <label for="">Masukan Kategori Rumah</label>
                  <select name="id_kategori" id="" class="form-control">
                    <option value="">-- Masukan Kategori Rumah --</option>
                    <?php 
                    while ($dt_kat = mysqli_fetch_array($ambil_data_kategori)) { ?>
                      <option value="<?= $dt_kat['id_kategori'] ?>"><?= $dt_kat['nama_kategori'] ?></option>
                    <?php }
                    ?>
                  </select>
                </div>
                 <div class="from-group">
                  <label for="id_site_plan">Site Plan</label>
                  <select class="form-control" name="id_site_plan">
                  <option value="">-- Pilih Site Plan --</option>
                  <?php 
                  while ($st_plan = mysqli_fetch_array($ambil_data_site_plan)) { ?>
                    <option value="<?= $st_plan['id_site_plan'] ?>"><?= $st_plan['nama_site_plan'] ?></option>
                  <?php }
                  ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="status">Status Rumah</label>
                  <select name="status" id="status" class="form-control">
                    <option value="">-- Pilih Status Rumah --</option>
                    <option value="0">Tersedia</option>
                    <option value="1">Terjual Cash</option>
                    <option value="2">Terjual Cash Tempo</option>
                    <option value="3">Terjual Kredit</option>
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
            <div class="modal-header">
              <h4 class="modal-title">Import Data Rumah</h4>
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
              <h4 class="modal-title">Edit Data Rumah</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <form action="edit.php" method="post">
                <div class="form-group">
                    <input type="hidden" name="id_rumah" class="form-control" id="id_rumah" required>
                </div>
                <div class="form-group">
                    <label for="kode_blok">Kode Blok</label>
                    <input type="text" name="kode_blok" class="form-control" id="kode_blok" placeholder="Masukan Kode Blok Rumah" required>
                </div>
                <div class="form-group">
                  <label for="">Masukan Kategori Rumah</label>
                  <select name="id_kategori" id="" class="form-control">
                    <option value="">-- Masukan Kategori Rumah --</option>
                    <?php 
                    mysqli_data_seek($ambil_data_kategori, 0);
                    while ($dt_kat = mysqli_fetch_array($ambil_data_kategori)) { ?>
                      <option value="<?= $dt_kat['id_kategori'] ?>"><?= $dt_kat['nama_kategori'] ?></option>
                    <?php }
                    ?>
                  </select>
                </div>
                <div class="from-group">
                  <label for="id_site_plan">Site Plan</label>
                  <select class="form-control" name="id_site_plan">
                  <option value="">-- Pilih Site Plan --</option>
                  <?php
                  mysqli_data_seek($ambil_data_site_plan, 0); 
                  while ($st_plan = mysqli_fetch_array($ambil_data_site_plan)) { ?>
                    <option value="<?= $st_plan['id_site_plan'] ?>"><?= $st_plan['nama_site_plan'] ?></option>
                  <?php }
                  ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="status">Status Rumah</label>
                  <select name="status" id="status" class="form-control">
                    <option value="">-- Pilih Status Rumah --</option>
                    <option value="0">Tersedia</option>
                    <option value="1">Terjual Cash</option>
                    <option value="2">Terjual Cash Tempo</option>
                    <option value="3">Terjual Kredit</option>
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

   var id_rumah = $(e.relatedTarget).data('id_rumah');
   var kode_blok = $(e.relatedTarget).data('kode_blok');
   var status = $(e.relatedTarget).data('status');
   var id_kategori = $(e.relatedTarget).data('id_kategori');
   var id_site_plan = $(e.relatedTarget).data('id_site_plan');
   
    $(e.currentTarget).find('input[name="id_rumah"]').val(id_rumah);
    $(e.currentTarget).find('input[name="kode_blok"]').val(kode_blok);
    $(e.currentTarget).find('select[name="status"]').val(status);
    $(e.currentTarget).find('select[name="id_kategori"]').val(id_kategori);
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