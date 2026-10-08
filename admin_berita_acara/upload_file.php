<?php
require_once '../database/config.php';

if (isset($_POST['btn_upload'])) {

    $id = trim(mysqli_real_escape_string($conn, $_POST['id']));
    $file = $_FILES['ttd_file']['name'];
    $ekstensi = explode('.', $file);
    $nama_file = 'bast_'.round(microtime(true)).'.'.end($ekstensi);

    $alamat_tujuan = '../assets/bast/'.$nama_file;
    $file_alamat_sumber = $_FILES['ttd_file']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);

    $query_foto = mysqli_query($conn, "UPDATE berita_acara SET file = '$nama_file' WHERE id_berita_acara = '$id'") or die(mysqli_error($conn));
    if  ($query_foto) {

    echo '<script> alert("Foto Berhasil disimpan");
    window.location.href="index.php" </script>';

    } else {
        echo '<script> alert("Foto Gagal disimpan");
    window.location.href="index.php" </script>';
    }

}
?>