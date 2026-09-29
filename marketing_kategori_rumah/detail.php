<?php
require_once '../database/config.php';
$authority = @$_SESSION['peran'];

if ($authority != 'M') {
    echo '<script> alert("Anda Tidak boleh masuk ke halaman ini!!!!");
  window.location.href="../logout.php" </script>';
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VISU Pro | Dashboard admin</title>
    <?php
    include '../layout_admin/css.php';
    $hal = 'kategori_rumah';
    ?>
    <style>
        .card-header-navy {
            background-color: #001F3F;
            color: #fff;
        }
        .detail-table td {
            padding: 6px 8px;
            vertical-align: top;
        }
        .detail-table td:first-child {
            font-weight: 600;
            color: #444;
        }
        .foto-card {
            border-radius: 8px;
            overflow: hidden;
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .foto-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,.25);
        }
        .foto-card img {
            height: 220px;
            object-fit: cover;
        }
        .foto-card .card-img-overlay {
            background: linear-gradient(to top, rgba(0,0,0,.75) 0%, rgba(0,0,0,0) 60%);
        }
        .foto-card .card-title {
            margin-bottom: 0;
            font-size: 1rem;
            color: #fff !important;
        }
        .harga-badge {
            font-size: 1.1rem;
            font-weight: 700;
            color: #001F3F;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-flex">
<div class="wrapper">

    <?php
    $query_web = mysqli_query($conn, "SELECT * FROM web WHERE id = '1'") or die(mysqli_error($conn));
    $web = mysqli_fetch_array($query_web);
    ?>
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__wobble rounded-circle" src="../assets/logo/<?= $web['logo'] ?>" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <?php include '../layout_admin/navbar.php'; ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php
    include '../layout_admin/sidebar.php';
    $id_kategori = @$_GET['id'];
    $data_kategori_rumah = mysqli_query($conn, "SELECT * FROM kategori_rumah WHERE id_kategori = '$id_kategori'") or die(mysqli_error($conn));
    $dt_k = mysqli_fetch_array($data_kategori_rumah);
    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Detail Kategori Rumah</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Kategori Rumah</a></li>
                            <li class="breadcrumb-item active"><?= $dt_k['nama_kategori'] ?></li>
                        </ol>
                    </div>
                </div>
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
                            <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Detail Spek Rumah</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Detail Harga Rumah</a>
                        </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-three-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                            <div class="row">
                            <div class="col-lg-6">
                                <table class="table table-borderless detail-table mb-0">
                                    <tr>
                                        <td width="35%">Nama Cluster</td>
                                        <td width="5%">:</td>
                                        <td><?= $dt_k['nama_kategori'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Luas Bangunan</td>
                                        <td>:</td>
                                        <td><?= $dt_k['luas_bangunan'] ?> m&sup2;</td>
                                    </tr>
                                    <tr>
                                        <td>Luas Tanah</td>
                                        <td>:</td>
                                        <td><?= $dt_k['luas_tanah'] ?> m&sup2;</td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Kamar</td>
                                        <td>:</td>
                                        <td><?= $dt_k['jumlah_kamar'] ?></td>
                                    </tr>
                                </table>
                            </div>
                            <!-- end col -->
                            <div class="col-lg-6">
                                <div class="form-group mb-0">
                                    <label for="deskripsi"><strong>Deskripsi Rumah</strong></label>
                                    <textarea class="form-control" rows="6" id="deskripsi" readonly><?= $dt_k['deskripsi'] ?></textarea>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->

                        <hr>

                        <h5 class="mb-3"><i class="fas fa-images mr-2"></i>Foto Spesifikasi Rumah</h5>
                        <div class="card-tools">
                            
                        </div>
                        <div class="row">
                            <?php
                            $query_foto_rmh = mysqli_query($conn, "SELECT * FROM foto_rumah WHERE id_kategori = '$id_kategori'") or die(mysqli_error($conn));

                            if (mysqli_num_rows($query_foto_rmh) === 0) {
                                echo '<div class="col-12"><p class="text-muted">Belum ada foto untuk kategori rumah ini.</p></div>';
                            }

                            while ($dt_f = mysqli_fetch_array($query_foto_rmh)) { ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card foto-card mb-0 bg-dark">
                                        <img class="card-img" src="../assets/fto_rumah/<?= $dt_f['foto'] ?>" alt="Foto Spek Rumah">
                                        <div class="card-img-overlay d-flex flex-column justify-content-end p-2">
                                            <h5 class="card-title"><b><?= $dt_f['keterangan_foto'] ?></b></h5>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <!-- end row -->
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                            <div class="row">
                              <div class="col-lg-12">
                                <div class="card">
                                <div class="card-header">
                                    
                                 </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    
                                    <table id="example1" class="table table-bordered table-striped">
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
                                <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                               </div>
                            </div>
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


    <!-- modal Foto -->
    <div class="modal fade" id="modal-foto">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header card-header-navy">
                    <h4 class="modal-title">TAMBAH FOTO RUMAH</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="tambah_foto.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id_kategori" value="<?= $id_kategori ?>">
                        <div class="form-group">
                            <label for="keterangan_foto">Keterangan Foto</label>
                            <input type="text" name="keterangan_foto" class="form-control" id="keterangan_foto" placeholder="Masukan Keterangan Foto" required>
                        </div>
                        <div class="form-group mb-0">
                            <label for="foto">Upload Foto</label>
                            <input type="file" accept=".jpg,.jpeg,.png" name="foto" class="form-control" id="foto" required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" name="btn_tambah" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal Foto -->

    <!-- modal Tambah -->
      <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">TAMBAH DATA PEMBAYARAN RUMAH</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="tambah_pembayaran.php" method="post">
                <div class="form-group">
                    <input type="hidden" name="id_kategori" value="<?= $id_kategori ?>">
                    <label for="jenis_pembayaran">Jenis Pembayaran</label>
                    <input type="text" name="jenis_pembayaran" class="form-control" id="jenis_pembayaran" placeholder="Masukan Jenis Pembayaran Rumah" required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="text" name="harga" class="form-control" id="input-harga" placeholder="Masukan Harga Jenis Pembayaran" required>
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

    <!-- modal Edit -->
      <div class="modal fade" id="modal-edit" >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #001F3F; color: white;">
              <h4 class="modal-title">EDIT DATA HARGA</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <form action="edit_pembayaran.php" method="post">
                <div class="form-group">
                    <input type="hidden" name="id_kategori" value="<?= $id_kategori ?>">
                    <input type="hidden" name="id_jenis_pembayaran">
                    <label for="jenis_pembayaran">Jenis Pembayaran</label>
                    <input type="text" name="jenis_pembayaran" class="form-control" id="jenis_pembayaran" placeholder="Masukan Jenis Pembayaran Rumah" required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="text" name="harga" class="form-control" id="harga" placeholder="Masukan Harga Jenis Pembayaran" required>
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
    

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->


    <!-- Main Footer -->
    <?php include '../layout_admin/footer.php'; ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php include '../layout_admin/js.php'; ?>
<script type="text/javascript">
    // 1. Mengisi data saat Modal Edit dibuka
    $('#modal-edit').on('show.bs.modal', function (e) {
        var id_jenis_pembayaran = $(e.relatedTarget).data('id_jenis_pembayaran');
        var jenis_pembayaran = $(e.relatedTarget).data('jenis_pembayaran');
        var harga = $(e.relatedTarget).data('harga'); // Contoh: 1500000
        var id_kategori_rumah = $(e.relatedTarget).data('id_kategori_rumah');
        
        // Format angka dari database kasih titik saat modal edit terbuka
        var hargaFormatted = new Intl.NumberFormat('id-ID').format(harga);

        $(e.currentTarget).find('input[name="id_jenis_pembayaran"]').val(id_jenis_pembayaran);
        $(e.currentTarget).find('input[name="jenis_pembayaran"]').val(jenis_pembayaran);
        $(e.currentTarget).find('input[name="harga"]').val(hargaFormatted); // Masukkan yang sudah ada titiknya
        $(e.currentTarget).find('input[name="id_kategori_rumah"]').val(id_kategori_rumah);
    });

    $('#modal-foto').on('show.bs.modal', function (e) {
        var id_karyawan = $(e.relatedTarget).data('id_karyawan');
        $(e.currentTarget).find('input[name="id_karyawan"]').val(id_karyawan);
    });

    // 2. Fungsi untuk membuat input otomatis ada titiknya
    function formatRupiah(element) {
        element.addEventListener('input', function() {
            // Bersihkan semua huruf/simbol, ambil angkanya saja
            let angkaMurni = this.value.replace(/[^0-9]/g, '');
            
            if (angkaMurni === '') {
                this.value = '';
                return;
            }
            
            // Format ulang dengan titik
            this.value = new Intl.NumberFormat('id-ID').format(angkaMurni);
        });
    }

    // Terapkan fungsi formatRupiah ke input Tambah dan Edit
    const inputHargaTambah = document.getElementById('input-harga');
    const inputHargaEdit = document.getElementById('harga');

    if (inputHargaTambah) formatRupiah(inputHargaTambah);
    if (inputHargaEdit) formatRupiah(inputHargaEdit);

    // 3. PENTING: Hilangkan titik sebelum form disubmit ke backend (PHP)
    // Ini memastikan script tambah_pembayaran.php & edit_pembayaran.php 
    // menerima angka utuh tanpa titik (contoh: 1500000 bukan 1.500.000)
    $('form').on('submit', function() {
        if ($('#input-harga').length) {
            let valTambah = $('#input-harga').val().replace(/\./g, '');
            $('#input-harga').val(valTambah);
        }
        if ($('#harga').length) {
            let valEdit = $('#harga').val().replace(/\./g, '');
            $('#harga').val(valEdit);
        }
    });
</script>
</body>
</html>
<?php } ?>


                