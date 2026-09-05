<?php
require_once '../database/config.php';

if (isset($_POST['btn_brosur'])) {

    $id_site_plan = trim(mysqli_real_escape_string($conn, $_POST['id_site_plan']));
    $file = $_FILES['brosur']['name'];
    $ekstensi = explode('.', $file);
    $nama_file = 'brosur'.round(microtime(true)).'.'.end($ekstensi);

    $alamat_tujuan = '../assets/brosur/'.$nama_file;
    $file_alamat_sumber = $_FILES['brosur']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);

    $query_foto = mysqli_query($conn, "UPDATE site_plan SET brosur = '$nama_file' WHERE id_site_plan = '$id_site_plan'") or die(mysqli_error($conn));
    if  ($query_foto) {

    echo '<script> alert("Foto Berhasil disimpan");
    window.location.href="index.php" </script>';

    } else {
        echo '<script> alert("Foto Gagal disimpan");
    window.location.href="index.php" </script>';
    }

}

if (isset($_POST['btn_edit_brosur'])) {

    $id_site_plan = trim(mysqli_real_escape_string($conn, $_POST['id_site_plan']));
    $file = $_FILES['brosur']['name'];
    $ekstensi = explode('.', $file);
    $nama_file = 'brosur'.round(microtime(true)).'.'.end($ekstensi);

    $alamat_tujuan = '../assets/brosur/'.$nama_file;
    $file_alamat_sumber = $_FILES['brosur']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);

    $query_foto = mysqli_query($conn, "UPDATE site_plan SET brosur = '$nama_file' WHERE id_site_plan = '$id_site_plan'") or die(mysqli_error($conn));
    if  ($query_foto) {

    echo '<script> alert("Foto Berhasil disimpan");
    window.location.href="index.php" </script>';

    } else {
        echo '<script> alert("Foto Gagal disimpan");
    window.location.href="index.php" </script>';
    }

}
?>