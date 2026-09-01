<?php 

require_once '../database/config.php';

if (isset($_POST['btn_edit'])) {

  $id_kategori = trim(mysqli_real_escape_string($conn, $_POST['id_kategori']));
  $nama_kategori = trim(mysqli_real_escape_string($conn, $_POST['nama_kategori']));
  $luas_bangunan = trim(mysqli_real_escape_string($conn, $_POST['luas_bangunan']));
  $luas_tanah = trim(mysqli_real_escape_string($conn, $_POST['luas_tanah']));
  $jumlah_kamar = trim(mysqli_real_escape_string($conn, $_POST['jumlah_kamar']));
  $harga = trim(mysqli_real_escape_string($conn, $_POST['harga']));
  $deskripsi = trim(mysqli_real_escape_string($conn, $_POST['deskripsi']));

  $query_cek_kategori = mysqli_query($conn, "SELECT nama_kategori FROM kategori_rumah WHERE nama_kategori = '$nama_kategori' AND id_kategori != '$id_kategori'")
  or die (mysqli_error($conn));
  $rv_kategori = mysqli_num_rows($query_cek_kategori);

  if ($rv_kategori > 0) {
    echo '<script> alert("Nama Kategori Sudah Terdaftar! Input yang lain");
    window.location.href="index.php" </script>';
  } else {
    $query_edit = "UPDATE kategori_rumah SET nama_kategori='$nama_kategori', luas_bangunan='$luas_bangunan', luas_tanah='$luas_tanah', jumlah_kamar='$jumlah_kamar', harga='$harga', deskripsi='$deskripsi' WHERE id_kategori='$id_kategori'";
    $result_edit = mysqli_query($conn, $query_edit);

    if ($result_edit) {
      echo "<script>alert('Data Kategori berhasil diperbarui.'); window.location.href='index.php';</script>";
    } else {
      echo "<script>alert('Terjadi kesalahan saat memperbarui data kategori.');</script>";
    }
  }

}

?>