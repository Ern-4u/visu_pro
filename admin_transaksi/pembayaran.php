<?php 
require_once '../database/config.php';

if (isset($_POST['btn_bayar'])) {
    $id_jadwal_pembayaran = trim(mysqli_real_escape_string($conn, $_POST['id_jadwal_pembayaran']));
    $tanggal_pembayaran = trim(mysqli_real_escape_string($conn, $_POST['tanggal_pembayaran']));
    $jumlah_pembayaran = trim(mysqli_real_escape_string($conn, $_POST['jumlah_tagihan']));
    $metode_pembayaran = trim(mysqli_real_escape_string($conn, $_POST['metode_pembayaran']));
    $jenis_pembayaran =trim(mysqli_real_escape_string($conn, $_POST['jenis_pembayaran']));
    $id_transaksi =trim(mysqli_real_escape_string($conn, $_POST['id_transaksi']));

    $no_pembayaran = strtoupper($jenis_pembayaran).'-'.$id_jadwal_pembayaran.'-'.$tanggal_pembayaran;

    $query_cek_pembayaran = mysqli_query($conn, "SELECT * FROM pembayaran WHERE id_jadwal_pembayaran = $id_jadwal_pembayaran")or die(mysqli_error($conn));
    $rv = mysqli_num_rows($query_cek_pembayaran);
    if ($rv == 0) {
       $query_insert = mysqli_query($conn, "INSERT INTO pembayaran
        VALUES ( null,
        '$id_jadwal_pembayaran',
        '$no_pembayaran',
        '$tanggal_pembayaran',
        '$jumlah_pembayaran',
        '$metode_pembayaran'
        )")or die(mysqli_error($conn));


        $ambil_tanggal_jatuh_tempo = mysqli_query($conn, "SELECT jatuh_tempo FROM jadwal_pembayaran WHERE id_jadwal_pembayaran = '$id_jadwal_pembayaran'")or die(mysqli_error($conn));
        $tanggal_jatuh_tempo = mysqli_fetch_array($ambil_tanggal_jatuh_tempo);
        $jatuh_tempo = $tanggal_jatuh_tempo['jatuh_tempo'];

        if ($tanggal_pembayaran >= $jatuh_tempo ) {
            $query_update_status = mysqli_query($conn, "UPDATE jadwal_pembayaran SET status = 'telat' WHERE id_jadwal_pembayaran = '$id_jadwal_pembayaran'")or die(mysqli_error($conn));
        } else {
            $query_update_status = mysqli_query($conn, "UPDATE jadwal_pembayaran SET status = 'lunas' WHERE id_jadwal_pembayaran = '$id_jadwal_pembayaran'")or die(mysqli_error($conn));
        }

        

        echo '<script> alert("Pembayaran Berhasil");
        window.location.href="jadwal_pembayaran_booking.php?id='.$id_transaksi.'&jenis_tagihan='.$jenis_pembayaran.'" </script>';
    
    } else {
       echo '<script> alert("Tagihan Sudah Dibayar!!");
        window.location.href="jadwal_pembayaran_booking.php?id='.$id_transaksi.'&jenis_tagihan='.$jenis_pembayaran.'" </script>';
    }
    
}
?>