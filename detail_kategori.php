<?php
require_once 'database/config.php';

// Ambil ID kategori dari parameter URL
$id_kategori = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_kategori <= 0) {
    header("Location: index.php");
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

// Fetch data Kategori Rumah
$query_kat = mysqli_query($conn, "SELECT * FROM kategori_rumah WHERE id_kategori = '$id_kategori'") or die(mysqli_error($conn));
$kategori = mysqli_fetch_assoc($query_kat);

if (!$kategori) {
    echo "<script>alert('Kategori rumah tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}

// Format nomor WhatsApp proyek
$wa_number = preg_replace('/[^0-9]/', '', $cp_proyek);
if (substr($wa_number, 0, 1) === '0') {
    $wa_number = '62' . substr($wa_number, 1);
}

// Fetch Foto-foto Spesifikasi Kategori Rumah
$query_foto = mysqli_query($conn, "SELECT * FROM foto_rumah WHERE id_kategori = '$id_kategori' ORDER BY id_foto_rumah ASC") or die(mysqli_error($conn));
$foto_list = [];
while ($f = mysqli_fetch_assoc($query_foto)) {
    $foto_list[] = $f;
}

// Fetch Detail Jenis Pembayaran & Harga
$query_harga = mysqli_query($conn, "SELECT * FROM jenis_pembayaran WHERE id_kategori = '$id_kategori' ORDER BY harga ASC") or die(mysqli_error($conn));
$harga_list = [];
$min_harga = null;
while ($h = mysqli_fetch_assoc($query_harga)) {
    $harga_list[] = $h;
    if ($min_harga === null || $h['harga'] < $min_harga) {
        $min_harga = $h['harga'];
    }
}

// Fetch Site Plan / Komplek yang memiliki tipe kategori ini
$query_site_plans = mysqli_query($conn, "SELECT sp.*, COUNT(r.id_rumah) AS total_unit_sp,
    SUM(CASE WHEN r.status = '0' THEN 1 ELSE 0 END) AS unit_tersedia_sp,
    SUM(CASE WHEN r.status = '1' THEN 1 ELSE 0 END) AS unit_terjual_sp
    FROM site_plan sp
    INNER JOIN rumah r ON sp.id_site_plan = r.id_site_plan
    WHERE r.id_kategori = '$id_kategori'
    GROUP BY sp.id_site_plan
    ORDER BY sp.nama_site_plan ASC") or die(mysqli_error($conn));

$site_plan_list = [];
$total_unit_kategori = 0;
$total_unit_tersedia = 0;
$total_unit_terjual = 0;

while ($sp = mysqli_fetch_assoc($query_site_plans)) {
    $site_plan_list[] = $sp;
    $total_unit_kategori += $sp['total_unit_sp'];
    $total_unit_tersedia += $sp['unit_tersedia_sp'];
    $total_unit_terjual += $sp['unit_terjual_sp'];
}

// Fetch seluruh unit rumah dengan kategori ini
$query_units = mysqli_query($conn, "SELECT r.*, sp.nama_site_plan, sp.lokasi, sp.penanggung_jawab
    FROM rumah r
    LEFT JOIN site_plan sp ON r.id_site_plan = sp.id_site_plan
    WHERE r.id_kategori = '$id_kategori'
    ORDER BY sp.nama_site_plan ASC, r.kode_blok ASC") or die(mysqli_error($conn));

$units_list = [];
while ($u = mysqli_fetch_assoc($query_units)) {
    $units_list[] = $u;
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
    <title><?= htmlspecialchars($kategori['nama_kategori']) ?> - Detail Tipe Rumah | <?= htmlspecialchars($nama_proyek) ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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

        /* Hero Banner */
        .category-hero {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--secondary-navy) 100%);
            color: #ffffff;
            padding: 50px 0 45px 0;
            position: relative;
        }
        .badge-type {
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
        .category-hero h1 {
            font-size: 2.6rem;
            font-weight: 700;
            margin-bottom: 14px;
        }

        /* Quick Stat Badges */
        .stat-pills-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
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

        /* Main Spec Cards */
        .spec-box-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 26px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.05);
            border: 1px solid #eef2f7;
            height: 100%;
        }
        .spec-feature-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 10px;
            margin-bottom: 12px;
            border: 1px solid #edf2f7;
        }
        .spec-feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: rgba(206, 167, 81, 0.15);
            color: var(--mustrad-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .spec-feature-val {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-navy);
            line-height: 1.2;
        }
        .spec-feature-lbl {
            font-size: 0.8rem;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Gallery / Photo Card */
        .gallery-card {
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #eef2f7;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .gallery-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .gallery-img-wrap {
            height: 220px;
            overflow: hidden;
            position: relative;
            background: var(--secondary-navy);
            cursor: pointer;
        }
        .gallery-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .gallery-card:hover .gallery-img-wrap img {
            transform: scale(1.06);
        }
        .gallery-overlay-btn {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: rgba(10, 25, 47, 0.85);
            color: #ffffff;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            backdrop-filter: blur(4px);
        }
        .gallery-caption {
            padding: 14px 16px;
            font-weight: 600;
            color: var(--primary-navy);
            font-size: 0.95rem;
            border-top: 1px solid #f1f5f9;
        }

        /* Price Table */
        .price-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.05);
            border: 1px solid #eef2f7;
        }
        .price-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            background: #f8fafc;
            border-radius: 10px;
            margin-bottom: 10px;
            border: 1px solid #edf2f7;
        }
        .price-item-name {
            font-weight: 600;
            color: var(--primary-navy);
            font-size: 1rem;
        }
        .price-item-val {
            font-size: 1.15rem;
            font-weight: 700;
            color: #198754;
        }

        /* Site Plan Cards */
        .location-siteplan-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 18px;
            border: 1px solid #eef2f7;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: all 0.25s;
        }
        .location-siteplan-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            border-color: var(--gold-accent);
        }

        /* Unit Cards */
        .unit-card-simple {
            background: #ffffff;
            border-radius: 12px;
            padding: 18px;
            border: 1px solid #eef2f7;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: all 0.25s;
        }
        .unit-card-simple:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
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
        .btn-wa-unit {
            background: #25D366;
            color: #ffffff;
            padding: 7px 12px;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            transition: all 0.2s;
        }
        .btn-wa-unit:hover {
            background: #1eb956;
            color: #ffffff;
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
                <?php if (!empty($wa_number)) : ?>
                    <a href="https://wa.me/<?= $wa_number ?>?text=Halo%20Marketing%20<?= urlencode($nama_proyek) ?>,%20saya%20tertarik%20dengan%20tipe%20rumah%20<?= urlencode($kategori['nama_kategori']) ?>" target="_blank" class="btn btn-warning btn-sm fw-semibold d-none d-sm-inline-flex align-items-center gap-1" style="background-color: var(--mustrad-gold); border-color: var(--mustrad-gold); color: var(--primary-navy);">
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
                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($kategori['nama_kategori']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Category Hero Header -->
    <section class="category-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge-type">
                        <i class="bi bi-layers-fill me-1"></i> Spesifikasi Tipe Hunian
                    </span>
                    <h1><?= htmlspecialchars($kategori['nama_kategori']) ?></h1>
                    <p class="text-white-50 lead fs-6 mb-0" style="max-width: 700px;">
                        Hunian modern berkonsep eksklusif dengan pencahayaan dan sirkulasi alami yang dirancang untuk kenyamanan keluarga Anda.
                    </p>

                    <!-- Quick Stats -->
                    <div class="stat-pills-row">
                        <div class="stat-pill">
                            <i class="bi bi-bounding-box stat-pill-icon"></i>
                            <div>
                                <div class="stat-pill-num"><?= htmlspecialchars($kategori['luas_bangunan']) ?> m&sup2;</div>
                                <div class="stat-pill-label">Luas Bangunan</div>
                            </div>
                        </div>
                        <div class="stat-pill">
                            <i class="bi bi-aspect-ratio stat-pill-icon"></i>
                            <div>
                                <div class="stat-pill-num"><?= htmlspecialchars($kategori['luas_tanah']) ?> m&sup2;</div>
                                <div class="stat-pill-label">Luas Tanah</div>
                            </div>
                        </div>
                        <div class="stat-pill">
                            <i class="bi bi-door-open stat-pill-icon"></i>
                            <div>
                                <div class="stat-pill-num"><?= htmlspecialchars($kategori['jumlah_kamar']) ?></div>
                                <div class="stat-pill-label">Jumlah Kamar</div>
                            </div>
                        </div>
                        <div class="stat-pill">
                            <i class="bi bi-houses-fill stat-pill-icon text-success"></i>
                            <div>
                                <div class="stat-pill-num text-success"><?= $total_unit_tersedia ?></div>
                                <div class="stat-pill-label">Unit Tersedia</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mt-4 mt-lg-0 text-lg-end">
                    <div class="d-inline-flex flex-column text-start bg-white bg-opacity-10 p-4 rounded-3 border border-white border-opacity-10 w-100" style="max-width: 350px;">
                        <span class="text-white-50 small fw-bold text-uppercase">Konsultasi Unit Ini:</span>
                        <h4 class="text-white fw-bold mt-1 mb-3">Tertarik Dengan Tipe Ini?</h4>
                        <p class="text-white-50 small mb-4">Dapatkan informasi promo, simulasi KPR, serta jadwal survei lokasi langsung bersama marketing kami.</p>
                        <?php if (!empty($wa_number)) : ?>
                            <a href="https://wa.me/<?= $wa_number ?>?text=Halo%20Marketing%20<?= urlencode($nama_proyek) ?>,%20saya%20ingin%20konsultasi%20mengenai%20tipe%20rumah%20<?= urlencode($kategori['nama_kategori']) ?>" target="_blank" class="btn btn-warning w-100 fw-bold py-2 d-flex align-items-center justify-content-center gap-2" style="background-color: var(--mustrad-gold); border-color: var(--mustrad-gold); color: var(--primary-navy);">
                                <i class="bi bi-whatsapp fs-5"></i> Chat Marketing
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 1: SPESIFIKASI & DESKRIPSI -->
    <section class="content-section">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                <!-- Kolom Spesifikasi Teknis -->
                <div class="col-lg-6">
                    <div class="spec-box-card">
                        <span class="section-tag">Spesifikasi Fisik</span>
                        <h3 class="section-main-title fs-4 mb-4">Rincian Ukuran & Ruang</h3>

                        <div class="spec-feature-item">
                            <div class="spec-feature-icon">
                                <i class="bi bi-bounding-box"></i>
                            </div>
                            <div>
                                <div class="spec-feature-val"><?= htmlspecialchars($kategori['luas_bangunan']) ?> m&sup2;</div>
                                <div class="spec-feature-lbl">Luas Bangunan (LB)</div>
                            </div>
                        </div>

                        <div class="spec-feature-item">
                            <div class="spec-feature-icon">
                                <i class="bi bi-aspect-ratio"></i>
                            </div>
                            <div>
                                <div class="spec-feature-val"><?= htmlspecialchars($kategori['luas_tanah']) ?> m&sup2;</div>
                                <div class="spec-feature-lbl">Luas Tanah (LT)</div>
                            </div>
                        </div>

                        <div class="spec-feature-item">
                            <div class="spec-feature-icon">
                                <i class="bi bi-door-open"></i>
                            </div>
                            <div>
                                <div class="spec-feature-val"><?= htmlspecialchars($kategori['jumlah_kamar']) ?> Ruang Kamar</div>
                                <div class="spec-feature-lbl">Jumlah Kamar Tidur</div>
                            </div>
                        </div>

                        <div class="spec-feature-item">
                            <div class="spec-feature-icon">
                                <i class="bi bi-pin-map-fill"></i>
                            </div>
                            <div>
                                <div class="spec-feature-val"><?= count($site_plan_list) ?> Kawasan Komplek</div>
                                <div class="spec-feature-lbl">Tersedia di Berbagai Lokasi Site Plan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Deskripsi Rumah & Skema Pembayaran -->
                <div class="col-lg-6">
                    <div class="spec-box-card d-flex flex-column justify-content-between">
                        <div>
                            <span class="section-tag">Deskripsi Properti</span>
                            <h3 class="section-main-title fs-4 mb-3">Tentang Tipe Ini</h3>
                            <div class="text-muted leading-relaxed mb-4" style="line-height: 1.7;">
                                <?= nl2br(htmlspecialchars($kategori['deskripsi'] ?: 'Tipe hunian ini dirancang dengan tata ruang yang fungsional, memanfaatkan sirkulasi udara alami dan pencahayaan optimal untuk menjamin kehangatan dan kenyamanan keluarga Anda.')) ?>
                            </div>
                        </div>

                        <!-- Skema Pembayaran / Harga -->
                        <div>
                            <span class="section-tag">Rencana Pembiayaan</span>
                            <h5 class="fw-bold mb-3 text-navy" style="color: var(--primary-navy);">Skema Harga & Pembayaran</h5>
                            <?php if (empty($harga_list)) : ?>
                                <div class="alert alert-light border small text-muted">
                                    <i class="bi bi-info-circle me-1"></i> Informasi harga detail dapat dikonfirmasi langsung dengan tim marketing kami.
                                </div>
                            <?php else : ?>
                                <?php foreach ($harga_list as $h) : ?>
                                    <div class="price-item-row">
                                        <div class="price-item-name">
                                            <i class="bi bi-wallet2 text-warning me-2"></i><?= htmlspecialchars($h['jenis_pembayaran']) ?>
                                        </div>
                                        <div class="price-item-val"><?= formatRupiah($h['harga']) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: GALERI FOTO SPESIFIKASI RUMAH -->
    <section class="content-section bg-white border-top border-bottom">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-4">
                <div>
                    <span class="section-tag">Galeri Visual</span>
                    <h2 class="section-main-title">Dokumentasi & Desain Rumah</h2>
                    <p class="text-muted small mt-1 mb-0">Lihat tampak depan, denah interior, dan visual pengerjaan untuk tipe <?= htmlspecialchars($kategori['nama_kategori']) ?>.</p>
                </div>
                <div class="text-muted small">
                    Total: <strong class="text-dark"><?= count($foto_list) ?> Foto</strong>
                </div>
            </div>

            <?php if (empty($foto_list)) : ?>
                <div class="alert alert-secondary text-center py-5">
                    <i class="bi bi-images fs-2 d-block mb-2 text-muted"></i>
                    Belum ada foto dokumentasi spesifikasi yang diunggah untuk tipe rumah ini.
                </div>
            <?php else : ?>
                <div class="row g-4">
                    <?php foreach ($foto_list as $f) : 
                        $path_foto = 'assets/fto_rumah/' . $f['foto'];
                        $has_f = file_exists($path_foto);
                    ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="gallery-card">
                                <div class="gallery-img-wrap" onclick="showGalleryModal('<?= $path_foto ?>', '<?= htmlspecialchars(addslashes($f['keterangan_foto'])) ?>')">
                                    <?php if ($has_f) : ?>
                                        <img src="<?= $path_foto ?>" alt="<?= htmlspecialchars($f['keterangan_foto']) ?>" loading="lazy">
                                    <?php else : ?>
                                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white bg-dark">
                                            <i class="bi bi-image fs-1 text-muted mb-2"></i>
                                            <span class="small text-white-50">Foto tidak ditemukan</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="gallery-overlay-btn">
                                        <i class="bi bi-arrows-fullscreen me-1"></i> Perbesar
                                    </div>
                                </div>
                                <div class="gallery-caption">
                                    <i class="bi bi-camera me-1 text-warning"></i>
                                    <?= htmlspecialchars($f['keterangan_foto'] ?: 'Spesifikasi Rumah') ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- SECTION 3: KAWASAN / SITE PLAN TERKAIT -->
    <section class="content-section">
        <div class="container">
            <div class="mb-4">
                <span class="section-tag">Kawasan Perumahan</span>
                <h2 class="section-main-title">Lokasi Komplek Yang Menyediakan Tipe Ini</h2>
                <p class="text-muted small mt-1">Anda dapat menemukan tipe <?= htmlspecialchars($kategori['nama_kategori']) ?> di berbagai kawasan perumahan berikut:</p>
            </div>

            <?php if (empty($site_plan_list)) : ?>
                <div class="alert alert-light border text-center py-4">
                    Belum ada unit bertipe ini yang terhubung ke kawasan site plan.
                </div>
            <?php else : ?>
                <div class="row g-4">
                    <?php foreach ($site_plan_list as $sp) : ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="location-siteplan-card">
                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <div class="bg-primary text-white p-3 rounded-3" style="background-color: var(--primary-navy) !important;">
                                        <i class="bi bi-geo-alt fs-4 text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold mb-1" style="color: var(--primary-navy);">Komplek <?= htmlspecialchars($sp['nama_site_plan']) ?></h5>
                                        <p class="text-muted small mb-0 line-clamp-2" style="font-size: 0.84rem;"><?= htmlspecialchars($sp['lokasi']) ?></p>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mb-3">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill small">
                                        <?= $sp['unit_tersedia_sp'] ?> Unit Tersedia
                                    </span>
                                    <span class="badge bg-light text-dark border px-2 py-1 rounded-pill small">
                                        Total <?= $sp['total_unit_sp'] ?> Unit
                                    </span>
                                </div>

                                <div class="mt-auto">
                                    <a href="detail_site_plan.php?id=<?= $sp['id_site_plan'] ?>" class="btn btn-outline-dark btn-sm w-100 fw-semibold">
                                        <i class="bi bi-map me-1"></i> Buka Site Plan Komplek Ini
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- SECTION 4: DAFTAR UNIT BLOK RUMAH -->
    <section class="content-section bg-white border-top">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-4">
                <div>
                    <span class="section-tag">Daftar Unit</span>
                    <h2 class="section-main-title">Ketersediaan Unit Rumah</h2>
                    <p class="text-muted small mt-1 mb-0">Daftar seluruh nomor blok yang menggunakan tipe <?= htmlspecialchars($kategori['nama_kategori']) ?>.</p>
                </div>
                <div>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill me-2">
                        Tersedia: <?= $total_unit_tersedia ?>
                    </span>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">
                        Terjual: <?= $total_unit_terjual ?>
                    </span>
                </div>
            </div>

            <?php if (empty($units_list)) : ?>
                <div class="alert alert-secondary text-center py-4">
                    Belum ada data unit rumah untuk kategori ini.
                </div>
            <?php else : ?>
                <div class="row g-3">
                    <?php foreach ($units_list as $u) : 
                        $is_avail = ($u['status'] == '0');
                        $wa_kontak_target = !empty($u['penanggung_jawab']) ? preg_replace('/[^0-9]/', '', $u['penanggung_jawab']) : $wa_number;
                        if (substr($wa_kontak_target, 0, 1) === '0') {
                            $wa_kontak_target = '62' . substr($wa_kontak_target, 1);
                        }
                        $msg_unit = urlencode("Halo Marketing, saya tertarik dengan rumah Blok " . $u['kode_blok'] . " (" . $kategori['nama_kategori'] . ") di Komplek " . $u['nama_site_plan'] . ". Apakah unit masih tersedia?");
                    ?>
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="unit-card-simple">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="fw-bold mb-0 text-navy" style="color: var(--primary-navy);"><?= htmlspecialchars($u['kode_blok']) ?></h5>
                                    <?php if ($is_avail) : ?>
                                        <span class="unit-badge-status tersedia">
                                            <i class="bi bi-check-circle-fill"></i> Tersedia
                                        </span>
                                    <?php else : ?>
                                        <span class="unit-badge-status terjual">
                                            <i class="bi bi-x-circle-fill"></i> Terjual
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="text-muted small mb-3">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i>Komplek <?= htmlspecialchars($u['nama_site_plan'] ?? '-') ?>
                                </div>

                                <div class="mt-auto">
                                    <?php if ($is_avail) : ?>
                                        <a href="https://wa.me/<?= $wa_kontak_target ?>?text=<?= $msg_unit ?>" target="_blank" class="btn-wa-unit">
                                            <i class="bi bi-whatsapp"></i> Tanya Unit Ini
                                        </a>
                                    <?php else : ?>
                                        <a href="history_rumah.php?id_rumah=<?= $u['id_rumah'] ?>" class="btn btn-outline-secondary btn-sm w-100" style="font-size: 0.8rem;">
                                            <i class="bi bi-clock-history me-1"></i> Cek Progres
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Modal Zoom Foto Galeri -->
    <div class="modal fade" id="modalFotoGaleri" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header text-white" style="background-color: var(--primary-navy);">
                    <h5 class="modal-title fs-6" id="modalGaleriTitle">Dokumentasi Tipe Rumah</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center bg-dark">
                    <img src="" id="modalGaleriImg" class="img-fluid w-100" style="max-height: 80vh; object-fit: contain;">
                </div>
                <div class="modal-footer bg-light">
                    <span id="modalGaleriCaption" class="text-muted small me-auto"></span>
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
                    <h6 class="text-white fw-bold mb-3">Kontak Marketing</h6>
                    <p class="text-white-50 small">Untuk survei lokasi langsung dan simulasi KPR tipe <?= htmlspecialchars($kategori['nama_kategori']) ?>, hubungi kami.</p>
                    <?php if (!empty($wa_number)) : ?>
                        <a href="https://wa.me/<?= $wa_number ?>?text=Halo%20Marketing,%20saya%20ingin%20konsultasi%20mengenai%20tipe%20<?= urlencode($kategori['nama_kategori']) ?>" target="_blank" class="btn btn-warning btn-sm fw-semibold" style="background-color: var(--mustrad-gold); border-color: var(--mustrad-gold); color: var(--primary-navy);">
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showGalleryModal(src, caption) {
            document.getElementById('modalGaleriImg').src = src;
            document.getElementById('modalGaleriCaption').innerText = caption;
            var myModal = new bootstrap.Modal(document.getElementById('modalFotoGaleri'));
            myModal.show();
        }
    </script>
</body>
</html>

