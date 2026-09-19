<?php
require_once '../database/config.php';
$authority = @$_SESSION['peran'];

if ($authority != 'A') {
  echo '<script> alert("Anda Tidak boleh masuk ke halaman ini!!!!");
  window.location.href="../logout.php" </script>';
}

else {
$ambil_data_rumah = mysqli_query($conn, "SELECT rumah.*,site_plan.*, kategori_rumah.*
            FROM rumah 
            LEFT JOIN site_plan ON rumah.id_site_plan = site_plan.id_site_plan
            LEFT JOIN kategori_rumah ON rumah.id_kategori = kategori_rumah.id_kategori
            WHERE rumah.status = '0'
            ") or die(mysqli_error($conn));
  $ambil_data_pembeli = mysqli_query($conn, "SELECT * FROM pembeli") or die(mysqli_error($conn));
  $ambil_data_marketing = mysqli_query($conn, "SELECT * FROM marketing") or die(mysqli_error($conn));
  
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
      <div class="container-fluid">
       
      </div>
    </div>
    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            Input Transaksi Rumah
          </h3>
        </div>
        <div class="card-body">
              <form action="tambah.php" method="post">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                        <label for="">Pilih Rumah Yang Akan Di beli</label>
                        <select name="id_rumah" id="pilih_rumah" class="form-control select2"  style="width: 100%;" >
                            <option value="">-- Pilih Rumah --</option>
                            <?php 
                            while ($dt_rmh = mysqli_fetch_array($ambil_data_rumah)) { ?>
                            <option value="<?= $dt_rmh['id_rumah'] ?>" data-harga="<?= $dt_rmh['harga'] ?>"><?= $dt_rmh['kode_blok'] ?> - <?= $dt_rmh['nama_kategori'] ?> - <?= $dt_rmh['nama_site_plan'] ?></option>
                            <?php }
                            ?>
                        </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                        <label for="">Pilih Data Pembeli</label>
                        <select name="id_pembeli" id="" class="form-control select2" style="width: 100%;" >
                            <option value="">-- Pilih Pembeli --</option>
                            <?php 
                            while ($dt_pbl = mysqli_fetch_array($ambil_data_pembeli)) { ?>
                            <option value="<?= $dt_pbl['id_pembeli'] ?>"><?= $dt_pbl['nama_pembeli'] ?></option>
                            <?php }
                            ?>
                        </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                        <label for="">Pilih Data Marketing</label>
                        <select name="id_karyawan" id="" class="form-control select2" style="width: 100%;" >
                            <option value="">-- Pilih Marketing --</option>
                            <?php 
                            while ($dt_mkg = mysqli_fetch_array($ambil_data_marketing)) { ?>
                            <option value="<?= $dt_mkg['id_karyawan'] ?>"><?= $dt_mkg['nama'] ?></option>
                            <?php }
                            ?>
                        </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="form-group">

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                    <h4 class="mb-3" style="text-align: center; font-family: Arial, Helvetica, sans-serif;"><b>INPUT DAFTAR HARGA</b></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Booking Fee</label>
                            <input type="number" class="form-control" name="booking_fee" placeholder="Masukan Harga Booking" required>
                        </div>
                        <div class="form-group">
                            <label for="">Pembangunan Rumah</label>
                            <input type="number" class="form-control" name="pem_rumah" id="pem_rumah" placeholder="Masukan Harga Pembangunan Rumah" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="">Pajak Pembangunan Rumah</label>
                            <input type="number" class="form-control" name="pb_rumah" placeholder="Masukan Harga Pajak Pembangunan Rumah" required>
                        </div>
                        <div class="form-group">
                            <label for="">Akte Jual Beli</label>
                            <input type="number" class="form-control" name="ajb" placeholder="Masukan Harga Akte Jual Beli" required>
                        </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                            <label for="">Notaris</label>
                            <input type="number" class="form-control" name="notaris" placeholder="Masukah Harga jasa Notaris" required>
                        </div>
                        <div class="form-group">
                            <label for="">Lahan Makam</label>
                            <input type="number" class="form-control" name="lahan_makam" placeholder="Masukan Harga Lahan Makam" required>
                        </div>
                        <div class="form-group">
                            <label for="">Hook</label>
                            <input type="number" class="form-control" name="hook" placeholder="Masukan Harga Hook Rumah" >
                        </div>
                        </div>
                    </div>
              </div>
              <div class="card-footer">
                <a href="index.php" class="btn btn-default" data-dismiss="modal">Kembali</a>
                <button type="submit" name="btn_tambah" class="btn btn-primary">Simpan</button>
              </form>
            </div>
      </div>      

    </div>  
    <!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php 
  
  ?>

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
$(document).ready(function() {
    // Ketika pilihan pada select dengan id 'pilih_rumah' berubah
    $('#pilih_rumah').on('change', function() {
        // Ambil nilai dari atribut data-harga dari option yang sedang dipilih
        var harga = $(this).find(':selected').data('harga');
        
        // Masukkan nilai tersebut ke dalam input dengan id 'pem_rumah'
        $('#pem_rumah').val(harga);
    });
});
</script>
</html>

<?php
}
?>