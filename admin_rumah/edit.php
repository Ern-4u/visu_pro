<?php 

require_once '../database/config.php';

if (isset($_POST['btn_edit'])) {

  $id_rumah = trim(mysqli_real_escape_string($conn, $_POST['id_rumah']));
  $kode_blok = trim(mysqli_real_escape_string($conn, $_POST['kode_blok']));
  $id_kategori = trim(mysqli_real_escape_string($conn, $_POST['id_kategori']));
  $status = trim(mysqli_real_escape_string($conn, $_POST['status']));

  $query_cek_blok = mysqli_query($conn, "SELECT kode_blok FROM rumah WHERE kode_blok = '$kode_blok' AND id_rumah != '$id_rumah'")
  or die (mysqli_error($conn));
  $rv_kategori = mysqli_num_rows($query_cek_blok);

  if ($rv_kategori > 0) {
    echo '<script> alert("Kode Blok  Sudah Terdaftar! Input yang lain");
    window.location.href="index.php" </script>';
  } else {
    $query_edit = "UPDATE rumah SET kode_blok='$kode_blok', id_kategori='$id_kategori', status='$status' WHERE id_rumah='$id_rumah'";
    $result_edit = mysqli_query($conn, $query_edit);

    if ($result_edit) {
      echo "<script>alert('Data Rumah berhasil diperbarui.'); window.location.href='index.php';</script>";
    } else {
      echo "<script>alert('Terjadi kesalahan saat memperbarui data Rumah.');</script>";
    }
  }

}

?>