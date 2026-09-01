<?php 
require_once('../database/config.php');
?>
<html>
<head>
</head>
<body>
<?php
if (isset($_POST['btn_tambah'])) {
    $id_karyawan = trim(mysqli_real_escape_string($conn, $_POST['id_karyawan']));
    $nama = trim(mysqli_real_escape_string($conn, $_POST['nama']));
    $kontak = trim(mysqli_real_escape_string($conn, $_POST['kontak']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $kelamin = trim(mysqli_real_escape_string($conn, $_POST['kelamin']));
    $password = sha1($id_karyawan);
    $pin = '123456';
    $peran = 'M';

    $cek_user = mysqli_query($conn, "SELECT username FROM users WHERE username = '$id_karyawan' ") 
    or die (mysqli_error($conn));
    $rv_user = mysqli_num_rows($cek_user);
    $cek_id = mysqli_query($conn, "SELECT id_karyawan FROM marketing WHERE id_karyawan = '$id_karyawan' ") 
    or die (mysqli_error($conn));
    $rv_id = mysqli_num_rows($cek_id);


    if ($rv_user > 0 || $rv_id > 0) {
        echo '<script> alert("ID Karyawan Sudah Terdaftar! Input yang lain");
        window.location.href="index.php" </script>';

    } else {
        $query_simpan_users = mysqli_query($conn, "INSERT INTO users 
        (username,
         sandi,
         peran,
         pin,
         nama) 
         VALUES 
         ('$id_karyawan', 
         '$password',
         '$peran',
         '$pin',
         '$nama')
         ") or die (mysqli_error($conn)) ;

         $query_simpan_marketing = mysqli_query($conn, "INSERT INTO marketing VALUES 
         ('$id_karyawan',
         '$nama',
         '$kontak',
         '$email',
         '$kelamin',
         null
         )") or die(mysqli_error($conn));

         echo '<script> alert("Data Berhasil Disimpan"); 
         window.location.href="index.php" </script>';
    }
}



?>
</body>
</html>