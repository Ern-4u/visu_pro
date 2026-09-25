<?php
require_once '../database/config.php';
$authority = @$_SESSION['peran'];

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
  <title>VISU Pro | Dashboard admin</title>
  <?php
    include '../layout_marketing/css.php';
    $hal = 'site_plan';
  ?>
  
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-flex">
<div class="wrapper">

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
  include '../layout_marketing/sidebar.php';

  $data_site_plan = mysqli_query($conn, "SELECT * FROM site_plan")or die(mysqli_error($conn));
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
                <h3 class="card-title"> DATA SITE PLAN</h3>
                <div class="card-tools">
                  <a href="export.php" class="btn btn-light btn-sm"><i class="fas fa-file-download"></i> Export Excel</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr class="text-center">
                    <th width="5%">No</th>
                    <th>Nama Perumahan</th>
                    <th>Lokasi</th>
                    <th>Kontak PJ</th>
                    <th>Brosur</th>
                    <th>Sosial Media</th>
                    
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                  $no = 1; 
                  while ($d = mysqli_fetch_array($data_site_plan)) { ?>
                    <tr>
                      <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                      <td><?= $d['nama_site_plan']; ?></td>
                      <td><?= $d['lokasi']; ?></td>
                      <td><a href="https://wa.me/<?= $d['penanggung_jawab']; ?>?text=Hai Bolehkah saya bertanya tentang Rumah?" target="_blank" type="button"  class="btn btn-success btn-xs">
                           <i class="bi bi-whatsapp"></i><?= $d['penanggung_jawab'];?>                           
                      </a></td>
                      <td class="text-center"> <?php if ($d['brosur']== '') {?>
                        <button type="button" class="btn btn-default btn-xs"
                        data-target="#modal-brosur"
                        data-toggle="modal"
                        data-id_site_plan="<?= $d['id_site_plan'] ?>"
                        >Brosur Belum Diupload</button>
                      <?php } else { ?>
                      <a href="../assets/brosur/<?= $d['brosur']; ?>" target="_blank"><img src="../assets/brosur/<?= $d['brosur']; ?>" alt="brosur" width="50px" height="100px"></a>
                      <?php }?>
                      </td>
                      <td class="text-center">
                        <a href="https://instagram.com/<?= $d['ig'] ?>" target="_blank" class="btn btn-xs"><img
                            src="https://cdn.jsdelivr.net/gh/glincker/thesvg@main/public/icons/instagram/default.svg"
                            alt="Instagram"
                            width="25"
                            height="25"/></i></a>
                        <a href="https://www.tiktok.com/<?= $d['tiktok'] ?>" target="_blank" class="btn btn-xs"><img
                          src="https://cdn.jsdelivr.net/gh/glincker/thesvg@main/public/icons/tiktok/light.svg"
                          alt="TikTok"
                          width="24"
                          height="24"
                        /></a>
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
   $('#modal-edit').on('show.bs.modal', function(e) {

   var id_site_plan = $(e.relatedTarget).data('id_site_plan');
   var nama_site_plan = $(e.relatedTarget).data('nama_site_plan');
   var lokasi = $(e.relatedTarget).data('lokasi');
   var penanggung_jawab = $(e.relatedTarget).data('penanggung_jawab');
   var ig = $(e.relatedTarget).data('ig');
   var tiktok = $(e.relatedTarget).data('tiktok');
  

  
    $(e.currentTarget).find('input[name="id_site_plan"]').val(id_site_plan);
    $(e.currentTarget).find('input[name="nama_site_plan"]').val(nama_site_plan);
    $(e.currentTarget).find('textarea[name="lokasi"]').val(lokasi);
    $(e.currentTarget).find('input[name="penanggung_jawab"]').val(penanggung_jawab);
    $(e.currentTarget).find('input[name="ig"]').val(ig);
    $(e.currentTarget).find('input[name="tiktok"]').val(tiktok); 
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