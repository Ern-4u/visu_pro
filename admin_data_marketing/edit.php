<?php 

require_once '../database/config.php';

if (isset($_POST['btn_edit'])) {

  $id_karyawan = trim(mysqli_real_escape_string($conn, $_POST['id_karyawan']));
  $nama = trim(mysqli_real_escape_string($conn, $_POST['nama']));
  $kontak = trim(mysqli_real_escape_string($conn, $_POST['kontak']));
  $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
  $kelamin = trim(mysqli_real_escape_string($conn, $_POST['kelamin']));

  

  $query = "UPDATE marketing SET nama='$nama', kontak ='$kontak', email='$email', kelamin='$kelamin' WHERE id_karyawan='$id_karyawan'";
  $result = mysqli_query($conn, $query);

  $query_user = mysqli_query($conn, "UPDATE users SET nama = '$nama' WHERE username='$id_karyawan'") or die(mysqli_error($conn));

  if ($result) {
    echo "<script>alert('Data Karyawan berhasil diperbarui.'); window.location.href='index.php';</script>";
  } else {
    echo "<script>alert('Terjadi kesalahan saat memperbarui data karyawan.');</script>";
  }
}

?>