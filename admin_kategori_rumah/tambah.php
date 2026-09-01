<?php 
require_once('../database/config.php');
?>
<html>
<head>
</head>
<body>
<?php
if (isset($_POST['btn_tambah'])) {
    $nama_kategori = trim(mysqli_real_escape_string($conn, $_POST['nama_kategori']));
    $luas_tanah = trim(mysqli_real_escape_string($conn, $_POST['luas_tanah']));
    $luas_bangunan = trim(mysqli_real_escape_string($conn, $_POST['luas_bangunan']));
    $harga = trim(mysqli_real_escape_string($conn, $_POST['harga']));
    $deskripsi = trim(mysqli_real_escape_string($conn, $_POST['deskripsi']));
    $jumlah_kamar = trim(mysqli_real_escape_string($conn, $_POST['jumlah_kamar']));
    

    $cek_kategori = mysqli_query($conn, "SELECT nama_kategori FROM kategori_rumah WHERE nama_kategori = '$nama_kategori' ") 
    or die (mysqli_error($conn));
    $rv_kategori = mysqli_num_rows($cek_kategori);


    if ( $rv_kategori > 0) {
        echo '<script> alert("Nama Kategori Sudah Terdaftar! Input yang lain");
        window.location.href="index.php" </script>';

    } else {
        $query_simpan_kategori = mysqli_query($conn, "INSERT INTO kategori_rumah 
         VALUES 
         (null,
         '$nama_kategori', 
         '$luas_bangunan',
         '$luas_tanah',
         '$jumlah_kamar',
         '$harga',
         '$deskripsi'
         )
         ") or die (mysqli_error($conn)) ;

         echo '<script> alert("Data Berhasil Disimpan"); 
         window.location.href="index.php" </script>';
    }
}



?>
</body>
</html>