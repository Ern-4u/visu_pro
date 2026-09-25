<?php
require_once '../database/config.php';
$authority = @$_SESSION['peran'];

if ($authority != 'A') {
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
    include '../layout_marketing/css.php';
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
    <?php include '../layout_marketing/navbar.php'; ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php
    include '../layout_marketing/sidebar.php';
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

                <div class="card">
                    <div class="card-header card-header-navy">
                        <h3 class="card-title"><i class="fas fa-home mr-2"></i>DATA KATEGORI RUMAH</h3>
                        <div class="card-tools">
                            <button class="btn btn-sm btn-light" data-toggle="modal" data-target="#modal-tambah">
                                <i class="fas fa-plus mr-1"></i>Tambah Foto
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
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
                                    <tr>
                                        <td>Harga</td>
                                        <td>:</td>
                                        <td class="harga-badge">Rp <?= number_format($dt_k['harga'], 0, ',', '.'); ?></td>
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
    <!-- /.modal Tambah -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->


    <!-- Main Footer -->
    <?php include '../layout_marketing/footer.php'; ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php include '../layout_marketing/js.php'; ?>
<script type="text/javascript">
    $('#modal-edit').on('show.bs.modal', function (e) {
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

    $('#modal-foto').on('show.bs.modal', function (e) {
        var id_karyawan = $(e.relatedTarget).data('id_karyawan');
        $(e.currentTarget).find('input[name="id_karyawan"]').val(id_karyawan);
    });
</script>

</body>
</html>
<?php } ?>