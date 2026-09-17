<?php
require_once '../database/config.php';
$authority = @$_SESSION['peran'];

if ($authority != 'A') {
  echo '<script> alert("Anda Tidak boleh masuk ke halaman ini!!!!");
  window.location.href="../logout.php" </script>';
}

else {
  
  $id_transaksi = @$_GET['id'];
  $data_detail_transaksi = mysqli_query($conn, "SELECT detail_transaksi.*, transaksi.*
            FROM detail_transaksi 
            LEFT JOIN transaksi ON detail_transaksi.id_transaksi = transaksi.id_transaksi
            WHERE detail_transaksi.id_transaksi = '$id_transaksi'
            ") or die(mysqli_error($conn));
  
  $data_detail_transaksi_rumah = mysqli_query($conn, "SELECT detail_transaksi_rumah.*, transaksi.*
            FROM detail_transaksi_rumah
            LEFT JOIN transaksi ON detail_transaksi_rumah.id_transaksi = transaksi.id_transaksi
            WHERE detail_transaksi_rumah.id_transaksi = '$id_transaksi'
            ") or die(mysqli_error($conn));

  $data_detail_booking_rumah = mysqli_query($conn, "SELECT booking.*, transaksi.*
            FROM booking
            LEFT JOIN transaksi ON booking.id_transaksi = transaksi.id_transaksi
            WHERE booking.id_transaksi = '$id_transaksi'
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
    <div class="content-header">

    </div>
    
    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Data Booking Rumah</h3>
        </div>
        <div class="card-body">
          <buttton class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-tambah_booking_rumah"><i class="fas fa-plus"></i> Tambah Data</buttton>
          <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr class="text-center">
                    <th width="5%">No</th>
                    <th>Tanggal Booking</th>
                    <th>Booking Fee</th>
                    <th>Tenor</th>
                    <th>Status Booking</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                  $no = 1; 
                  while ($d = mysqli_fetch_array($data_detail_booking_rumah)) { ?>
                    <tr>
                      <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                      <td><?= $d['tanggal_booking'] ?> </td>
                      <td><?= $d['booking_fee']; ?></td>
                      <td><?= $d['tenor']; ?></td>
                      <td><?= ($d['status_booking'] == 'lunas') ? 'Lunas' : 'Belum Lunas' ?></td>
                      <td class="text-center">
                        <a href="jadwal_pembayaran_booking.php?id=<?= $d['id_transaksi'] ?>&jenis_tagihan=<?= 'booking' ?>" 
                        class="btn btn-info btn-xs"><i class="bi bi-list-ol"></i> Jadwal Tagihan
                        </a>
                        <a href="hapus_booking_rumah.php?id=<?= $d['id_transaksi']; ?>" 
                        class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')"
                        ><i class="fas fa-trash"></i></a>
                      </td>
                    </tr>
                     <?php } ?>
                  </tbody>
                </table>
        </div>
      </div>

      <!-- selesai tabel booking rumah -->

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Data Transaksi Rumah</h3>
        </div>
        <div class="card-body">
          <buttton class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-tambah_transaksi_rumah"><i class="fas fa-plus"></i> Tambah Data</buttton>
          <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr class="text-center">
                    <th width="5%">No</th>
                    <th>Metode Pembayaran</th>
                    <th>Tenor</th>
                    <th>Tanggal Akad</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                  $no = 1; 
                  while ($d = mysqli_fetch_array($data_detail_transaksi_rumah)) { ?>
                    <tr>
                      <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                      <td><?= $d['metode_pembayaran'] ?> </td>
                      <td><?= $d['tenor'] ?></td>
                      <td><?= $d['tanggal_akad'] ?></td>
                      <td class="text-center">
                        <a href="jadwal_pembayaran_rumah.php?id=<?= $d['id_transaksi'] ?>&jenis_tagihan=rumah" 
                        class="btn btn-info btn-xs"><i class="bi bi-list-ol"></i> Jadwal Tagihan
                        </a>
                        <a href="hapus_transaksi_rumah.php?id=<?= $d['id_transaksi']; ?>" 
                        class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')"
                        ><i class="fas fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                     <?php } ?>
                  </tbody>
                </table>
        </div>
      </div>

      <!-- Selesai tabel data pembayaran rumah -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            Data Transaksi Rumah Tambahan
          </h3>
        </div>
        <div class="card-body">
          <buttton class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-tambah_detail_transaksi"><i class="fas fa-plus"></i> Tambah Data</buttton>
          <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr class="text-center">
                    <th width="5%">No</th>
                    <th>Nama Item</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                  $no = 1; 
                  mysqli_data_seek($data_detail_transaksi,0);
                  while ($d = mysqli_fetch_array($data_detail_transaksi)) { ?>
                    <tr>
                      <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                      <td><?= $d['nama_item']; ?></td>
                      <td><?= $d['harga']; ?></td>
                      <td><?= ($d['status_detail_transaksi'] == 'lunas') ? 'Lunas' : 'Belum Lunas' ?></td>
                      <td class="text-center">
                        <a href="nota_detail_transaksi.php?id=<?= $d['id_detail_transaksi'] ?>" class="btn btn-xs btn-info"><i class="bi bi-filetype-pdf"></i> Cetak Nota</a>
                        <a href="hapus_detail_transaksi.php?id=<?= $d['id_transaksi']; ?>" 
                        class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')"
                        ><i class="fas fa-trash"></i></a>
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

  <!-- selesai data detail transaksi rumah tambahan -->


<!-- modal transaksi booking rumah -->

<!-- modal -->
      <div class="modal fade" id="modal-tambah_booking_rumah">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Transaksi Booking</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah_booking_rumah.php" method="post">
                <div class="form-group">
                    <input type="hidden" name="id_transaksi" class="form-control" value="<?= $id_transaksi ?>">
                    <label for="tanggal_booking">Tanggal Booking</label>
                    <input type="date" name="tanggal_booking" class="form-control" id="tanggal_booking" placeholder="Masukan Tanggal Booking Rumah" required>
                </div>
                <div class="form-group">
                    <label for="booking_fee">Booking Feee</label>
                    <input type="number" name="booking_fee" class="form-control" id="booking_fee" placeholder="Masukan Jumlah Booking Fee" required>
                </div>
                <div class="form-group">
                    <label for="tenor">Tenor</label>
                    <input type="number" name="tenor" class="form-control" id="tenor" placeholder="Masukan Jumlah Tenor Angsuran" required>
                </div>
                <div class="form-group">
                    <label for="tenor">Status Booking</label>
                    <select name="status_booking" id="status_booking" class="form-control">
                      <option value="">-- Masukan Status Booking --</option>
                      <option value="belum_lunas">Belum Lunas</option>
                      <option value="lunas">Lunas</option>
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

<!-- selesai modal transaksi booking rumah -->



<!-- modal transaksi rumah -->
  <!-- modal -->
      <div class="modal fade" id="modal-tambah_transaksi_rumah">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Transaksi</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah_transaksi_rumah.php" method="post">
                <div class="form-group">
                    <input type="hidden" name="id_transaksi" class="form-control" value="<?= $id_transaksi ?>">
                    <label for="metode_pembayaran">Metode Pembayaran Rumah</label>
                    <input type="text" name="metode_pembayaran" class="form-control" id="metode_pembayaran" placeholder="Masukan Metode Pembayaran Rumah" required>
                </div>
                <div class="form-group">
                    <label for="tenor">Tenor</label>
                    <input type="number" name="tenor" class="form-control" id="tenor" placeholder="Masukan Tenor Pembayaran" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_akad">Tanggal</label>
                    <input type="date" name="tanggal_akad" class="form-control" id="tanggal_akad" placeholder="Masukan Tanggal Akad" required>
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

      <!-- selesai modal transaksi rumah -->

      <!-- mulai tambah modal detail transaksi tambahan -->

  <!-- modal Tambah -->
      <div class="modal fade" id="modal-tambah_detail_transaksi">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Detail Transaksi</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah_detail_transaksi.php" method="post">
                <div class="form-group">
                    <input type="hidden" name="id_transaksi" class="form-control" value="<?= $id_transaksi ?>">
                    <label for="nama_item">Nama Item</label>
                    <input type="text" name="nama_item" class="form-control" id="nama_item" placeholder="Masukan Item Yang Akan Dibeli" required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga Item</label>
                    <input type="number" name="harga" class="form-control" id="harga" placeholder="Masukan Harga Item Yang Akan Dibeli" required>
                </div>
                <div class="form-group">
                    <label for="tenor">Status Transaksi</label>
                    <select name="status_detail_transaksi" id="status_detail_transaksi" class="form-control">
                      <option value="">-- Masukan Status Transaksi --</option>
                      <option value="belum_lunas">Belum Lunas</option>
                      <option value="lunas">Lunas</option>
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

      <!-- selesai modal transaksi tambahan -->

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