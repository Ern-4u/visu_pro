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
    $metode_pembayaran = trim(mysqli_real_escape_string($conn, $_POST['metode_pembayaran']));
    $dp = trim(mysqli_real_escape_string($conn, $_POST['dp']));
    $tanggal_akad = trim(mysqli_real_escape_string($conn, $_POST['tanggal_akad']));
    $tenor = trim(mysqli_real_escape_string($conn, $_POST['tenor']));
    
        $query_simpan_detail_transaksi = mysqli_query($conn, "INSERT INTO detail_transaksi_rumah
         VALUES 
         (
         '$id_transaksi', 
         '$metode_pembayaran',
         '$dp',
         '$tenor',
         '$tanggal_akad'
         )
         ") or die (mysqli_error($conn)) ;

         echo '<script> alert("Data Berhasil Disimpan"); 
         window.location.href="detail.php?id='.$id_transaksi.'" </script>';
    
}



?>
</body>
</html>