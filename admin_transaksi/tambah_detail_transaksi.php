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
    $nama_item = trim(mysqli_real_escape_string($conn, $_POST['nama_item']));
    $harga = trim(mysqli_real_escape_string($conn, $_POST['harga']));
    $status_detail_transaksi = trim(mysqli_real_escape_string($conn, $_POST['status_detail_transaksi']));

    $cek_item = mysqli_query($conn, "SELECT nama_item,id_transaksi FROM detail_transaksi WHERE nama_item = '$nama_item' AND id_transaksi ='$id_transaksi' ") 
    or die (mysqli_error($conn));
    $rv = mysqli_num_rows($cek_item);


    if ( $rv > 0) {
        echo '<script> alert("Nama Item Sudah ada! Input yang lain");
        window.location.href="detail.php?id='.$id_transaksi.'" </script>';

    } else {
        $query_simpan_detail_transaksi = mysqli_query($conn, "INSERT INTO detail_transaksi 
         VALUES 
         (null,
         '$id_transaksi', 
         '$nama_item',
         '$harga',
         '$status_detail_transaksi'
         )
         ") or die (mysqli_error($conn)) ;

         echo '<script> alert("Data Berhasil Disimpan"); 
         window.location.href="detail.php?id='.$id_transaksi.'" </script>';
    }
}



?>
</body>
</html>