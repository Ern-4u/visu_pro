<?php 
require_once '../database/config.php';
$id = @$_GET['id'];
$id_rumah = @$_GET['id_rumah'];
        
        
$query_update_status_transaksi = mysqli_query($conn, "UPDATE transaksi SET status = 'Gagal Bayar' WHERE id_transaksi = '$id'")or die(mysqli_error($conn));
$update_status_rumah = mysqli_query($conn, "UPDATE rumah SET status = '0' WHERE id_rumah = '$id_rumah'") or die(mysqli_error($conn));

echo    '<script>
        alert("Status Transaksi Berhasil Di Ubah");
        window.location.href="index.php";
        </script>'


 ?>