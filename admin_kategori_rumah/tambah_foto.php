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
    $keterangan_foto = trim(mysqli_real_escape_string($conn, $_POST['keterangan_foto']));

    $file = $_FILES['foto']['name'];
    $ekstensi = explode('.', $file);
    $nama_file = 'fto_rumah'.round(microtime(true)).'.'.end($ekstensi);

    $alamat_tujuan = '../assets/fto_rumah/'.$nama_file;
    $file_alamat_sumber = $_FILES['foto']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);

    $query_foto = mysqli_query($conn, "INSERT INTO foto_rumah VALUES (null,'$id_kategori','$nama_file','$keterangan_foto') ") or die(mysqli_error($conn));
    
    echo '<script> alert("Foto Rumah Berhasil Disimpan");
    window.location.href="detail.php>id='.$id_kategori.'; </script>';
    
    
}



?>
</body>
</html>