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
        $double = 0; // Tambahkan variabel untuk menghitung data ganda

        for($i = 1; $i < count($sheetData); $i++) {
            $kode_blok    = mysqli_real_escape_string($conn, trim($sheetData[$i][1] ?? ''));
            $id_kategori  = mysqli_real_escape_string($conn, trim($sheetData[$i][2] ?? ''));
            $status       = mysqli_real_escape_string($conn, trim($sheetData[$i][3] ?? ''));
            $id_site_plan = mysqli_real_escape_string($conn, trim($sheetData[$i][4] ?? ''));

            if(empty($kode_blok) || empty($id_kategori) || empty($status) || empty($id_site_plan) ) {
                $gagal++;
                continue;
            }

            // CEK DATA GANDA berdasarkan kode_blok dan id_site_plan
            $cek_data_ganda = mysqli_query($conn, "SELECT * FROM rumah WHERE kode_blok = '$kode_blok' AND id_site_plan = '$id_site_plan'");
            
            if (mysqli_num_rows($cek_data_ganda) > 0) {
                // Jika data sudah ada, lewati proses insert dan tambahkan angka double
                $double++;
                continue;
            }

            // Jika tidak ganda, lakukan insert
            $query = "INSERT INTO rumah (kode_blok, id_kategori, status, id_site_plan) VALUES ('$kode_blok', '$id_kategori', '$status', '$id_site_plan')";
            if(mysqli_query($conn, $query)) {
                $berhasil++;
            } else {
                $gagal++;
            }
        }

        // Tampilkan juga jumlah data yang terdeteksi ganda pada alert
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