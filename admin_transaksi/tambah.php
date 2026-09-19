<?php
require_once '../database/config.php';

if (isset($_POST['btn_tambah'])) {
    $id_pembeli = trim(mysqli_real_escape_string($conn, $_POST['id_pembeli']));
    $id_karyawan = trim(mysqli_real_escape_string($conn, $_POST['id_karyawan']));
    $id_rumah = trim(mysqli_real_escape_string($conn, $_POST['id_rumah']));
    $status_transaksi = 'Berlangsung';
    $total = 0;

    $tanggal = date('y-m-d');

    $prefix = $tanggal . "-";

    $query = "SELECT no_transaksi FROM transaksi WHERE no_transaksi LIKE '$prefix%' ORDER BY no_transaksi DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nota_terakhir = $row['no_transaksi'];
        
        $pecah = explode("-", $nota_terakhir);
        $nomor_terakhir = (int) end($pecah); 
        
        $nomor_baru = $nomor_terakhir + 1;
    } else {
       
        $nomor_baru = 1;
    }

    $nomor_urut_format = str_pad($nomor_baru, 4, "0", STR_PAD_LEFT);

    $no_nota_generate = $prefix . $nomor_urut_format;


    
        $query_simpan = mysqli_query($conn, "INSERT INTO transaksi 
         VALUES 
         (null,
         '$no_nota_generate', 
         '$id_pembeli',
         '$id_karyawan',
         '$id_rumah',
         '$status_transaksi',
         '$total',
         '$tanggal'
         )
         ") or die (mysqli_error($conn)) ;
         $id_transaksi = mysqli_insert_id($conn);

        $update_status_rumah = mysqli_query($conn, "UPDATE rumah SET status = '1' WHERE id_rumah = '$id_rumah'") or die(mysqli_error($conn));

        $hrg_booking = trim(mysqli_real_escape_string($conn, $_POST['booking_fee']));
        $jns_booking = 'Booking Fee';
        $bayar_booking = mysqli_query($conn, "INSERT INTO jenis_pembayaran VALUES (
        null,
        '$id_transaksi',
        '$jns_booking',
        '$hrg_booking'
        )") or die(mysqli_error($conn));

        $hrg_rumah = trim(mysqli_real_escape_string($conn, $_POST['pem_rumah']));
        $jns_rumah = 'Pembangunan Rumah';
        $bayar_rumah = mysqli_query($conn, "INSERT INTO jenis_pembayaran VALUES (
        null,
        '$id_transaksi',
        '$jns_rumah',
        '$hrg_rumah'
        )") or die(mysqli_error($conn));

        $hrg_pb_rumah = trim(mysqli_real_escape_string($conn, $_POST['pb_rumah']));
        $jns_pb_rumah = 'Pajak Bangunan';
        $bayar_pbr = mysqli_query($conn, "INSERT INTO jenis_pembayaran VALUES (
        null,
        '$id_transaksi',
        '$jns_pb_rumah',
        '$hrg_pb_rumah'
        )") or die(mysqli_error($conn));

        $hrg_ajb = trim(mysqli_real_escape_string($conn, $_POST['ajb']));
        $jns_ajb = 'Akte Jual Beli';
        $bayar_pbr = mysqli_query($conn, "INSERT INTO jenis_pembayaran VALUES (
        null,
        '$id_transaksi',
        '$jns_ajb',
        '$hrg_ajb'
        )") or die(mysqli_error($conn));

        $hrg_notaris = trim(mysqli_real_escape_string($conn, $_POST['notaris']));
        $jns_notaris = 'Notaris';
        $bayar_notaris = mysqli_query($conn, "INSERT INTO jenis_pembayaran VALUES (
        null,
        '$id_transaksi',
        '$jns_notaris',
        '$hrg_notaris'
        )") or die(mysqli_error($conn));

        $hrg_makam = trim(mysqli_real_escape_string($conn, $_POST['lahan_makam']));
        $jns_makam = 'Lahan Makam';
        $bayar_notaris = mysqli_query($conn, "INSERT INTO jenis_pembayaran VALUES (
        null,
        '$id_transaksi',
        '$jns_makam',
        '$hrg_makam'
        )") or die(mysqli_error($conn));

        $hrg_hook = trim(mysqli_real_escape_string($conn, $_POST['hook']));
        $jns_hook = 'Hook';
        if ($hrg_hook == '') {
            
        } else {
            $bayar_hook = mysqli_query($conn, "INSERT INTO jenis_pembayaran VALUES (
            null,
            '$id_transaksi',
            '$jns_hook',
            '$hrg_hook'
            )") or die(mysqli_error($conn));
        }
        

        echo '<script> alert("Data Berhasil Disimpan"); 
        window.location.href="index.php" </script>';
    
}



?>