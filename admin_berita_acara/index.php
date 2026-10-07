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
    $hal = 'berita_acara';
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

  $data_berita_acara = mysqli_query($conn, "SELECT ba.*, t.*,p.*,k.nama_kategori,s.nama_site_plan
                                      FROM berita_acara ba 
                                      LEFT JOIN transaksi t ON ba.id_transaksi = t.id_transaksi
                                      LEFT JOIN pembeli p ON t.id_pembeli = p.id_pembeli
                                      LEFT JOIN rumah r ON t.id_rumah = r.id_rumah
                                      LEFT JOIN kategori_rumah k ON r.id_kategori = k.id_kategori
                                      LEFT JOIN site_plan s ON r.id_site_plan = s.id_site_plan ")or die(mysqli_error($conn));
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
              <div class="card-header" style="background-color: #001F3F; color: white;">
                <h3 class="card-title">DATA PEMBELI</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="card-tools">
                  <button class="btn mb-3 btn-sm" style="background-color: #001F3F; color: white;" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i>Tambah Data</button>
                  <a href="export.php" class="btn mb-3 btn-sm" style="background-color: #001F3F; color: white;"><i class="fas fa-file-download"></i> Export Excel</a>
                </div>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr class="text-center">
                    <th width="5%">No</th>
                    <th>Nomor Surat</th>
                    <th>Tanggal Surat</th>
                    <th>Nama Penerima</th>
                   
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                  $no = 1; 
                  while ($d = mysqli_fetch_array($data_berita_acara)) { ?>
                    <tr>
                      <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                      <td><?= $d['no_berita_acara']; ?></td>
                      <td><?= $d['tanggal_serah_terima']; ?></td>
                      <td><?= $d['nama_pembeli'] ?></td>
                      <td class="text-center">
                        <a href="pdf.php" class="btn btn-info btn-xs" target="_blank">Generate</a>
                        <a href="hapus.php?id=<?= $d['id_berita_acara']; ?>" 
                        class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')"
                        ><i class="fas fa-trash"></i></a>
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
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">TAMBAH BERITA ACARA</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="id_transaksi">Pilih Transaksi</label>
                    <select name="id_transaksi" id="" class="form-control">
                    <option value="">-- Pilih Transaksi --</option>
                    <?php 
                    $ambil_transaksi = mysqli_query($conn, "SELECT t.*,p.* 
                                                            FROM transaksi t
                                                            LEFT JOIN pembeli p ON t.id_pembeli = p.id_pembeli
                                                            WHERE t.status_transaksi = 'Selesai'
                                                            ")or die(mysqli_error($conn));
                    while ($d_trans = mysqli_fetch_array($ambil_transaksi)) { ?>
                      <option value="<?= $d_trans['id_transaksi'] ?>"><?= $d_trans['nama_pembeli'] ?> | <?= $d_trans['kontak'] ?></option>
                    <?php }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="jml_kunci">Jumlah Kunci</label>
                    <input type="number" name="jml_kunci" class="form-control" id="jml_kunci" placeholder="Masukan Jumlah Kunci" required>
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
<script type="text/javascript">
   $('#modal-edit').on('show.bs.modal', function(e) {

   var id_pembeli = $(e.relatedTarget).data('id_pembeli');
   var nik = $(e.relatedTarget).data('nik');
   var nama_pembeli = $(e.relatedTarget).data('nama_pembeli');
   var pasangan = $(e.relatedTarget).data('pasangan');
   var alamat = $(e.relatedTarget).data('alamat');
   var kontak = $(e.relatedTarget).data('kontak');
  

  
    $(e.currentTarget).find('input[name="id_pembeli"]').val(id_pembeli);
    $(e.currentTarget).find('input[name="nama_pembeli"]').val(nama_pembeli);
    $(e.currentTarget).find('input[name="pasangan"]').val(pasangan);
    $(e.currentTarget).find('input[name="alamat"]').val(alamat);
    $(e.currentTarget).find('input[name="kontak"]').val(kontak);
    $(e.currentTarget).find('input[name="nik"]').val(nik); 
   });


  $('#modal-brosur').on('show.bs.modal', function(e) {

   var id_site_plan = $(e.relatedTarget).data('id_site_plan');
  
   $(e.currentTarget).find('input[name="id_site_plan"]').val(id_site_plan);
  
  });
 
</script> 


</body>
</html>
<?php 
} ?>