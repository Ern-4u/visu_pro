<?php
require_once '../database/config.php';
$authority = @$_SESSION['peran'];

if ($authority != 'A') {
  echo '<script> alert("Anda Tidak boleh masuk ke halaman ini!!!!");
  window.location.href="../logout.php" </script>';
}

else {
  $id_transaksi = @$_GET['id'];
  $booking = @$_GET['jenis_tagihan'];

  $data_jadwal = mysqli_query($conn, "SELECT jadwal_pembayaran.*, booking.*
            FROM jadwal_pembayaran 
            LEFT JOIN booking ON jadwal_pembayaran.id_transaksi = booking.id_transaksi
            WHERE jadwal_pembayaran.id_transaksi = '$id_transaksi' AND jadwal_pembayaran.jenis_tagihan = '$booking'
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
                        <?php 
                        $id_jadwal_pembayaran = $d['id_jadwal_pembayaran'];
                        $query_cek_pembayaran = mysqli_query($conn, "SELECT * FROM pembayaran WHERE id_jadwal_pembayaran = $id_jadwal_pembayaran")or die(mysqli_error($conn));
                        $rv = mysqli_num_rows($query_cek_pembayaran);

                        if ($rv == 0) { ?>
                          <button type="submit" class="btn btn-sm btn-info" data-target="#modal_pembayaran" data-toggle="modal"
                          data-id_jadwal_pembayaran ="<?= $d['id_jadwal_pembayaran'] ?>"
                          data-jenis_tagihan = "<?= $d['jenis_tagihan'] ?>"
                          data-jumlah_tagihan="<?= $d['jumlah_tagihan'] ?>">
                          <i class="bi bi-cash"> Bayar Tagihan</i>
                        </button>
                        <?php } else { ?> 
                          <a href="nota_booking.php?id=<?= $d['id_jadwal_pembayaran'] ?>" class="btn btn-sm btn-success"><i class="bi  bi-clipboard2-check-fill"></i> Cetak Nota</a>
                        <?php } ?>
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

  <!-- modal PEMBAYARAN -->
      <div class="modal fade" id="modal_pembayaran">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Pembayaran Booking</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="pembayaran.php" method="post">
                <div class="form-group">
                    <input type="hidden" name="id_jadwal_pembayaran" class="form-control">
                    <input type="hidden" name="jenis_tagihan" readonly>
                    <input type="hidden" name="jumlah_tagihan" readonly>
                    <input type="hidden" name="jenis_pembayaran" value="booking" readonly>
                    <input type="hidden" name="id_transaksi" value="<?= $id_transaksi ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="metode_pembayaran">Metode Bayar</label>
                    <select name="metode_pembayaran" class="form-control" id="metode_pembayaran" required>
                    <option value="">-- Masukan Metode Pembayaran</option>
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    </select>
                </div>
                <div class="form-group">
                  <label for="tanggal_pembayaran">Tanggal Bayar</label>
                  <input type="date" class="form-control" name="tanggal_pembayaran" required>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_bayar" class="btn btn-primary">Simpan</button>
              </div>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
  <!-- /.modal PEMBAYARAN -->

      <!-- modal DATA PEMBAYARAN -->
      <div class="modal fade" id="modal_pembayaran">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Data Pembayaran Booking</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="form-group"></div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_bayar" class="btn btn-primary">Simpan</button>
                </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal DATA PEMBAYARAN -->

  

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
   $('#modal_pembayaran').on('show.bs.modal', function(e) {

   var id_jadwal_pembayaran = $(e.relatedTarget).data('id_jadwal_pembayaran');
   var jenis_tagihan = $(e.relatedTarget).data('jenis_tagihan');
   var jumlah_tagihan = $(e.relatedTarget).data('jumlah_tagihan');

    $(e.currentTarget).find('input[name="id_jadwal_pembayaran"]').val(id_jadwal_pembayaran);
    $(e.currentTarget).find('input[name="jenis_tagihan"]').val(jenis_tagihan);
    $(e.currentTarget).find('input[name="jumlah_tagihan"]').val(jumlah_tagihan);
    });
</script>
</body>

 
</html>

<?php
}
?>