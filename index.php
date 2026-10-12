<?php
require_once 'database/config.php';

// Fetch web configurations
$query_web = mysqli_query($conn, "SELECT * FROM web WHERE id = 1") or die(mysqli_error($conn));
$result_web = mysqli_fetch_assoc($query_web);

$nama_proyek = $result_web['nama_proyek'];
$alamat_proyek = $result_web['alamat'];
$cp_proyek = $result_web['cp'];
$ig_proyek = $result_web['instagram'];
$tt_proyek = $result_web['tiktok'];


$logo_filename = $result_web['logo'];


// Search Filter Parameters
$search_location = isset($_GET['location']) ? trim(mysqli_real_escape_string($conn, $_GET['location'])) : '';
$search_type     = isset($_GET['type']) ? trim(mysqli_real_escape_string($conn, $_GET['type'])) : '';
$search_price    = isset($_GET['price']) ? trim(mysqli_real_escape_string($conn, $_GET['price'])) : '';

// Count Statistics from Database
$q_count_unit = mysqli_query($conn, "SELECT COUNT(*) as total FROM rumah");
$count_unit = ($q_count_unit && $row = mysqli_fetch_assoc($q_count_unit)) ? $row['total'] : 99;

$q_count_tipe = mysqli_query($conn, "SELECT COUNT(*) as total FROM kategori_rumah");
$count_tipe = ($q_count_tipe && $row = mysqli_fetch_assoc($q_count_tipe)) ? $row['total'] : 9;

$q_count_lokasi = mysqli_query($conn, "SELECT COUNT(*) as total FROM site_plan");
$count_lokasi = ($q_count_lokasi && $row = mysqli_fetch_assoc($q_count_lokasi)) ? $row['total'] : 5;

// Price Formatter Helper
function formatPriceLabel($harga) {
    if ($harga >= 1000000000) {
        $val = $harga / 1000000000;
        return 'Rp ' . (floor($val) == $val ? number_format($val, 0) : number_format($val, 1, ',', '.')) . ' M';
    } elseif ($harga >= 1000000) {
        $val = $harga / 1000000;
        return 'Rp ' . number_format($val, 0, ',', '.') . ' Juta';
    } else {
        return 'Rp ' . number_format($harga, 0, ',', '.');
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $nama_proyek ?> - Properti Impian Anda</title>
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
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Montserrat', sans-serif;
        }

        /* Navbar */
        .navbar {
            background-color: var(--primary-navy);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        .navbar-brand {
            color: var(--text-white) !important;
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: 0.5px;
        }
        .nav-link {
            color: var(--text-light) !important;
            font-weight: 500;
            margin: 0 10px;
            transition: color 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--mustrad-gold) !important;
        }
        .btn-login {
            background-color: transparent;
            border: 2px solid var(--mustrad-gold);
            color: var(--mustrad-gold);
            font-weight: 600;
            padding: 8px 24px;
            border-radius: 4px;
            transition: all 0.3s;
            text-decoration: none;
        }
        .btn-login:hover {
            background-color: var(--mustrad-gold);
            color: var(--primary-navy);
        }
        .navbar-logo {
            display: flex;
            align-items: center;
            margin-right: 12px;
            text-decoration: none;
        }

        .navbar-logo img {
            max-height: 42px;
            width: auto;
            object-fit: contain;
        }

        /* Hero Section */
        .hero {
            background-color: var(--primary-navy);
            position: relative;
            padding: 110px 0 70px 0;
            color: var(--text-white);
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(23,42,69,1) 0%, rgba(10,25,47,0) 70%);
            border-radius: 50%;
            z-index: 0;
        }
        .hero .container {
            position: relative;
            z-index: 1;
        }
        .hero-badge {
            display: inline-block;
            background-color: rgba(206, 167, 81, 0.15);
            color: var(--mustrad-gold);
            padding: 6px 18px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 20px;
            border: 1px solid rgba(206, 167, 81, 0.3);
        }
        .hero h1 {
            font-size: 3.2rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        .hero p {
            color: var(--text-light);
            font-size: 1.1rem;
            max-width: 650px;
            margin: 0 auto 40px auto;
        }

        /* Search Bar */
        .search-bar {
            background: var(--text-white);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            margin-top: 20px;
        }
        .search-bar label {
            color: var(--primary-navy);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        .search-bar .form-control, .search-bar .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 12px 15px;
            font-size: 0.95rem;
            color: #333;
        }
        .search-bar .form-control:focus, .search-bar .form-select:focus {
            border-color: var(--secondary-navy);
            box-shadow: none;
        }
        .btn-search {
            background-color: var(--secondary-navy);
            color: var(--text-white);
            padding: 12px 20px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-search:hover {
            background-color: var(--mustrad-gold);
            color: var(--primary-navy);
        }


        .info-card {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            height: 100%;
            padding: 18px;
            border: 1px solid #eef0f4;
            border-radius: 10px;
            background: #fff;
        }
        .info-card-icon {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: var(--secondary-navy);
            color: var(--mustrad-gold);
            font-size: 1.5rem;
        }
        .info-card-label {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
        }
        .info-card-number {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.9rem;
            font-weight: 700;
            line-height: 1.1;
            color: var(--primary-navy);
        }
        .info-card-number small {
            font-size: 0.85rem;
            font-weight: 500;
            color: #6c757d;
        }
        .info-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
        }
        .info-tag {
            background: rgba(206, 167, 81, 0.15);
            color: #8a6d1f;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        /* Featured Properties Section */
        .featured-properties {
            padding-top: 80px;
            padding-bottom: 80px;
            background-color: #f8f9fa;
        }
        .section-title {
            color: var(--primary-navy);
            font-weight: 700;
            margin-bottom: 15px;
        }
        .section-subtitle {
            color: #6c757d;
            margin-bottom: 50px;
        }

        /* Property & Site Plan Card */
        .property-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 30px;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #edf0f5;
            position: relative;
        }
        .property-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 35px rgba(10,25,47,0.12);
            border-color: var(--gold-accent);
        }
        .property-img {
            height: 230px;
            background-color: var(--secondary-navy);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .property-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.92;
            transition: transform 0.5s ease, opacity 0.3s;
        }
        .property-card:hover .property-img img {
            opacity: 1;
            transform: scale(1.06);
        }
        .property-price {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: rgba(10, 25, 47, 0.85);
            color: var(--mustrad-gold);
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.82rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(206, 167, 81, 0.3);
        }
        .property-badge-status {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #198754;
            color: #ffffff;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .property-content {
            padding: 22px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .property-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-navy);
            margin-bottom: 8px;
            text-decoration: none;
            display: block;
            transition: color 0.25s;
        }
        .property-title:hover {
            color: var(--mustrad-gold);
        }
        .property-location {
            color: #6c757d;
            font-size: 0.88rem;
            margin-bottom: 14px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .property-stats-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 14px;
        }
        .property-stat-pill {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            font-size: 0.78rem;
            font-weight: 600;
            color: #475569;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .property-type-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 18px;
        }
        .property-type-chip {
            background: rgba(206, 167, 81, 0.12);
            color: #8c6b1b;
            font-size: 0.73rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
        }
        .property-actions {
            margin-top: auto;
            border-top: 1px solid #edf2f7;
            padding-top: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .btn-open-siteplan {
            background-color: var(--primary-navy);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 9px 16px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
            flex-grow: 1;
            justify-content: center;
        }
        .btn-open-siteplan:hover {
            background-color: var(--mustrad-gold);
            color: var(--primary-navy);
            transform: translateY(-1px);
        }
        .property-social-links {
            display: flex;
            gap: 6px;
        }
        .social-circle-link {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            background: #f1f5f9;
            color: var(--primary-navy);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.25s;
        }
        .social-circle-link:hover {
            background: var(--mustrad-gold);
            color: var(--primary-navy);
        }
        
        .section-label {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--mustrad-gold);
            margin-bottom: 8px;
            display: block;
        }

        /* About Us Section */
        .about-section {
            padding: 80px 0;
            background-color: #ffffff;
        }
        .about-img-box {
            position: relative;
            padding-left: 20px;
            padding-top: 20px;
        }
        .about-img-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 80%;
            height: 80%;
            background-color: var(--gold-accent);
            z-index: 0;
            border-radius: 10px;
        }
        .about-img-box img {
            position: relative;
            z-index: 1;
            border-radius: 10px;
            width: 100%;
            object-fit: cover;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        .stat-item {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(206, 167, 81, 0.15);
            color: var(--mustrad-gold);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 15px;
        }
        .stat-number {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--primary-navy);
            line-height: 1;
        }
        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
        }

        /* Location & Access Section */
        .location-section {
            background-color: var(--primary-navy);
            color: var(--text-white);
            padding: 80px 0;
        }
        .access-card {
            background-color: var(--secondary-navy);
            padding: 25px;
            border-radius: 10px;
            height: 100%;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            transition: transform 0.3s;
        }
        .access-card:hover {
            transform: translateY(-5px);
        }
        .access-icon {
            width: 45px;
            height: 45px;
            background-color: var(--gold-accent);
            color: var(--primary-navy);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .access-card h5 {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .access-card p {
            font-size: 0.85rem;
            color: var(--text-light);
            margin: 0;
        }

        /* Features / Why Choose Us */
        .features-section {
            background-color: var(--primary-navy);
            border-top: 1px solid rgba(255,255,255,0.08);
            color: var(--text-white);
            padding: 60px 0;
        }
        .feature-box {
            text-align: center;
            padding: 15px;
        }
        .feature-box i {
            font-size: 2.2rem;
            color: var(--gold-accent);
            margin-bottom: 15px;
            display: inline-block;
        }
        .feature-box h5 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .feature-box p {
            font-size: 0.8rem;
            color: var(--text-light);
            margin: 0;
        }

        /* How to Buy Section */
        .buy-process-section {
            padding: 80px 0;
            background-color: #ffffff;
        }
        .step-card {
            text-align: center;
            position: relative;
        }
        .step-number {
            width: 32px;
            height: 32px;
            background-color: var(--gold-accent);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            margin: 0 auto 15px auto;
        }
        .step-icon {
            width: 60px;
            height: 60px;
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary-navy);
            margin: 0 auto 15px auto;
        }
        .step-card h5 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--primary-navy);
            margin-bottom: 6px;
        }
        .step-card p {
            font-size: 0.8rem;
            color: #6c757d;
            margin: 0;
        }

        /* Promo Banner Section */
        .promo-banner {
            background: linear-gradient(rgba(10, 25, 47, 0.88), rgba(10, 25, 47, 0.88)), url('assets/images/bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 60px 0;
        }
        .btn-gold {
            background-color: var(--gold-accent);
            color: var(--primary-navy);
            font-weight: 700;
            padding: 10px 24px;
            border-radius: 6px;
            border: none;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-gold:hover {
            background-color: var(--mustrad-gold);
            color: #fff;
        }

        /* Footer Custom */
        .footer {
            background-color: var(--primary-navy);
            color: var(--text-light);
            padding: 60px 0 20px;
        }
        .footer-brand {
            color: var(--text-white);
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 15px;
            display: inline-block;
            text-decoration: none;
        }
        .footer h5 {
            color: var(--text-white);
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 1rem;
        }
        .footer-links {
            list-style: none;
            padding: 0;
        }
        .footer-links li {
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
        .footer-links a {
            color: var(--text-light);
            text-decoration: none;
            transition: color 0.3s;
        }
        .footer-links a:hover {
            color: var(--mustrad-gold);
        }
        .copyright {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 40px;
            padding-top: 20px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a href="index.php" class="navbar-logo">
                <?php if (!empty($logo_filename) && file_exists('assets/logo/' . $logo_filename)) : ?>
                    <img src="assets/logo/<?= htmlspecialchars($logo_filename) ?>" alt="Logo <?= htmlspecialchars($nama_proyek) ?>">
                <?php else : ?>
                    <i class="bi bi-building fs-3 text-warning"></i>
                <?php endif; ?>
                <span class="navbar-brand ms-2"><?= htmlspecialchars($nama_proyek) ?></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list text-white fs-1"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#properti">Site Plan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang-kami">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    <?php if (isset($_SESSION['username']) && isset($_SESSION['peran'])) : ?>
                        <?php $dashboard_url = ($_SESSION['peran'] == 'A') ? 'admin_home' : 'marketing_home'; ?>
                        <a href="<?= $dashboard_url ?>" class="btn btn-login"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                        <a href="logout.php" class="btn btn-outline-light btn-sm px-3 py-2" title="Logout"><i class="bi bi-box-arrow-right"></i></a>
                    <?php else : ?>
                        <a href="login.php" class="btn btn-login"><i class="bi bi-box-arrow-in-right me-2"></i>Login Staff</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-center">
        <div class="container">
            <span class="hero-badge">
                <i class="bi bi-shield-check me-2"></i>Developer & Kawasan Hunian Terpercaya
            </span>
            <h1>Membangun Hunian Impian,<br>Menghadirkan Masa Depan</h1>
            <p>Eksplorasi denah site plan dan hunian pilihan terbaik di berbagai komplek strategis. Klik salah satu kawasan untuk melihat ketersediaan unit dan detail rumah impian Anda.</p>
            
            <div class="search-bar text-start">
                <div class="row g-3 align-items-center mb-3">
                    <div class="col-lg-9 col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3"><i class="bi bi-search"></i></span>
                            <input type="text" id="filterKomplekInput" class="form-control border-start-0 ps-2" placeholder="Cari nama komplek atau lokasi perumahan..." onkeyup="filterSiteplanCards()">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <a href="#properti" class="btn btn-search w-100 text-center">
                            <i class="bi bi-map me-1"></i> Eksplorasi Site Plan
                        </a>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- Site Plan Stat -->
                    <div class="col-lg-4 col-md-6">
                        <div class="info-card">
                            <div class="info-card-icon"><i class="bi bi-map"></i></div>
                            <div>
                                <?php 
                                $query_total_site_plan = mysqli_query($conn, "SELECT COUNT(id_site_plan) AS total_site_plan FROM site_plan") or die(mysqli_error($conn));
                                $tl_stplan = mysqli_fetch_array($query_total_site_plan);
                                ?>
                                <div class="info-card-label">Kawasan Perumahan</div>
                                <div class="info-card-number"><?= $tl_stplan['total_site_plan'] ?> <small>lokasi</small></div>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori Rumah Stat -->
                    <div class="col-lg-4 col-md-6">
                        <div class="info-card">
                            <div class="info-card-icon"><i class="bi bi-houses"></i></div>
                            <div>
                                <?php 
                                $query_total_kategori = mysqli_query($conn, "SELECT COUNT(id_kategori) AS total_kategori FROM kategori_rumah") or die(mysqli_error($conn));
                                $tl_kat = mysqli_fetch_array($query_total_kategori);
                                ?>
                                <div class="info-card-label">Kategori / Tipe Rumah</div>
                                <div class="info-card-number"><?= $tl_kat['total_kategori'] ?> <small>tipe</small></div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Rumah Stat -->
                    <div class="col-lg-4 col-md-12">
                        <div class="info-card">
                            <div class="info-card-icon"><i class="bi bi-house-check-fill"></i></div>
                            <div>
                                <?php 
                                $query_total_rumah = mysqli_query($conn, "SELECT COUNT(id_rumah) AS total_rumah FROM rumah") or die(mysqli_error($conn));
                                $tl_rmh = mysqli_fetch_array($query_total_rumah);
                                ?>
                                <div class="info-card-label">Total Unit Rumah</div>
                                <div class="info-card-number"><?= $tl_rmh['total_rumah'] ?> <small>unit</small></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Properties / Site Plan Section -->
    <section id="properti" class="featured-properties">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-label">Masterplan & Properti</span>
                <h2 class="section-title">Kawasan Site Plan Perumahan</h2>
                <p class="section-subtitle">Klik pada salah satu kawasan komplek untuk melihat denah masterplan, kategori rumah, dan daftar ketersediaan unit di dalamnya.</p>
            </div>

            <div class="row g-4" id="siteplanCardsGrid">
                <?php 
                $query_site_plan = mysqli_query($conn, "SELECT * FROM site_plan ORDER BY id_site_plan ASC") or die(mysqli_error($conn));

                while ($site_plan = mysqli_fetch_array($query_site_plan)) { 
                    $sp_id = $site_plan['id_site_plan'];
                    
                    // Hitung total unit dan unit tersedia
                    $q_sp_unit = mysqli_query($conn, "SELECT 
                        COUNT(id_rumah) AS total,
                        SUM(CASE WHEN status='0' THEN 1 ELSE 0 END) AS tersedia,
                        SUM(CASE WHEN status='1' THEN 1 ELSE 0 END) AS terjual
                        FROM rumah WHERE id_site_plan = '$sp_id'");
                    $sp_stat = mysqli_fetch_assoc($q_sp_unit);
                    $sp_total = $sp_stat['total'] ?? 0;
                    $sp_tersedia = $sp_stat['tersedia'] ?? 0;

                    // Ambil beberapa tipe/kategori rumah yang ada di site plan ini
                    $q_sp_kat = mysqli_query($conn, "SELECT DISTINCT k.nama_kategori 
                        FROM kategori_rumah k 
                        JOIN rumah r ON k.id_kategori = r.id_kategori 
                        WHERE r.id_site_plan = '$sp_id' 
                        ORDER BY k.nama_kategori ASC 
                        LIMIT 3");
                    $kat_list = [];
                    while ($kl = mysqli_fetch_assoc($q_sp_kat)) {
                        $kat_list[] = $kl['nama_kategori'];
                    }

                    $gambar_brosur = $site_plan['brosur'];
                    $path_brosur = 'assets/brosur/' . $gambar_brosur;
                    $has_brosur = !empty($gambar_brosur) && file_exists($path_brosur);

                    $wa_penanggung = !empty($site_plan['penanggung_jawab']) ? preg_replace('/[^0-9]/', '', $site_plan['penanggung_jawab']) : preg_replace('/[^0-9]/', '', $cp_proyek);
                    if (substr($wa_penanggung, 0, 1) === '0') {
                        $wa_penanggung = '62' . substr($wa_penanggung, 1);
                    }
                ?>
                    <div class="col-lg-4 col-md-6 siteplan-card-col" data-name="<?= strtolower(htmlspecialchars($site_plan['nama_site_plan'])) ?>" data-location="<?= strtolower(htmlspecialchars($site_plan['lokasi'])) ?>">
                        <div class="property-card">
                            <a href="detail_site_plan.php?id=<?= $sp_id ?>" class="property-img text-decoration-none">
                                <?php if ($has_brosur) : ?>
                                    <img src="<?= $path_brosur ?>" alt="Denah Komplek <?= htmlspecialchars($site_plan['nama_site_plan']) ?>" loading="lazy">
                                <?php else : ?>
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white bg-dark p-3 text-center">
                                        <i class="bi bi-map fs-1 text-warning mb-2"></i>
                                        <span class="fw-bold small text-light">Denah Komplek</span>
                                        <span class="text-white-50 small"><?= htmlspecialchars($site_plan['nama_site_plan']) ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="property-badge-status">
                                    <i class="bi bi-check-circle-fill me-1"></i><?= $sp_tersedia ?> Unit Tersedia
                                </div>
                                <div class="property-price">
                                    <i class="bi bi-map me-1"></i>Denah Site Plan
                                </div>
                            </a>
                            <div class="property-content">
                                <a href="detail_site_plan.php?id=<?= $sp_id ?>" class="property-title">
                                    Komplek <?= htmlspecialchars($site_plan['nama_site_plan']) ?>
                                </a>
                                <div class="property-location" title="<?= htmlspecialchars($site_plan['lokasi']) ?>">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($site_plan['lokasi']) ?>
                                </div>
                                
                                <div class="property-stats-grid">
                                    <span class="property-stat-pill" title="Total Unit Rumah">
                                        <i class="bi bi-houses text-primary"></i> <?= $sp_total ?> Unit Total
                                    </span>
                                    <span class="property-stat-pill text-success" title="Unit Siap Huni / Tersedia">
                                        <i class="bi bi-check2-circle text-success"></i> <?= $sp_tersedia ?> Tersedia
                                    </span>
                                </div>

                                <?php if (!empty($kat_list)) : ?>
                                    <div class="property-type-tags">
                                        <?php foreach ($kat_list as $kn) : ?>
                                            <span class="property-type-chip"><i class="bi bi-layers me-1"></i><?= htmlspecialchars($kn) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="property-actions">
                                    <a href="detail_site_plan.php?id=<?= $sp_id ?>" class="btn-open-siteplan">
                                        <span>Lihat Site Plan & Rumah</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                    <div class="property-social-links">
                                        <?php if (!empty($site_plan['ig'])) : ?>
                                            <a href="https://instagram.com/<?= htmlspecialchars(ltrim($site_plan['ig'], '@')) ?>" target="_blank" class="social-circle-link" title="Instagram Komplek">
                                                <i class="bi bi-instagram"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($site_plan['tiktok'])) : ?>
                                            <a href="https://tiktok.com/<?= htmlspecialchars($site_plan['tiktok']) ?>" target="_blank" class="social-circle-link" title="TikTok Komplek">
                                                <i class="bi bi-tiktok"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($wa_penanggung)) : ?>
                                            <a href="https://wa.me/<?= $wa_penanggung ?>?text=Halo%20Marketing,%20saya%20tertarik%20dengan%20Komplek%20<?= urlencode($site_plan['nama_site_plan']) ?>" target="_blank" class="social-circle-link text-success" title="WhatsApp Marketing">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- Empty Search State -->
            <div id="noSiteplanFound" class="alert alert-warning text-center py-4 mt-3 d-none">
                <i class="bi bi-search fs-3 d-block mb-2"></i>
                Komplek perumahan tidak ditemukan. Silakan coba kata kunci lain.
            </div>
        </div>
    </section>

    <!-- Section 1: Tentang Kami & Statistik -->
    <section id="tentang-kami" class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-img-box">
                        <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Hunian Nyaman">
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <span class="section-label">Tentang Kami</span>
                    <h2 class="section-title mb-3">Hunian Nyaman<br>untuk Masa Depan</h2>
                    <p class="text-muted mb-3"><?= htmlspecialchars($nama_proyek) ?> merupakan developer & platform terpercaya yang bergerak dalam pembangunan dan pengembangan kawasan hunian. Kami menghadirkan pilihan rumah yang dirancang dengan memperhatikan kenyamanan, kualitas, keamanan, dan kebutuhan masyarakat.</p>
                    <p class="text-muted mb-4">Dengan konsep hunian yang modern dan lingkungan yang nyaman, kami berkomitmen memberikan tempat tinggal yang tidak hanya menjadi rumah, tetapi juga menjadi bagian dari masa depan setiap keluarga.</p>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-icon"><i class="bi bi-house-door"></i></div>
                                <div>
                                    <div class="stat-number"><?= $count_unit ?>+</div>
                                    <div class="stat-label">Unit Hunian</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-icon"><i class="bi bi-layers"></i></div>
                                <div>
                                    <div class="stat-number"><?= $count_tipe ?>+</div>
                                    <div class="stat-label">Tipe Rumah</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-icon"><i class="bi bi-geo-alt"></i></div>
                                <div>
                                    <div class="stat-number"><?= $count_lokasi ?>+</div>
                                    <div class="stat-label">Lokasi Pengembangan</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-icon"><i class="bi bi-shield-check"></i></div>
                                <div>
                                    <div class="stat-number">100%</div>
                                    <div class="stat-label">Kualitas & Kenyamanan</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2: Lokasi & Akses Strategis -->
    <section class="location-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-5">
                    <span class="section-label text-white-50">Lokasi & Akses</span>
                    <h2 class="text-white fw-bold">Lokasi yang Strategis</h2>
                </div>
                <div class="col-lg-7">
                    <p class="text-white-50 m-0">Hunian yang baik tidak hanya tentang bangunan, tetapi juga tentang kemudahan akses menuju berbagai kebutuhan sehari-hari.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="access-card">
                        <div class="access-icon"><i class="bi bi-cart3"></i></div>
                        <div>
                            <h5>Perdagangan</h5>
                            <p>Akses mudah menuju pusat perbelanjaan, pasar, pertokoan, dan berbagai kebutuhan sehari-hari.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="access-card">
                        <div class="access-icon"><i class="bi bi-hospital"></i></div>
                        <div>
                            <h5>Kesehatan</h5>
                            <p>Dekat dengan fasilitas kesehatan seperti klinik, puskesmas, rumah sakit, dan fasilitas kesehatan lainnya.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="access-card">
                        <div class="access-icon"><i class="bi bi-mortarboard"></i></div>
                        <div>
                            <h5>Pendidikan</h5>
                            <p>Kemudahan akses menuju berbagai fasilitas pendidikan mulai dari sekolah hingga perguruan tinggi.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="access-card">
                        <div class="access-icon"><i class="bi bi-signpost-turn-right"></i></div>
                        <div>
                            <h5>Kemudahan Akses</h5>
                            <p>Lokasi yang memiliki akses menuju jalan utama dan berbagai kawasan di sekitarnya.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="access-card">
                        <div class="access-icon"><i class="bi bi-tree"></i></div>
                        <div>
                            <h5>Lingkungan Nyaman</h5>
                            <p>Kawasan hunian yang dirancang untuk menciptakan lingkungan tempat tinggal yang nyaman dan kondusif.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="access-card">
                        <div class="access-icon"><i class="bi bi-shield-lock"></i></div>
                        <div>
                            <h5>Hunian Aman & Nyaman</h5>
                            <p>Mengutamakan keamanan, kenyamanan, serta kualitas lingkungan untuk mendukung kehidupan keluarga.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Keunggulan -->
    <section class="features-section">
        <div class="container">
            <div class="mb-4">
                <span class="section-label text-white-50">Keunggulan</span>
                <h3 class="text-white fw-bold">Mengapa Memilih <?= $nama_proyek ?>?</h3>
            </div>
            <div class="row g-4">
                <div class="col-lg col-md-4">
                    <div class="feature-box">
                        <i class="bi bi-award"></i>
                        <h5>Kualitas Pembangunan</h5>
                        <p>Kami mengutamakan kualitas dalam setiap proses pembangunan hunian.</p>
                    </div>
                </div>
                <div class="col-lg col-md-4">
                    <div class="feature-box">
                        <i class="bi bi-house-heart"></i>
                        <h5>Lingkungan Nyaman</h5>
                        <p>Kawasan dirancang untuk memberikan lingkungan tempat tinggal yang nyaman.</p>
                    </div>
                </div>
                <div class="col-lg col-md-4">
                    <div class="feature-box">
                        <i class="bi bi-geo-alt"></i>
                        <h5>Lokasi Strategis</h5>
                        <p>Memudahkan penghuni menjangkau berbagai fasilitas dan kebutuhan.</p>
                    </div>
                </div>
                <div class="col-lg col-md-4">
                    <div class="feature-box">
                        <i class="bi bi-houses"></i>
                        <h5>Pilihan Hunian Beragam</h5>
                        <p>Tersedia pilihan tipe rumah yang dapat disesuaikan dengan kebutuhan.</p>
                    </div>
                </div>
                <div class="col-lg col-md-4">
                    <div class="feature-box">
                        <i class="bi bi-journal-check"></i>
                        <h5>Proses Pembelian Terarah</h5>
                        <p>Memandu setiap langkah pembelian dari konsultasi hingga serah terima.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 4: Cara Pembelian -->
    <section class="buy-process-section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-label">Cara Pembelian</span>
                <h2 class="section-title">Mudah Memiliki Hunian Impian</h2>
            </div>
            <div class="row g-4">
                <div class="col-md">
                    <div class="step-card">
                        <div class="step-number">01</div>
                        <div class="step-icon"><i class="bi bi-house-door"></i></div>
                        <h5>Pilih Unit</h5>
                        <p>Pilih tipe dan unit rumah yang sesuai kebutuhan Anda.</p>
                    </div>
                </div>
                <div class="col-md">
                    <div class="step-card">
                        <div class="step-number">02</div>
                        <div class="step-icon"><i class="bi bi-journal-bookmark"></i></div>
                        <h5>Booking</h5>
                        <p>Lakukan proses booking pada unit yang telah dipilih.</p>
                    </div>
                </div>
                <div class="col-md">
                    <div class="step-card">
                        <div class="step-number">03</div>
                        <div class="step-icon"><i class="bi bi-wallet2"></i></div>
                        <h5>Pembayaran</h5>
                        <p>Lanjutkan proses pembayaran sesuai skema pilihan.</p>
                    </div>
                </div>
                <div class="col-md">
                    <div class="step-card">
                        <div class="step-number">04</div>
                        <div class="step-icon"><i class="bi bi-key"></i></div>
                        <h5>Serah Terima</h5>
                        <p>Nikmati hunian baru bersama keluarga.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 5: Promo Banner -->
    <section class="promo-banner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <span class="section-label" style="color: var(--gold-accent);">Promo & Penawaran Terbaru</span>
                    <h2 class="fw-bold text-white mb-2">Dapatkan Informasi Terbaru Mengenai Promo & Penawaran Terbatas</h2>
                    <p class="text-white-50 m-0">Dapatkan informasi promo, ketersediaan unit, dan penawaran menarik dari <?= htmlspecialchars($nama_proyek) ?>.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="https://wa.me/<?= $cp_proyek ?>?text=Halo%20<?= urlencode($nama_proyek) ?>,%20saya%20ingin%20menanyakan%20promo%20dan%20unit%20properti" target="_blank" class="btn-gold">Hubungi Marketing <i class="bi bi-whatsapp ms-1"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 6: Footer -->
    <footer id="kontak" class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <a href="index.php" class="footer-brand"><?= htmlspecialchars($nama_proyek) ?></a>
                    <p class="text-white-50 small mb-3">Kantor Developer:<br><i class="bi bi-geo-alt me-1"></i> <?= htmlspecialchars($alamat_proyek) ?></p>
                    <p class="text-white-50 small mb-1"><i class="bi bi-telephone me-2"></i><?= htmlspecialchars($cp_proyek) ?></p>
                    <p class="text-white-50 small"><i class="bi bi-envelope me-2"></i>info@<?= strtolower(str_replace(' ', '', $nama_proyek)) ?>.com</p>
                </div>
                <div class="col-lg-3">
                    <h5>Proyek Perumahan</h5>
                    <ul class="footer-links">
                        <?php
                        $q_site_footer = mysqli_query($conn, "SELECT * FROM site_plan ORDER BY id_site_plan DESC LIMIT 5");
                        if ($q_site_footer && mysqli_num_rows($q_site_footer) > 0) {
                            while ($sp_f = mysqli_fetch_assoc($q_site_footer)) {
                                echo '<li><a href="detail_site_plan.php?id=' . $sp_f['id_site_plan'] . '"><i class="bi bi-chevron-right me-1 small"></i>' . htmlspecialchars($sp_f['nama_site_plan']) . '</a></li>';
                            }
                        } else {
                            echo '<li><a href="#properti">Perumahan Griya Sejahtera</a></li>';
                            echo '<li><a href="#properti">Perumahan Taman Indah</a></li>';
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-lg-2">
                    <h5>Menu Utama</h5>
                    <ul class="footer-links">
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="#properti">Site Plan</a></li>
                        <li><a href="#tentang-kami">Tentang Kami</a></li>
                        <li><a href="#kontak">Kontak</a></li>
                        <li><a href="login.php">Login Staff</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5>Sosial Media</h5>
                    <div class="d-flex gap-3 mb-3">
                        <?php if (!empty($ig_proyek)) : ?>
                            <a href="https://instagram.com/<?= htmlspecialchars(ltrim($ig_proyek, '@')) ?>" target="_blank" class="text-light fs-4"><i class="bi bi-instagram"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($tt_proyek)) : ?>
                            <a href="https://tiktok.com/<?= htmlspecialchars($tt_proyek) ?>" target="_blank" class="text-light fs-4"><i class="bi bi-tiktok"></i></a>
                        <?php endif; ?>
                        <a href="https://wa.me/<?= $cp_proyek ?>" target="_blank" class="text-light fs-4"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <div class="copyright text-center">
                &copy; <?php echo date('Y'); ?> <?= htmlspecialchars($nama_proyek) ?>. All Rights Reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Navbar Scroll & Siteplan Filter Script -->
    <script>
        window.addEventListener('scroll', function() {
            var navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 4px 15px rgba(0,0,0,0.3)';
                navbar.style.padding = '0.5rem 0';
            } else {
                navbar.style.boxShadow = 'none';
                navbar.style.padding = '1rem 0';
            }
        });

        // Quick Search Filter for Site Plan Cards
        function filterSiteplanCards() {
            var query = document.getElementById('filterKomplekInput').value.toLowerCase().trim();
            var cards = document.querySelectorAll('.siteplan-card-col');
            var visibleCount = 0;

            cards.forEach(function(card) {
                var name = card.getAttribute('data-name') || '';
                var location = card.getAttribute('data-location') || '';

                if (name.includes(query) || location.includes(query)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            var noFoundAlert = document.getElementById('noSiteplanFound');
            if (noFoundAlert) {
                if (visibleCount === 0) {
                    noFoundAlert.classList.remove('d-none');
                } else {
                    noFoundAlert.classList.add('d-none');
                }
            }
        }
    </script>
</body>
</html>