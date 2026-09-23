<?php
require_once '../database/config.php';
require_once '../includes/tanggal.php';
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
    $hal = 'home_admin';
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
  include '../layout_admin/sidebar.php'
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <div class="content-header">
      <div class="container-fluid">

      </div>
    </div>
    
    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow">
              <span class="info-box-icon" style="background-color: #003366; color: white;"><i class="bi bi-house-check-fill"></i></span>
                <?php 
                $query_jumlah_rumah_tersedia = mysqli_query($conn, "SELECT COUNT(id_rumah) AS total_rumah_tersedia FROM rumah WHERE status = '0'")or die(mysqli_error($conn));
                $jml_rumah = mysqli_fetch_array($query_jumlah_rumah_tersedia);
                ?>
              <div class="info-box-content">
                <span class="info-box-text">Rumah Tersedia</span>
                <span class="info-box-number"><?= $jml_rumah['total_rumah_tersedia'] ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow">
              <span class="info-box-icon " style="background-color: #1D4A7A ; color: white;"><i class="bi bi-receipt-cutoff"></i></span>
                <?php 
                $query_jumlah_transaksi = mysqli_query($conn, "SELECT COUNT(id_transaksi) AS total_transaksi FROM transaksi WHERE status_transaksi = 'Berlangsung'")or die(mysqli_error($conn));
                $jml_trans = mysqli_fetch_array($query_jumlah_transaksi);
                ?>
              <div class="info-box-content">
                <span class="info-box-text">Transaksi Berlangsung</span>
                <span class="info-box-number"><?= $jml_trans['total_transaksi'] ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow">
              <span class="info-box-icon" style="background-color: #4A709C  ; color: white;"><i class="bi bi-clipboard2-check-fill"></i></span>
                <?php 
                $query_jumlah_transaksi_sls = mysqli_query($conn, "SELECT COUNT(id_transaksi) AS total_transaksi FROM transaksi WHERE status_transaksi = 'Selesai'")or die(mysqli_error($conn));
                $jml_trans_sls = mysqli_fetch_array($query_jumlah_transaksi_sls);
                ?>
              <div class="info-box-content">
                <span class="info-box-text">Transaksi Selesai</span>
                <span class="info-box-number"><?= $jml_trans_sls['total_transaksi'] ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow">
              <span class="info-box-icon" style="background-color: #A1B7D1  ; color: white;"><i class="bi bi-clipboard2-x-fill"></i></span>
                <?php 
                $query_jumlah_transaksi_ggl = mysqli_query($conn, "SELECT COUNT(id_transaksi) AS total_transaksi FROM transaksi WHERE status_transaksi = 'Gagal Bayar'")or die(mysqli_error($conn));
                $jml_trans_ggl = mysqli_fetch_array($query_jumlah_transaksi_ggl);
                ?>
              <div class="info-box-content">
                <span class="info-box-text">Transaksi Gagal</span>
                <span class="info-box-number"><?= $jml_trans_ggl['total_transaksi'] ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow">
              <span class="info-box-icon "style="background-color: #003366 ; color: white;"><i class="bi bi-cash-stack"></i></span>
                <?php 
                $query_jumlah_pend = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_pendapatan FROM detail_transaksi WHERE detail_transaksi.tanggal_pembayaran >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)")or die(mysqli_error($conn));
                $jml_pend = mysqli_fetch_array($query_jumlah_pend);
                ?>
              <div class="info-box-content">
                <span class="info-box-text">Uang Masuk 1 Bulan Terakhir</span>
                <span class="info-box-number">Rp <?= number_format($jml_pend['total_pendapatan'], 0, ',', '.'); ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow">
              <span class="info-box-icon "style="background-color: #1D4A7A ; color: white;"><i class="bi bi-pin-map-fill"></i></span>
                <?php 
                $query_jumlah_st_plan = mysqli_query($conn, "SELECT COUNT(id_site_plan) AS jml_st_plan FROM site_plan")or die(mysqli_error($conn));
                $jml_st = mysqli_fetch_array($query_jumlah_st_plan);
                ?>
              <div class="info-box-content">
                <span class="info-box-text">jumlah Site Plan</span>
                <span class="info-box-number"><?= $jml_st['jml_st_plan'] ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow">
              <span class="info-box-icon" style="background-color: #4A709C ; color: white;"><i class="bi bi-house-gear-fill"></i></span>
                <?php 
                $query_jumlah_kat = mysqli_query($conn, "SELECT COUNT(id_kategori) AS jml_kat FROM kategori_rumah")or die(mysqli_error($conn));
                $jml_kat = mysqli_fetch_array($query_jumlah_kat);
                ?>
              <div class="info-box-content">
                <span class="info-box-text">Jumlah Cluster Rumah</span>
                <span class="info-box-number"> <?=$jml_kat['jml_kat'] ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow">
              <span class="info-box-icon" style="background-color: #A1B7D1 ; color: white;"><i class="bi bi-file-earmark-person-fill"></i></span>
                <?php 
                $query_jumlah_mar = mysqli_query($conn, "SELECT COUNT(id_karyawan) AS jml_mar FROM marketing")or die(mysqli_error($conn));
                $jml_mar = mysqli_fetch_array($query_jumlah_mar);
                ?>
              <div class="info-box-content">
                <span class="info-box-text">Humlah Tim Marketing</span>
                <span class="info-box-number"><?= $jml_mar['jml_mar']; ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
      </div>
      <!-- End ROW -->
       <div class="row">
        <div class="col-lg-6">
          <div class="card shadow" >
            <div class="card-header" style="background-color: #001F3F; color: white;">
              <h3 class="card-title">
                DAFTAR JANJI BAYAR
              </h3>
            </div>
            <div class="card-body">
                <table id="tbl-jj" class="table table-bordered table-striped text-center">
                  <thead>
                  <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Nama Pembeli</th>
                    <th>Tempo</th>
                    <th>Ingatkan</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $query_daftar_janji = mysqli_query($conn, "SELECT j.* , t.id_pembeli, p.*, r.*, s.*,k.*
                               FROM janji_bayar j
                               LEFT JOIN transaksi t ON j.id_transaksi = t.id_transaksi
                               LEFT JOIN pembeli p ON t.id_pembeli = p.id_pembeli
                               LEFT JOIN rumah r ON t.id_rumah = r.id_rumah
                               LEFT JOIN site_plan s ON r.id_site_plan = s.id_site_plan
                               LEFT JOIN kategori_rumah k ON r.id_kategori = k.id_kategori
                               WHERE j.status = 'Aktif'") or die(mysqli_error($conn));
                    $no = 1;
                    while ($jj = mysqli_fetch_array($query_daftar_janji)) { ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $jj['nama_pembeli'] ?></td>
                        <td><?= tanggal_indonesia($jj['tanggal_dijanjikan']) ?></td>
                        <td>
                          <?php 
                          $pj = $_SESSION['nama'];
                          $st_plan = $jj['nama_site_plan'];
                          $ktgr = $jj['nama_kategori'];

                          $chat = 'Halo '.$jj['nama_pembeli'].',
                                    Izin follow-up untuk janji bayar transaksi Rumah '.$ktgr.' di perumahan '.$st_plan.' yang jatuh tempo pada '.tanggal_indonesia($jj['tanggal_dijanjikan']).' ya.

                                    Boleh diinfokan apakah pembayarannya sudah bisa diproses hari ini? Jika sudah ditransfer atau bisa datang ke kantor kami, mohon bantuannya untuk mengirimkan bukti bayarnya ya agar pesanannya/statusnya bisa kami proses lebih lanjut.
                                    Terima kasih! :)
                                    
                                    -'.$pj ;
                          ?>
                          <a href="https://wa.me/<?= $jj['kontak']; ?>?text=<?= $chat ?>" target="_blank" type="button"  class="btn btn-success btn-xs">
                           <i class="bi bi-whatsapp"></i> <?= $jj['kontak'];?>                           
                          </a>
                        </td>
                      </tr>
                    <?php }
                    ?>
                  </tbody>
                  
                </table>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
        <!-- end col -->
         <div class="col-lg-6">
          <div class="card shadow" >
            <div class="card-header" style="background-color: #001F3F; color: white;">
              <h3 class="card-title">
                KARYAWAN DENGAN TRANSAKSI TERBANYAK
              </h3>
            </div>
            <div class="card-body">
                <table id="" class="table table-bordered table-striped text-center">
                  <thead>
                  <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Nama Karyawan</th>
                    <th>Jumlah Transaksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $query_karyawan_teratas = mysqli_query($conn, "
                          SELECT k.nama, COUNT(t.id_transaksi) AS total_transaksi
                          FROM transaksi t
                          LEFT JOIN marketing k ON t.id_karyawan = k.id_karyawan
                          GROUP BY t.id_karyawan
                          ORDER BY total_transaksi DESC
                          LIMIT 5
                      ") or die(mysqli_error($conn));
                    $no = 1;
                    while ($kt = mysqli_fetch_array($query_karyawan_teratas)) { ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $kt['nama'] ?></td>
                        <td><?= $kt['total_transaksi'] ?></td>
                      </tr>
                    <?php }
                    ?>
                  </tbody>
                  
                </table>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
        <!-- end col -->
       </div>
       <!-- end row -->
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

<script>
  $(function () {
    // Ubah titik (.) menjadi pagar (#)
    $("#tbl-jj").DataTable({
      "responsive": true, 
      "lengthChange": false, 
      "autoWidth": false,
    }).buttons().container().appendTo('#tbl-jj_wrapper .col-md-6:eq(0)'); 
    // ^ Tambahkan appendTo di atas agar tombol export muncul di posisi yang tepat
  });
</script>
</html>

<?php
}
?>