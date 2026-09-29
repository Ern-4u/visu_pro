<?php
require_once '../database/config.php';
$authority = @$_SESSION['peran'];

if ($authority != 'M') {
  echo '<script> alert("Anda Tidak boleh masuk ke halaman ini!!!!");
  window.location.href="../logout.php" </script>';
}

else {
$id_rumah = @$_GET['id'];
$ambil_data_rumah = mysqli_query($conn, "SELECT rumah.*,site_plan.*, kategori_rumah.*
            FROM rumah 
            LEFT JOIN site_plan ON rumah.id_site_plan = site_plan.id_site_plan
            LEFT JOIN kategori_rumah ON rumah.id_kategori = kategori_rumah.id_kategori
            WHERE rumah.id_rumah = '$id_rumah'
            ") or die(mysqli_error($conn));

$id_karyawan = $_SESSION['username'];  
  $ambil_data_pembeli = mysqli_query($conn, "SELECT * FROM pembeli WHERE id_karyawan = '$id_karyawan'") or die(mysqli_error($conn));
  $ambil_data_marketing = mysqli_query($conn, "SELECT * FROM marketing") or die(mysqli_error($conn));
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>VISU Pro | Dashboard admin</title>
  <?php
    include '../layout_marketing/css.php';
    $hal = 'transaksi';
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
  include '../layout_marketing/navbar.php'
  ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php
  include '../layout_marketing/sidebar.php'
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
              <form action="beli_rumah.php" method="post">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                        <label for="">Pilih Rumah Yang Akan Di beli</label>
                        <select name="id_rumah" id="pilih_rumah" class="form-control"  style="width: 100%;" >
                            <?php
                            $dt_rmh = mysqli_fetch_array($ambil_data_rumah); 
                            $id_kategori = $dt_rmh['id_kategori']
                            ?>
                            <option value="<?= $id_rumah ?>"> <?= $dt_rmh['kode_blok'] ?> - <?= $dt_rmh['nama_kategori'] ?> - <?= $dt_rmh['nama_site_plan'] ?></option>
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
                        <input type="text" class="form-control" name="id_karyawan" readonly value="<?= $_SESSION['username'] ?>"> 
                        </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr class="text-center">
                          <th class="text-center" width="5%">No</th>
                          <th>Jenis Pembayaran</th>
                          <th>Harga</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $query_detail_harga = mysqli_query($conn, "SELECT * FROM jenis_pembayaran WHERE id_kategori = $id_kategori")or die(mysqli_error($conn));
                        $no = 1; 
                        while ($dh = mysqli_fetch_array($query_detail_harga)) { ?>
                      <tr>
                        <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                        <td><?= $dh['jenis_pembayaran']; ?></td>
                        <td>Rp <?= number_format($dh['harga'] ?? 0, 0, ',', '.') ?></td>
                      </tr>
                      <?php } ?>
                      </tbody>
                    </table>
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
  include '../layout_marketing/footer.php'
  ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php
include '../layout_marketing/js.php'
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