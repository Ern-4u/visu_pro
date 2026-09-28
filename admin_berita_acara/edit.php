<?php 

require_once '../database/config.php';

if (isset($_POST['btn_edit'])) {

  $id_pembeli = trim(mysqli_real_escape_string($conn, $_POST['id_pembeli']));
  $nama_pembeli = trim(mysqli_real_escape_string($conn, $_POST['nama_pembeli']));
  $nik = trim(mysqli_real_escape_string($conn, $_POST['nik']));
  $pasangan = trim(mysqli_real_escape_string($conn, $_POST['pasangan']));
  $alamat = trim(mysqli_real_escape_string($conn, $_POST['alamat']));
  $kontak = trim(mysqli_real_escape_string($conn, $_POST['kontak']));
  

  
    $query_edit = "UPDATE pembeli SET nama_pembeli='$nama_pembeli', nik='$nik', pasangan='$pasangan', alamat='$alamat', kontak='$kontak' WHERE id_pembeli='$id_pembeli'";
    $result_edit = mysqli_query($conn, $query_edit);

      echo "<script>alert('Data berhasil diperbarui.'); window.location.href='index.php';</script>";
    
  

}

?>