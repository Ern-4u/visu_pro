<?php 
require_once('../database/config.php');
?>
<html>
<head>
</head>
<body>
<?php
if (isset($_POST['btn_tambah'])) {
    $nik = trim(mysqli_real_escape_string($conn, $_POST['nik']));
    $nama_pembeli = trim(mysqli_real_escape_string($conn, $_POST['nama_pembeli']));
    $pasangan = trim(mysqli_real_escape_string($conn, $_POST['pasangan']));
    $alamat = trim(mysqli_real_escape_string($conn, $_POST['alamat']));
    $kontak = trim(mysqli_real_escape_string($conn, $_POST['kontak']));


    
        $query_simpan = mysqli_query($conn, "INSERT INTO pembeli 
         VALUES 
         (null,
         '$nik', 
         '$nama_pembeli',
         '$pasangan',
         '$alamat',
         '$kontak')
         ") or die (mysqli_error($conn)) ;

         echo '<script> alert("Data Berhasil Disimpan"); 
         window.location.href="index.php" </script>';
    
}



?>
</body>
</html>