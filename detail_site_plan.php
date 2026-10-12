<?php
require_once 'database/config.php';

// Ambil ID site plan dari parameter URL
$id_site_plan = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_site_plan <= 0) {
    header("Location: index.php#properti");
    exit;
}

// Fetch web configurations
$query_web = mysqli_query($conn, "SELECT * FROM web WHERE id = 1") or die(mysqli_error($conn));
$result_web = mysqli_fetch_assoc($query_web);

$nama_proyek = $result_web['nama_proyek'] ?? 'Real Estate';
$alamat_proyek = $result_web['alamat'] ?? '';
$cp_proyek = $result_web['cp'] ?? '';
$ig_proyek = $result_web['instagram'] ?? '';
$tt_proyek = $result_web['tiktok'] ?? '';
$logo_filename = $result_web['logo'] ?? '';

// Fetch data Site Plan
$query_sp = mysqli_query($conn, "SELECT * FROM site_plan WHERE id_site_plan = '$id_site_plan'") or die(mysqli_error($conn));
$site_plan = mysqli_fetch_assoc($query_sp);

if (!$site_plan) {
    echo "<script>alert('Site Plan tidak ditemukan!'); window.location.href='index.php#properti';</script>";
    exit;
}

// Ambil nomor kontak marketing komplek (fallback ke kontak proyek jika kosong)
$kontak_marketing = !empty($site_plan['penanggung_jawab']) ? $site_plan['penanggung_jawab'] : $cp_proyek;
// Bersihkan nomor WhatsApp agar format internasional jika dimulai dengan 0
$wa_number = preg_replace('/[^0-9]/', '', $kontak_marketing);
if (substr($wa_number, 0, 1) === '0') {
    $wa_number = '62' . substr($wa_number, 1);
}

// Hitung statistik unit rumah pada site plan ini
$q_stat = mysqli_query($conn, "SELECT 
    COUNT(id_rumah) AS total_unit,
    SUM(CASE WHEN status = '0' THEN 1 ELSE 0 END) AS unit_tersedia,
    SUM(CASE WHEN status = '1' THEN 1 ELSE 0 END) AS unit_terjual
    FROM rumah WHERE id_site_plan = '$id_site_plan'");
$stat = mysqli_fetch_assoc($q_stat);
$total_unit = $stat['total_unit'] ?? 0;
$unit_tersedia = $stat['unit_tersedia'] ?? 0;
$unit_terjual = $stat['unit_terjual'] ?? 0;

// Ambil kategori rumah yang ada di dalam site plan ini
$q_kategori = mysqli_query($conn, "SELECT 
    k.*, 
    COUNT(r.id_rumah) AS total_unit_kategori,
    SUM(CASE WHEN r.status = '0' THEN 1 ELSE 0 END) AS tersedia_kategori,
    SUM(CASE WHEN r.status = '1' THEN 1 ELSE 0 END) AS terjual_kategori
    FROM kategori_rumah k
    INNER JOIN rumah r ON k.id_kategori = r.id_kategori
    WHERE r.id_site_plan = '$id_site_plan'
    GROUP BY k.id_kategori
    ORDER BY k.nama_kategori ASC") or die(mysqli_error($conn));

$list_kategori = [];
while ($kat = mysqli_fetch_assoc($q_kategori)) {
    // Ambil foto untuk kategori ini
    $q_foto = mysqli_query($conn, "SELECT foto, keterangan_foto FROM foto_rumah WHERE id_kategori = '{$kat['id_kategori']}' ORDER BY id_foto_rumah ASC");
    $fotos = [];
    while ($f = mysqli_fetch_assoc($q_foto)) {
        $fotos[] = $f;
    }
    $kat['fotos'] = $fotos;

    // Ambil daftar harga / jenis pembayaran
    $q_harga = mysqli_query($conn, "SELECT jenis_pembayaran, harga FROM jenis_pembayaran WHERE id_kategori = '{$kat['id_kategori']}' ORDER BY harga ASC");
    $hargas = [];
    while ($h = mysqli_fetch_assoc($q_harga)) {
        $hargas[] = $h;
    }
    $kat['hargas'] = $hargas;

    $list_kategori[] = $kat;
}

// Ambil semua data rumah pada site plan ini
$q_rumah = mysqli_query($conn, "SELECT r.*, k.nama_kategori, k.luas_bangunan, k.luas_tanah, k.jumlah_kamar, k.deskripsi
    FROM rumah r
    LEFT JOIN kategori_rumah k ON r.id_kategori = k.id_kategori
    WHERE r.id_site_plan = '$id_site_plan'
    ORDER BY r.kode_blok ASC") or die(mysqli_error($conn));

$list_rumah = [];
while ($rmh = mysqli_fetch_assoc($q_rumah)) {
    $list_rumah[] = $rmh;
}

function formatRupiah($angka) {
    if (!$angka) return 'Rp 0';
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komplek <?= htmlspecialchars($site_plan['nama_site_plan']) ?> - <?= htmlspecialchars($nama_proyek) ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
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

        /* Breadcrumb Bar */
        .breadcrumb-section {
            background: #ffffff;
            border-bottom: 1px solid #e5e9f2;
            padding: 12px 0;
        }
        .breadcrumb {
            margin: 0;
            font-size: 0.88rem;
        }
        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
            font-weight: 500;
        }
        .breadcrumb-item a:hover {
            color: var(--mustrad-gold);
        }
        .breadcrumb-item.active {
            color: var(--primary-navy);
            font-weight: 600;
        }

        /* Detail Header Banner */
        .siteplan-hero {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--secondary-navy) 100%);
            color: #ffffff;
            padding: 50px 0 45px 0;
            position: relative;
        }
        .siteplan-hero .badge-kategori {
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
        .siteplan-hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .siteplan-hero p.location {
            color: var(--text-light);
            font-size: 1rem;
            max-width: 800px;
            margin-bottom: 25px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        /* Stat Pills */
        .stat-pills-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        .stat-pill {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 10px 18px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            backdrop-filter: blur(5px);
        }
        .stat-pill-icon {
            font-size: 1.5rem;
            color: var(--mustrad-gold);
        }
        .stat-pill-num {
            font-size: 1.25rem;
            font-weight: 700;
            line-height: 1.1;
        }
        .stat-pill-label {
            font-size: 0.75rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Section Styling */
        .content-section {
            padding: 45px 0;
        }
        .section-heading-wrap {
            margin-bottom: 30px;
        }
        .section-tag {
            color: var(--mustrad-gold);
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 6px;
        }
        .section-main-title {
            color: var(--primary-navy);
            font-weight: 700;
            font-size: 1.85rem;
            margin: 0;
        }
        .section-desc {
            color: #6c757d;
            font-size: 0.95rem;
            margin-top: 6px;
        }

        /* Site Plan Image Card */
        .siteplan-image-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            border: 1px solid #eef2f7;
            height: 100%;
        }
        .siteplan-img-preview {
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            background: #0A192F;
            position: relative;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            min-height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .siteplan-img-preview img {
            width: 100%;
            height: auto;
            max-height: 480px;
            object-fit: contain;
            transition: transform 0.4s ease;
        }
        .siteplan-img-preview:hover img {
            transform: scale(1.02);
        }
        .siteplan-zoom-overlay {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: rgba(10, 25, 47, 0.85);
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s;
        }
        .siteplan-img-preview:hover .siteplan-zoom-overlay {
            background: var(--mustrad-gold);
            color: var(--primary-navy);
        }

        /* Contact & Marketing Card */
        .marketing-box {
            background: #ffffff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            border: 1px solid #eef2f7;
        }
        .btn-wa-direct {
            background-color: #25D366;
            color: #ffffff;
            font-weight: 700;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        }
        .btn-wa-direct:hover {
            background-color: #1EBE5D;
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* Kategori Card */
        .kategori-card {
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.05);
            border: 1px solid #eef2f7;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .kategori-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        }
        .kategori-img-wrap {
            height: 200px;
            position: relative;
            background: var(--secondary-navy);
            overflow: hidden;
        }
        .kategori-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .kategori-card:hover .kategori-img-wrap img {
            transform: scale(1.05);
        }
        .kategori-unit-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(10, 25, 47, 0.85);
            color: var(--mustrad-gold);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(206, 167, 81, 0.3);
        }
        .kategori-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .kategori-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-navy);
            margin-bottom: 8px;
        }
        .spec-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            background: #f8fafc;
            padding: 12px;
            border-radius: 8px;
            margin: 14px 0;
            text-align: center;
        }
        .spec-item i {
            color: var(--mustrad-gold);
            font-size: 1.1rem;
            display: block;
            margin-bottom: 2px;
        }
        .spec-item .val {
            font-weight: 700;
            color: var(--primary-navy);
            font-size: 0.88rem;
        }
        .spec-item .lbl {
            font-size: 0.72rem;
            color: #718096;
            text-transform: uppercase;
        }
        .price-badge-wrap {
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid #edf2f7;
        }
        .price-start-from {
            font-size: 0.78rem;
            color: #718096;
            margin-bottom: 2px;
        }
        .price-main {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary-navy);
        }
        .btn-filter-kategori {
            background-color: var(--primary-navy);
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 6px;
            border: none;
            transition: all 0.3s;
            width: 100%;
            margin-top: 12px;
        }
        .btn-filter-kategori:hover {
            background-color: var(--mustrad-gold);
            color: var(--primary-navy);
        }

        /* Rumah Section & Filter Bar */
        .filter-toolbar {
            background: #ffffff;
            border-radius: 12px;
            padding: 18px 22px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            border: 1px solid #eef2f7;
            margin-bottom: 25px;
        }
        .status-pill-filter {
            cursor: pointer;
            padding: 7px 16px;
            border-radius: 20px;
            font-size: 0.88rem;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #4a5568;
            transition: all 0.25s;
            user-select: none;
        }
        .status-pill-filter:hover {
            background: #f1f5f9;
        }
        .status-pill-filter.active {
            background: var(--primary-navy);
            color: #ffffff;
            border-color: var(--primary-navy);
        }
        .status-pill-filter.active-available {
            background: #198754;
            color: #ffffff;
            border-color: #198754;
        }
        .status-pill-filter.active-sold {
            background: #dc3545;
            color: #ffffff;
            border-color: #dc3545;
        }

        /* Rumah Cards */
        .rumah-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 18px;
            border: 1px solid #eef2f7;
            box-shadow: 0 4px 14px rgba(0,0,0,0.04);
            transition: all 0.25s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .rumah-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            border-color: var(--gold-accent);
        }
        .rumah-card.is-sold {
            background: #fafafa;
            border-color: #e9ecef;
            opacity: 0.85;
        }
        .rumah-card.is-sold:hover {
            opacity: 1;
        }
        .unit-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        .unit-block-code {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-navy);
        }
        .unit-badge-status {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .unit-badge-status.tersedia {
            background-color: #e6f7ee;
            color: #0e8345;
            border: 1px solid #a3e6be;
        }
        .unit-badge-status.terjual {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }
        .unit-kategori-name {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
        }
        .unit-specs-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 15px;
        }
        .unit-spec-tag {
            background: #f1f5f9;
            color: #475569;
            font-size: 0.76rem;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn-unit-action {
            margin-top: auto;
            display: flex;
            gap: 8px;
        }
        .btn-order-wa {
            flex-grow: 1;
            background: #25D366;
            color: #ffffff;
            border: none;
            padding: 7px 12px;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-order-wa:hover {
            background: #1eb956;
            color: #ffffff;
        }
        .btn-unit-detail {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            color: var(--primary-navy);
            padding: 7px 10px;
            border-radius: 6px;
            font-size: 0.82rem;
            transition: all 0.2s;
        }
        .btn-unit-detail:hover {
            background: var(--primary-navy);
            color: #ffffff;
            border-color: var(--primary-navy);
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
                <?php endif; ?>
                <span class="navbar-brand ms-2"><?= htmlspecialchars($nama_proyek) ?></span>
            </a>
            <div class="ms-auto d-flex align-items-center gap-2">
                <a href="index.php#properti" class="btn-back-nav"><i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda</a>
                <?php if (!empty($wa_number)) : ?>
                    <a href="https://wa.me/<?= $wa_number ?>?text=Halo%20Marketing%20<?= urlencode($nama_proyek) ?>,%20saya%20ingin%20konsultasi%20mengenai%20Komplek%20<?= urlencode($site_plan['nama_site_plan']) ?>" target="_blank" class="btn btn-warning btn-sm fw-semibold d-none d-sm-inline-flex align-items-center gap-1" style="background-color: var(--mustrad-gold); border-color: var(--mustrad-gold); color: var(--primary-navy);">
                        <i class="bi bi-whatsapp"></i> Hubungi Marketing
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house me-1"></i>Beranda</a></li>
                    <li class="breadcrumb-item"><a href="index.php#properti">Site Plan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Komplek <?= htmlspecialchars($site_plan['nama_site_plan']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Site Plan Hero Banner -->
    <section class="siteplan-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge-kategori">
                        <i class="bi bi-map me-1"></i> Kawasan Perumahan Terpadu
                    </span>
                    <h1>Komplek <?= htmlspecialchars($site_plan['nama_site_plan']) ?></h1>
                    <p class="location">
                        <i class="bi bi-geo-alt-fill text-warning fs-5 flex-shrink-0"></i>
                        <span><?= htmlspecialchars($site_plan['lokasi']) ?></span>
                    </p>

                    <!-- Statistik Cepat -->
                    <div class="stat-pills-row">
                        <div class="stat-pill">
                            <i class="bi bi-houses-fill stat-pill-icon"></i>
                            <div>
                                <div class="stat-pill-num"><?= $total_unit ?></div>
                                <div class="stat-pill-label">Total Unit</div>
                            </div>
                        </div>
                        <div class="stat-pill">
                            <i class="bi bi-check-circle-fill stat-pill-icon text-success"></i>
                            <div>
                                <div class="stat-pill-num text-success"><?= $unit_tersedia ?></div>
                                <div class="stat-pill-label">Unit Tersedia</div>
                            </div>
                        </div>
                        <div class="stat-pill">
                            <i class="bi bi-slash-circle-fill stat-pill-icon text-danger"></i>
                            <div>
                                <div class="stat-pill-num text-danger"><?= $unit_terjual ?></div>
                                <div class="stat-pill-label">Unit Terjual</div>
                            </div>
                        </div>
                        <div class="stat-pill">
                            <i class="bi bi-layers-fill stat-pill-icon"></i>
                            <div>
                                <div class="stat-pill-num"><?= count($list_kategori) ?></div>
                                <div class="stat-pill-label">Tipe Rumah</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mt-4 mt-lg-0 text-lg-end">
                    <div class="d-inline-flex flex-column gap-2 text-start bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-10">
                        <span class="text-white-50 small fw-semibold text-uppercase">Sosial Media Komplek</span>
                        <div class="d-flex gap-3 align-items-center mt-1">
                            <?php if (!empty($site_plan['ig'])) : ?>
                                <a href="https://instagram.com/<?= htmlspecialchars(ltrim($site_plan['ig'], '@')) ?>" target="_blank" class="text-white fs-4" title="Instagram Komplek">
                                    <i class="bi bi-instagram"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($site_plan['tiktok'])) : ?>
                                <a href="https://tiktok.com/<?= htmlspecialchars($site_plan['tiktok']) ?>" target="_blank" class="text-white fs-4" title="TikTok Komplek">
                                    <i class="bi bi-tiktok"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($wa_number)) : ?>
                                <a href="https://wa.me/<?= $wa_number ?>?text=Halo%20Marketing,%20saya%20tertarik%20dengan%20Komplek%20<?= urlencode($site_plan['nama_site_plan']) ?>" target="_blank" class="text-success fs-4" title="WhatsApp Marketing">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($site_plan['penanggung_jawab'])) : ?>
                            <span class="text-white small mt-1"><i class="bi bi-telephone-fill me-1 text-warning"></i> <?= htmlspecialchars($site_plan['penanggung_jawab']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 1: MASTER SITE PLAN IMAGE & INFORMASI -->
    <section class="content-section">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                <!-- Kolom Denah Site Plan -->
                <div class="col-lg-8">
                    <div class="siteplan-image-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="section-tag">Masterplan Visual</span>
                                <h3 class="section-main-title fs-4">Denah Site Plan Komplek</h3>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill small fw-semibold">
                                <i class="bi bi-zoom-in me-1"></i> Klik untuk Memperbesar
                            </span>
                        </div>

                        <?php 
                        $gambar_brosur = $site_plan['brosur'];
                        $path_brosur = 'assets/brosur/' . $gambar_brosur;
                        $has_brosur = !empty($gambar_brosur) && file_exists($path_brosur);
                        ?>

                        <div class="siteplan-img-preview" data-bs-toggle="modal" data-bs-target="#modalZoomSiteplan">
                            <?php if ($has_brosur) : ?>
                                <img src="<?= $path_brosur ?>" alt="Denah Site Plan Komplek <?= htmlspecialchars($site_plan['nama_site_plan']) ?>" id="siteplanMainImg">
                            <?php else : ?>
                                <div class="text-center p-5 text-white">
                                    <i class="bi bi-map fs-1 text-warning mb-2 d-block"></i>
                                    <h5 class="fw-bold mb-1">Denah Site Plan Digital</h5>
                                    <p class="text-white-50 small mb-0">Denah layout visual komplek <?= htmlspecialchars($site_plan['nama_site_plan']) ?></p>
                                </div>
                            <?php endif; ?>
                            <div class="siteplan-zoom-overlay">
                                <i class="bi bi-arrows-fullscreen"></i> Perbesar Denah
                            </div>
                        </div>

                        <div class="mt-3 text-muted small d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-info-circle me-1"></i> Perhatikan kode blok rumah pada denah di atas untuk mencocokkan dengan ketersediaan unit.</span>
                            <?php if ($has_brosur) : ?>
                                <a href="<?= $path_brosur ?>" target="_blank" download class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-download me-1"></i> Unduh Denah
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kontak & Panduan Memilih Unit -->
                <div class="col-lg-4">
                    <div class="marketing-box h-100 d-flex flex-direction-column justify-content-between">
                        <div>
                            <span class="section-tag">Konsultasi Pembelian</span>
                            <h4 class="fw-bold mb-3 text-navy" style="color: var(--primary-navy);">Tertarik Memiliki Unit di Sini?</h4>
                            <p class="text-muted small mb-4">
                                Tim marketing kami siap membantu Anda mulai dari survei lokasi langsung, pemilihan posisi blok favorit, simulasi angsuran, hingga proses akad pembelian.
                            </p>

                            <div class="p-3 bg-light rounded-3 border mb-4">
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <div class="bg-primary text-white p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: var(--primary-navy) !important;">
                                        <i class="bi bi-person-fill fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold small text-dark">Marketing Komplek</div>
                                        <div class="text-muted small"><?= htmlspecialchars($site_plan['penanggung_jawab'] ?: $cp_proyek) ?></div>
                                    </div>
                                </div>
                                <div class="small text-muted">
                                    <i class="bi bi-clock me-1 text-primary"></i> Respon cepat setiap hari (08.00 - 18.00 WIB)
                                </div>
                            </div>
                        </div>

                        <div>
                            <a href="https://wa.me/<?= $wa_number ?>?text=Halo%20Marketing,%20saya%20ingin%20menanyakan%20ketersediaan%20unit%20dan%20jadwal%20survei%20ke%20Komplek%20<?= urlencode($site_plan['nama_site_plan']) ?>" target="_blank" class="btn-wa-direct mb-2">
                                <i class="bi bi-whatsapp fs-5"></i> Chat Marketing WhatsApp
                            </a>
                            <a href="#daftar-unit-rumah" class="btn btn-outline-dark w-100 py-2 small fw-semibold">
                                <i class="bi bi-arrow-down-circle me-1"></i> Lihat Daftar Unit Rumah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: KATEGORI & TIPE RUMAH DI SITE PLAN INI -->
    <section class="content-section bg-white border-top border-bottom">
        <div class="container">
            <div class="section-heading-wrap d-flex justify-content-between align-items-end flex-wrap gap-2">
                <div>
                    <span class="section-tag">Pilihan Hunian</span>
                    <h2 class="section-main-title">Kategori & Tipe Rumah</h2>
                    <p class="section-desc">Berikut tipe dan varian hunian yang tersedia khusus di kawasan Komplek <?= htmlspecialchars($site_plan['nama_site_plan']) ?>.</p>
                </div>
                <div class="text-muted small">
                    Total: <strong class="text-dark"><?= count($list_kategori) ?> Tipe</strong>
                </div>
            </div>

            <?php if (empty($list_kategori)) : ?>
                <div class="alert alert-info text-center py-4">
                    <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                    Belum ada kategori rumah yang terdaftar untuk kawasan site plan ini.
                </div>
            <?php else : ?>
                <div class="row g-4">
                    <?php foreach ($list_kategori as $kat) : 
                        $foto_utama = !empty($kat['fotos']) ? 'assets/fto_rumah/' . $kat['fotos'][0]['foto'] : '';
                        $has_foto = !empty($foto_utama) && file_exists($foto_utama);

                        // Ambil harga terendah jika ada
                        $min_harga = null;
                        if (!empty($kat['hargas'])) {
                            $min_harga = $kat['hargas'][0]['harga'];
                            foreach ($kat['hargas'] as $h) {
                                if ($h['harga'] < $min_harga) {
                                    $min_harga = $h['harga'];
                                }
                            }
                        }
                    ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="kategori-card">
                                <div class="kategori-img-wrap">
                                    <?php if ($has_foto) : ?>
                                        <img src="<?= $foto_utama ?>" alt="<?= htmlspecialchars($kat['nama_kategori']) ?>">
                                    <?php else : ?>
                                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white bg-dark">
                                            <i class="bi bi-house fs-1 text-warning mb-1"></i>
                                            <span class="small text-white-50"><?= htmlspecialchars($kat['nama_kategori']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="kategori-unit-badge">
                                        <?= $kat['tersedia_kategori'] ?> Unit Tersedia
                                    </div>
                                </div>
                                <div class="kategori-body">
                                    <h4 class="kategori-title"><?= htmlspecialchars($kat['nama_kategori']) ?></h4>
                                    
                                    <div class="spec-grid">
                                        <div class="spec-item">
                                            <i class="bi bi-bounding-box"></i>
                                            <div class="val"><?= htmlspecialchars($kat['luas_bangunan']) ?> m&sup2;</div>
                                            <div class="lbl">Bangunan</div>
                                        </div>
                                        <div class="spec-item">
                                            <i class="bi bi-aspect-ratio"></i>
                                            <div class="val"><?= htmlspecialchars($kat['luas_tanah']) ?> m&sup2;</div>
                                            <div class="lbl">Tanah</div>
                                        </div>
                                        <div class="spec-item">
                                            <i class="bi bi-door-open"></i>
                                            <div class="val"><?= htmlspecialchars($kat['jumlah_kamar']) ?></div>
                                            <div class="lbl">Kamar</div>
                                        </div>
                                    </div>

                                    <p class="text-muted small mb-3 flex-grow-1">
                                        <?= nl2br(htmlspecialchars($kat['deskripsi'] ?: 'Desain hunian berkelas dengan sirkulasi udara optimal dan kenyamanan maksimal.')) ?>
                                    </p>

                                    <!-- Informasi Skema Pembayaran / Harga -->
                                    <div class="price-badge-wrap">
                                        <?php if (!empty($kat['hargas'])) : ?>
                                            <div class="price-start-from">Skema Pembayaran Tersedia:</div>
                                            <div class="d-flex flex-wrap gap-1 mb-2">
                                                <?php foreach ($kat['hargas'] as $jh) : ?>
                                                    <span class="badge bg-light text-dark border small" title="<?= htmlspecialchars($jh['jenis_pembayaran']) ?>">
                                                        <?= htmlspecialchars($jh['jenis_pembayaran']) ?>: <?= formatRupiah($jh['harga']) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>

                                        <button class="btn-filter-kategori" onclick="filterByKategori('<?= htmlspecialchars(addslashes($kat['nama_kategori'])) ?>')">
                                            <i class="bi bi-search me-1"></i> Lihat Unit Tipe Ini (<?= $kat['total_unit_kategori'] ?> unit)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- SECTION 3: DATA RUMAH (BLOK & KETERSEDIAAN) -->
    <section class="content-section" id="daftar-unit-rumah">
        <div class="container">
            <div class="section-heading-wrap d-flex justify-content-between align-items-end flex-wrap gap-2">
                <div>
                    <span class="section-tag">Status Unit</span>
                    <h2 class="section-main-title">Daftar Rumah & Blok</h2>
                    <p class="section-desc">Cek ketersediaan setiap blok rumah. Klik 'Pesan via WhatsApp' untuk menanyakan langsung unit yang Anda minati.</p>
                </div>
                <div>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill me-2">
                        <i class="bi bi-circle-fill me-1 small"></i> Tersedia: <?= $unit_tersedia ?>
                    </span>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">
                        <i class="bi bi-x-circle me-1"></i> Terjual: <?= $unit_terjual ?>
                    </span>
                </div>
            </div>

            <!-- Filter Toolbar -->
            <div class="filter-toolbar">
                <div class="row g-3 align-items-center">
                    <!-- Filter Status -->
                    <div class="col-md-5">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span class="small text-muted fw-bold me-1">Status:</span>
                            <span class="status-pill-filter active" data-status="all" onclick="setStatusFilter('all', this)">Semua (<?= $total_unit ?>)</span>
                            <span class="status-pill-filter" data-status="0" onclick="setStatusFilter('0', this)"><i class="bi bi-check-circle-fill text-success me-1"></i> Tersedia (<?= $unit_tersedia ?>)</span>
                            <span class="status-pill-filter" data-status="1" onclick="setStatusFilter('1', this)"><i class="bi bi-slash-circle-fill text-danger me-1"></i> Terjual (<?= $unit_terjual ?>)</span>
                        </div>
                    </div>

                    <!-- Filter Kategori Dropdown -->
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-layers"></i></span>
                            <select class="form-select form-select-sm" id="selectKategoriFilter" onchange="applyFilters()">
                                <option value="all">Semua Tipe / Kategori Rumah</option>
                                <?php foreach ($list_kategori as $k_opt) : ?>
                                    <option value="<?= htmlspecialchars($k_opt['nama_kategori']) ?>"><?= htmlspecialchars($k_opt['nama_kategori']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Search Input Blok -->
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchBlokInput" class="form-control form-control-sm" placeholder="Cari Kode Blok..." onkeyup="applyFilters()">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid Data Unit Rumah -->
            <div class="row g-3" id="rumahGrid">
                <?php if (empty($list_rumah)) : ?>
                    <div class="col-12">
                        <div class="alert alert-secondary text-center py-4">
                            Belum ada unit rumah yang didaftarkan pada kawasan ini.
                        </div>
                    </div>
                <?php else : ?>
                    <?php foreach ($list_rumah as $rmh) : 
                        $is_available = ($rmh['status'] == '0');
                        $wa_msg = urlencode("Halo Marketing " . $nama_proyek . ", saya ingin menanyakan ketersediaan unit Blok *" . $rmh['kode_blok'] . "* (" . $rmh['nama_kategori'] . ") di Komplek *" . $site_plan['nama_site_plan'] . "*.");
                    ?>
                        <div class="col-xl-3 col-lg-4 col-md-6 unit-item" 
                             data-status="<?= $rmh['status'] ?>" 
                             data-kategori="<?= htmlspecialchars($rmh['nama_kategori']) ?>" 
                             data-blok="<?= htmlspecialchars(strtolower($rmh['kode_blok'])) ?>">
                            <div class="rumah-card <?= !$is_available ? 'is-sold' : '' ?>">
                                <div class="unit-header">
                                    <div class="unit-block-code"><?= htmlspecialchars($rmh['kode_blok']) ?></div>
                                    <?php if ($is_available) : ?>
                                        <span class="unit-badge-status tersedia">
                                            <i class="bi bi-check-circle-fill"></i> Tersedia
                                        </span>
                                    <?php else : ?>
                                        <span class="unit-badge-status terjual">
                                            <i class="bi bi-x-circle-fill"></i> Terjual
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="unit-kategori-name">
                                    <i class="bi bi-house-door text-warning me-1"></i>
                                    <?= htmlspecialchars($rmh['nama_kategori'] ?? 'Tipe Standar') ?>
                                </div>

                                <div class="unit-specs-pills">
                                    <span class="unit-spec-tag" title="Luas Bangunan">
                                        <i class="bi bi-bounding-box"></i> LB: <?= htmlspecialchars($rmh['luas_bangunan'] ?? '-') ?>m&sup2;
                                    </span>
                                    <span class="unit-spec-tag" title="Luas Tanah">
                                        <i class="bi bi-aspect-ratio"></i> LT: <?= htmlspecialchars($rmh['luas_tanah'] ?? '-') ?>m&sup2;
                                    </span>
                                    <span class="unit-spec-tag" title="Jumlah Kamar">
                                        <i class="bi bi-door-open"></i> <?= htmlspecialchars($rmh['jumlah_kamar'] ?? '-') ?> Kamar
                                    </span>
                                </div>

                                <div class="btn-unit-action">
                                    <?php if ($is_available) : ?>
                                        <a href="https://wa.me/<?= $wa_number ?>?text=<?= $wa_msg ?>" target="_blank" class="btn-order-wa">
                                            <i class="bi bi-whatsapp"></i> Tanya Unit Ini
                                        </a>
                                    <?php else : ?>
                                        <button class="btn btn-secondary btn-sm w-100 disabled" style="font-size: 0.8rem;">
                                            Unit Sudah Terjual
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Empty State Filter -->
            <div id="noUnitFoundAlert" class="alert alert-warning text-center mt-3 py-4 d-none">
                <i class="bi bi-search fs-3 d-block mb-2"></i>
                Tidak ada unit rumah yang sesuai dengan filter atau pencarian Anda.
            </div>

        </div>
    </section>

    <!-- Modal Zoom Denah Site Plan -->
    <div class="modal fade" id="modalZoomSiteplan" tabindex="-1" aria-labelledby="modalZoomSiteplanLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header text-white" style="background-color: var(--primary-navy);">
                    <h5 class="modal-title" id="modalZoomSiteplanLabel">
                        <i class="bi bi-map me-2"></i>Denah Site Plan - Komplek <?= htmlspecialchars($site_plan['nama_site_plan']) ?>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center bg-dark">
                    <?php if ($has_brosur) : ?>
                        <img src="<?= $path_brosur ?>" alt="Denah Besar" class="img-fluid w-100" style="max-height: 85vh; object-fit: contain;">
                    <?php else : ?>
                        <div class="p-5 text-white">
                            <i class="bi bi-image fs-1 text-muted mb-2 d-block"></i>
                            Denah gambar belum tersedia untuk komplek ini.
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer bg-light">
                    <span class="text-muted small me-auto"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($site_plan['lokasi']) ?></span>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <?php if ($has_brosur) : ?>
                        <a href="<?= $path_brosur ?>" target="_blank" download class="btn btn-primary btn-sm" style="background-color: var(--primary-navy); border-color: var(--primary-navy);">
                            <i class="bi bi-download me-1"></i> Unduh Gambar Denah
                        </a>
                    <?php endif; ?>
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
                        <li class="mb-2"><a href="index.php#tentang-kami" class="text-white-50 text-decoration-none">Tentang Kami</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="text-white fw-bold mb-3">Kontak Marketing</h6>
                    <p class="text-white-50 small">Untuk informasi detail unit, survey lokasi, dan simulasi KPR/Cash, hubungi tim marketing kami.</p>
                    <?php if (!empty($wa_number)) : ?>
                        <a href="https://wa.me/<?= $wa_number ?>?text=Halo%20Marketing,%20saya%20ingin%20konsultasi%20mengenai%20Komplek%20<?= urlencode($site_plan['nama_site_plan']) ?>" target="_blank" class="btn btn-warning btn-sm fw-semibold" style="background-color: var(--mustrad-gold); border-color: var(--mustrad-gold); color: var(--primary-navy);">
                            <i class="bi bi-whatsapp me-1"></i> Chat WhatsApp
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="copyright text-center text-white-50">
                &copy; <?= date('Y') ?> <?= htmlspecialchars($nama_proyek) ?>. All Rights Reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Client-side Interactive Filtering -->
    <script>
        let currentStatusFilter = 'all';

        function setStatusFilter(status, elem) {
            currentStatusFilter = status;
            document.querySelectorAll('.status-pill-filter').forEach(el => {
                el.classList.remove('active', 'active-available', 'active-sold');
            });

            if (status === 'all') {
                elem.classList.add('active');
            } else if (status === '0') {
                elem.classList.add('active-available');
            } else if (status === '1') {
                elem.classList.add('active-sold');
            }

            applyFilters();
        }

        function filterByKategori(kategoriName) {
            const select = document.getElementById('selectKategoriFilter');
            select.value = kategoriName;
            applyFilters();
            
            // Scroll halus ke daftar unit
            const el = document.getElementById('daftar-unit-rumah');
            if (el) {
                el.scrollIntoView({ behavior: 'smooth' });
            }
        }

        function applyFilters() {
            const kategoriVal = document.getElementById('selectKategoriFilter').value;
            const searchVal = document.getElementById('searchBlokInput').value.trim().toLowerCase();
            const items = document.querySelectorAll('.unit-item');
            let visibleCount = 0;

            items.forEach(item => {
                const itemStatus = item.getAttribute('data-status');
                const itemKategori = item.getAttribute('data-kategori');
                const itemBlok = item.getAttribute('data-blok');

                const matchStatus = (currentStatusFilter === 'all' || itemStatus === currentStatusFilter);
                const matchKategori = (kategoriVal === 'all' || itemKategori === kategoriVal);
                const matchSearch = (!searchVal || itemBlok.includes(searchVal));

                if (matchStatus && matchKategori && matchSearch) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            const noUnitAlert = document.getElementById('noUnitFoundAlert');
            if (visibleCount === 0) {
                noUnitAlert.classList.remove('d-none');
            } else {
                noUnitAlert.classList.add('d-none');
            }
        }
    </script>
</body>
</html>

