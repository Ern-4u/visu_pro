<?php
require_once '../database/config.php';
$authority = @$_SESSION['peran'];

if ($authority != 'A') {
  echo '<script> alert("Anda Tidak boleh masuk ke halaman ini!!!!");
  window.location.href="../logout.php" </script>';
}

else {

  $data_transaksi = mysqli_query($conn, "SELECT transaksi.*, marketing.*, rumah.*,pembeli.*
            FROM transaksi 
            LEFT JOIN marketing ON transaksi.id_karyawan = marketing.id_karyawan
            LEFT JOIN rumah ON transaksi.id_rumah = rumah.id_rumah
            LEFT JOIN pembeli ON transaksi.id_pembeli = pembeli.id_pembeli
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
    $hal = 'home_admin';
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

          </h3>
        </div>
        <div class="card-body">
          <buttton class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i> Tambah Data</buttton>
          <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr class="text-center">
                    <th width="5%">No</th>
                    <th>No Transaksi</th>
                    <th>Pembeli</th>
                    <th>Rumah</th>
                    <th>Marketing</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                  $no = 1; 
                  while ($d = mysqli_fetch_array($data_transaksi)) { ?>
                    <tr>
                      <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                      <td><?= $d['no_transaksi']; ?></td>
                      <td><?= $d['nama_pembeli']; ?></td>
                      <td><?= $d['kode_blok']; ?> - <?= $d['nama_site_plan'] ?></td>
                      <td><?= $d['nama'] ?></td>
                      <td><?= $d['tanggal_transaksi'] ?></td>
                      <td><?= $d['status_transaksi'] ?></td>
                      <td><?= $d['total'] ?></td>
                      <td class="text-center">
                        <a href="hapus.php?id=<?= $d['id_transaksi']; ?>" 
                        class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')"
                        ><i class="fas fa-trash"></i></a>
                        <button class="btn btn-warning btn-xs" type="submit" 
                        data-target="#modal-edit" 
                        data-id_site_plan="<?= $d['id_site_plan'] ?>" 
                        data-nama_site_plan="<?= $d['nama_site_plan']?>" 
                        data-penanggung_jawab="<?= $d['penanggung_jawab']?>"
                        data-ig="<?= $d['ig']?>"
                        data-tiktok="<?= $d['tiktok']?>"
                        data-lokasi="<?= $d['lokasi'] ?>"
                        data-toggle="modal">
                        <i class="fas fa-edit"> </i>
                      </button>
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

  <?php 
  $ambil_data_rumah = mysqli_query($conn, "SELECT rumah.*,site_plan.*
            FROM rumah 
            LEFT JOIN site_plan ON rumah.id_site_plan = site_plan.id_site_plan
            ") or die(mysqli_error($conn));
  $ambil_data_pembeli = mysqli_query($conn, "SELECT * FROM pembeli") or die(mysqli_error($conn));
  $ambil_data_marketing = mysqli_query($conn, "SELECT * FROM marketing") or die(mysqli_error($conn));
  ?>

  <!-- modal Tambah -->
      <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Data Transaksi</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah.php" method="post">
                <div class="form-group">
                    <label for="no_transaksi">Nomor Transaksi</label>
                    <input type="text" name="no_transaksi" class="form-control" id="no_transaksi" placeholder="Masukan Nomor Transaksi" required>
                </div>
                <div class="form-group">
                  <label for="">Pilih Rumah Yang Akan Di beli</label>
                  <select name="id_rumah" id="" class="form-control select2" style="width: 100%;" >
                    <?php 
                    while ($dt_rmh = mysqli_fetch_array($ambil_data_rumah)) { ?>
                      <option value="<?= $dt_rmh['id_rumah'] ?>"><?= $dt_rmh['kode_blok'] ?> - <?= $dt_rmh['nama_site_plan'] ?></option>
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