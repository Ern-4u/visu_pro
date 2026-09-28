<?php 
require_once('../database/config.php');
?>
<html>
<head>
</head>
<body>
<?php
if (isset($_POST['btn_edit'])) {
    $id_kategori = trim(mysqli_real_escape_string($conn, $_POST['id_kategori']));
    $id_jenis_pembayaran = trim(mysqli_real_escape_string($conn, $_POST['id_jenis_pembayaran']));
    $jenis_pembayaran = trim(mysqli_real_escape_string($conn, $_POST['jenis_pembayaran']));
    $harga = str_replace('.', '', $_POST['harga']); 
   

   
    $query_edit_harga = mysqli_query($conn, "UPDATE jenis_pembayaran 
        SET jenis_pembayaran = '$jenis_pembayaran', harga = '$harga' WHERE id_jenis_pembayaran = '$id_jenis_pembayaran'
        ") or die (mysqli_error($conn)) ;

        echo '<script> alert("Data Berhasil Disimpan"); 
        window.location.href="detail.php?id='.$id_kategori.'" </script>';
    
}



?>
</body>
</html>