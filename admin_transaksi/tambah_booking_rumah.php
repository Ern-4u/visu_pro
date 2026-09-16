<?php 
require_once('../database/config.php');

if (isset($_POST['btn_tambah'])) {
    $id_transaksi = trim(mysqli_real_escape_string($conn, $_POST['id_transaksi']));
    $tanggal_booking = trim(mysqli_real_escape_string($conn, $_POST['tanggal_booking']));
    $booking_fee = trim(mysqli_real_escape_string($conn, $_POST['booking_fee']));
    $status_booking = trim(mysqli_real_escape_string($conn, $_POST['status_booking']));
    $tenor = (int) trim(mysqli_real_escape_string($conn, $_POST['tenor'])); 

    if ($tenor <= 0) {
        echo '<script> alert("Error: Tenor harus lebih dari 0"); window.history.back(); </script>';
        exit;
    }

    // MULAI TRANSAKSI
    mysqli_begin_transaction($conn);

    try {
        // PERBAIKAN 1: Tambahkan kolom 'tenor' pada query insert booking
        $query_booking = "INSERT INTO booking 
                          (id_transaksi, tanggal_booking, booking_fee, tenor, status_booking) 
                          VALUES 
                          ('$id_transaksi', '$tanggal_booking', '$booking_fee', '$tenor', '$status_booking')";
        mysqli_query($conn, $query_booking) or throw new Exception(mysqli_error($conn));
        
        $pembayaran_ke = 1;
        $tanggal_awal = date('Y-m-d'); 
        $jumlah_tagihan = $booking_fee / $tenor;

        // Looping simpan jadwal pembayaran
        while($pembayaran_ke <= $tenor) {
            $jatuh_tempo = date('Y-m-d', strtotime("+$pembayaran_ke month", strtotime($tanggal_awal)));
            
            // PERBAIKAN 2: Sesuaikan nama kolom persis dengan image_cb96a3.png
            $query_jadwal = "INSERT INTO jadwal_pembayaran 
                             (id_jadwal_pembayaran, id_transaksi, jenis_tagihan, angsuran_ke, jatuh_tempo, jumlah_tagihan, status) 
                             VALUES 
                             (null, '$id_transaksi', 'booking', '$pembayaran_ke', '$jatuh_tempo', '$jumlah_tagihan', 'belum_bayar')";
            
            mysqli_query($conn, $query_jadwal) or throw new Exception(mysqli_error($conn));
            
            $pembayaran_ke++; 
        }

        // SIMPAN PERMANEN KE DATABASE
        mysqli_commit($conn);
        
        echo '<script> alert("Data Berhasil Disimpan"); 
        window.location.href="detail.php?id='.$id_transaksi.'" </script>';

    } catch (Exception $e) {
        // BATALKAN JIKA ADA ERROR
        mysqli_rollback($conn);
        echo '<script> alert("Gagal menyimpan data: ' . $e->getMessage() . '"); 
        window.history.back(); </script>';
    }
}
?>