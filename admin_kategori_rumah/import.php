<?php
require '../vendor/autoload.php';
require_once '../database/config.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['btn_import'])) {
    $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    if(isset($_FILES['file_excel']['name']) && in_array($_FILES['file_excel']['type'], $file_mimes)) {
        
        $arr_file = explode('.', $_FILES['file_excel']['name']);
        $extension = end($arr_file);

        if('csv' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else if('xls' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }

        $spreadsheet = $reader->load($_FILES['file_excel']['tmp_name']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $berhasil = 0;
        $gagal = 0;
        $double = 0; // Menambahkan variabel double jika ingin mencegah duplikasi

        // Looping dimulai dari 1 untuk melewati baris header di Excel
        for($i = 1; $i < count($sheetData); $i++) {
            
            // Menyesuaikan index array dengan urutan kolom di Excel
            $nama_kategori = mysqli_real_escape_string($conn, trim($sheetData[$i][1] ?? ''));
            $luas_bangunan = mysqli_real_escape_string($conn, trim($sheetData[$i][2] ?? ''));
            $luas_tanah    = mysqli_real_escape_string($conn, trim($sheetData[$i][3] ?? ''));
            $jumlah_kamar  = mysqli_real_escape_string($conn, trim($sheetData[$i][4] ?? ''));
            $harga         = mysqli_real_escape_string($conn, trim($sheetData[$i][5] ?? ''));
            $deskripsi     = mysqli_real_escape_string($conn, trim($sheetData[$i][6] ?? ''));

            // Validasi: Skip jika nama_kategori atau harga kosong
            if(empty($nama_kategori) || empty($harga)) {
                $gagal++;
                continue;
            }

            // (Opsional) Pengecekan data ganda berdasarkan nama_kategori
            $cek_ganda = mysqli_query($conn, "SELECT * FROM kategori_rumah WHERE nama_kategori = '$nama_kategori'");
            if (mysqli_num_rows($cek_ganda) > 0) {
                $double++;
                continue;
            }

            // Eksekusi query INSERT ke tabel kategori_rumah
            $query = "INSERT INTO kategori_rumah (nama_kategori, luas_bangunan, luas_tanah, jumlah_kamar, harga, deskripsi) 
                      VALUES ('$nama_kategori', '$luas_bangunan', '$luas_tanah', '$jumlah_kamar', '$harga', '$deskripsi')";
            
            if(mysqli_query($conn, $query)) {
                $berhasil++;
            } else {
                $gagal++;
            }
        }

        echo "<script>
            alert('Import Data Selesai. Berhasil: $berhasil, Gagal/Skip: $gagal, Double: $double');
            window.location.href='index.php';
        </script>";
    } else {
        echo "<script>
            alert('Format file tidak didukung! Gunakan file .xls, .xlsx, atau .csv');
            window.location.href='index.php';
        </script>";
    }
}
?>