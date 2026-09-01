<?php 

require_once '../database/config.php';

if (isset($_POST['btn_edit'])) {

  
  $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
  $nama = trim(mysqli_real_escape_string($conn, $_POST['nama']));
  
  


  $query = "UPDATE users SET  nama='$nama' WHERE username='$username'";
  $result = mysqli_query($conn, $query);

  if ($result) {
    echo "<script>alert('Data admin berhasil diperbarui.'); window.location.href='index.php';</script>";
  } else {
    echo "<script>alert('Terjadi kesalahan saat memperbarui data admin.'); window.location.href='index.php';</script>";
  }
}

?>