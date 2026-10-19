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
                    <th>Denah Komplek</th>
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
                      <td class="text-center">
                        <?php if (!empty($d['foto_site_plan'])) { ?>
                          <button type="button" class="btn btn-info btn-xs"
                            data-toggle="modal" data-target="#modal-denah"
                            data-foto="<?= htmlspecialchars($d['foto_site_plan']) ?>">
                            <i class="fas fa-search-plus"></i> Lihat Denah
                          </button>
                          <button type="button" class="btn btn-warning btn-xs"
                            data-toggle="modal" data-target="#modal-edit-denah"
                            data-id_edit_denah="<?= $d['id_site_plan'] ?>">
                            <i class="fas fa-edit"></i>
                          </button>
                        <?php } else { ?>
                          <button type="button" class="btn btn-default btn-xs"
                            data-toggle="modal" data-target="#modal-tambah-denah"
                            data-id_tambah_denah="<?= $d['id_site_plan'] ?>">
                            <i class="fas fa-plus"></i> Tambah Denah
                          </button>
                        <?php } ?>
                      </td>
                      <td class="text-center"> <?php if ($d['brosur']== '') {?>
                        <button type="button" class="btn btn-default btn-xs"
                        data-target="#modal-brosur"
                        data-toggle="modal"
                        data-id_site_plan="<?= $d['id_site_plan'] ?>"
                        >Brosur Belum Diupload</button>
                      <?php } else { ?>
                      <button type="button" class="btn btn-warning btn-xs mb-3"
                        data-target="#modal-brosur"
                        data-toggle="modal"
                        data-id_site_plan="<?= $d['id_site_plan'] ?>"
                        ><i class="fas fa-edit"></i>Edit Brosur</button>
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
  <!-- modal Tambah -->
      <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">TAMBAH DATA KATEGORI RUMAH</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama_site_plan">Nama Perumahan</label>
                    <input type="text" name="nama_site_plan" class="form-control" id="nama_site_plan" placeholder="Masukan Nama Site Plan Perumahan" required>
                </div>
                <div class="form-group">
                    <label for="lokasi">Lokasi Site Plan</label>
                    <textarea type="text" name="lokasi" class="form-control" id="lokasi" required>
                    </textarea>
                </div>
                <div class="form-group">
                    <label for="penanggung_jawab">Kontak Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" class="form-control" id="penanggung_jawab" placeholder="Masukan Kontak Penanggung Jawab Proyek" required>
                </div>
                <div class="form-group">
                    <label for="ig">Instagram Proyek</label>
                    <input type="text" name="ig" class="form-control" id="ig" placeholder="Masukan Instagram Proyek Perumahan" required>
                </div>
                <div class="form-group">
                    <label for="tiktok">Tiktok Proyek</label>
                    <input type="text" name="tiktok" class="form-control" id="tiktok" placeholder="Masukan Tiktok Proyek Perumahan" required>
                </div>
                <div class="form-group">
                    <label for="brosur">Upload Brosur Proyek</label>
                    <input type="file" class="form-control" name="brosur" id="brosur">
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

      <!-- modal Import -->
      <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">IMPORT DATA SITEPLAN</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="import.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file_excel">Pilih File Excel (.xls, .xlsx, .csv)</label>
                    <input type="file" name="file_excel" class="form-control" id="file_excel" required accept=".xls, .xlsx, .csv">
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_import" class="btn btn-success">Import Data</button>
              </div>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Import -->

      <!-- modal Edit -->
      <div class="modal fade" id="modal-edit" >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">EDIT DATA SITE PLAN</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <form action="edit.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                     <input type="hidden" name="id_site_plan">
                    <label for="nama_site_plan">Nama Perumahan</label>
                    <input type="text" name="nama_site_plan" class="form-control" id="nama_site_plan" placeholder="Masukan Nama Site Plan Perumahan" required>
                </div>
                <div class="form-group">
                    <label for="lokasi">Lokasi Site Plan</label>
                    <textarea type="text" name="lokasi" class="form-control" id="lokasi" required>
                    </textarea>
                </div>
                <div class="form-group">
                    <label for="penanggung_jawab">Kontak Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" class="form-control" id="penanggung_jawab" placeholder="Masukan Kontak Penanggung Jawab Proyek" required>
                </div>
                <div class="form-group">
                    <label for="ig">Instagram Proyek</label>
                    <input type="text" name="ig" class="form-control" id="ig" placeholder="Masukan Instagram Proyek Perumahan" required>
                </div>
                <div class="form-group">
                    <label for="tiktok">Tiktok Proyek</label>
                    <input type="text" name="tiktok" class="form-control" id="tiktok" placeholder="Masukan Tiktok Proyek Perumahan" required>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_edit" class="btn btn-primary">Simpan</button>
              </div>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Edit -->

      <!-- modal Bosur -->
      <div class="modal fade" id="modal-brosur">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">UPLOAD BROSUR</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="brosur.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <input type="hidden" class="form-control" name="id_site_plan" id="id_site_plan" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="brosur">Upload Brosur</label>
                    <input type="file" accept=".jpg,.jpeg,.png,.pdf" name="brosur" class="form-control" id="brosur" placeholder="Upload File" required>
                </div> 
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_brosur" class="btn btn-primary">Simpan</button>
              </div>
              </form>
            </div>  
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Brosur -->

      <!-- modal Tambah Denah -->
      <div class="modal fade" id="modal-tambah-denah">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">TAMBAH FOTO DENAH</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="denah.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <input type="hidden" name="id_tambah_denah" required>
                    <label for="foto_denah">Pilih Foto Denah (.jpg, .jpeg, .png)</label>
                    <input type="file" name="tambah_foto_site_plan" class="form-control" id="foto_site_plan" required accept=".jpg, .jpeg, .png">
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_tambah_denah" class="btn btn-success">Tambah Data</button>
              </div>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Tambah Denah -->
       <!-- modal Edit Denah -->
      <div class="modal fade" id="modal-edit-denah">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">TAMBAH FOTO DENAH</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="denah.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <input type="hidden" name="id_edit_denah" required>
                    <label for="foto_denah">Pilih Foto Denah (.jpg, .jpeg, .png)</label>
                    <input type="file" name="edit_foto_site_plan" class="form-control" id="foto_site_plan" required accept=".jpg, .jpeg, .png">
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="btn_edit_denah" class="btn btn-success">Edit Data</button>
              </div>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Tambah Denah -->

      <!-- modal Lihat Denah -->
      <div class="modal fade" id="modal-denah">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">FOTO DENAH</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body text-center">
              <img id="img-denah" src="" alt="Denah" class="img-fluid">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal Lihat Denah -->
       
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

  $('#modal-tambah-denah').on('show.bs.modal', function(e) {

   var id_tambah_denah = $(e.relatedTarget).data('id_tambah_denah');
  
   $(e.currentTarget).find('input[name="id_tambah_denah"]').val(id_tambah_denah);
  
  });
  $('#modal-edit-denah').on('show.bs.modal', function(e) {

   var id_edit_denah = $(e.relatedTarget).data('id_edit_denah');
  
   $(e.currentTarget).find('input[name="id_edit_denah"]').val(id_edit_denah);
  
  });
  $('#modal-denah').on('show.bs.modal', function(e) {

   var id_site_plan = $(e.relatedTarget).data('id_site_plan');
  
   $(e.currentTarget).find('input[name="id_site_plan"]').val(id_site_plan);
  
  });

  $('#modal-denah').on('show.bs.modal', function (e) {
  var foto = $(e.relatedTarget).data('foto');
  $('#img-denah').attr('src', '../assets/foto_site_plan/' + foto);
  });
 
</script> 


</body>
</html>
<?php 
} ?>