<?php
require_once '../database/config.php';
require_once '../includes/tanggal.php';
$authority = @$_SESSION['peran'];

if ($authority != 'A') {
  echo '<script> alert("Anda Tidak boleh masuk ke halaman ini!!!!");
  window.location.href="../logout.php" </script>';
}

else {
  
  $id_transaksi = @$_GET['id'];
  $query_data_transaksi = mysqli_query($conn, "SELECT transaksi.*, rumah.*,kategori_rumah.*,pembeli.*
                                                FROM transaksi
                                                LEFT JOIN rumah ON transaksi.id_rumah = rumah.id_rumah
                                                LEFT JOIN pembeli ON transaksi.id_pembeli = pembeli.id_pembeli
                                                LEFT JOIN kategori_rumah ON rumah.id_kategori = kategori_rumah.id_kategori
                                                WHERE transaksi.id_transaksi = '$id_transaksi'") or die(mysqli_error($conn));
  $data_transaksi = mysqli_fetch_array($query_data_transaksi);
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
  
  <!-- CSS Khusus untuk Kwitansi -->
  <style>
    .kwitansi-wrapper {
      background-color: #ffffff;
      max-width: 900px;
      margin: 20px auto;
      padding: 40px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      font-family: Arial, sans-serif;
      color: #000;
    }
    .kwitansi-title {
      font-weight: 900;
      font-size: 2.8rem;
      line-height: 1;
      margin-bottom: 0;
      letter-spacing: -1px;
    }
    .contact-info {
      font-size: 15px;
    }
    .contact-info i {
      color: #0072c6;
      margin-right: 10px;
      font-size: 1.3rem;
      width: 20px;
      text-align: center;
    }
    .logo-container {
      background-color: #0072c6;
      color: white;
      text-align: center;
      padding: 30px 20px;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    .logo-icon {
      font-size: 3.5rem;
      margin-bottom: 10px;
    }
    .logo-text {
      font-weight: bold;
      font-size: 1.3rem;
      line-height: 1.1;
    }
    .blue-line {
      height: 8px;
      background-color: #0072c6;
      width: 100%;
      margin-top: 15px;
      margin-bottom: 35px;
    }
    .form-group-custom {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }
    .form-group-custom label {
      margin-bottom: 0;
      white-space: nowrap;
      margin-right: 10px;
      font-weight: normal;
      font-size: 15px;
    }
    .input-box {
      border: 1px solid #000;
      border-radius: 0;
      height: 35px;
      width: 100%;
    }
    .input-underline {
      border: none;
      border-bottom: 1px solid #000;
      border-radius: 0;
      padding-left: 5px;
      background-color: transparent;
      width: 100%;
    }
    /* Tambahan agar terbilang terlihat beda/miring */
    #kwitansi_sebesar {
      font-style: italic;
    }
    .input-underline:focus, .input-box:focus {
      box-shadow: none;
      outline: none;
      border-color: #0072c6;
    }
    .label-min-width {
      min-width: 170px;
      display: inline-block;
    }
    .colon {
      margin-right: 10px;
    }
    .footer-text {
      font-weight: bold;
      text-align: center;
      margin-top: 60px;
      font-size: 1.2rem;
    }
    .pembuat-nota {
      margin-top: 40px;
      text-align: right;
    }
    .pembuat-nota-label {
      margin-right: 10px;
    }
    .pembuat-nota-input {
      display: inline-block;
      text-align: left;
      width: 250px;
      border-bottom: 1px solid #000;
    }
  </style>

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-flex">
<div class="wrapper">

  <!-- Preloader -->
  <!-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble rounded-circle" src="../assets/logo/visupro.png" alt="AdminLTELogo" height="60" width="60">
  </div> -->

  <!-- Navbar -->
  <?php include '../layout_admin/navbar.php' ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include '../layout_admin/sidebar.php' ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
       
      </div>
    </div>
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- ================= MULA KWITANSI ================= -->
        <?php 
         $tanggal = date('y-m-d');

        $prefix = $tanggal . "-";

        $query_kwitansi = "SELECT no_kwitansi FROM detail_transaksi WHERE no_kwitansi LIKE '$prefix%' ORDER BY no_kwitansi DESC LIMIT 1";
        $result = $conn->query($query_kwitansi);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $kwitansi_terakhir = $row['no_kwitansi'];
            
            $pecah = explode("-", $kwitansi_terakhir);
            $nomor_terakhir = (int) end($pecah); 
            
            $nomor_baru = $nomor_terakhir + 1;
        } else {
            $nomor_baru = 1;
        }

        $nomor_urut_format = str_pad($nomor_baru, 4, "0", STR_PAD_LEFT);

        $no_kwitansi_generate = $prefix . $nomor_urut_format;

        //generate nomor kwitansi selesai

        ?>

        <div class="kwitansi-wrapper">
            <!-- Header Row -->
            <div class="row align-items-center">
                <!-- Judul & Kontak -->
                <div class="col-md-8">
                    <h1 class="kwitansi-title">KWITANSI<br>PEMBAYARAN</h1>
                    
                    <div class="row mt-4">
                        <div class="col-sm-6 contact-info">
                            <div class="mb-2"><i class="fas fa-phone-alt"></i> +123-456-7890</div>
                            <div><i class="fas fa-globe"></i> REALLYGREATSITE.COM</div>
                        </div>
                    </div>
                </div>
                
                <!-- Logo -->
                <div class="col-md-4 p-0">
                    <div class="logo-container">
                        <i class="fas fa-layer-group logo-icon"></i>
                        <div class="logo-text">ALDENAIRE &<br>PARTNERS</div>
                    </div>
                </div>
            </div>

            <!-- Garis Biru -->
            <div class="blue-line"></div>

            <!-- Baris No Nota & Tanggal -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label>NO KWITANSI:</label>
                        <input type="text" class="form-control input-box" value="<?php echo $no_kwitansi_generate; ?>" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label>TANGGAL:</label>
                        <input type="text" class="form-control input-box" value="<?= tanggal_indonesia($tanggal) ?>" readonly>
                    </div>
                </div>
            </div>

            <!-- Baris Nama & Kontak -->
            <div class="row mb-3">
                <div class="col-md-7">
                    <div class="d-flex align-items-end">
                        <label class="mb-0 mr-2">NAMA:</label>
                        <input type="text" class="form-control input-underline" value="<?= $data_transaksi['nama_pembeli'] ?>" readonly>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="d-flex align-items-end">
                        <label class="mb-0 mr-2">KONTAK:</label>
                        <input type="text" class="form-control input-underline" value="<?= $data_transaksi['kontak'] ?>" readonly>
                    </div>
                </div>
            </div>

            <!-- Baris Alamat -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex align-items-end">
                        <label class="mb-0 label-min-width">ALAMAT</label>
                        <span class="colon">:</span>
                        <input type="text" class="form-control input-underline" value="<?= $data_transaksi['alamat'] ?>" readonly>
                    </div>
                </div>
            </div>

            <!-- Baris Jenis Pembayaran -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex align-items-end">
                        <label class="mb-0 label-min-width">JENIS PEMBAYARAN</label>
                        <span class="colon">:</span>
                        <!-- id="kwitansi_jenis_pembayaran" dipertahankan -->
                        <input type="text" id="kwitansi_jenis_pembayaran" class="form-control input-underline" readonly>
                    </div>
                </div>
            </div>

            <!-- Baris Sebesar (Terbilang akan masuk ke sini) -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex align-items-end">
                        <label class="mb-0 label-min-width">SEBESAR</label>
                        <span class="colon">:</span>
                        <!-- id="kwitansi_sebesar" dipertahankan -->
                        <input type="text" id="kwitansi_sebesar" class="form-control input-underline" readonly>
                    </div>
                </div>
            </div>

            <!-- Baris Metode Pembayaran -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex align-items-end">
                        <label class="mb-0 label-min-width">METODE PEMBAYARAN</label>
                        <span class="colon">:</span>
                        <input type="text" id="kwitansi_metode_pembayaran" class="form-control input-underline" readonly>
                    </div>
                </div>
            </div>
            <?php
            $pembuat_nota = $_SESSION['nama']
            ?>
            <!-- Tanda Tangan / Pembuat Nota -->
            <div class="row">
                <div class="col-12 pembuat-nota">
                    <span class="pembuat-nota-label">PEMBUAT NOTA :</span>
                    <div class="pembuat-nota-input"><?= $pembuat_nota ?></div>
                </div>
            </div>

            <!-- Footer Text -->
            <div class="row">
                <div class="col-12">
                    <p class="footer-text">TRIMAKASIH ATAS PEMBAYARAN ANDA</p>
                </div>
            </div>
        </div>
        <!-- ================= AKHIR KWITANSI ================= -->

        <form action="proses_pembayaran.php" method="POST" target="_blank">
  
        <!-- DATA HIDDEN UNTUK DIKIRIM KE BACKEND -->
        <input type="hidden" name="id_transaksi" value="<?= $id_transaksi ?>">
        <input type="hidden" name="no_kwitansi" value="<?= $no_kwitansi_generate ?>">
        <input type="hidden" name="tanggal_pembayaran" value="<?= $tanggal ?>">

        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-lg-4">
                <div class="form-group">
                  <label for="input_sebesar">Masukan Jumlah Transaksi</label>
                  <!-- Name form ini adalah 'dibayarkan' -->
                  <input type="text" id="input_sebesar" name="dibayarkan" class="form-control" placeholder="Hanya ketik angka (misal: 1000000)" required>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label for="input_jenis_pembayaran">Pilih Jenis Pembayaran</label>
                  <select name="jenis_pembayaran" id="input_jenis_pembayaran" class="form-control" required>
                    <option value="">-- Pilih Jenis Pembayaran --</option>
                    <!-- Sesuaikan option ini dengan data pembayaran di tabel Anda -->
                    <option value="Booking Fee">Booking Fee</option>
                    <option value="Pembangunan Rumah">Pembangunan Rumah</option>
                    <option value="Pajak Bangunan">Pajak Bangunan</option>
                    <option value="Akte Jual Beli">Akte Jual Beli</option>
                    <option value="Notaris">Notaris</option>
                    <option value="Lahan Makam">Lahan Makam</option>
                    <option value="Hook">Hook</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label for="input_metode_pembayaran">Pilih Metode Pembayaran</label>
                  <!-- Catatan: metode_pembayaran tidak ada di gambar struktur DB Anda, jadi kita abaikan proses insert-nya di backend -->
                  <select name="metode_pembayaran" id="input_metode_pembayaran" class="form-control">
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="Tunai">Tunai</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="E-Wallet">E-Wallet</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- TOMBOL SUBMIT -->
            <div class="row mt-3">
              <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class="fas fa-print"></i> Submit & Cetak Nota</button>
              </div>
            </div>

          </div>
        </div>
      </form>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">

            </h3>
          </div>
          <div class="card-body">
            <table class="table table-bordered table-striped">
              <thead>
                <tr class="text-center">
                  <th>Jenis Pembayaran</th>
                  <th>Sudah Dibayarkan</th>
                  <th>Yang Harus Dibayar</th>
                  <th>Yang Belum Di bayar</th>
                </tr>
              </thead>
              <tbody class="text-center">
                
                <!-- Row 1: Booking Fee -->
                <tr>
                  <?php 
                  $jns_booking = 'Booking Fee';
                  $queri_hrg_booking = mysqli_query($conn, "SELECT harga FROM jenis_pembayaran WHERE id_transaksi ='$id_transaksi' AND jenis_pembayaran ='$jns_booking' ") or die(mysqli_error($conn));
                  $hrg_booking = mysqli_fetch_array($queri_hrg_booking);
                  $queri_total_booking = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND jenis_pembayaran = '$jns_booking'")or die(mysqli_error($conn));
                  $total_booking = mysqli_fetch_array($queri_total_booking);
                  ?>
                  <td>Booking Fee</td>
                  <td> Rp <?= number_format($total_booking['total_harga'] ?? 0, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($hrg_booking['harga'] ?? 0, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format(($hrg_booking['harga'] ?? 0) - ($total_booking['total_harga'] ?? 0), 0, ',', '.') ?></td>
                </tr>

                <!-- Row 2: Pembangunan Rumah -->
                <tr>
                  <?php 
                  $jns_pem_rumah = 'Pembangunan Rumah';
                  $queri_hrg_rmh = mysqli_query($conn, "SELECT harga FROM jenis_pembayaran WHERE id_transaksi ='$id_transaksi' AND jenis_pembayaran ='$jns_pem_rumah' ") or die(mysqli_error($conn));
                  $hrg_rumah = mysqli_fetch_array($queri_hrg_rmh);
                  $queri_total_rmh = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND jenis_pembayaran = '$jns_pem_rumah'")or die(mysqli_error($conn));
                  $total_rmh = mysqli_fetch_array($queri_total_rmh);
                  ?>
                  <td>Pembangunan Rumah</td>
                  <td> Rp <?= number_format($total_rmh['total_harga'] ?? 0, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($hrg_rumah['harga'] ?? 0, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format(($hrg_rumah['harga'] ?? 0) - ($total_rmh['total_harga'] ?? 0), 0, ',', '.') ?></td>
                </tr>

                <!-- Row 3: Pajak Bangunan -->
                <tr>
                  <?php 
                  $jns_pb_rumah = 'Pajak Bangunan';
                  $queri_hrg_pb = mysqli_query($conn, "SELECT harga FROM jenis_pembayaran WHERE id_transaksi ='$id_transaksi' AND jenis_pembayaran ='$jns_pb_rumah' ") or die(mysqli_error($conn));
                  $hrg_pb = mysqli_fetch_array($queri_hrg_pb);
                  $queri_total_pb = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND jenis_pembayaran = '$jns_pb_rumah'")or die(mysqli_error($conn));
                  $total_pb = mysqli_fetch_array($queri_total_pb);
                  ?>
                  <td>Pajak Bangunan</td>
                  <td> Rp <?= number_format($total_pb['total_harga'] ?? 0, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($hrg_pb['harga'] ?? 0, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format(($hrg_pb['harga'] ?? 0) - ($total_pb['total_harga'] ?? 0), 0, ',', '.') ?></td>
                </tr>

                <!-- Row 4: Akte Jual Beli -->
                <tr>
                  <?php 
                  $jns_ajb = 'Akte Jual Beli';
                  $queri_hrg_ajb = mysqli_query($conn, "SELECT harga FROM jenis_pembayaran WHERE id_transaksi ='$id_transaksi' AND jenis_pembayaran ='$jns_ajb' ") or die(mysqli_error($conn));
                  $hrg_ajb = mysqli_fetch_array($queri_hrg_ajb);
                  $queri_total_ajb = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND jenis_pembayaran = '$jns_ajb'")or die(mysqli_error($conn));
                  $total_ajb = mysqli_fetch_array($queri_total_ajb);
                  ?>
                  <td>Akte Jual Beli</td>
                  <td> Rp <?= number_format($total_ajb['total_harga'] ?? 0, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($hrg_ajb['harga'] ?? 0, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format(($hrg_ajb['harga'] ?? 0) - ($total_ajb['total_harga'] ?? 0), 0, ',', '.') ?></td>
                </tr>

                <!-- Row 5: Notaris -->
                <tr>
                  <?php 
                  $jns_notaris = 'Notaris';
                  $queri_hrg_notaris = mysqli_query($conn, "SELECT harga FROM jenis_pembayaran WHERE id_transaksi ='$id_transaksi' AND jenis_pembayaran ='$jns_notaris' ") or die(mysqli_error($conn));
                  $hrg_notaris = mysqli_fetch_array($queri_hrg_notaris);
                  $queri_total_notaris = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND jenis_pembayaran = '$jns_notaris'")or die(mysqli_error($conn));
                  $total_notaris = mysqli_fetch_array($queri_total_notaris);
                  ?>
                  <td>Notaris</td>
                  <td> Rp <?= number_format($total_notaris['total_harga'] ?? 0, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($hrg_notaris['harga'] ?? 0, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format(($hrg_notaris['harga'] ?? 0) - ($total_notaris['total_harga'] ?? 0), 0, ',', '.') ?></td>
                </tr>

                <!-- Row 6: Lahan Makam -->
                <tr>
                  <?php 
                  $jns_makam = 'Lahan Makam';
                  $queri_hrg_makam = mysqli_query($conn, "SELECT harga FROM jenis_pembayaran WHERE id_transaksi ='$id_transaksi' AND jenis_pembayaran ='$jns_makam' ") or die(mysqli_error($conn));
                  $hrg_makam = mysqli_fetch_array($queri_hrg_makam);
                  $queri_total_makam = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND jenis_pembayaran = '$jns_makam'")or die(mysqli_error($conn));
                  $total_makam = mysqli_fetch_array($queri_total_makam);
                  ?>
                  <td>Lahan Makam</td>
                  <td> Rp <?= number_format($total_makam['total_harga'] ?? 0, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($hrg_makam['harga'] ?? 0, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format(($hrg_makam['harga'] ?? 0) - ($total_makam['total_harga'] ?? 0), 0, ',', '.') ?></td>
                </tr>

                <!-- Row 7: Hook -->
                <tr>
                  <?php 
                  $jns_hook = 'Hook';
                  $queri_hrg_hook = mysqli_query($conn, "SELECT harga FROM jenis_pembayaran WHERE id_transaksi ='$id_transaksi' AND jenis_pembayaran ='$jns_hook' ") or die(mysqli_error($conn));
                  $hrg_hook = mysqli_fetch_array($queri_hrg_hook);
                  $queri_total_hook = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND jenis_pembayaran = '$jns_hook'")or die(mysqli_error($conn));
                  $total_hook = mysqli_fetch_array($queri_total_hook);
                  ?>
                  <td>Hook</td>
                  <td> Rp <?= number_format($total_hook['total_harga'] ?? 0, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($hrg_hook['harga'] ?? 0, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format(($hrg_hook['harga'] ?? 0) - ($total_hook['total_harga'] ?? 0), 0, ',', '.') ?></td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h1 class="card-title">Histori Pembayaran</h1>
          </div>
          <div class="card-body">
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

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
   <?php include '../layout_admin/footer.php' ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php include '../layout_admin/js.php' ?>

<!-- ================= SCRIPT SINKRONISASI & TERBILANG ================= -->
<script>
  // FUNGSI KONVERSI ANGKA KE TERBILANG (BAHASA INDONESIA)
  function terbilang(angka) {
    var bilangan = ['','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas'];
    angka = Math.abs(angka);
    var simpan = "";

    if (angka < 12) {
      simpan = " " + bilangan[angka];
    } else if (angka < 20) {
      simpan = terbilang(Math.floor(angka - 10)) + " Belas";
    } else if (angka < 100) {
      simpan = terbilang(Math.floor(angka / 10)) + " Puluh" + terbilang(angka % 10);
    } else if (angka < 200) {
      simpan = " Seratus" + terbilang(angka - 100);
    } else if (angka < 1000) {
      simpan = terbilang(Math.floor(angka / 100)) + " Ratus" + terbilang(angka % 100);
    } else if (angka < 2000) {
      simpan = " Seribu" + terbilang(angka - 1000);
    } else if (angka < 1000000) {
      simpan = terbilang(Math.floor(angka / 1000)) + " Ribu" + terbilang(angka % 1000);
    } else if (angka < 1000000000) {
      simpan = terbilang(Math.floor(angka / 1000000)) + " Juta" + terbilang(angka % 1000000);
    } else if (angka < 1000000000000) {
      simpan = terbilang(Math.floor(angka / 1000000000)) + " Miliar" + terbilang(angka % 1000000000);
    } else if (angka < 1000000000000000) {
      simpan = terbilang(Math.floor(angka / 1000000000000)) + " Triliun" + terbilang(angka % 1000000000000);
    }
    return simpan;
  }

  // Mengambil elemen
  const inputSebesar = document.getElementById('input_sebesar');
  const kwitansiSebesar = document.getElementById('kwitansi_sebesar');
  
  const inputJenisPembayaran = document.getElementById('input_jenis_pembayaran');
  const kwitansiJenisPembayaran = document.getElementById('kwitansi_jenis_pembayaran');

  const inputMetodePembayaran = document.getElementById('input_metode_pembayaran');
  const kwitansiMetodePembayaran = document.getElementById('kwitansi_metode_pembayaran');

  // Event listener saat user mengetik jumlah transaksi
  inputSebesar.addEventListener('input', function() {
    // 1. Bersihkan input dari huruf, ambil murni angka saja
    let angkaMurni = this.value.replace(/[^0-9]/g, '');

    // 2. Jika input kosong, kosongkan juga form bawah dan kwitansinya
    if (angkaMurni === '') {
      this.value = '';
      kwitansiSebesar.value = '';
      return;
    }

    // 3. (Opsional tapi rapi) Format angka di input bawah jadi pakai titik (Rupiah) saat mengetik
    let formattedNumber = new Intl.NumberFormat('id-ID').format(angkaMurni);
    this.value = formattedNumber; 

    // 4. Ubah angka murni tersebut ke string Terbilang
    let teksTerbilang = terbilang(parseInt(angkaMurni));

    // 5. Gabungkan menjadi satu kalimat di kwitansi
    // Output Contoh: Rp 1.000.000 ( Satu Juta Rupiah )
    kwitansiSebesar.value = 'Rp ' + formattedNumber + '  ( ' + teksTerbilang.trim() + ' Rupiah )';
  });

  // Event listener saat user memilih jenis pembayaran
  inputJenisPembayaran.addEventListener('change', function() {
    kwitansiJenisPembayaran.value = this.value; 
  });

  // Event listener saat user memilih metode pembayaran
  inputMetodePembayaran.addEventListener('change', function() {
    kwitansiMetodePembayaran.value = this.value; 
  });
</script>

</body>
</html>

<?php
}
?>