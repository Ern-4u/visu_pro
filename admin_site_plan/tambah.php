<?php 
require_once('../database/config.php');
?>
<html>
<head>
</head>
<body>
<?php
if (isset($_POST['btn_tambah'])) {
    $nama_site_plan = trim(mysqli_real_escape_string($conn, $_POST['nama_site_plan']));
    $lokasi = trim(mysqli_real_escape_string($conn, $_POST['lokasi']));
    $penanggung_jawab = trim(mysqli_real_escape_string($conn, $_POST['penanggung_jawab']));
    $ig = trim(mysqli_real_escape_string($conn, $_POST['ig']));
    $tiktok = trim(mysqli_real_escape_string($conn, $_POST['tiktok']));

    $file = $_FILES['brosur']['name'];
    $ekstensi = explode('.', $file);
    $brosur = 'brosur'.round(microtime(true)).'.'.end($ekstensi);

    $alamat_tujuan = '../assets/brosur/'.$brosur;
    $file_alamat_sumber = $_FILES['brosur']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);
    

    $cek_site_plan = mysqli_query($conn, "SELECT nama_site_plan FROM site_plan WHERE nama_site_plan = '$nama_site_plan' ") 
    or die (mysqli_error($conn));
    $rv = mysqli_num_rows($cek_site_plan);


    if ( $rv > 0) {
        echo '<script> alert("Nama Proyek Perumahan Sudah Terdaftar! Input yang lain");
        window.location.href="index.php" </script>';

    } else {
        $query_simpan_kategori = mysqli_query($conn, "INSERT INTO site_plan 
         VALUES 
         (null,
         '$nama_site_plan', 
         '$lokasi',
         '$penanggung_jawab',
         '$ig',
         '$tiktok',
         '$brosur'
         )
         ") or die (mysqli_error($conn)) ;

         echo '<script> alert("Data Berhasil Disimpan"); 
         window.location.href="index.php" </script>';
    }
}



?>
</body>
</html>