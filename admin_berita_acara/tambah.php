<?php 
require_once('../database/config.php');
?>
<html>
<head>
</head>
<body>
<?php
if (isset($_POST['btn_tambah'])) {
    $id_transaksi = trim(mysqli_real_escape_string($conn, $_POST['id_transaksi']));
    $jml_kunci = trim(mysqli_real_escape_string($conn, $_POST['jml_kunci']));
    $tanggal = date('Y-m-d');
    
        $prefix = $tanggal . "-";

        $query_berita_acara = "SELECT no_berita_acara FROM berita_acara WHERE no_berita_acara LIKE '$prefix%' ORDER BY no_berita_acara DESC LIMIT 1";
        $result = $conn->query($query_berita_acara);

        if ($result->num_rows > 0) { 
            $row = $result->fetch_assoc();
            $berita_acara_terahir = $row['no_berita_acara'];
            
            $pecah = explode("-", $berita_acara_terahir);
            $nomor_terakhir = (int) end($pecah); 
            
            $nomor_baru = $nomor_terakhir + 1;
        } else {
            $nomor_baru = 1;
        }

        $nomor_urut_format = str_pad($nomor_baru, 4, "0", STR_PAD_LEFT);

        $no_berita_acara_generate = $prefix . $nomor_urut_format;


        $query_cek_berita_acara = mysqli_query($conn, "SELECT * FROM berita_acara WHERE id_transaksi = '$id_transaksi'") or die(mysqli_error($conn));
        $rv = mysqli_num_rows($query_cek_berita_acara);

        if ($rv > 0) {
            echo '<script> alert("Berita Acara Untuk Transaksi Ini Sudah Ada!!!!"); 
         window.location.href="index.php" </script>';
        } else {
            $query_simpan = mysqli_query($conn, "INSERT INTO berita_acara 
            VALUES 
            (null,
            '$no_berita_acara_generate', 
            '$id_transaksi',
            '$tanggal',
            '$jml_kunci')
            ") or die (mysqli_error($conn)) ;

            echo '<script> alert("Data Berhasil Disimpan"); 
            window.location.href="index.php" </script>';
        }
    
    
}



?>
</body>
</html>