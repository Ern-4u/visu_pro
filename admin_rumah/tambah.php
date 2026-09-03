<?php 
require_once('../database/config.php');
?>
<html>
<head>
</head>
<body>
<?php
if (isset($_POST['btn_tambah'])) {
    $kode_blok = trim(mysqli_real_escape_string($conn, $_POST['kode_blok']));
    $id_kategori = trim(mysqli_real_escape_string($conn, $_POST['id_kategori']));
    $status = trim(mysqli_real_escape_string($conn, $_POST['status']));
    

    $cek_kode = mysqli_query($conn, "SELECT kode_blok FROM rumah WHERE kode_blok = '$kode_blok' ") 
    or die (mysqli_error($conn));
    $rv_kategori = mysqli_num_rows($cek_kode);


    if ( $rv_kategori > 0) {
        echo '<script> alert("Kode Blok Rumah Sudah Terdaftar! Input yang lain");
        window.location.href="index.php" </script>';

    } else {
        $query_simpan_kategori = mysqli_query($conn, "INSERT INTO rumah 
         VALUES 
         (null,
         '$kode_blok', 
         '$id_kategori',
         '$status'
         )
         ") or die (mysqli_error($conn)) ;

         echo '<script> alert("Data Berhasil Disimpan"); 
         window.location.href="index.php" </script>';
    }
}



?>
</body>
</html>