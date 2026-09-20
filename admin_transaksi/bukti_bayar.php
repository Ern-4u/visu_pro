<?php
require_once '../database/config.php';

if (isset($_POST['btn_bukti_bayar'])) {

    $id_detail_transaksi = trim(mysqli_real_escape_string($conn, $_POST['id_detail_transaksi']));
    $id_transaksi = trim(mysqli_real_escape_string($conn, $_POST['id_transaksi']));
    $file = $_FILES['bukti_pembayaran']['name'];
    $ekstensi = explode('.', $file);
    $nama_file = 'Bukti_Bayar'.round(microtime(true)).'.'.end($ekstensi);

    $alamat_tujuan = '../assets/bukti_bayar/'.$nama_file;
    $file_alamat_sumber = $_FILES['bukti_pembayaran']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);

    $query_foto = mysqli_query($conn, "UPDATE detail_transaksi SET bukti_pembayaran = '$nama_file' WHERE id_detail_transaksi = '$id_detail_transaksi'") or die(mysqli_error($conn));
    if  ($query_foto) {

     echo '<script> alert("Foto Berhasil disimpan");
     window.location.href="detail.php?id='.$id_transaksi.'" </script>';

    } else {
        echo '<script> alert("Foto Gagal disimpan");
    window.location.href="detail.php?id='.$id_transaksi.'" </script>';
    }

}

?>