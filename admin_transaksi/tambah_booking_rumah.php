<?php 
require_once('../database/config.php');
?>
<html>
<head>
</head>
<body>
<?php
if (isset($_POST['btn_tambah'])) {
    $id_transaksi =trim(mysqli_real_escape_string($conn, $_POST['id_transaksi']));
    $tanggal_booking = trim(mysqli_real_escape_string($conn, $_POST['tanggal_booking']));
    $booking_fee = trim(mysqli_real_escape_string($conn, $_POST['booking_fee']));
    $status_booking = trim(mysqli_real_escape_string($conn, $_POST['status_booking']));
    
        $query_simpan_detail_transaksi = mysqli_query($conn, "INSERT INTO booking
         VALUES 
         (
         '$id_transaksi', 
         '$tanggal_booking',
         '$booking_fee',
         '$status_booking'
         )
         ") or die (mysqli_error($conn)) ;

         echo '<script> alert("Data Berhasil Disimpan"); 
         window.location.href="detail.php?id='.$id_transaksi.'" </script>';
    
}



?>
</body>
</html>