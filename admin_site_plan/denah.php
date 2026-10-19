<?php
require_once '../database/config.php';

if (isset($_POST['btn_tambah_denah'])) {

    $id_site_plan = trim(mysqli_real_escape_string($conn, $_POST['id_tambah_denah']));
    $file = $_FILES['tambah_foto_site_plan']['name'];
    $ekstensi = explode('.', $file);
    $nama_file = 'denah-'.round(microtime(true)).'.'.end($ekstensi);

    $alamat_tujuan = '../assets/foto_site_plan/'.$nama_file;
    $file_alamat_sumber = $_FILES['tambah_foto_site_plan']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);

    $query_foto = mysqli_query($conn, "UPDATE site_plan SET foto_site_plan = '$nama_file' WHERE id_site_plan = '$id_site_plan'") or die(mysqli_error($conn));
    if  ($query_foto) {

    echo '<script> alert("Denah Berhasil disimpan");
    window.location.href="index.php" </script>';

    } else {
        echo '<script> alert("Denah Gagal disimpan");
    window.location.href="index.php" </script>';
    }

}

if (isset($_POST['btn_edit_denah'])) {

    $id_site_plan = trim(mysqli_real_escape_string($conn, $_POST['id_edit_denah']));
    $file = $_FILES['edit_foto_site_plan']['name'];
    $ekstensi = explode('.', $file);
    $nama_file = 'denah-'.round(microtime(true)).'.'.end($ekstensi);

    $alamat_tujuan = '../assets/foto_site_plan/'.$nama_file;
    $file_alamat_sumber = $_FILES['edit_foto_site_plan']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);

    $query_foto = mysqli_query($conn, "UPDATE site_plan SET foto_site_plan = '$nama_file' WHERE id_site_plan = '$id_site_plan'") or die(mysqli_error($conn));
    if  ($query_foto) {

    echo '<script> alert("Foto Denah Berhasil disimpan");
    window.location.href="index.php" </script>';

    } else {
        echo '<script> alert("Foto Denah Gagal disimpan");
    window.location.href="index.php" </script>';
    }

}
?>