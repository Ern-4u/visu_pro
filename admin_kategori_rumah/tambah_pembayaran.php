<?php 
require_once('../database/config.php');
?>
<html>
<head>
</head>
<body>
<?php
if (isset($_POST['btn_tambah'])) {
    $id_kategori = trim(mysqli_real_escape_string($conn, $_POST['id_kategori']));
    $jenis_pembayaran = trim(mysqli_real_escape_string($conn, $_POST['jenis_pembayaran']));
    $harga = str_replace('.', '', $_POST['harga']); 
   

   
        $query_simpan_harga = mysqli_query($conn, "INSERT INTO jenis_pembayaran 
         VALUES 
         (null,
         '$id_kategori', 
         '$jenis_pembayaran',
         '$harga'
         )
         ") or die (mysqli_error($conn)) ;

         echo '<script> alert("Data Berhasil Disimpan"); 
         window.location.href="detail.php?id='.$id_kategori.'" </script>';
    
}



?>
</body>
</html>