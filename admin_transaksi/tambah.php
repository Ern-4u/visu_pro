<?php
require_once '../database/config.php';

if (isset($_POST['btn_tambah'])) {
    $no_transaksi = trim(mysqli_real_escape_string($conn, $_POST['no_transaksi']));
    $id_pembeli = trim(mysqli_real_escape_string($conn, $_POST['id_pembeli']));
    $id_karyawan = trim(mysqli_real_escape_string($conn, $_POST['id_karyawan']));
    $id_rumah = trim(mysqli_real_escape_string($conn, $_POST['id_rumah']));
    $status_transaksi = 'Berlangsung';
    $total = 0;
    $tanggal = date('y,m,d');
    

    $cek_no_transaksi = mysqli_query($conn, "SELECT no_transaksi FROM transaksi WHERE no_transaksi = '$no_transaksi' ") 
    or die (mysqli_error($conn));
    $rv_kategori = mysqli_num_rows($cek_no_transaksi);


    if ( $rv_kategori > 0) {
        echo '<script> alert("Nomor Transaksi Sudah Ada! Input yang lain");
        window.location.href="index.php" </script>';

    } else {
        $query_simpan = mysqli_query($conn, "INSERT INTO transaksi 
         VALUES 
         (null,
         '$no_transaksi', 
         '$id_pembeli',
         '$id_karyawan',
         '$id_rumah',
         '$status_transaksi',
         '$total',
         '$tanggal'
         )
         ") or die (mysqli_error($conn)) ;

         echo '<script> alert("Data Berhasil Disimpan"); 
         window.location.href="index.php" </script>';
    }
}



?>