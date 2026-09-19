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
    $hal = 'site_plan';
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
  include '../layout_admin/sidebar.php';

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
              <div class="card-header">
                <h3 class="card-title">Daftar Data Site Plan</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i>Tambah Data</button>
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-import"><i class="fas fa-file-excel"></i> Import Excel</button>
                <a href="export.php" class="btn btn-info mb-3"><i class="fas fa-file-download"></i> Export Excel</a>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr class="text-center">
                    <th width="5%">No</th>
                    <th>Nama Perumahan</th>
                    <th>Lokasi</th>
                    <th>Kontak PJ</th>
                    <th>Brosur</th>
                    <th>Sosial Media</th>
                    <th>Aksi</th>
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
                      <td><a href="https://wa.me/<?= $d['penanggung_jawab']; ?>?text=Hai Bolehkah saya bertanya tentang Rumah?" target="_blank" type="button" class="btn btn-success btn-xs">
                           <i class="bi bi-whatsapp"></i><?= $d['penanggung_jawab'];?>                           
                      </a></td>
                      <td class="text-center"> <?php if ($d['brosur']== '') {?>
                        <button type="button" class="btn btn-danger btn-xs"
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
                        <a href="https://instagram.com/<?= $d['ig'] ?>" target="_blank" class="btn btn-success btn-xs"><i class="bi bi-instagram"></i> Instagram</a>
                        <a href="https://www.tiktok.com/<?= $d['tiktok'] ?>" target="_blank" class="btn btn-success btn-xs"><i class="bi bi-tiktok"></i> Tiktok</a>
                      </td>
                      <td class="text-center">
                        <a href="hapus.php?id=<?= $d['id_site_plan']; ?>" 
                        class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')"
                        ><i class="fas fa-trash"></i></a>
                        <button class="btn btn-warning btn-xs" type="submit" 
                        data-target="#modal-edit" 
                        data-id_site_plan="<?= $d['id_site_plan'] ?>" 
                        data-nama_site_plan="<?= $d['nama_site_plan']?>" 
                        data-penanggung_jawab="<?= $d['penanggung_jawab']?>"
                        data-ig="<?= $d['ig']?>"
                        data-tiktok="<?= $d['tiktok']?>"
                        data-lokasi="<?= $d['lokasi'] ?>"
                        data-toggle="modal">
                        <i class="fas fa-edit"> </i>
                      </button>
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
            <div class="modal-header">
              <h4 class="modal-title">Tambah Data Kategori Rumah</h4>
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
            <div class="modal-header">
              <h4 class="modal-title">Import Data Site Plan</h4>
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
            <div class="modal-header">
              <h4 class="modal-title">Edit Data Kategori Rumah</h4>
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
            <div class="modal-header">
              <h4 class="modal-title">Upload Brosur</h4>
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