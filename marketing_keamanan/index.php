<?php
require_once '../database/config.php';

$authority = @$_SESSION['peran'];
$username_session = @$_SESSION['username'];

if ($authority != 'M') {
    echo '<script> alert("Anda Tidak boleh masuk ke halaman ini!!!!");
    window.location.href="../logout.php"; </script>';
    exit();
}

$pesan_sukses = "";
$pesan_error = "";

// Ambil data user terkini
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username_session'") or die(mysqli_error($conn));
$user_data = mysqli_fetch_assoc($query_user);

// Handle Form Ganti Password
if (isset($_POST['btn_ganti_password'])) {
    $password_lama = trim($_POST['password_lama']);
    $password_baru = trim($_POST['password_baru']);
    $konfirmasi_password = trim($_POST['konfirmasi_password']);

    $hash_lama = sha1($password_lama);

    if ($hash_lama !== $user_data['sandi']) {
        $pesan_error = "Password lama yang Anda masukkan salah!";
    } elseif (strlen($password_baru) < 4) {
        $pesan_error = "Password baru minimal 4 karakter!";
    } elseif ($password_baru !== $konfirmasi_password) {
        $pesan_error = "Konfirmasi password baru tidak cocok!";
    } else {
        $hash_baru = sha1($password_baru);
        $update_pwd = mysqli_query($conn, "UPDATE users SET sandi = '$hash_baru' WHERE username = '$username_session'") or die(mysqli_error($conn));
        if ($update_pwd) {
            $_SESSION['sandi'] = $hash_baru;
            $user_data['sandi'] = $hash_baru;
            $pesan_sukses = "Password berhasil diperbarui!";
        } else {
            $pesan_error = "Gagal memperbarui password. Silakan coba lagi.";
        }
    }
}

// Handle Form Ganti PIN
if (isset($_POST['btn_ganti_pin'])) {
    $pin_lama = trim($_POST['pin_lama']);
    $pin_baru = trim($_POST['pin_baru']);
    $konfirmasi_pin = trim($_POST['konfirmasi_pin']);

    if ($pin_lama !== $user_data['pin']) {
        $pesan_error = "PIN lama yang Anda masukkan salah!";
    } elseif (!ctype_digit($pin_baru) || strlen($pin_baru) !== 6) {
        $pesan_error = "PIN baru harus berupa 6 digit angka!";
    } elseif ($pin_baru !== $konfirmasi_pin) {
        $pesan_error = "Konfirmasi PIN baru tidak cocok!";
    } else {
        $update_pin = mysqli_query($conn, "UPDATE users SET pin = '$pin_baru' WHERE username = '$username_session'") or die(mysqli_error($conn));
        if ($update_pin) {
            $_SESSION['pin'] = $pin_baru;
            $user_data['pin'] = $pin_baru;
            $pesan_sukses = "PIN keamanan (2FA) berhasil diperbarui!";
        } else {
            $pesan_error = "Gagal memperbarui PIN. Silakan coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>VISU Pro | Keamanan Akun Marketing</title>
  <?php
    include '../layout_marketing/css.php';
    $hal = 'keamanan';
  ?>
  <style>
    .card-navy-header {
      background-color: #001F3F;
      color: #ffffff;
    }
    .input-group-text {
      cursor: pointer;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-flex">
<div class="wrapper">

  <?php
  $query_web = mysqli_query($conn, "SELECT * FROM web WHERE id = '1'") or die(mysqli_error($conn));
  $web = mysqli_fetch_array($query_web); 
  ?>
  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble rounded-circle" src="../assets/logo/<?= $web['logo'] ?>" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <?php include '../layout_marketing/navbar.php'; ?>
  <!-- Main Sidebar Container -->
  <?php include '../layout_marketing/sidebar.php'; ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><i class="fas fa-user-shield mr-2"></i>Keamanan Akun</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../marketing_home/">Dashboard</a></li>
              <li class="breadcrumb-item active">Keamanan Akun</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <?php if (!empty($pesan_sukses)) : ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icon fas fa-check"></i> <strong>Berhasil!</strong> <?= htmlspecialchars($pesan_sukses) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <?php if (!empty($pesan_error)) : ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="icon fas fa-ban"></i> <strong>Peringatan!</strong> <?= htmlspecialchars($pesan_error) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <!-- Info Profil Singkat -->
        <div class="card card-outline card-navy mb-4">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <div class="bg-navy text-white p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                  <i class="fas fa-user-tag fa-2x"></i>
                </div>
              </div>
              <div class="col">
                <h4 class="mb-1 font-weight-bold"><?= htmlspecialchars($user_data['nama']) ?></h4>
                <p class="text-muted mb-0">
                  <span class="badge badge-success mr-2">Staff Marketing</span>
                  ID Karyawan / Username: <strong><?= htmlspecialchars($user_data['username']) ?></strong>
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- Form Ganti Password -->
          <div class="col-lg-6">
            <div class="card card-navy h-100">
              <div class="card-header card-navy-header">
                <h3 class="card-title font-weight-bold">
                  <i class="fas fa-key mr-2"></i>Ganti Password
                </h3>
              </div>
              <form action="" method="post">
                <div class="card-body">
                  <p class="text-muted small">
                    Gunakan password yang kuat dengan kombinasi huruf dan angka untuk menjaga keamanan akun Anda.
                  </p>

                  <div class="form-group">
                    <label for="password_lama">Password Saat Ini <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <input type="password" name="password_lama" id="password_lama" class="form-control" placeholder="Masukkan password saat ini" required>
                      <div class="input-group-append">
                        <span class="input-group-text" onclick="togglePassword('password_lama', this)">
                          <i class="fas fa-eye"></i>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="password_baru">Password Baru <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <input type="password" name="password_baru" id="password_baru" class="form-control" placeholder="Masukkan password baru" required minlength="4">
                      <div class="input-group-append">
                        <span class="input-group-text" onclick="togglePassword('password_baru', this)">
                          <i class="fas fa-eye"></i>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="konfirmasi_password">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control" placeholder="Ulangi password baru" required minlength="4">
                      <div class="input-group-append">
                        <span class="input-group-text" onclick="togglePassword('konfirmasi_password', this)">
                          <i class="fas fa-eye"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer bg-light text-right">
                  <button type="submit" name="btn_ganti_password" class="btn" style="background-color: #001F3F; color: white;">
                    <i class="fas fa-save mr-1"></i> Simpan Password Baru
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Form Ganti PIN -->
          <div class="col-lg-6 mt-4 mt-lg-0">
            <div class="card card-navy h-100">
              <div class="card-header card-navy-header">
                <h3 class="card-title font-weight-bold">
                  <i class="fas fa-shield-alt mr-2"></i>Ganti PIN Keamanan (2FA)
                </h3>
              </div>
              <form action="" method="post">
                <div class="card-body">
                  <p class="text-muted small">
                    PIN keamanan terdiri dari <strong>6 digit angka</strong> yang digunakan untuk verifikasi login dua langkah (2-Factor Authentication).
                  </p>

                  <div class="form-group">
                    <label for="pin_lama">PIN Saat Ini <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <input type="password" name="pin_lama" id="pin_lama" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" placeholder="6 digit PIN saat ini" required>
                      <div class="input-group-append">
                        <span class="input-group-text" onclick="togglePassword('pin_lama', this)">
                          <i class="fas fa-eye"></i>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="pin_baru">PIN Baru (6 Digit Angka) <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <input type="password" name="pin_baru" id="pin_baru" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" placeholder="Masukkan 6 digit angka baru" required>
                      <div class="input-group-append">
                        <span class="input-group-text" onclick="togglePassword('pin_baru', this)">
                          <i class="fas fa-eye"></i>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="konfirmasi_pin">Konfirmasi PIN Baru <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <input type="password" name="konfirmasi_pin" id="konfirmasi_pin" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" placeholder="Ulangi 6 digit angka baru" required>
                      <div class="input-group-append">
                        <span class="input-group-text" onclick="togglePassword('konfirmasi_pin', this)">
                          <i class="fas fa-eye"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer bg-light text-right">
                  <button type="submit" name="btn_ganti_pin" class="btn" style="background-color: #001F3F; color: white;">
                    <i class="fas fa-shield-alt mr-1"></i> Simpan PIN Baru
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </section>
  </div>

  <!-- Footer -->
  <?php include '../layout_marketing/footer.php'; ?>
</div>

<?php include '../layout_marketing/js.php'; ?>
<script>
  function togglePassword(inputId, elem) {
    var input = document.getElementById(inputId);
    var icon = elem.querySelector('i');
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }
</script>
</body>
</html>

