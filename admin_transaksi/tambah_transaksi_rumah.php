<?php 
require_once('../database/config.php');

if (isset($_POST['btn_tambah'])) {
    $id_transaksi = trim(mysqli_real_escape_string($conn, $_POST['id_transaksi']));
    $metode_pembayaran = trim(mysqli_real_escape_string($conn, $_POST['metode_pembayaran']));
    $tanggal_akad = trim(mysqli_real_escape_string($conn, $_POST['tanggal_akad']));
    $tenor = (int) trim(mysqli_real_escape_string($conn, $_POST['tenor'])); 

    if ($tenor <= 0) {
        echo '<script> alert("Error: Tenor harus lebih dari 0"); window.history.back(); </script>';
        exit;
    }

    // MULAI TRANSAKSI
    mysqli_begin_transaction($conn);

    try {
        // 1. AMBIL HARGA RUMAH DARI DATABASE BERDASARKAN id_transaksi
        $query_harga = "SELECT kategori_rumah.harga 
                        FROM transaksi
                        JOIN rumah ON transaksi.id_rumah = rumah.id_rumah
                        JOIN kategori_rumah ON rumah.id_kategori = kategori_rumah.id_kategori
                        WHERE transaksi.id_transaksi = '$id_transaksi'";
        
        $result_harga = mysqli_query($conn, $query_harga) or throw new Exception(mysqli_error($conn));
        
        if (mysqli_num_rows($result_harga) == 0) {
            throw new Exception("Data transaksi atau rumah tidak ditemukan.");
        }
        
        $data_harga = mysqli_fetch_assoc($result_harga);
        $harga_rumah = $data_harga['harga'];

        // 2. SIMPAN DETAIL TRANSAKSI
        // Catatan: Typo 'mrtode_pembayaran' di kode sebelumnya sudah saya perbaiki menyesuaikan tabel Anda
        $query_detail = "INSERT INTO detail_transaksi_rumah 
                          (id_transaksi, metode_pembayaran, tanggal_akad, tenor, status_transaksi_rumah) 
                          VALUES 
                          ('$id_transaksi', '$metode_pembayaran', '$tanggal_akad', '$tenor', 'berjalan')";
        mysqli_query($conn, $query_detail) or throw new Exception(mysqli_error($conn));
        
        $pembayaran_ke = 1;
        $tanggal_awal = $tanggal_akad; // Menggunakan tanggal akad sebagai patokan awal cicilan
        
        // 3. HITUNG JUMLAH TAGIHAN (Harga Rumah dibagi Tenor)
        $jumlah_tagihan = $harga_rumah / $tenor;

        // Looping simpan jadwal pembayaran
        while($pembayaran_ke <= $tenor) {
            $jatuh_tempo = date('Y-m-d', strtotime("+$pembayaran_ke month", strtotime($tanggal_awal)));
            
            // Catatan: jenis_tagihan saya ubah menjadi 'rumah' menyesuaikan enum di database Anda
            $query_jadwal = "INSERT INTO jadwal_pembayaran 
                             (id_jadwal_pembayaran, id_transaksi, jenis_tagihan, angsuran_ke, jatuh_tempo, jumlah_tagihan, status) 
                             VALUES 
                             (null, '$id_transaksi', 'rumah', '$pembayaran_ke', '$jatuh_tempo', '$jumlah_tagihan', 'belum_bayar')";
            
            mysqli_query($conn, $query_jadwal) or throw new Exception(mysqli_error($conn));
            
            $pembayaran_ke++; 
        }

        // SIMPAN PERMANEN KE DATABASE
        mysqli_commit($conn);
        
        echo '<script> alert("Data Berhasil Disimpan"); 
        window.location.href="detail.php?id='.$id_transaksi.'" </script>';

    } catch (Exception $e) {
        // BATALKAN JIKA ADA ERROR
        mysqli_rollback($conn);
        echo '<script> alert("Gagal menyimpan data: ' . $e->getMessage() . '"); 
        window.history.back(); </script>';
    }
}
?>