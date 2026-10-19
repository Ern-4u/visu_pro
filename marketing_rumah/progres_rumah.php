<?php
require_once '../database/config.php';
require_once '../includes/tanggal.php';
$authority = @$_SESSION['peran'];
$id_rumah = @$_GET['id'];

if ($authority != 'M') {
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
  <title>VISU Pro | Dashboard marketing</title>
  <?php
    include '../layout_marketing/css.php';
    $hal = 'rumah';

  ?>
  
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-flex">
<div class="wrapper">

  <?php
  $query_web = mysqli_query($conn, "SELECT * FROM web WHERE id = '1'")or die(mysqli_error($conn));
  $web = mysqli_fetch_array($query_web); 
  ?>
  <!-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble rounded-circle" src="../assets/logo/<?= $web['logo'] ?>" alt="AdminLTELogo" height="60" width="60">
  </div> -->

  <!-- Navbar -->
  <?php
  include '../layout_marketing/navbar.php'
  ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php
  include '../layout_marketing/sidebar.php';

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
      <div class="card card-navy card-tabs">
        <div class="card-header p-0 pt-1 border-bottom-0">
          <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="tahap1-tab" data-toggle="pill" href="#tahap1" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Tahap 1</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tahap2-tab" data-toggle="pill" href="#tahap2" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Tahap 2</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tahap3-tab" data-toggle="pill" href="#tahap3" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Tahap 3</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tahap4-tab" data-toggle="pill" href="#tahap4" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Tahap 4</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-tabs-three-tabContent">
            <div class="tab-pane fade show active" id="tahap1" role="tabpanel" aria-labelledby="tahap1-tab">
             
             <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Persiapan Lahan Dan Pondasi</h3>
                </div>
               <!-- /.card-header -->
                <div class="card-body"> 
                  
                  <table id="tbl-tahap1" class="table table-bordered table-striped text-center">
                    <thead>
                      <tr class="text-center">
                        <th class="text-center" width="5%">No</th>
                        <th>Tanggal Update</th>
                        <th>Presentase</th>
                        <th>Keterangan</th>
                        <th>Foto</th>
                       
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query_ambil_data_tahap_1 = mysqli_query($conn , "SELECT * FROM history_pembangunan WHERE id_rumah = '$id_rumah' AND id_tahap = '1'")or die(mysqli_error($conn));
                      $no = 1; 
                      while ($thp_1 = mysqli_fetch_array($query_ambil_data_tahap_1)) { ?>
                        <tr>
                          <td><?= $no++ ?></td>
                          <td><?= tanggal_indonesia($thp_1['tanggal_update']) ?></td>
                          <td><?= $thp_1['presentase_progres'] ?> %</td>
                          <td><?= $thp_1['keterangan_history'] ?></td>
                          <td>
                            <img src="../assets/fto_history/<?= $thp_1['foto'] ?>" alt="foto_history" width="150px" height="100px">
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
            <div class="tab-pane fade" id="tahap2" role="tabpanel" aria-labelledby="tahap2-tab">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Struktur Dan Atap</h3>
                </div>
               <!-- /.card-header -->
                <div class="card-body"> 
                  
                  <table id="tbl-tahap2" class="table table-bordered table-striped text-center">
                    <thead>
                      <tr class="text-center">
                        <th class="text-center" width="5%">No</th>
                        <th>Tanggal Update</th>
                        <th>Presentase</th>
                        <th>Keterangan</th>
                        <th>Foto</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query_ambil_data_tahap_2 = mysqli_query($conn , "SELECT * FROM history_pembangunan WHERE id_rumah = '$id_rumah' AND id_tahap = '2'")or die(mysqli_error($conn));
                      $no = 1; 
                      while ($thp_2 = mysqli_fetch_array($query_ambil_data_tahap_2)) { ?>
                        <tr>
                          <td><?= $no++ ?></td>
                          <td><?= tanggal_indonesia($thp_2['tanggal_update']) ?></td>
                          <td><?= $thp_2['presentase_progres'] ?> %</td>
                          <td><?= $thp_2['keterangan_history'] ?></td>
                          <td>
                            <img src="../assets/fto_history/<?= $thp_2['foto'] ?>" alt="foto_history" width="150px" height="100px">
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
            <div class="tab-pane fade" id="tahap3" role="tabpanel" aria-labelledby="tahap3-tab">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Instalasi dan Pekerjaan Dalam</h3>
                </div>
               <!-- /.card-header -->
                <div class="card-body"> 
                  
                  <table id="tbl-tahap3" class="table table-bordered table-striped text-center">
                    <thead>
                      <tr class="text-center">
                        <th class="text-center" width="5%">No</th>
                        <th>Tanggal Update</th>
                        <th>Presentase</th>
                        <th>Keterangan</th>
                        <th>Foto</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query_ambil_data_tahap_3 = mysqli_query($conn , "SELECT * FROM history_pembangunan WHERE id_rumah = '$id_rumah' AND id_tahap = '3'")or die(mysqli_error($conn));
                      $no = 1; 
                      while ($thp_3 = mysqli_fetch_array($query_ambil_data_tahap_3)) { ?>
                        <tr>
                          <td><?= $no++ ?></td>
                          <td><?= tanggal_indonesia($thp_3['tanggal_update']) ?></td>
                          <td><?= $thp_3['presentase_progres'] ?> %</td>
                          <td><?= $thp_3['keterangan_history'] ?></td>
                          <td>
                            <img src="../assets/fto_history/<?= $thp_3['foto'] ?>" alt="foto_history" width="150px" height="100px">
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
            <div class="tab-pane fade" id="tahap4" role="tabpanel" aria-labelledby="tahap4-tab">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Finishing</h3>
                </div>
               <!-- /.card-header -->
                <div class="card-body"> 
                  
                  <table id="tbl-tahap4" class="table table-bordered table-striped text-center">
                    <thead>
                      <tr class="text-center">
                        <th class="text-center" width="5%">No</th>
                        <th>Tanggal Update</th>
                        <th>Presentase</th>
                        <th>Keterangan</th>
                        <th>Foto</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query_ambil_data_tahap_4 = mysqli_query($conn , "SELECT * FROM history_pembangunan WHERE id_rumah = '$id_rumah' AND id_tahap = '4'")or die(mysqli_error($conn));
                      $no = 1; 
                      while ($thp_4 = mysqli_fetch_array($query_ambil_data_tahap_4)) { ?>
                        <tr>
                          <td><?= $no++ ?></td>
                          <td><?= tanggal_indonesia($thp_4['tanggal_update']) ?></td>
                          <td><?= $thp_4['presentase_progres'] ?> %</td>
                          <td><?= $thp_4['keterangan_history'] ?></td>
                          <td>
                            <img src="../assets/fto_history/<?= $thp_4['foto'] ?>" alt="foto_history" width="150px" height="100px">
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
           </div>
          </div>
        </div>
    </div>  
    <!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  
  <!-- modal Tambah Tahap 1 -->
      <div class="modal fade" id="modal-tambah-tahap1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">TAMBAH DATA HISTORY TAHAP 1</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <input type="hidden" name="id_rumah" value="<?= $id_rumah ?>">
                    <label for="presentase">Presentase</label>
                    <select name="presentase" id="" class="form-control" required>
                      <option value="">-- Pilih Presentase Progress --</option>
                      <option value="10">10 %</option>
                      <option value="20">20 %</option>
                      <option value="30">30 %</option>
                      <option value="40">40 %</option>
                      <option value="50">50 %</option>
                      <option value="60">60 %</option>
                      <option value="70">70 %</option>
                      <option value="80">80 %</option>
                      <option value="90">90 %</option>
                      <option value="100">100 %</option>
                    </select>
                </div>
                <div class="form-group">
                  <label for="keterangan">Keterangan Progres</label>
                  <input type="text" class="form-control" name="keterangan" required placeholder="Masukan Keterangan Progres Pembangunan">
                </div>
                <div class="form-group">
                  <label for="foto">Upload Foto Perkembangan</label>
                  <input type="file" class="form-control" name="foto" accept=".jpg,.jpeg,.png" required>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_tambah_tahap1" class="btn btn-primary">Simpan</button>
              </div>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Tambah Tahap 1 -->

      <!-- modal Tambah Tahap 2 -->
      <div class="modal fade" id="modal-tambah-tahap2">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">TAMBAH DATA HISTORY TAHAP 2</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <input type="hidden" name="id_rumah" value="<?= $id_rumah ?>">
                    <label for="presentase">Presentase</label>
                    <select name="presentase" id="" class="form-control" required>
                      <option value="">-- Pilih Presentase Progress --</option>
                      <option value="10">10 %</option>
                      <option value="20">20 %</option>
                      <option value="30">30 %</option>
                      <option value="40">40 %</option>
                      <option value="50">50 %</option>
                      <option value="60">60 %</option>
                      <option value="70">70 %</option>
                      <option value="80">80 %</option>
                      <option value="90">90 %</option>
                      <option value="100">100 %</option>
                    </select>
                </div>
                <div class="form-group">
                  <label for="keterangan">Keterangan Progres</label>
                  <input type="text" class="form-control" name="keterangan" required placeholder="Masukan Keterangan Progres Pembangunan">
                </div>
                <div class="form-group">
                  <label for="foto">Upload Foto Perkembangan</label>
                  <input type="file" class="form-control" name="foto" accept=".jpg,.jpeg,.png" required>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_tambah_tahap2" class="btn btn-primary">Simpan</button>
              </div>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Tambah Tahap 2 -->

      <!-- modal Tambah Tahap 3 -->
      <div class="modal fade" id="modal-tambah-tahap3">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">TAMBAH DATA HISTORY TAHAP 3</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <input type="hidden" name="id_rumah" value="<?= $id_rumah ?>">
                    <label for="presentase">Presentase</label>
                    <select name="presentase" id="" class="form-control" required>
                      <option value="">-- Pilih Presentase Progress --</option>
                      <option value="10">10 %</option>
                      <option value="20">20 %</option>
                      <option value="30">30 %</option>
                      <option value="40">40 %</option>
                      <option value="50">50 %</option>
                      <option value="60">60 %</option>
                      <option value="70">70 %</option>
                      <option value="80">80 %</option>
                      <option value="90">90 %</option>
                      <option value="100">100 %</option>
                    </select>
                </div>
                <div class="form-group">
                  <label for="keterangan">Keterangan Progres</label>
                  <input type="text" class="form-control" name="keterangan" required placeholder="Masukan Keterangan Progres Pembangunan">
                </div>
                <div class="form-group">
                  <label for="foto">Upload Foto Perkembangan</label>
                  <input type="file" class="form-control" name="foto" accept=".jpg,.jpeg,.png" required>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_tambah_tahap3" class="btn btn-primary">Simpan</button>
              </div>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Tambah Tahap 3 -->

      <!-- modal Tambah Tahap 4 -->
      <div class="modal fade" id="modal-tambah-tahap4">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">TAMBAH DATA HISTORY TAHAP 4</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <input type="hidden" name="id_rumah" value="<?= $id_rumah ?>">
                    <label for="presentase">Presentase</label>
                    <select name="presentase" id="" class="form-control" required>
                      <option value="">-- Pilih Presentase Progress --</option>
                      <option value="10">10 %</option>
                      <option value="20">20 %</option>
                      <option value="30">30 %</option>
                      <option value="40">40 %</option>
                      <option value="50">50 %</option>
                      <option value="60">60 %</option>
                      <option value="70">70 %</option>
                      <option value="80">80 %</option>
                      <option value="90">90 %</option>
                      <option value="100">100 %</option>
                    </select>
                </div>
                <div class="form-group">
                  <label for="keterangan">Keterangan Progres</label>
                  <input type="text" class="form-control" name="keterangan" required placeholder="Masukan Keterangan Progres Pembangunan">
                </div>
                <div class="form-group">
                  <label for="foto">Upload Foto Perkembangan</label>
                  <input type="file" class="form-control" name="foto" accept=".jpg,.jpeg,.png" required>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_tambah_tahap4" class="btn btn-primary">Simpan</button>
              </div>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Tambah Tahap 4 -->

    
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
<script type="text/javascript">
   $('#modal-o').on('show.bs.modal', function(e) {

   var id_kategori = $(e.relatedTarget).data('id_kategori');
   var nama_kategori = $(e.relatedTarget).data('nama_kategori');
   var luas_bangunan = $(e.relatedTarget).data('luas_bangunan');
   var luas_tanah = $(e.relatedTarget).data('luas_tanah');
   var jumlah_kamar = $(e.relatedTarget).data('jumlah_kamar');
   var harga = $(e.relatedTarget).data('harga');
  var deskripsi = $(e.relatedTarget).data('deskripsi');
  var id_site_plan = $(e.relatedTarget).data('id_site_plan');
  

  
    $(e.currentTarget).find('input[name="id_kategori"]').val(id_kategori);
    $(e.currentTarget).find('input[name="nama_kategori"]').val(nama_kategori);
    $(e.currentTarget).find('input[name="luas_bangunan"]').val(luas_bangunan);
    $(e.currentTarget).find('input[name="luas_tanah"]').val(luas_tanah);
    $(e.currentTarget).find('input[name="jumlah_kamar"]').val(jumlah_kamar);
    $(e.currentTarget).find('input[name="harga"]').val(harga);
    $(e.currentTarget).find('textarea[name="deskripsi"]').val(deskripsi);
    $(e.currentTarget).find('select[name="id_site_plan"]').val(id_site_plan);
   });


  $('#modal-ko').on('show.bs.modal', function(e) {

   var id_karyawan = $(e.relatedTarget).data('id_karyawan');
  
   $(e.currentTarget).find('input[name="id_karyawan"]').val(id_karyawan);
  
  });
 
</script> 


</body>
<script>
  $(function () {
    $("#tbl-tahap1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false
    }).buttons().container().appendTo('#tbl-tahap1_wrapper .col-md-6:eq(0)');
    
    $("#tbl-tahap2").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false
    }).buttons().container().appendTo('#tbl-tahap2_wrapper .col-md-6:eq(0)');
    
    $("#tbl-tahap3").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false
    }).buttons().container().appendTo('#tbl-tahap3_wrapper .col-md-6:eq(0)');
    
    $("#tbl-tahap4").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false
    }).buttons().container().appendTo('#tbl-tahap4_wrapper .col-md-6:eq(0)');
    
    
  });
</script>
</html>
<?php 
} ?>