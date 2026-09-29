<?php
require_once '../database/config.php';

if (isset($_POST['btn_tambah'])) {
    $id_pembeli = trim(mysqli_real_escape_string($conn, $_POST['id_pembeli']));
    $id_karyawan = trim(mysqli_real_escape_string($conn, $_POST['id_karyawan']));
    $id_rumah = trim(mysqli_real_escape_string($conn, $_POST['id_rumah']));
    $status_transaksi = 'Berlangsung';
    $total = 0;

    $tanggal = date('y-m-d');

    $prefix = $tanggal . "-";

    $query = "SELECT no_transaksi FROM transaksi WHERE no_transaksi LIKE '$prefix%' ORDER BY no_transaksi DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nota_terakhir = $row['no_transaksi'];
        
        $pecah = explode("-", $nota_terakhir);
        $nomor_terakhir = (int) end($pecah); 
        
        $nomor_baru = $nomor_terakhir + 1;
    } else {
       
        $nomor_baru = 1;
    }

    $nomor_urut_format = str_pad($nomor_baru, 4, "0", STR_PAD_LEFT);

    $no_nota_generate = $prefix . $nomor_urut_format;


    
        $query_simpan = mysqli_query($conn, "INSERT INTO transaksi 
         VALUES 
         (null,
         '$no_nota_generate', 
         '$id_pembeli',
         '$id_karyawan',
         '$id_rumah',
         '$status_transaksi',
         '$tanggal'
         )
         ") or die (mysqli_error($conn)) ;
         $id_transaksi = mysqli_insert_id($conn);

        $update_status_rumah = mysqli_query($conn, "UPDATE rumah SET status = '4' WHERE id_rumah = '$id_rumah'") or die(mysqli_error($conn));
        

        echo '<script> alert("Data Berhasil Disimpan"); 
        window.location.href="index.php" </script>';
    
}



?>