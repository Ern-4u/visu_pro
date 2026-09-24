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
      color: #001F3F;
      margin-right: 10px;
      font-size: 1.3rem;
      width: 20px;
      text-align: center;
    }
    .logo-container {
      background-color: #ffffff;
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
      background-color: #001F3F;
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
      border-color: #001F3F;
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
  <?php
  $query_web = mysqli_query($conn, "SELECT * FROM web WHERE id = '1'")or die(mysqli_error($conn));
  $web = mysqli_fetch_array($query_web); 
  ?>
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble rounded-circle" src="../assets/logo/<?= $web['logo'] ?>" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <?php include '../layout_admin/navbar.php' ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include '../layout_admin/sidebar.php' ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <a href="index.php" class="btn btn-default"><i class="bi bi-arrow-90deg-left"></i> Kembali</a>
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
                        <img src="../assets/logo/<?= $web['logo'] ?>" width="250px" height="150px" alt="">
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

        <div class="card card-navy card-outline card-tabs">
              <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Transaksi</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Data Janji Bayar</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                    <form action="proses_pembayaran.php" method="POST" id="form-pembayaran">
                    <!-- DATA HIDDEN UNTUK DIKIRIM KE BACKEND -->
                    <input type="hidden" name="id_transaksi" value="<?= $id_transaksi ?>">
                    <input type="hidden" name="no_kwitansi" value="<?= $no_kwitansi_generate ?>">
                    <input type="hidden" name="tanggal_pembayaran" value="<?= $tanggal ?>">
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
                    </form>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                     <div class="row">
                      <div class="col-lg-4">
                          <div class="card">
                              <div class="card-header" style="background-color: #001F3F; color: white;">
                              <h3 class="card-title">BUAT JANJI BAYAR</h3>
                              </div>
                              <form action="janji_bayar.php" method="post">
                              <div class="card-body">
                                  <div class="form-group">
                                    <input type="hidden" name="id_transaksi" value="<?= $id_transaksi ?>">
                                    <label for="">Buat Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal_dijanjikan" placeholder="Masukan Tanggal Janji" required>
                                  </div>
                                  <div class="form-group">
                                      <label for="">Keterangan</label>
                                      <input type="text" class="form-control" name="keterangan" placeholder="Masukan Keterangan Janji" required>
                                  </div>
                              </div>
                              <div class="card-footer">
                                  <button type="submit" name="btn_tambah_janji" class="btn btn-primary mb-3">Simpan</button>
                              </div>
                              </form>
                          </div>
                      </div>
                      <div class="col-lg-8">
                          <div class="card">
                            <div class="card-header" style="background-color: #001F3F; color: white;">
                              <h3 class="card-title">
                              HISTORY JANJI BAYAR
                              </h3>
                            </div>
                            <div class="card-body">
                                <table id="tbl-janji" class="table table-bordered table-striped">
                                    <thead>
                                      <tr>
                                      <th>NO</th>
                                      <th>Tanggal Buat</th>
                                      <th>Janji Bayar</th>
                                      <th>Status</th>
                                      <th>Keterangan</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $query_janji_bayar = mysqli_query($conn, "SELECT * FROM janji_bayar WHERE id_transaksi = '$id_transaksi' ORDER BY id_janji_bayar DESC") or die(mysqli_error($conn));
                                    $no = 1;
                                    while ($jj = mysqli_fetch_array($query_janji_bayar)) { ?>
                                      <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= tanggal_indonesia($jj['tanggal_janji']) ?></td>
                                        <td><?= tanggal_indonesia($jj['tanggal_dijanjikan']) ?></td>
                                        <td><?= $jj['status'] ?></td>
                                        <td><?= $jj['keterangan'] ?></td>
                                      </tr>
                                      <?php }
                                      ?>
                                    </tbody>
                                </table>
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>
              </div>
              </div>
        </div>
       <!-- TRANSAKSI DAN JANJI BAYAR SELESAI -->
        <div class="card">
          <div class="card-header" style="background-color: #001F3F; color: white;">
            <h3 class="card-title">
              SUMMARY PEMBAYARAN
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
                
                <?php 
                // Siapkan variabel untuk menampung Grand Total
                $grand_total_dibayarkan = 0;
                $grand_total_harus_dibayar = 0;
                $grand_total_sisa = 0;

                // Daftar jenis pembayaran untuk memperpendek kode (Opsional, tapi ini cara manual per baris sesuai kode Anda)
                ?>

                <!-- Row 1: Booking Fee -->
                <tr>
                  <?php 
                  $jns_booking = 'Booking Fee';
                  $queri_hrg_booking = mysqli_query($conn, "SELECT harga FROM jenis_pembayaran WHERE id_transaksi ='$id_transaksi' AND jenis_pembayaran ='$jns_booking' ") or die(mysqli_error($conn));
                  $hrg_booking = mysqli_fetch_array($queri_hrg_booking);
                  
                  $queri_total_booking = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND jenis_pembayaran = '$jns_booking'")or die(mysqli_error($conn));
                  $total_booking = mysqli_fetch_array($queri_total_booking);

                  // Ambil nilai angka asli
                  $val_dibayarkan_booking = $total_booking['total_harga'] ?? 0;
                  $val_harga_booking = $hrg_booking['harga'] ?? 0;
                  $val_sisa_booking = $val_harga_booking - $val_dibayarkan_booking;

                  // Tambahkan ke Grand Total
                  $grand_total_dibayarkan += $val_dibayarkan_booking;
                  $grand_total_harus_dibayar += $val_harga_booking;
                  $grand_total_sisa += $val_sisa_booking;
                  ?>
                  <td>Booking Fee</td>
                  <td> Rp <?= number_format($val_dibayarkan_booking, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($val_harga_booking, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($val_sisa_booking, 0, ',', '.') ?></td>
                </tr>

                <!-- Row 2: Pembangunan Rumah -->
                <tr>
                  <?php 
                  $jns_pem_rumah = 'Pembangunan Rumah';
                  $queri_hrg_rmh = mysqli_query($conn, "SELECT harga FROM jenis_pembayaran WHERE id_transaksi ='$id_transaksi' AND jenis_pembayaran ='$jns_pem_rumah' ") or die(mysqli_error($conn));
                  $hrg_rumah = mysqli_fetch_array($queri_hrg_rmh);
                  
                  $queri_total_rmh = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND jenis_pembayaran = '$jns_pem_rumah'")or die(mysqli_error($conn));
                  $total_rmh = mysqli_fetch_array($queri_total_rmh);

                  $val_dibayarkan_rmh = $total_rmh['total_harga'] ?? 0;
                  $val_harga_rmh = $hrg_rumah['harga'] ?? 0;
                  $val_sisa_rmh = $val_harga_rmh - $val_dibayarkan_rmh;

                  $grand_total_dibayarkan += $val_dibayarkan_rmh;
                  $grand_total_harus_dibayar += $val_harga_rmh;
                  $grand_total_sisa += $val_sisa_rmh;
                  ?>
                  <td>Pembangunan Rumah</td>
                  <td> Rp <?= number_format($val_dibayarkan_rmh, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($val_harga_rmh, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($val_sisa_rmh, 0, ',', '.') ?></td>
                </tr>

                <!-- Row 3: Pajak Bangunan -->
                <tr>
                  <?php 
                  $jns_pb_rumah = 'Pajak Bangunan';
                  $queri_hrg_pb = mysqli_query($conn, "SELECT harga FROM jenis_pembayaran WHERE id_transaksi ='$id_transaksi' AND jenis_pembayaran ='$jns_pb_rumah' ") or die(mysqli_error($conn));
                  $hrg_pb = mysqli_fetch_array($queri_hrg_pb);
                  
                  $queri_total_pb = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND jenis_pembayaran = '$jns_pb_rumah'")or die(mysqli_error($conn));
                  $total_pb = mysqli_fetch_array($queri_total_pb);

                  $val_dibayarkan_pb = $total_pb['total_harga'] ?? 0;
                  $val_harga_pb = $hrg_pb['harga'] ?? 0;
                  $val_sisa_pb = $val_harga_pb - $val_dibayarkan_pb;

                  $grand_total_dibayarkan += $val_dibayarkan_pb;
                  $grand_total_harus_dibayar += $val_harga_pb;
                  $grand_total_sisa += $val_sisa_pb;
                  ?>
                  <td>Pajak Bangunan</td>
                  <td> Rp <?= number_format($val_dibayarkan_pb, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($val_harga_pb, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($val_sisa_pb, 0, ',', '.') ?></td>
                </tr>

                <!-- Row 4: Akte Jual Beli -->
                <tr>
                  <?php 
                  $jns_ajb = 'Akte Jual Beli';
                  $queri_hrg_ajb = mysqli_query($conn, "SELECT harga FROM jenis_pembayaran WHERE id_transaksi ='$id_transaksi' AND jenis_pembayaran ='$jns_ajb' ") or die(mysqli_error($conn));
                  $hrg_ajb = mysqli_fetch_array($queri_hrg_ajb);
                  
                  $queri_total_ajb = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND jenis_pembayaran = '$jns_ajb'")or die(mysqli_error($conn));
                  $total_ajb = mysqli_fetch_array($queri_total_ajb);

                  $val_dibayarkan_ajb = $total_ajb['total_harga'] ?? 0;
                  $val_harga_ajb = $hrg_ajb['harga'] ?? 0;
                  $val_sisa_ajb = $val_harga_ajb - $val_dibayarkan_ajb;

                  $grand_total_dibayarkan += $val_dibayarkan_ajb;
                  $grand_total_harus_dibayar += $val_harga_ajb;
                  $grand_total_sisa += $val_sisa_ajb;
                  ?>
                  <td>Akte Jual Beli</td>
                  <td> Rp <?= number_format($val_dibayarkan_ajb, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($val_harga_ajb, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($val_sisa_ajb, 0, ',', '.') ?></td>
                </tr>

                <!-- Row 5: Notaris -->
                <tr>
                  <?php 
                  $jns_notaris = 'Notaris';
                  $queri_hrg_notaris = mysqli_query($conn, "SELECT harga FROM jenis_pembayaran WHERE id_transaksi ='$id_transaksi' AND jenis_pembayaran ='$jns_notaris' ") or die(mysqli_error($conn));
                  $hrg_notaris = mysqli_fetch_array($queri_hrg_notaris);
                  
                  $queri_total_notaris = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND jenis_pembayaran = '$jns_notaris'")or die(mysqli_error($conn));
                  $total_notaris = mysqli_fetch_array($queri_total_notaris);

                  $val_dibayarkan_notaris = $total_notaris['total_harga'] ?? 0;
                  $val_harga_notaris = $hrg_notaris['harga'] ?? 0;
                  $val_sisa_notaris = $val_harga_notaris - $val_dibayarkan_notaris;

                  $grand_total_dibayarkan += $val_dibayarkan_notaris;
                  $grand_total_harus_dibayar += $val_harga_notaris;
                  $grand_total_sisa += $val_sisa_notaris;
                  ?>
                  <td>Notaris</td>
                  <td> Rp <?= number_format($val_dibayarkan_notaris, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($val_harga_notaris, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($val_sisa_notaris, 0, ',', '.') ?></td>
                </tr>

                <!-- Row 6: Lahan Makam -->
                <tr>
                  <?php 
                  $jns_makam = 'Lahan Makam';
                  $queri_hrg_makam = mysqli_query($conn, "SELECT harga FROM jenis_pembayaran WHERE id_transaksi ='$id_transaksi' AND jenis_pembayaran ='$jns_makam' ") or die(mysqli_error($conn));
                  $hrg_makam = mysqli_fetch_array($queri_hrg_makam);
                  
                  $queri_total_makam = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND jenis_pembayaran = '$jns_makam'")or die(mysqli_error($conn));
                  $total_makam = mysqli_fetch_array($queri_total_makam);

                  $val_dibayarkan_makam = $total_makam['total_harga'] ?? 0;
                  $val_harga_makam = $hrg_makam['harga'] ?? 0;
                  $val_sisa_makam = $val_harga_makam - $val_dibayarkan_makam;

                  $grand_total_dibayarkan += $val_dibayarkan_makam;
                  $grand_total_harus_dibayar += $val_harga_makam;
                  $grand_total_sisa += $val_sisa_makam;
                  ?>
                  <td>Lahan Makam</td>
                  <td> Rp <?= number_format($val_dibayarkan_makam, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($val_harga_makam, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($val_sisa_makam, 0, ',', '.') ?></td>
                </tr>

                <!-- Row 7: Hook -->
                <tr>
                  <?php 
                  $jns_hook = 'Hook';
                  $queri_hrg_hook = mysqli_query($conn, "SELECT harga FROM jenis_pembayaran WHERE id_transaksi ='$id_transaksi' AND jenis_pembayaran ='$jns_hook' ") or die(mysqli_error($conn));
                  $hrg_hook = mysqli_fetch_array($queri_hrg_hook);
                  
                  $queri_total_hook = mysqli_query($conn, "SELECT SUM(dibayarkan) AS total_harga FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND jenis_pembayaran = '$jns_hook'")or die(mysqli_error($conn));
                  $total_hook = mysqli_fetch_array($queri_total_hook);

                  $val_dibayarkan_hook = $total_hook['total_harga'] ?? 0;
                  $val_harga_hook = $hrg_hook['harga'] ?? 0;
                  $val_sisa_hook = $val_harga_hook - $val_dibayarkan_hook;

                  $grand_total_dibayarkan += $val_dibayarkan_hook;
                  $grand_total_harus_dibayar += $val_harga_hook;
                  $grand_total_sisa += $val_sisa_hook;

                  if ($grand_total_sisa == 0) {
                    $query_update_status_transaksi = mysqli_query($conn, "UPDATE transaksi SET status_transaksi = 'Selesai' WHERE id_transaksi ='$id_transaksi'")or die(mysqli_error($conn));
                  }
                  ?>
                  <td>Hook</td>
                  <td> Rp <?= number_format($val_dibayarkan_hook, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($val_harga_hook, 0, ',', '.') ?></td>
                  <td> Rp <?= number_format($val_sisa_hook, 0, ',', '.') ?></td>
                </tr>

                <!-- Baris Total -->
                <tr>
                  <td><b>TOTAL :</b></td>
                  <td><b>Rp <?= number_format($grand_total_dibayarkan, 0, ',', '.') ?></b></td>
                  <td><b>Rp <?= number_format($grand_total_harus_dibayar, 0, ',', '.') ?></b></td>
                  <td><b>Rp <?= number_format($grand_total_sisa, 0, ',', '.') ?></b></td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>


        <div class="card">
          <div class="card-header" style="background-color: #001F3F; color: white;">
            <h1 class="card-title">HISTORY PEMBAYARAN</h1>
          </div>
          <div class="card-body">
            <table id="tbl-histori" class="table table-bordered table-striped">
                  <thead>
                  <tr class="text-center">
                    <th width="5%">No</th>
                    <th>No Kwitansi</th>
                    <th>Jumlah Bayar</th>
                    <th>Tanggal</th>
                    <th>Jenis Bayar</th>
                    <th>Kwitansi</th>
                    <th>Bukti Bayar</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no =1; 
                    $query_detail_transaksi = mysqli_query($conn, "SELECT * FROM detail_transaksi WHERE id_transaksi = '$id_transaksi'")or die(mysqli_error($conn));
                    while ($dt_trans = mysqli_fetch_array($query_detail_transaksi)) {
                    ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $dt_trans['no_kwitansi'] ?></td>
                        <td> Rp <?= number_format($dt_trans['dibayarkan'] ?? 0, 0, ',', '.') ?></td>
                        <td><?= tanggal_indonesia($dt_trans['tanggal_pembayaran']) ?></td>
                        <td><?= $dt_trans['jenis_pembayaran'] ?></td>
                        <td class="text-center">
                          <a href="../assets/kwitansi/<?= $dt_trans['kwitansi'] ?>" class="btn btn-info btn-xs" target="_blank">Download Kwitansi</a>
                        </td>
                        <td class="text-center"> <?php if ($dt_trans['bukti_pembayaran']== '') {?>
                          <button type="button" class="btn btn-danger btn-xs"
                          data-target="#modal-bukti-bayar"
                          data-toggle="modal"
                          data-id_detail_transaksi="<?= $dt_trans['id_detail_transaksi'] ?>"
                          >Bukti Belum Diupload</button>
                          <?php } else { ?>
                          <button type="button" class="btn btn-warning btn-xs mb-3"
                            data-target="#modal-bukti-bayar"
                            data-toggle="modal"
                            data-id_detail_transaksi="<?= $dt_trans['id_detail_transaksi'] ?>"
                            ><i class="fas fa-edit"></i>Edit Bukti</button>
                          <a href="../assets/bukti_bayar/<?= $dt_trans['bukti_pembayaran']; ?>" target="_blank"><img src="../assets/bukti_bayar/<?= $dt_trans['bukti_pembayaran']; ?>" alt="bukti_transaksi" width="50px" height="100px"></a>
                          <?php }?>
                        </td>
                        <td class="text-center">
                          <a href="hapus_detail_transaksi.php?id=<?= $dt_trans['id_detail_transaksi'] ?>&id_transaksi=<?= $id_transaksi ?>" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></a>
                        </td>
                      </tr>
                    <?php
                    }
                    ?>
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

  <!-- modal Bukti Bayar -->
      <div class="modal fade" id="modal-bukti-bayar">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">UPLOAD BUKTI PEMBAYARAN</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="bukti_bayar.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <input type="hidden" class="form-control" name="id_detail_transaksi" id="id_detail_transaksi" value="" readonly>
                  <input type="hidden" class="form-control" name="id_transaksi" id="id_transaksi" value="<?= $id_transaksi ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="bukti_pembayaran">Upload Bukti Pembayaran</label>
                    <input type="file" accept=".jpg,.jpeg,.png,.pdf" name="bukti_pembayaran" class="form-control" id="bukti_pembayaran" placeholder="Upload File" required>
                </div> 
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_bukti_bayar" class="btn btn-primary">Simpan</button>
              </div>
              </form>
            </div>  
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Bukti Bayaar -->

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

  document.getElementById('form-pembayaran').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = this;

    // Buka tab kosong SAAT INI JUGA (masih dalam konteks klik user)
    // supaya tidak diblokir popup blocker browser
    const newTab = window.open('', '_blank');

    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // Arahkan tab yang sudah dibuka tadi ke file kwitansi
            newTab.location.href = '../assets/kwitansi/' + data.file_kwitansi;
            // Refresh halaman detail supaya summary & history ikut update
            window.location.reload();
        } else {
            newTab.close();
            alert(data.message || 'Terjadi kesalahan saat menyimpan pembayaran.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(err => {
        newTab.close();
        alert('Gagal menghubungi server: ' + err);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>



</body>
<script>
  $(function () {
    // Ubah titik (.) menjadi pagar (#)
    $("#tbl-histori").DataTable({
      "responsive": true, 
      "lengthChange": false, 
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#tbl-histori_wrapper .col-md-6:eq(0)'); 
    // ^ Tambahkan appendTo di atas agar tombol export muncul di posisi yang tepat
  $("#tbl-janji").DataTable({
      "responsive": true,
      "pageLength": 5, 
      "lengthChange": false, 
      "autoWidth": false,
      "buttons": ["copy", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#tbl-janji_wrapper .col-md-6:eq(0)');

  $('#modal-bukti-bayar').on('show.bs.modal', function(e) {

   var id_detail_transaksi = $(e.relatedTarget).data('id_detail_transaksi');
  
   $(e.currentTarget).find('input[name="id_detail_transaksi"]').val(id_detail_transaksi);
  
  });
    
  });
</script>
</html>

<?php
}
?>