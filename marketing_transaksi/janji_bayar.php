<?php
require_once '../database/config.php';

if (isset($_POST['btn_tambah_janji'])) {
    $id_transaksi = trim(mysqli_real_escape_string($conn, $_POST['id_transaksi']));
    $tanggal_dijanjikan = trim(mysqli_real_escape_string($conn, $_POST['tanggal_dijanjikan']));
    $keterangan = trim(mysqli_real_escape_string($conn, $_POST['keterangan']));
    $tanggal_janji = date('Y-m-d');
        
    $query_gagal_janji = mysqli_query($conn , "UPDATE janji_bayar SET status = 'gagal' WHERE id_transaksi = '$id_transaksi' AND status = 'aktif'") or die(mysqli_error($conn));

    $query_buat_janji = mysqli_query($conn, "INSERT INTO janji_bayar VALUES (
    null,
    '$id_transaksi',
    '$tanggal_janji',
    '$tanggal_dijanjikan',
    'Aktif',
    '$keterangan'
    )
    ")or die(mysqli_error($conn));

    echo '<script> alert("Data Berhasil Disimpan"); 
    window.location.href="detail.php?id='.$id_transaksi.'" </script>';
    
}



?>