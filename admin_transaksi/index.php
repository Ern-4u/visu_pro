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
       <h3>DATA TRANSAKSI</h3>
      </div>
    </div>
    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">

      <div class="card card-navy card-tabs">
        <div class="card-header p-0 pt-1 border-bottom-0">
          <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="semua-transaksi-tab" data-toggle="pill" href="#semua-transaksi" role="tab" aria-controls="semua-transaksi" aria-selected="true">Semua Transaksi</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="transaksi-berlangsung-tab" data-toggle="pill" href="#transaksi-berlangsung" role="tab" aria-controls="transaksi-berlangsung" aria-selected="false">Transaksi Belansung</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="transaksi-selesai-tab" data-toggle="pill" href="#transaksi-selesai" role="tab" aria-controls="transaksi-selesai" aria-selected="false">Transaksi Selesai</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="transaksi-gagal-tab" data-toggle="pill" href="#transaksi-gagal" role="tab" aria-controls="transaksi-gagal" aria-selected="false">Transaksi Gagal</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-tabs-three-tabContent">
            <div class="tab-pane fade show active" id="semua-transaksi" role="tabpanel" aria-labelledby="semua-transaksi-tab">
              <table id="tbl-semua-transaksi" class="table table-bordered table-striped">
                <thead>
                  <tr class="text-center">
                    <th width="5%">No</th>
                    <th>No Transaksi</th>
                    <th>Pembeli</th>
                    <th>Rumah</th>
                    <th>Marketing</th>
                    <th>Rencana Pembelian</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                    $data_transaksi = mysqli_query($conn, "SELECT transaksi.*, marketing.*, rumah.*,pembeli.*
                                                            FROM transaksi 
                                                            LEFT JOIN marketing ON transaksi.id_karyawan = marketing.id_karyawan
                                                            LEFT JOIN rumah ON transaksi.id_rumah = rumah.id_rumah
                                                            LEFT JOIN pembeli ON transaksi.id_pembeli = pembeli.id_pembeli ORDER BY id_transaksi DESC
                                                            ") or die(mysqli_error($conn));
                  $no = 1; 
                  while ($d = mysqli_fetch_array($data_transaksi)) { ?>
                  <tr>
                    <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                    <td><?= $d['no_transaksi']; ?></td>
                    <td><?= $d['nama_pembeli']; ?></td>
                      <?php 
                      $id_rumah = $d['id_rumah'];
                      $query_dt_rmh = mysqli_query($conn, "SELECT rumah.* , site_plan.nama_site_plan, kategori_rumah.nama_kategori
                        FROM rumah
                        LEFT JOIN site_plan ON rumah.id_site_plan = site_plan.id_site_plan
                        LEFT JOIN kategori_rumah ON rumah.id_kategori = kategori_rumah.id_kategori
                        WHERE rumah.id_rumah = '$id_rumah'
                        ");
                      $rmh = mysqli_fetch_array($query_dt_rmh);
                      ?>
                    <td><?= $rmh['kode_blok']; ?> - <?= $rmh['nama_kategori']  ?> - <?= $rmh['nama_site_plan'] ?></td>
                    <td><?= $d['nama'] ?></td>
                    <td><?= $d['rencana_pembelian'] ?></td>
                    <td><?= tanggal_indonesia($d['tanggal_transaksi']) ?></td>
                    <td><?php
                      if ($d['status_transaksi'] == 'Berlangsung') {
                        ?> <span class="btn btn-warning btn-sm">Berlangsung</span> <?php
                      } elseif ($d['status_transaksi'] == 'Selesai') {
                        ?> <span class="btn btn-success btn-sm">Selesai</span> <?php
                      } else {
                        ?> <span class="btn btn-danger btn-sm">Gagal</span> <?php
                      }
                      ?></td>
                    <td class="text-center">
                        <a href="hapus.php?id=<?= $d['id_transaksi']; ?>&id_rumah=<?= $d['id_rumah'] ?>" 
                        class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')"
                        ><i class="fas fa-trash"></i></a>
                        <a href="detail.php?id=<?= $d['id_transaksi']; ?>" 
                        class="btn btn-success btn-xs"><i class="bi bi-list-ul"></i></a>
                        <a href="edit_status.php?id=<?= $d['id_transaksi']; ?>&id_rumah=<?= $d['id_rumah'] ?>" 
                        class="btn btn-warning btn-xs" onclick="return confirm('Anda yakin akan Menggagalkan Transaksi ini?')">Gagalkan</a>
                    </td>
                  </tr>
                     <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="tab-pane fade" id="transaksi-berlangsung" role="tabpanel" aria-labelledby="transaksi-berlangsung-tab">
              <table id="tbl-transaksi-berlangsung" class="table table-bordered table-striped">
                <thead>
                  <tr class="text-center">
                    <th width="5%">No</th>
                    <th>No Transaksi</th>
                    <th>Pembeli</th>
                    <th>Rumah</th>
                    <th>Marketing</th>
                    <th>Rencana Pembelian</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                  $no = 1; 
                  $data_transaksi_berlangsung = mysqli_query($conn, "SELECT transaksi.*, marketing.*, rumah.*,pembeli.*
                                                          FROM transaksi 
                                                          LEFT JOIN marketing ON transaksi.id_karyawan = marketing.id_karyawan
                                                          LEFT JOIN rumah ON transaksi.id_rumah = rumah.id_rumah
                                                          LEFT JOIN pembeli ON transaksi.id_pembeli = pembeli.id_pembeli
                                                          WHERE transaksi.status_transaksi= 'Berlangsung'  ") or die(mysqli_error($conn));
                  while ($d_ber = mysqli_fetch_array($data_transaksi_berlangsung)) { ?>
                  <tr>
                    <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                    <td><?= $d_ber['no_transaksi']; ?></td>
                    <td><?= $d_ber['nama_pembeli']; ?></td>
                      <?php 
                      $id_rumah = $d_ber['id_rumah'];
                      $query_dt_rmh = mysqli_query($conn, "SELECT rumah.* , site_plan.nama_site_plan, kategori_rumah.nama_kategori
                        FROM rumah
                        LEFT JOIN site_plan ON rumah.id_site_plan = site_plan.id_site_plan
                        LEFT JOIN kategori_rumah ON rumah.id_kategori = kategori_rumah.id_kategori
                        WHERE rumah.id_rumah = '$id_rumah'
                        ");
                      $rmh = mysqli_fetch_array($query_dt_rmh);
                      ?>
                    <td><?= $rmh['kode_blok']; ?> - <?= $rmh['nama_kategori']  ?> - <?= $rmh['nama_site_plan'] ?></td>
                    <td><?= $d_ber['nama'] ?></td>
                    <td><?= $d_ber['rencana_pembelian'] ?></td>
                    <td><?= tanggal_indonesia($d_ber['tanggal_transaksi']) ?></td>
                    <td><?php
                      if ($d_ber['status_transaksi'] == 'Berlangsung') {
                        ?> <span class="btn btn-warning btn-sm">Berlangsung</span> <?php
                      } elseif ($d_ber['status_transaksi'] == 'Selesai') {
                        ?> <span class="btn btn-success btn-sm">Selesai</span> <?php
                      } else {
                        ?> <span class="btn btn-danger btn-sm">Gagal</span> <?php
                      }
                      ?></td>
                    <td class="text-center">
                        <a href="hapus.php?id=<?= $d_ber['id_transaksi']; ?>&id_rumah=<?= $d_ber['id_rumah'] ?>" 
                        class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')"
                        ><i class="fas fa-trash"></i></a>
                        <a href="detail.php?id=<?= $d_ber['id_transaksi']; ?>" 
                        class="btn btn-success btn-xs"><i class="bi bi-list-ul"></i></a>
                        <a href="edit_status.php?id=<?= $d_ber['id_transaksi']; ?>&id_rumah=<?= $d_ber['id_rumah'] ?>" 
                        class="btn btn-warning btn-xs" onclick="return confirm('Anda yakin akan Menggagalkan Transaksi ini?')">Gagalkan</a>
                    </td>
                  </tr>
                     <?php } ?>
                </tbody>
              </table>          
            </div>
            <div class="tab-pane fade" id="transaksi-selesai" role="tabpanel" aria-labelledby="transaksi-selesai-tab">
              <table id="tbl-transaksi-selesai" class="table table-bordered table-striped">
                <thead>
                  <tr class="text-center">
                    <th width="5%">No</th>
                    <th>No Transaksi</th>
                    <th>Pembeli</th>
                    <th>Rumah</th>
                    <th>Marketing</th>
                    <th>Rencana Pembelian</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                  $no = 1; 
                  $data_transaksi_selesai = mysqli_query($conn, "SELECT transaksi.*, marketing.*, rumah.*,pembeli.*
                                                          FROM transaksi 
                                                          LEFT JOIN marketing ON transaksi.id_karyawan = marketing.id_karyawan
                                                          LEFT JOIN rumah ON transaksi.id_rumah = rumah.id_rumah
                                                          LEFT JOIN pembeli ON transaksi.id_pembeli = pembeli.id_pembeli
                                                          WHERE transaksi.status_transaksi= 'Selesai'  ") or die(mysqli_error($conn));
                  while ($d_sls = mysqli_fetch_array($data_transaksi_selesai)) { ?>
                  <tr>
                    <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                    <td><?= $d_sls['no_transaksi']; ?></td>
                    <td><?= $d_sls['nama_pembeli']; ?></td>
                      <?php 
                      $id_rumah = $d_sls['id_rumah'];
                      $query_dt_rmh = mysqli_query($conn, "SELECT rumah.* , site_plan.nama_site_plan, kategori_rumah.nama_kategori
                        FROM rumah
                        LEFT JOIN site_plan ON rumah.id_site_plan = site_plan.id_site_plan
                        LEFT JOIN kategori_rumah ON rumah.id_kategori = kategori_rumah.id_kategori
                        WHERE rumah.id_rumah = '$id_rumah'
                        ");
                      $rmh = mysqli_fetch_array($query_dt_rmh);
                      ?>
                    <td><?= $rmh['kode_blok']; ?> - <?= $rmh['nama_kategori']  ?> - <?= $rmh['nama_site_plan'] ?></td>
                    <td><?= $d_sls['nama'] ?></td>
                    <td><?= $d_sls['rencana_pembelian'] ?></td>
                    <td><?= tanggal_indonesia($d_sls['tanggal_transaksi']) ?></td>
                    <td><?php
                      if ($d_sls['status_transaksi'] == 'Berlangsung') {
                        ?> <span class="btn btn-warning btn-sm">Berlangsung</span> <?php
                      } elseif ($d_sls['status_transaksi'] == 'Selesai') {
                        ?> <span class="btn btn-success btn-sm">Selesai</span> <?php
                      } else {
                        ?> <span class="btn btn-danger btn-sm">Gagal</span> <?php
                      }
                      ?></td>
                    <td class="text-center">
                        <a href="hapus.php?id=<?= $d_sls['id_transaksi']; ?>&id_rumah=<?= $d_sls['id_rumah'] ?>" 
                        class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')"
                        ><i class="fas fa-trash"></i></a>
                        <a href="detail.php?id=<?= $d_sls['id_transaksi']; ?>" 
                        class="btn btn-success btn-xs"><i class="bi bi-list-ul"></i></a>
                        <a href="edit_status.php?id=<?= $d_sls['id_transaksi']; ?>&id_rumah=<?= $d_sls['id_rumah'] ?>" 
                        class="btn btn-warning btn-xs" onclick="return confirm('Anda yakin akan Menggagalkan Transaksi ini?')">Gagalkan</a>
                    </td>
                  </tr>
                     <?php } ?>
                </tbody>
              </table>          
            </div>
            <div class="tab-pane fade" id="transaksi-gagal" role="tabpanel" aria-labelledby="transaksi-gagal-tab">
              <table id="tbl-transaksi-gagal" class="table table-bordered table-striped">
                <thead>
                  <tr class="text-center">
                    <th width="5%">No</th>
                    <th>No Transaksi</th>
                    <th>Pembeli</th>
                    <th>Rumah</th>
                    <th>Marketing</th>
                    <th>Rencana Pembelian</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                  $no = 1; 
                  $data_transaksi_gagal = mysqli_query($conn, "SELECT transaksi.*, marketing.*, rumah.*,pembeli.*
                                                          FROM transaksi 
                                                          LEFT JOIN marketing ON transaksi.id_karyawan = marketing.id_karyawan
                                                          LEFT JOIN rumah ON transaksi.id_rumah = rumah.id_rumah
                                                          LEFT JOIN pembeli ON transaksi.id_pembeli = pembeli.id_pembeli
                                                          WHERE transaksi.status_transaksi= 'Gagal'  ") or die(mysqli_error($conn));
                  while ($d_ggl = mysqli_fetch_array($data_transaksi_gagal)) { ?>
                  <tr>
                    <td width="5%" class="text-center"><?=  $no++ ; ?></td>
                    <td><?= $d_ggl['no_transaksi']; ?></td>
                    <td><?= $d_ggl['nama_pembeli']; ?></td>
                      <?php 
                      $id_rumah = $d_ggl['id_rumah'];
                      $query_dt_rmh = mysqli_query($conn, "SELECT rumah.* , site_plan.nama_site_plan, kategori_rumah.nama_kategori
                        FROM rumah
                        LEFT JOIN site_plan ON rumah.id_site_plan = site_plan.id_site_plan
                        LEFT JOIN kategori_rumah ON rumah.id_kategori = kategori_rumah.id_kategori
                        WHERE rumah.id_rumah = '$id_rumah'
                        ");
                      $rmh = mysqli_fetch_array($query_dt_rmh);
                      ?>
                    <td><?= $rmh['kode_blok']; ?> - <?= $rmh['nama_kategori']  ?> - <?= $rmh['nama_site_plan'] ?></td>
                    <td><?= $d_ggl['nama'] ?></td>
                    <td><?= $d_ggl['rencana_pembelian'] ?></td>
                    <td><?= tanggal_indonesia($d['tanggal_transaksi']) ?></td>
                    <td><?php
                      if ($d_ggl['status_transaksi'] == 'Berlangsung') {
                        ?> <span class="btn btn-warning btn-sm">Berlangsung</span> <?php
                      } elseif ($d_ggl['status_transaksi'] == 'Selesai') {
                        ?> <span class="btn btn-success btn-sm">Selesai</span> <?php
                      } else {
                        ?> <span class="btn btn-danger btn-sm">Gagal</span> <?php
                      }
                      ?></td>
                    <td class="text-center">
                        <a href="hapus.php?id=<?= $d_ggl['id_transaksi']; ?>&id_rumah=<?= $d_ggl['id_rumah'] ?>" 
                        class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')"
                        ><i class="fas fa-trash"></i></a>
                        <a href="detail.php?id=<?= $d_ggl['id_transaksi']; ?>" 
                        class="btn btn-success btn-xs"><i class="bi bi-list-ul"></i></a>
                        <a href="lanjut_bayar.php?id=<?= $d_ggl['id_transaksi']; ?>&id_rumah=<?= $d_ggl['id_rumah'] ?>" 
                        class="btn btn-warning btn-xs" onclick="return confirm('Anda yakin akan Melanjutkan Transaksi ini?')">Lanjutkan</a>
                    </td>
                  </tr>
                     <?php } ?>
                </tbody>
              </table>          
            </div>
          </div>
        </div>
      </div>
                
          <!-- /.card -->
    </div>  
    <!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php 
  $ambil_data_rumah = mysqli_query($conn, "SELECT rumah.*,site_plan.*, kategori_rumah.nama_kategori
            FROM rumah 
            LEFT JOIN site_plan ON rumah.id_site_plan = site_plan.id_site_plan
            LEFT JOIN kategori_rumah ON rumah.id_kategori = kategori_rumah.id_kategori
            WHERE rumah.status = '0'
            ") or die(mysqli_error($conn));
  $ambil_data_pembeli = mysqli_query($conn, "SELECT * FROM pembeli") or die(mysqli_error($conn));
  $ambil_data_marketing = mysqli_query($conn, "SELECT * FROM marketing") or die(mysqli_error($conn));
  ?>

  <!-- modal Tambah -->
      <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">Tambah Data Transaksi</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah.php" method="post">
                <div class="form-group">
                  <label for="">Pilih Rumah Yang Akan Di beli</label>
                  <select name="id_rumah" id="" class="form-control select2" style="width: 100%;" >
                    <option value="">-- Pilih Rumah --</option>
                    <?php 
                    while ($dt_rmh = mysqli_fetch_array($ambil_data_rumah)) { ?>
                      <option value="<?= $dt_rmh['id_rumah'] ?>"><?= $dt_rmh['kode_blok'] ?> - <?= $dt_rmh['nama_kategori'] ?> - <?= $dt_rmh['nama_site_plan'] ?></option>
                    <?php }
                    ?>
                  </select>
                </div>
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
</body>
<script>
  $(function () {
    // Ubah titik (.) menjadi pagar (#)
    $("#tbl-semua-transaksi").DataTable({
      "responsive": true, 
      "lengthChange": false, 
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#tbl-semua-transaksi_wrapper .col-md-6:eq(0)'); 
    // ^ Tambahkan appendTo di atas agar tombol export muncul di posisi yang tepat
  $("#tbl-transaksi-berlangsung").DataTable({
      "responsive": true,
      "pageLength": 5, 
      "lengthChange": false, 
      "autoWidth": false,
      "buttons": ["copy", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#tbl-transaksi-berlangsung_wrapper .col-md-6:eq(0)');

  $("#tbl-transaksi-selesai").DataTable({
      "responsive": true,
      "pageLength": 5, 
      "lengthChange": false, 
      "autoWidth": false,
      "buttons": ["copy", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#tbl-transaksi-selesai_wrapper .col-md-6:eq(0)');

  $("#tbl-transaksi-gagal").DataTable({
      "responsive": true,
      "pageLength": 5, 
      "lengthChange": false, 
      "autoWidth": false,
      "buttons": ["copy", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#tbl-transaksi-gagal_wrapper .col-md-6:eq(0)');
    
  });
</script>
</html>

<?php
}
?>