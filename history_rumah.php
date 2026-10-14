<?php
require_once 'database/config.php';
require_once 'includes/tanggal.php';

// Fetch web configuration
$query_web = mysqli_query($conn, "SELECT * FROM web WHERE id = 1") or die(mysqli_error($conn));
$result_web = mysqli_fetch_assoc($query_web);

$nama_proyek = $result_web['nama_proyek'] ?? 'Real Estate';
$alamat_proyek = $result_web['alamat'] ?? '';
$cp_proyek = $result_web['cp'] ?? '';
$ig_proyek = $result_web['instagram'] ?? '';
$tt_proyek = $result_web['tiktok'] ?? '';
$logo_filename = $result_web['logo'] ?? '';

// Ambil list semua rumah untuk pilihan dropdown
$query_list_rumah = mysqli_query($conn, "SELECT r.id_rumah, r.kode_blok, sp.nama_site_plan, k.nama_kategori
    FROM rumah r
    LEFT JOIN site_plan sp ON r.id_site_plan = sp.id_site_plan
    LEFT JOIN kategori_rumah k ON r.id_kategori = k.id_kategori
    ORDER BY sp.nama_site_plan ASC, r.kode_blok ASC") or die(mysqli_error($conn));

$data_rumah_options = [];
while ($row_rmh = mysqli_fetch_assoc($query_list_rumah)) {
    $data_rumah_options[] = $row_rmh;
}

// Inisialisasi variabel pencarian
$input_id_rumah = isset($_GET['id_rumah']) ? intval($_GET['id_rumah']) : (isset($_POST['id_rumah']) ? intval($_POST['id_rumah']) : 0);
$input_kontak   = isset($_GET['kontak']) ? trim($_GET['kontak']) : (isset($_POST['kontak']) ? trim($_POST['kontak']) : '');

$has_searched = (!empty($input_id_rumah) && !empty($input_kontak));
$error_message = '';
$data_validasi = null;
$list_tahap = [];
$total_progres_rata = 0;
$progres_per_tahap = [];

if ($has_searched) {
    // Normalisasi input nomor HP (buang spasi, strip, titik, dsb)
    $kontak_clean = preg_replace('/[^0-9]/', '', $input_kontak);
    // Support variasi leading 0 vs 62
    $kontak_variants = [$kontak_clean];
    if (strpos($kontak_clean, '62') === 0) {
        $kontak_variants[] = '0' . substr($kontak_clean, 2);
    } elseif (strpos($kontak_clean, '0') === 0) {
        $kontak_variants[] = '62' . substr($kontak_clean, 1);
    }

    $escaped_variants = "'" . implode("','", array_map(function($v) use ($conn) {
        return mysqli_real_escape_string($conn, $v);
    }, $kontak_variants)) . "'";

    // Cek kecocokan data pembeli & rumah di tabel transaksi & pembeli
    $query_cek = mysqli_query($conn, "SELECT 
            t.id_transaksi, t.no_transaksi, t.tanggal_transaksi, t.status_transaksi, t.rencana_pembelian,
            p.id_pembeli, p.nama_pembeli, p.kontak, p.alamat AS alamat_pembeli,
            r.id_rumah, r.kode_blok, r.status AS status_rumah,
            sp.nama_site_plan, sp.lokasi AS lokasi_site_plan, sp.penanggung_jawab,
            k.nama_kategori, k.luas_bangunan, k.luas_tanah, k.jumlah_kamar
        FROM transaksi t
        JOIN pembeli p ON t.id_pembeli = p.id_pembeli
        JOIN rumah r ON t.id_rumah = r.id_rumah
        LEFT JOIN site_plan sp ON r.id_site_plan = sp.id_site_plan
        LEFT JOIN kategori_rumah k ON r.id_kategori = k.id_kategori
        WHERE t.id_rumah = '$input_id_rumah' 
          AND (
            p.kontak IN ($escaped_variants)
            OR REPLACE(REPLACE(REPLACE(p.kontak, '-', ''), ' ', ''), '+', '') IN ($escaped_variants)
          )
        LIMIT 1") or die(mysqli_error($conn));

    if ($query_cek && mysqli_num_rows($query_cek) > 0) {
        $data_validasi = mysqli_fetch_assoc($query_cek);

        // Ambil master tahap pembangunan
        $q_tahap = mysqli_query($conn, "SELECT * FROM tahap_pembangunan ORDER BY urutan_tahap ASC") or die(mysqli_error($conn));
        
        $total_persen_sum = 0;
        $count_tahap = 0;

        while ($tahap_item = mysqli_fetch_assoc($q_tahap)) {
            $id_t = $tahap_item['id_tahap'];
            
            // Ambil history pembangunan per tahap untuk rumah ini
            $q_history = mysqli_query($conn, "SELECT * FROM history_pembangunan 
                WHERE id_rumah = '$input_id_rumah' AND id_tahap = '$id_t' 
                ORDER BY tanggal_update ASC, id_history_pembangunan ASC") or die(mysqli_error($conn));
            
            $history_entries = [];
            $max_progres = 0;
            while ($h = mysqli_fetch_assoc($q_history)) {
                $history_entries[] = $h;
                if ($h['presentase_progres'] > $max_progres) {
                    $max_progres = $h['presentase_progres'];
                }
            }

            $tahap_item['entries'] = $history_entries;
            $tahap_item['current_progres'] = $max_progres;
            $list_tahap[] = $tahap_item;

            $total_persen_sum += $max_progres;
            $count_tahap++;
        }

        // Rata-rata progress keseluruhan (atau jika 5 tahap dengan bobot setara)
        if ($count_tahap > 0) {
            $total_progres_rata = round($total_persen_sum / $count_tahap);
            if ($total_progres_rata > 100) $total_progres_rata = 100;
        }

    } else {
        $error_message = "Kombinasi Rumah dan Nomor HP tidak ditemukan atau belum terdaftar dalam sistem transaksi. Pastikan data yang dimasukkan sudah sesuai dengan saat pendaftaran pembelian.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Progres Pembangunan Rumah - <?= htmlspecialchars($nama_proyek) ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
    
    <style>
        :root {
            --primary-navy: #0A192F;
            --secondary-navy: #172A45;
            --mustrad-gold: #CEA751;
            --text-light: #CCD6F6;
            --text-white: #FFFFFF;
            --gold-accent: #C5A880;
            --bg-soft: #F4F6F9;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-soft);
            color: #333333;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Montserrat', sans-serif;
        }

        /* Navbar */
        .navbar {
            background-color: var(--primary-navy);
            padding: 0.85rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .navbar-brand {
            color: var(--text-white) !important;
            font-weight: 700;
            font-size: 1.3rem;
            letter-spacing: 0.5px;
        }
        .navbar-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .navbar-logo img {
            max-height: 40px;
            width: auto;
            object-fit: contain;
        }
        .btn-back-nav {
            background: rgba(255,255,255,0.1);
            color: var(--text-light);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-back-nav:hover {
            background-color: var(--mustrad-gold);
            color: var(--primary-navy);
            border-color: var(--mustrad-gold);
        }

        /* Hero Header */
        .page-hero {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--secondary-navy) 100%);
            color: #ffffff;
            padding: 50px 0 40px 0;
            position: relative;
        }
        .page-badge {
            background-color: rgba(206, 167, 81, 0.2);
            color: var(--mustrad-gold);
            border: 1px solid rgba(206, 167, 81, 0.4);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 12px;
        }

        /* Search Card Form */
        .search-container-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid #eef2f7;
            margin-top: -30px;
            position: relative;
            z-index: 2;
        }
        .form-label {
            font-weight: 600;
            color: var(--primary-navy);
            font-size: 0.9rem;
            margin-bottom: 6px;
        }
        .form-control, .form-select {
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--mustrad-gold);
            box-shadow: 0 0 0 0.25rem rgba(206, 167, 81, 0.25);
        }

        /* Select2 Customization */
        .select2-container--bootstrap4 .select2-selection--single {
            height: 48px !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            display: flex !important;
            align-items: center !important;
            background-color: #ffffff !important;
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            color: #333333 !important;
            font-size: 0.95rem !important;
            line-height: normal !important;
            padding-left: 0 !important;
        }
        .select2-container--bootstrap4.select2-container--focus .select2-selection--single {
            border-color: var(--mustrad-gold) !important;
            box-shadow: 0 0 0 0.25rem rgba(206, 167, 81, 0.25) !important;
        }
        .select2-dropdown {
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            overflow: hidden !important;
            z-index: 9999 !important;
        }
        .select2-container--bootstrap4 .select2-search--dropdown {
            padding: 8px !important;
        }
        .select2-container--bootstrap4 .select2-search--dropdown .select2-search__field {
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            padding: 8px 12px !important;
            font-size: 0.9rem !important;
        }
        .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary-navy) !important;
            color: #ffffff !important;
        }
        .select2-container--bootstrap4 .select2-results__option[aria-selected=true] {
            background-color: rgba(206, 167, 81, 0.15) !important;
            color: var(--primary-navy) !important;
            font-weight: 600 !important;
        }
        .btn-check-progress {
            background-color: var(--primary-navy);
            color: #ffffff;
            font-weight: 700;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }
        .btn-check-progress:hover {
            background-color: var(--mustrad-gold);
            color: var(--primary-navy);
            transform: translateY(-2px);
        }

        /* Info Card Buyer & Unit */
        .buyer-unit-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.05);
            border: 1px solid #eef2f7;
            margin-bottom: 25px;
        }
        .buyer-unit-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #edf2f7;
            padding-bottom: 15px;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .buyer-name {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--primary-navy);
            margin: 0;
        }
        .unit-badge-highlight {
            background: rgba(206, 167, 81, 0.15);
            color: #8c6b1b;
            border: 1px solid rgba(206, 167, 81, 0.3);
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.9rem;
        }

        /* Overall Progress Card */
        .overall-progress-card {
            background: linear-gradient(135deg, #0A192F 0%, #172A45 100%);
            color: #ffffff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 8px 25px rgba(10,25,47,0.15);
            margin-bottom: 30px;
        }
        .overall-num {
            font-family: 'Montserrat', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            color: var(--mustrad-gold);
            line-height: 1;
        }
        .progress-main-bar {
            height: 14px;
            border-radius: 10px;
            background: rgba(255,255,255,0.15);
            overflow: hidden;
            margin-top: 15px;
        }
        .progress-main-bar .progress-bar {
            background: linear-gradient(90deg, #CEA751 0%, #25D366 100%);
            border-radius: 10px;
            font-weight: 700;
        }

        /* Step Stages Accordion / Cards */
        .stage-box {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #eef2f7;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            transition: all 0.3s;
        }
        .stage-box:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        }
        .stage-header {
            padding: 18px 22px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
            flex-wrap: wrap;
            gap: 12px;
        }
        .stage-num-badge {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .stage-num-badge.completed {
            background: #e6f7ee;
            color: #198754;
            border: 2px solid #198754;
        }
        .stage-num-badge.in-progress {
            background: #fff8e6;
            color: #d97706;
            border: 2px solid #d97706;
        }
        .stage-num-badge.pending {
            background: #f1f5f9;
            color: #94a3b8;
            border: 2px solid #cbd5e1;
        }
        .stage-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary-navy);
            margin: 0;
        }
        .stage-progress-badge {
            font-size: 0.85rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
        }

        /* History Table & Photo */
        .history-table th {
            background: #f8fafc;
            color: var(--primary-navy);
            font-size: 0.82rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .history-table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }
        .img-history-thumb {
            width: 110px;
            height: 75px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            transition: transform 0.25s, box-shadow 0.25s;
            border: 1px solid #e2e8f0;
        }
        .img-history-thumb:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Footer */
        .footer {
            background-color: var(--primary-navy);
            color: var(--text-light);
            padding: 50px 0 20px;
            margin-top: 60px;
        }
        .footer-brand {
            color: var(--text-white);
            font-weight: 700;
            font-size: 1.3rem;
            text-decoration: none;
        }
        .copyright {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 35px;
            padding-top: 20px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a href="index.php" class="navbar-logo">
                <?php if (!empty($logo_filename) && file_exists('assets/logo/' . $logo_filename)) : ?>
                    <img src="assets/logo/<?= htmlspecialchars($logo_filename) ?>" alt="Logo <?= htmlspecialchars($nama_proyek) ?>">
                <?php else : ?>
                    <i class="bi bi-building fs-3 text-warning"></i>
                <?php endif; ?>
                <span class="navbar-brand ms-2"><?= htmlspecialchars($nama_proyek) ?></span>
            </a>
            <div class="ms-auto d-flex align-items-center gap-2">
                <a href="index.php" class="btn-back-nav"><i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda</a>
            </div>
        </div>
    </nav>

    <!-- Hero Header -->
    <section class="page-hero">
        <div class="container text-center">
            <span class="page-badge">
                <i class="bi bi-shield-check me-1"></i> Layanan Transparansi Pembeli
            </span>
            <h1 class="fw-bold mb-2">Pantau Progres Pembangunan Rumah</h1>
            <p class="text-white-50 mx-auto" style="max-width: 650px;">
                Cek perkembangan pengerjaan unit hunian Anda secara berkala mulai dari pondasi hingga tahap finishing dan serah terima kunci.
            </p>
        </div>
    </section>

    <!-- Search Form Card -->
    <div class="container mb-5">
        <div class="search-container-card">
            <form action="history_rumah.php" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5 col-md-6">
                        <label for="selectRumah" class="form-label">
                            <i class="bi bi-house-door-fill text-warning me-1"></i> Pilih Unit Rumah Yang Dibeli
                        </label>
                        <select name="id_rumah" id="selectRumah" class="form-control select2bs4" style="width: 100%;" required>
                            <option value="">-- Ketik / Cari Unit Rumah --</option>
                            <?php foreach ($data_rumah_options as $opt) : ?>
                                <option value="<?= $opt['id_rumah'] ?>" <?= ($input_id_rumah == $opt['id_rumah']) ? 'selected' : '' ?>>
                                    Blok <?= htmlspecialchars($opt['kode_blok']) ?> - <?= htmlspecialchars($opt['nama_kategori'] ?? 'Standar') ?> (Komplek <?= htmlspecialchars($opt['nama_site_plan'] ?? '-') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <label for="inputKontak" class="form-label">
                            <i class="bi bi-telephone-fill text-warning me-1"></i> Nomor HP / WhatsApp Pembeli
                        </label>
                        <input type="text" name="kontak" id="inputKontak" class="form-control" placeholder="Contoh: 082328936457" value="<?= htmlspecialchars($input_kontak) ?>" required>
                    </div>

                    <div class="col-lg-3 col-md-12">
                        <button type="submit" class="btn-check-progress">
                            <i class="bi bi-search"></i> Lihat Progres Rumah
                        </button>
                    </div>
                </div>
            </form>
            <div class="mt-2 text-muted small">
                <i class="bi bi-info-circle me-1"></i> Pastikan nomor HP sesuai dengan data yang Anda daftarkan saat proses pembelian rumah.
            </div>
        </div>

        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger mt-4 d-flex align-items-start gap-3 p-3 rounded-3 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-3 text-danger flex-shrink-0"></i>
                <div>
                    <h5 class="alert-heading fw-bold mb-1">Data Tidak Ditemukan!</h5>
                    <p class="mb-0 small"><?= htmlspecialchars($error_message) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($data_validasi) : ?>
            <!-- Hasil Progres Pembangunan -->
            <div class="mt-5">
                
                <!-- Info Pembeli & Unit -->
                <div class="buyer-unit-card">
                    <div class="buyer-unit-header">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold">Pemilik Rumah:</span>
                            <h3 class="buyer-name"><?= htmlspecialchars($data_validasi['nama_pembeli']) ?></h3>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="unit-badge-highlight">
                                <i class="bi bi-geo-alt me-1"></i> Komplek <?= htmlspecialchars($data_validasi['nama_site_plan']) ?>
                            </span>
                            <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill" style="background-color: var(--primary-navy) !important;">
                                Blok <?= htmlspecialchars($data_validasi['kode_blok']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="text-muted small">Tipe / Kategori</div>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($data_validasi['nama_kategori'] ?? 'Standar') ?></div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-muted small">Dimensi Bangunan</div>
                            <div class="fw-bold text-dark">LB <?= htmlspecialchars($data_validasi['luas_bangunan'] ?? '-') ?>m&sup2; / LT <?= htmlspecialchars($data_validasi['luas_tanah'] ?? '-') ?>m&sup2;</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-muted small">No. Transaksi</div>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($data_validasi['no_transaksi'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-muted small">Tanggal Pembelian</div>
                            <div class="fw-bold text-dark"><?= !empty($data_validasi['tanggal_transaksi']) ? tanggal_indonesia($data_validasi['tanggal_transaksi']) : '-' ?></div>
                        </div>
                    </div>
                </div>

                <!-- Kartu Persentase Keseluruhan -->
                <div class="overall-progress-card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <span class="text-uppercase small fw-bold" style="color: var(--mustrad-gold);">Total Akumulasi Pembangunan</span>
                            <h2 class="fw-bold mb-1 text-white">Status Pembangunan Rumah</h2>
                            <p class="text-white-50 small mb-0">
                                Progres dihitung berdasarkan pencapaian dari tahapan pekerjaan fisik di lapangan.
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="overall-num"><?= $total_progres_rata ?>%</div>
                            <div class="text-white-50 small fw-bold">TOTAL SELESAI</div>
                        </div>
                    </div>

                    <div class="progress progress-main-bar">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $total_progres_rata ?>%;" aria-valuenow="<?= $total_progres_rata ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- Daftar Tahap Pembangunan (1 sampai 5) -->
                <div class="mb-4">
                    <h3 class="fw-bold mb-3 text-navy" style="color: var(--primary-navy);">
                        <i class="bi bi-clock-history me-2 text-warning"></i>Tahapan & Riwayat Dokumentasi
                    </h3>
                </div>

                <?php foreach ($list_tahap as $index => $tahap) : 
                    $cur_p = $tahap['current_progres'];
                    $is_done = ($cur_p >= 100);
                    $is_in_progress = ($cur_p > 0 && $cur_p < 100);
                    
                    if ($is_done) {
                        $badge_class = 'bg-success text-white';
                        $status_text = 'Selesai (100%)';
                        $num_class = 'completed';
                    } elseif ($is_in_progress) {
                        $badge_class = 'bg-warning text-dark';
                        $status_text = 'Sedang Dikerjakan (' . $cur_p . '%)';
                        $num_class = 'in-progress';
                    } else {
                        $badge_class = 'bg-secondary text-white';
                        $status_text = 'Belum Dimulai (0%)';
                        $num_class = 'pending';
                    }
                ?>
                    <div class="stage-box">
                        <div class="stage-header">
                            <div class="d-flex align-items-center gap-3">
                                <div class="stage-num-badge <?= $num_class ?>">
                                    <?php if ($is_done) : ?>
                                        <i class="bi bi-check-lg fs-5"></i>
                                    <?php else : ?>
                                        <?= htmlspecialchars($tahap['urutan_tahap']) ?>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h4 class="stage-title">Tahap <?= htmlspecialchars($tahap['urutan_tahap']) ?>: <?= htmlspecialchars($tahap['tahap']) ?></h4>
                                    <div class="small text-muted">
                                        <?= count($tahap['entries']) ?> update catatan pengerjaan
                                    </div>
                                </div>
                            </div>
                            <div>
                                <span class="stage-progress-badge <?= $badge_class ?>">
                                    <?= $status_text ?>
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar Tahap -->
                        <div class="px-4 pt-3 pb-2 bg-light border-bottom">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Capaian Tahap Ini:</span>
                                <span class="fw-bold text-dark"><?= $cur_p ?>%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar <?= $is_done ? 'bg-success' : 'bg-warning' ?>" role="progressbar" style="width: <?= $cur_p ?>%;"></div>
                            </div>
                        </div>

                        <!-- Rincian Catatan & Foto -->
                        <div class="p-3">
                            <?php if (empty($tahap['entries'])) : ?>
                                <div class="text-center py-4 text-muted small">
                                    <i class="bi bi-hourglass-split fs-4 d-block mb-1 text-secondary"></i>
                                    Belum ada catatan atau dokumentasi foto pada tahap ini.
                                </div>
                            <?php else : ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered history-table mb-0">
                                        <thead>
                                            <tr class="text-center">
                                                <th width="5%">No</th>
                                                <th width="15%">Tanggal Update</th>
                                                <th width="12%">Progres</th>
                                                <th>Keterangan / Pekerjaan</th>
                                                <th width="20%">Dokumentasi Foto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($tahap['entries'] as $idx => $entry) : 
                                                $path_foto = 'assets/fto_history/' . $entry['foto'];
                                                $has_foto = !empty($entry['foto']) && file_exists($path_foto);
                                            ?>
                                                <tr>
                                                    <td class="text-center fw-bold"><?= $idx + 1 ?></td>
                                                    <td class="text-center"><?= tanggal_indonesia($entry['tanggal_update']) ?></td>
                                                    <td class="text-center">
                                                        <span class="badge bg-info text-dark fw-bold"><?= $entry['presentase_progres'] ?>%</span>
                                                    </td>
                                                    <td><?= nl2br(htmlspecialchars($entry['keterangan_history'])) ?></td>
                                                    <td class="text-center">
                                                        <?php if ($has_foto) : ?>
                                                            <img src="<?= $path_foto ?>" 
                                                                 alt="Foto Progres" 
                                                                 class="img-history-thumb" 
                                                                 onclick="showPhotoModal('<?= $path_foto ?>', 'Tahap <?= htmlspecialchars($tahap['urutan_tahap']) ?> - <?= htmlspecialchars($entry['keterangan_history']) ?> (<?= tanggal_indonesia($entry['tanggal_update']) ?>)')">
                                                            <div class="small text-muted mt-1" style="font-size: 0.75rem;">Klik untuk perbesar</div>
                                                        <?php else : ?>
                                                            <span class="text-muted small">Tidak ada foto</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>
    </div>

    <!-- Modal Perbesar Foto Progres -->
    <div class="modal fade" id="modalFotoProgres" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header text-white" style="background-color: var(--primary-navy);">
                    <h5 class="modal-title fs-6" id="modalFotoTitle">Dokumentasi Progres</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center bg-dark">
                    <img src="" id="modalFotoImg" class="img-fluid w-100" style="max-height: 80vh; object-fit: contain;">
                </div>
                <div class="modal-footer bg-light">
                    <span id="modalFotoCaption" class="text-muted small me-auto"></span>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <a href="index.php" class="footer-brand"><?= htmlspecialchars($nama_proyek) ?></a>
                    <p class="text-white-50 small mt-2 mb-3"><i class="bi bi-geo-alt me-1"></i> <?= htmlspecialchars($alamat_proyek) ?></p>
                    <p class="text-white-50 small mb-1"><i class="bi bi-telephone me-2"></i><?= htmlspecialchars($cp_proyek) ?></p>
                </div>
                <div class="col-lg-3">
                    <h6 class="text-white fw-bold mb-3">Navigasi</h6>
                    <ul class="list-unstyled small text-white-50 mb-0">
                        <li class="mb-2"><a href="index.php" class="text-white-50 text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="index.php#properti" class="text-white-50 text-decoration-none">Daftar Site Plan</a></li>
                        <li class="mb-2"><a href="history_rumah.php" class="text-white-50 text-decoration-none">Cek Progres Rumah</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="text-white fw-bold mb-3">Bantuan & Informasi</h6>
                    <p class="text-white-50 small">Apabila data Anda belum terdaftar atau terdapat ketidaksesuaian data progres, silakan hubungi customer service kami.</p>
                    <?php if (!empty($cp_proyek)) : ?>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $cp_proyek) ?>" target="_blank" class="btn btn-warning btn-sm fw-semibold" style="background-color: var(--mustrad-gold); border-color: var(--mustrad-gold); color: var(--primary-navy);">
                            <i class="bi bi-whatsapp me-1"></i> Hubungi Admin
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="copyright text-center text-white-50">
                &copy; <?= date('Y') ?> <?= htmlspecialchars($nama_proyek) ?>. All Rights Reserved.
            </div>
        </div>
    </footer>

    <!-- jQuery & Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 dengan theme bootstrap4 dan fitur pencarian
            $('#selectRumah').select2({
                theme: 'bootstrap4',
                placeholder: '-- Ketik / Cari Unit Rumah --',
                allowClear: true,
                width: '100%'
            });
        });

        function showPhotoModal(src, caption) {
            document.getElementById('modalFotoImg').src = src;
            document.getElementById('modalFotoCaption').innerText = caption;
            var myModal = new bootstrap.Modal(document.getElementById('modalFotoProgres'));
            myModal.show();
        }
    </script>
</body>
</html>

