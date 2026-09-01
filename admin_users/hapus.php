<?php 


require_once '../database/config.php';

$pengguna_login = $_SESSION['username'];
$pengguna = @$_GET['user'];

// Cek jumlah admin saat ini
$cek_admin = mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM users WHERE peran='A'") or die(mysqli_error($conn));
$data = mysqli_fetch_assoc($cek_admin);
$jumlah = $data['jumlah'];

// LOGIKA PERBAIKAN
if ($pengguna_login == $pengguna) {
    // 1. Blokir jika user mencoba menghapus akunnya sendiri
    echo '<script>
        alert("Anda tidak dapat menghapus akun yang sedang Anda gunakan untuk login!");
        window.location.href="index.php";
    </script>';

} elseif ($jumlah <= 1) {
    // 2. Blokir jika user mencoba menghapus admin terakhir yang tersisa di database
    echo '<script>
        alert("Gagal! Akun Admin harus tersisa minimal 1 di sistem.");
        window.location.href="index.php";
    </script>';

} else {
    // 3. Jika lolos kedua syarat di atas, data boleh dihapus
    $hapus_pengguna = mysqli_query($conn, "DELETE FROM users WHERE username = '$pengguna'") or die(mysqli_error($conn));
    
    echo '<script>
        alert("Data Pengguna '.$pengguna.' Berhasil Dihapus");
        window.location.href="index.php";
    </script>';
}
?>