<?php 
require_once('../database/config.php');
?>
<?php
// TAHAP 1
if (isset($_POST['btn_tambah_tahap1'])) {
    $presentase = trim(mysqli_real_escape_string($conn, $_POST['presentase']));
    $id_rumah = trim(mysqli_real_escape_string($conn, $_POST['id_rumah']));
    $keterangan = trim(mysqli_real_escape_string($conn, $_POST['keterangan']));
    $id_tahap = 1;
    $tanggal = date('Y-m-d');

    $file = $_FILES['foto']['name'];
    $ekstensi = explode('.', $file);
    $nama_file = 'fto_history-'.round(microtime(true)).'.'.end($ekstensi);

    $alamat_tujuan = '../assets/fto_history/'.$nama_file;
    $file_alamat_sumber = $_FILES['foto']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);

    $query_cek_presentase = mysqli_query($conn, "SELECT presentase_progres FROM history_pembangunan
                                        WHERE id_tahap = '$id_tahap' AND id_rumah ='$id_rumah'
                                        ORDER BY presentase_progres DESC LIMIT 1")or die(mysqli_error($conn));
    
    if (mysqli_num_rows($query_cek_presentase) > 0) {
        $cek_presentase = mysqli_fetch_array($query_cek_presentase);
        $presentase_sebelumnya = $cek_presentase['presentase_progres'];

        if ($presentase <= $presentase_sebelumnya) {
            echo '<script> alert("Gagal! Progres persentase tidak boleh sama atau kurang dari nilai sebelumnya (' . $presentase_sebelumnya . '%).");
            window.location.href="index.php?id='.$id_rumah.'" </script>';
            
        } else {
             $query_insert = mysqli_query($conn, "INSERT INTO history_pembangunan VALUES (
                                        null,
                                        '$id_tahap',
                                        '$id_rumah',
                                        '$tanggal',
                                        '$presentase',
                                        '$keterangan',
                                        '$nama_file'
                                        )")or die(mysqli_error($conn));
            echo '<script> alert("Berhasil Menambah Progres");
            window.location.href="index.php?id='.$id_rumah.'" </script>';
        }
    } else {
         $query_insert = mysqli_query($conn, "INSERT INTO history_pembangunan VALUES (
                                        null,
                                        '$id_tahap',
                                        '$id_rumah',
                                        '$tanggal',
                                        '$presentase',
                                        '$keterangan',
                                        '$nama_file'
                                        )")or die(mysqli_error($conn));

        echo '<script> alert("Berhasil Menambah Progres");
        window.location.href="index.php?id='.$id_rumah.'" </script>';
    }
   
}

// TAHAP 2
if (isset($_POST['btn_tambah_tahap2'])) { 
    $presentase = trim(mysqli_real_escape_string($conn, $_POST['presentase']));
    $id_rumah = trim(mysqli_real_escape_string($conn, $_POST['id_rumah']));
    $keterangan = trim(mysqli_real_escape_string($conn, $_POST['keterangan']));
    $id_tahap = 2; // Diubah menjadi 2
    $tanggal = date('Y-m-d');

    $file = $_FILES['foto']['name'];
    $ekstensi = explode('.', $file);
    $nama_file = 'fto_history-'.round(microtime(true)).'.'.end($ekstensi);

    $alamat_tujuan = '../assets/fto_history/'.$nama_file;
    $file_alamat_sumber = $_FILES['foto']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);

    
    $query_cek_tahap1 = mysqli_query($conn, "SELECT presentase_progres FROM history_pembangunan WHERE id_tahap = '1' AND id_rumah = '$id_rumah'
                                            ORDER BY presentase_progres DESC LIMIT 1")or die(mysqli_error($conn));
    
    if (mysqli_num_rows($query_cek_tahap1) > 0) {
        $presentase_tahap1 = mysqli_fetch_array($query_cek_tahap1);
        
        
        if ($presentase_tahap1['presentase_progres'] == 100) {
            
            $query_cek_presentase = mysqli_query($conn, "SELECT presentase_progres FROM history_pembangunan
                                                WHERE id_tahap = '$id_tahap' AND id_rumah ='$id_rumah'
                                                ORDER BY presentase_progres DESC LIMIT 1")or die(mysqli_error($conn));
            
            if (mysqli_num_rows($query_cek_presentase) > 0) {
                $cek_presentase = mysqli_fetch_array($query_cek_presentase);
                $presentase_sebelumnya = $cek_presentase['presentase_progres'];

                if ($presentase <= $presentase_sebelumnya) {
                    echo '<script> alert("Gagal! Progres persentase tidak boleh sama atau kurang dari nilai sebelumnya (' . $presentase_sebelumnya . '%).");
                    window.location.href="index.php?id='.$id_rumah.'" </script>';
                } else {
                    $query_insert = mysqli_query($conn, "INSERT INTO history_pembangunan VALUES (
                                            null,
                                            '$id_tahap',
                                            '$id_rumah',
                                            '$tanggal',
                                            '$presentase',
                                            '$keterangan',
                                            '$nama_file'
                                            )")or die(mysqli_error($conn));
                    echo '<script> alert("Berhasil Menambah Progres Tahap 2");
                    window.location.href="index.php?id='.$id_rumah.'" </script>';
                }
            } else {
                $query_insert = mysqli_query($conn, "INSERT INTO history_pembangunan VALUES (
                                            null,
                                            '$id_tahap',
                                            '$id_rumah',
                                            '$tanggal',
                                            '$presentase',
                                            '$keterangan',
                                            '$nama_file'
                                            )")or die(mysqli_error($conn));

                echo '<script> alert("Berhasil Menambah Progres Tahap 2");
                window.location.href="index.php?id='.$id_rumah.'" </script>';
            }
        } else {
            echo '<script> alert("Selesaikan Progres Tahap 1 hingga 100% terlebih dahulu!!!");
                window.location.href="index.php?id='.$id_rumah.'" </script>';
        }
    } else {
        echo '<script> alert("Progres Tahap 1 belum dimulai sama sekali!");
            window.location.href="index.php?id='.$id_rumah.'" </script>';
    }
}
// TAHAP 3
if (isset($_POST['btn_tambah_tahap3'])) {
    $presentase = trim(mysqli_real_escape_string($conn, $_POST['presentase']));
    $id_rumah = trim(mysqli_real_escape_string($conn, $_POST['id_rumah']));
    $keterangan = trim(mysqli_real_escape_string($conn, $_POST['keterangan']));
    $id_tahap = 3; 
    $tanggal = date('Y-m-d');

    $file = $_FILES['foto']['name'];
    $ekstensi = explode('.', $file);
    $nama_file = 'fto_history-'.round(microtime(true)).'.'.end($ekstensi);

    $alamat_tujuan = '../assets/fto_history/'.$nama_file;
    $file_alamat_sumber = $_FILES['foto']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);

    
    $query_cek_tahap2 = mysqli_query($conn, "SELECT presentase_progres FROM history_pembangunan WHERE id_tahap = '2' AND id_rumah = '$id_rumah'
                                            ORDER BY presentase_progres DESC LIMIT 1")or die(mysqli_error($conn));
    
    if (mysqli_num_rows($query_cek_tahap2) > 0) {
        $presentase_tahap2 = mysqli_fetch_array($query_cek_tahap2);
        
        
        if ($presentase_tahap2['presentase_progres'] == 100) {
            
            $query_cek_presentase = mysqli_query($conn, "SELECT presentase_progres FROM history_pembangunan
                                                WHERE id_tahap = '$id_tahap' AND id_rumah ='$id_rumah'
                                                ORDER BY presentase_progres DESC LIMIT 1")or die(mysqli_error($conn));
            
            if (mysqli_num_rows($query_cek_presentase) > 0) {
                $cek_presentase = mysqli_fetch_array($query_cek_presentase);
                $presentase_sebelumnya = $cek_presentase['presentase_progres'];

                if ($presentase <= $presentase_sebelumnya) {
                    echo '<script> alert("Gagal! Progres persentase tidak boleh sama atau kurang dari nilai sebelumnya (' . $presentase_sebelumnya . '%).");
                    window.location.href="index.php?id='.$id_rumah.'" </script>';
                } else {
                    $query_insert = mysqli_query($conn, "INSERT INTO history_pembangunan VALUES (
                                            null,
                                            '$id_tahap',
                                            '$id_rumah',
                                            '$tanggal',
                                            '$presentase',
                                            '$keterangan',
                                            '$nama_file'
                                            )")or die(mysqli_error($conn));
                    echo '<script> alert("Berhasil Menambah Progres Tahap 3");
                    window.location.href="index.php?id='.$id_rumah.'" </script>';
                }
            } else {
                $query_insert = mysqli_query($conn, "INSERT INTO history_pembangunan VALUES (
                                            null,
                                            '$id_tahap',
                                            '$id_rumah',
                                            '$tanggal',
                                            '$presentase',
                                            '$keterangan',
                                            '$nama_file'
                                            )")or die(mysqli_error($conn));

                echo '<script> alert("Berhasil Menambah Progres Tahap 3");
                window.location.href="index.php?id='.$id_rumah.'" </script>';
            }
        } else {
            echo '<script> alert("Selesaikan Progres Tahap 2 hingga 100% terlebih dahulu!!!");
                window.location.href="index.php?id='.$id_rumah.'" </script>';
        }
    } else {
        echo '<script> alert("Progres Tahap 2 belum dimulai sama sekali!");
            window.location.href="index.php?id='.$id_rumah.'" </script>';
    }
}
// TAHAPP 4
if (isset($_POST['btn_tambah_tahap4'])) { 
    $presentase = trim(mysqli_real_escape_string($conn, $_POST['presentase']));
    $id_rumah = trim(mysqli_real_escape_string($conn, $_POST['id_rumah']));
    $keterangan = trim(mysqli_real_escape_string($conn, $_POST['keterangan']));
    $id_tahap = 4; 
    $tanggal = date('Y-m-d');

    $file = $_FILES['foto']['name'];
    $ekstensi = explode('.', $file);
    $nama_file = 'fto_history-'.round(microtime(true)).'.'.end($ekstensi);

    $alamat_tujuan = '../assets/fto_history/'.$nama_file;
    $file_alamat_sumber = $_FILES['foto']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);

    
    $query_cek_tahap3 = mysqli_query($conn, "SELECT presentase_progres FROM history_pembangunan WHERE id_tahap = '3' AND id_rumah = '$id_rumah'
                                            ORDER BY presentase_progres DESC LIMIT 1")or die(mysqli_error($conn));
    
    if (mysqli_num_rows($query_cek_tahap3) > 0) {
        $presentase_tahap3 = mysqli_fetch_array($query_cek_tahap3);
        
        
        if ($presentase_tahap3['presentase_progres'] == 100) {
            
            $query_cek_presentase = mysqli_query($conn, "SELECT presentase_progres FROM history_pembangunan
                                                WHERE id_tahap = '$id_tahap' AND id_rumah ='$id_rumah'
                                                ORDER BY presentase_progres DESC LIMIT 1")or die(mysqli_error($conn));
            
            if (mysqli_num_rows($query_cek_presentase) > 0) {
                $cek_presentase = mysqli_fetch_array($query_cek_presentase);
                $presentase_sebelumnya = $cek_presentase['presentase_progres'];

                if ($presentase <= $presentase_sebelumnya) {
                    echo '<script> alert("Gagal! Progres persentase tidak boleh sama atau kurang dari nilai sebelumnya (' . $presentase_sebelumnya . '%).");
                    window.location.href="index.php?id='.$id_rumah.'" </script>';
                } else {
                    $query_insert = mysqli_query($conn, "INSERT INTO history_pembangunan VALUES (
                                            null,
                                            '$id_tahap',
                                            '$id_rumah',
                                            '$tanggal',
                                            '$presentase',
                                            '$keterangan',
                                            '$nama_file'
                                            )")or die(mysqli_error($conn));
                    echo '<script> alert("Berhasil Menambah Progres Tahap 4");
                    window.location.href="index.php?id='.$id_rumah.'" </script>';
                }
            } else {
                $query_insert = mysqli_query($conn, "INSERT INTO history_pembangunan VALUES (
                                            null,
                                            '$id_tahap',
                                            '$id_rumah',
                                            '$tanggal',
                                            '$presentase',
                                            '$keterangan',
                                            '$nama_file'
                                            )")or die(mysqli_error($conn));

                echo '<script> alert("Berhasil Menambah Progres Tahap 4");
                window.location.href="index.php?id='.$id_rumah.'" </script>';
            }
        } else {
            echo '<script> alert("Selesaikan Progres Tahap 3 hingga 100% terlebih dahulu!!!");
                window.location.href="index.php?id='.$id_rumah.'" </script>';
        }
    } else {
        echo '<script> alert("Progres Tahap 3 belum dimulai sama sekali!");
            window.location.href="index.php?id='.$id_rumah.'" </script>';
    }
}


?>
