<?php 

require_once '../database/config.php';

if (isset($_POST['btn_edit'])) {

  $id_site_plan = trim(mysqli_real_escape_string($conn, $_POST['id_site_plan']));
  $nama_site_plan = trim(mysqli_real_escape_string($conn, $_POST['nama_site_plan']));
  $lokasi = trim(mysqli_real_escape_string($conn, $_POST['lokasi']));
  $penanggung_jawab = trim(mysqli_real_escape_string($conn, $_POST['penanggung_jawab']));
  $ig = trim(mysqli_real_escape_string($conn, $_POST['ig']));
  $tiktok = trim(mysqli_real_escape_string($conn, $_POST['tiktok']));
  

  $query_cek_site_plan = mysqli_query($conn, "SELECT nama_site_plan FROM site_plan WHERE nama_site_plan = '$nama_site_plan' AND id_site_plan != '$id_site_plan'")
  or die (mysqli_error($conn));
  $rv_kategori = mysqli_num_rows($query_cek_site_plan);

  if ($rv_kategori > 0) {
    echo '<script> alert("Nama Proyek Sudah Terdaftar! Input yang lain");
    window.location.href="index.php" </script>';
  } else {
    $query_edit = "UPDATE site_plan SET nama_site_plan='$nama_site_plan', lokasi='$lokasi', penanggung_jawab='$penanggung_jawab', ig='$ig', tiktok='$tiktok' WHERE id_site_plan='$id_site_plan'";
    $result_edit = mysqli_query($conn, $query_edit);


      echo "<script>alert('Data Site Plan berhasil diperbarui.'); window.location.href='index.php';</script>";
    
  }

}

?>