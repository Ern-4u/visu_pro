<?php
require_once '../database/config.php';

if (isset($_POST['btn_foto'])) {

    $id = trim(mysqli_real_escape_string($conn, $_POST['id_karyawan']));
    $file = $_FILES['foto']['name'];
    $ekstensi = explode('.', $file);
    $nama_file = 'marketing'.round(microtime(true)).'.'.end($ekstensi);

    $alamat_tujuan = '../assets/foto_marketing/'.$nama_file;
    $file_alamat_sumber = $_FILES['foto']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);

    $query_foto = mysqli_query($conn, "UPDATE marketing SET foto = '$nama_file' WHERE id_karyawan = '$id'") or die(mysqli_error($conn));
    if  ($query_foto) {

    echo '<script> alert("Foto Berhasil disimpan");
    window.location.href="index.php" </script>';

    } else {
        echo '<script> alert("Foto Gagal disimpan");
    window.location.href="index.php" </script>';
    }

}
?>