<?php 
require_once '../database/config.php';

$pengguna_login = $_SESSION['username'] ?? '';
$pengguna = @$_GET['user'];

// 1. Cek peran dari user yang akan dihapus terlebih dahulu
$cek_target = mysqli_query($conn, "SELECT peran FROM users WHERE username = '$pengguna'") or die(mysqli_error($conn));
$data_target = mysqli_fetch_assoc($cek_target);
$peran_target = $data_target['peran'] ?? '';

// 2. Cek total jumlah admin yang ada di database saat ini
$cek_admin = mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM users WHERE peran='A'") or die(mysqli_error($conn));
$data_admin = mysqli_fetch_assoc($cek_admin);
$jumlah_admin = $data_admin['jumlah'];

// LOGIKA PERBAIKAN
if ($pengguna_login == $pengguna) {
    // Blokir jika user mencoba menghapus akunnya sendiri
    echo '<script>
        alert("Anda tidak dapat menghapus akun yang sedang Anda gunakan untuk login!");
        window.location.href="index.php";
    </script>';

} elseif ($peran_target == 'A' && $jumlah_admin <= 1) {
    // Blokir HANYA JIKA yang mau dihapus adalah admin DAN jumlah admin di sistem tinggal 1
    echo '<script>
        alert("Gagal! Akun Admin harus tersisa minimal 1 di sistem.");
        window.location.href="index.php";
    </script>';

} else {
    // Jika lolos syarat di atas, data boleh dihapus
    $hapus_pengguna = mysqli_query($conn, "DELETE FROM users WHERE username = '$pengguna'") or die(mysqli_error($conn));
    
    echo '<script>
        alert("Data Pengguna '.$pengguna.' Berhasil Dihapus");
        window.location.href="index.php";
    </script>';
}
?>