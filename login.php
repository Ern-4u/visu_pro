<?php
require_once 'database/config.php';

// Fetch web settings
$query_web = mysqli_query($conn, "SELECT * FROM web WHERE id = 1") or die(mysqli_error($conn));
$result_web = mysqli_fetch_array($query_web);

$nama_proyek = !empty($result_web['nama_proyek']) ? $result_web['nama_proyek'] : 'Bangun Indah Negeri';
$alamat_proyek = !empty($result_web['alamat']) ? $result_web['alamat'] : 'Jl. Jend. Sudirman No. 1, Jakarta';
$cp_proyek = !empty($result_web['cp']) ? $result_web['cp'] : '082329221056';

$logo_filename = !empty($result_web['logo']) ? $result_web['logo'] : '';
$logo_path = 'assets/logo/' . $logo_filename;
if (empty($logo_filename) || !file_exists($logo_path)) {
    if (file_exists('assets/logo/Logo.png')) {
        $logo_path = 'assets/logo/Logo.png';
    } elseif (file_exists('assets/logo/visupro.png')) {
        $logo_path = 'assets/logo/visupro.png';
    } else {
        $logo_path = 'img/logo.jpe';
    }
}

$error_msg = "";

// Authentication Handler
if (isset($_POST['btn_login'])) {
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $sandi    = sha1(trim(mysqli_real_escape_string($conn, $_POST['password'])));

    $query  = "SELECT * FROM users WHERE username='$username' AND sandi='$sandi'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $data_user = mysqli_fetch_assoc($result);
        $_SESSION['temp_login'] = true;
        $_SESSION['username']   = $data_user['username'];
        $_SESSION['peran']      = $data_user['peran'];
        $_SESSION['nama']       = $data_user['nama'];
        $_SESSION['sandi']      = $data_user['sandi'];

        echo "<script>window.location.href='pin.php';</script>";
        exit();
    } else {
        $error_msg = "Username atau Password yang Anda masukkan salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Staff - <?= htmlspecialchars($nama_proyek) ?></title>
    
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
            background-color: var(--primary-navy);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px 0;
            position: relative;
            overflow-x: hidden;
        }

        /* Radial Glow Background */
        body::before {
            content: '';
            position: absolute;
            top: -20%;
            left: -10%;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(23,42,69,0.8) 0%, rgba(10,25,47,0) 70%);
            border-radius: 50%;
            z-index: 0;
        }
        body::after {
            content: '';
            position: absolute;
            bottom: -20%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(206,167,81,0.12) 0%, rgba(10,25,47,0) 70%);
            border-radius: 50%;
            z-index: 0;
        }

        h1, h2, h3, h4, h5, h6, .btn {
            font-family: 'Montserrat', sans-serif;
        }

        .auth-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 960px;
            margin: 0 auto;
        }

        .auth-card {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Form Side */
        .auth-form-side {
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-logo-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 2rem;
            text-decoration: none;
        }

        .brand-logo-wrapper img {
            max-height: 42px;
            width: auto;
            object-fit: contain;
        }

        .brand-logo-text {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary-navy);
            margin: 0;
        }

        .auth-title {
            color: var(--primary-navy);
            font-weight: 700;
            font-size: 1.6rem;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .form-label {
            color: var(--primary-navy);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
            padding-left: 14px;
            padding-right: 14px;
        }

        .form-control {
            border-color: #dee2e6;
            padding: 12px 15px;
            font-size: 0.95rem;
            border-radius: 6px;
        }

        .form-control:focus {
            border-color: var(--secondary-navy);
            box-shadow: 0 0 0 3px rgba(23, 42, 69, 0.1);
        }

        .btn-gold {
            background-color: var(--mustrad-gold);
            color: var(--primary-navy);
            font-weight: 700;
            padding: 12px;
            border-radius: 6px;
            border: none;
            transition: all 0.3s;
            font-size: 1rem;
            margin-top: 10px;
        }

        .btn-gold:hover {
            background-color: #b89343;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(206, 167, 81, 0.3);
        }

        .back-link {
            color: #6c757d;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s;
            display: inline-flex;
            align-items: center;
        }

        .back-link:hover {
            color: var(--primary-navy);
        }

        /* Banner Side */
        .auth-banner-side {
            background: linear-gradient(135deg, rgba(10, 25, 47, 0.9), rgba(23, 42, 69, 0.85)), url('assets/images/bg.jpg');
            background-size: cover;
            background-position: center;
            color: var(--text-white);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .banner-badge {
            display: inline-block;
            background-color: rgba(206, 167, 81, 0.2);
            color: var(--mustrad-gold);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.82rem;
            font-weight: 600;
            border: 1px solid rgba(206, 167, 81, 0.4);
            align-self: flex-start;
        }

        .banner-content h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: #ffffff;
        }

        .banner-content p {
            color: var(--text-light);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .banner-info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.88rem;
            color: var(--text-light);
            margin-top: 10px;
        }

        .banner-info-item i {
            color: var(--mustrad-gold);
            font-size: 1.1rem;
        }

        .toggle-password {
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container auth-container">
    <div class="auth-card">
        <div class="row g-0">
            <!-- Left Side: Login Form -->
            <div class="col-lg-6 auth-form-side">
                <a href="index.php" class="brand-logo-wrapper">
                    <img src="<?= htmlspecialchars($logo_path) ?>" alt="Logo <?= htmlspecialchars($nama_proyek) ?>">
                    <span class="brand-logo-text"><?= htmlspecialchars($nama_proyek) ?></span>
                </a>

                <h1 class="auth-title">Login Staff</h1>
                <p class="auth-subtitle">Masukkan Username & Password untuk mengakses dashboard system.</p>

                <?php if (!empty($error_msg)) : ?>
                    <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <div><?= htmlspecialchars($error_msg) ?></div>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input type="text" name="username" class="form-control" id="username" placeholder="Masukkan username" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label mb-0">Password</label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password" required>
                            <span class="input-group-text toggle-password" id="togglePasswordBtn">
                                <i class="bi bi-eye-fill" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" name="btn_login" class="btn btn-gold w-100 mb-4">
                        Lanjut ke Verifikasi PIN <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>

                <div class="text-center">
                    <a href="index.php" class="back-link">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Landing Page
                    </a>
                </div>
            </div>

            <!-- Right Side: Project Branding Banner -->
            <div class="col-lg-6 d-none d-lg-flex auth-banner-side">
                <span class="banner-badge">
                    <i class="bi bi-shield-lock me-1"></i> System Access Portal
                </span>

                <div class="banner-content my-auto">
                    <h2><?= htmlspecialchars($nama_proyek) ?></h2>
                    <p>Sistem Manajemen Real Estate & Pemasaran Perumahan Terpadu. Kelola data unit, transaksi, dan laporan dengan aman dan efisien.</p>

                    <div class="banner-info-item mt-3">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span><?= htmlspecialchars($alamat_proyek) ?></span>
                    </div>
                    <div class="banner-info-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span>Contact Person: <?= htmlspecialchars($cp_proyek) ?></span>
                    </div>
                </div>

                <div class="text-white-50 small mt-4">
                    &copy; <?= date('Y') ?> <?= htmlspecialchars($nama_proyek) ?>. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Password visibility toggle
    const togglePasswordBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            toggleIcon.classList.toggle('bi-eye-fill');
            toggleIcon.classList.toggle('bi-eye-slash-fill');
        });
    }
</script>

</body>
</html>
</html>