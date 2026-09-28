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

        /* Property Card */
        .property-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 30px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }
        .property-img {
            height: 230px;
            background-color: var(--secondary-navy);
            position: relative;
            overflow: hidden;
        }
        .property-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.9;
            transition: transform 0.5s ease, opacity 0.3s;
        }
        .property-card:hover .property-img img {
            opacity: 1;
            transform: scale(1.05);
        }
        .property-price {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: var(--mustrad-gold);
            color: var(--primary-navy);
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 1.05rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .property-content {
            padding: 22px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .property-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-navy);
            margin-bottom: 10px;
            text-decoration: none;
            display: block;
        }
        .property-title:hover {
            color: var(--mustrad-gold);
        }
        .property-location {
            color: #6c757d;
            font-size: 0.88rem;
            margin-bottom: 18px;
            line-height: 1.4;
            flex-grow: 1;
        }
        .property-facilities {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #eee;
            padding-top: 15px;
            color: #555;
            font-size: 0.88rem;
        }
        .facility i {
            color: var(--mustrad-gold);
            margin-right: 5px;
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
                <img src="../assets/logo/<?= $logo_filename ?>" alt="Logo <?= $nama_proyek ?>">
                <span class="navbar-brand ms-2"><?=$nama_proyek ?></span>
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
                        <a class="nav-link" href="#properti">Properti</a>
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
                        <a href="login.php" class="btn btn-login"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-center">
        <div class="container">
            <span class="hero-badge">
                <i class="bi bi-building me-2"></i>Developer Perumahan Terpercaya
            </span>
            <h1>Membangun Hunian,<br>Menghadirkan Masa Depan</h1>
            <p>Eksplorasi daftar properti terverifikasi di berbagai lokasi strategis. Kami membantu Anda menemukan rumah yang sempurna sesuai dengan gaya hidup dan budget Anda.</p>
            
            <div class="search-bar text-start">
                <form action="index.php#properti" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <label for="location">Lokasi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-geo-alt text-muted"></i></span>
                                <select class="form-select" id="property-location" name="location">
                                <option value="">Semua Tipe</option>
                                <?php 
                                $q_st_pln = mysqli_query($conn, "SELECT * FROM site_plan ORDER BY nama_site_plan ASC");
                                if ($q_st_pln) {
                                    while ($st = mysqli_fetch_assoc($q_st_pln)) {
                                        $selected = ($search_type == $st['id_site_plan']) ? 'selected' : '';
                                        echo '<option value="' . $st['id_site_plan'] . '" ' . $selected . '>' . htmlspecialchars($st['nama_site_plan']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="property-type">Tipe Rumah</label>
                            <select class="form-select" id="property-type" name="type">
                                <option value="">Semua Tipe</option>
                                <?php 
                                $q_kat_opt = mysqli_query($conn, "SELECT * FROM kategori_rumah ORDER BY nama_kategori ASC");
                                if ($q_kat_opt) {
                                    while ($kat = mysqli_fetch_assoc($q_kat_opt)) {
                                        $selected = ($search_type == $kat['id_kategori']) ? 'selected' : '';
                                        echo '<option value="' . $kat['id_kategori'] . '" ' . $selected . '>' . htmlspecialchars($kat['nama_kategori']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="price-range">Budget</label>
                            <select class="form-select" id="price-range" name="price">
                                <option value="">Semua Harga</option>
                                <option value="1" <?= ($search_price == '1') ? 'selected' : '' ?>>&lt; Rp 500 Juta</option>
                                <option value="2" <?= ($search_price == '2') ? 'selected' : '' ?>>Rp 500 Juta - 1 Miliar</option>
                                <option value="3" <?= ($search_price == '3') ? 'selected' : '' ?>>&gt; Rp 1 Miliar</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <button type="submit" class="btn btn-search w-100">
                                <i class="bi bi-search me-2"></i>Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Featured Properties Section -->
    <section id="properti" class="featured-properties">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Properti Pilihan</h2>
                <p class="section-subtitle">Temukan rumah dan hunian terbaik yang tersedia untuk keluarga Anda</p>
            </div>

            <div class="row g-4">
                <?php
                $sql_properties = "SELECT kr.*, sp.id_site_plan, sp.nama_site_plan, sp.lokasi,
                (SELECT foto FROM foto_rumah fr WHERE fr.id_kategori = kr.id_kategori ORDER BY fr.id_foto_rumah ASC LIMIT 1) as foto
                FROM kategori_rumah kr
                JOIN rumah r ON r.id_kategori = kr.id_kategori
                JOIN site_plan sp ON sp.id_site_plan = r.id_site_plan";

                $where_clauses = [];
                if (!empty($search_location)) {
                    $where_clauses[] = "sp.id_site_plan = '$search_location'";
                }
                if (!empty($search_type)) {
                    $where_clauses[] = "kr.id_kategori = '$search_type'";
                }
                if (!empty($search_price)) {
                    if ($search_price == '1') {
                        $where_clauses[] = "kr.harga < 500000000";
                    } elseif ($search_price == '2') {
                        $where_clauses[] = "kr.harga BETWEEN 500000000 AND 1000000000";
                    } elseif ($search_price == '3') {
                        $where_clauses[] = "kr.harga > 1000000000";
                    }
                }

                if (count($where_clauses) > 0) {
                    $sql_properties .= " WHERE " . implode(" AND ", $where_clauses);
                }

                $sql_properties .= " ORDER BY kr.id_kategori DESC";
                $res_properties = mysqli_query($conn, $sql_properties);

                // Fallback unsplash photos array for realistic demo rendering
                $fallback_photos = [
                    "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    "https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    "https://images.unsplash.com/photo-1600607687920-4e2a09be15b1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    "https://images.unsplash.com/photo-1580587771525-78b9dba3b914?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    "https://images.unsplash.com/photo-1570129477492-45c003edd2be?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                ];

                if (mysqli_num_rows($res_properties) > 0) {
                    $idx = 0;
                    while ($prop = mysqli_fetch_assoc($res_properties)) {
                        $foto_url = '';
                        if (!empty($prop['foto']) && file_exists('assets/fto_rumah/' . $prop['foto'])) {
                            $foto_url = 'assets/fto_rumah/' . $prop['foto'];
                        } else {
                            $foto_url = $fallback_photos[$idx % count($fallback_photos)];
                        }
                        
                        $prop_location = !empty($prop['lokasi']) ? $prop['lokasi'] : $alamat_proyek;
                        $display_title = !empty($prop['nama_site_plan']) ? $prop['nama_site_plan'] . ' (' . $prop['nama_kategori'] . ')' : $prop['nama_kategori'];
                        $idx++;
                ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="property-card">
                                <div class="property-img">
                                    <img src="<?= htmlspecialchars($foto_url) ?>" alt="<?= htmlspecialchars($prop['nama_kategori']) ?>">
                                    <div class="property-price"><?= formatPriceLabel($prop['harga']) ?></div>
                                </div>
                                <div class="property-content">
                                    <span class="property-title"><?= htmlspecialchars($display_title) ?></span>
                                    <div class="property-location">
                                        <i class="bi bi-geo-alt text-muted me-1"></i><?= htmlspecialchars($prop_location) ?>
                                    </div>
                                    <div class="property-facilities">
                                        <div class="facility" title="Kamar Tidur"><i class="bi bi-moon-stars"></i> <?= htmlspecialchars($prop['jumlah_kamar']) ?> Kamar</div>
                                        <div class="facility" title="Luas Bangunan"><i class="bi bi-building"></i> LB: <?= htmlspecialchars($prop['luas_bangunan']) ?>m&sup2;</div>
                                        <div class="facility" title="Luas Tanah"><i class="bi bi-arrows-fullscreen"></i> LT: <?= htmlspecialchars($prop['luas_tanah']) ?>m&sup2;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php 
                    }
                } else {
                ?>
                    <div class="col-12 text-center py-5">
                        <div class="p-4 bg-white rounded-3 shadow-sm d-inline-block">
                            <i class="bi bi-house-x text-muted fs-1 mb-3 d-block"></i>
                            <h4 class="text-dark font-weight-bold">Tidak ada properti ditemukan</h4>
                            <p class="text-muted">Silakan coba kata kunci atau kriteria pencarian lain.</p>
                            <a href="index.php#properti" class="btn btn-search btn-sm mt-2">Reset Pencarian</a>
                        </div>
                    </div>
                <?php } ?>
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
                                echo '<li><a href="index.php#properti"><i class="bi bi-chevron-right me-1 small"></i>' . htmlspecialchars($sp_f['nama_site_plan']) . '</a></li>';
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
                        <li><a href="index.php">Home</a></li>
                        <li><a href="#tentang-kami">Tentang Kami</a></li>
                        <li><a href="#properti">Properti</a></li>
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
                        <a href="https://wa.me/<?= $wa_number ?>" target="_blank" class="text-light fs-4"><i class="bi bi-whatsapp"></i></a>
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
    
    <!-- Navbar Scroll Script -->
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
    </script>
</body>
</html>