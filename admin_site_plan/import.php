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

        for($i = 1; $i < count($sheetData); $i++) {
            $nama_site_plan   = mysqli_real_escape_string($conn, trim($sheetData[$i][1] ?? ''));
            $lokasi           = mysqli_real_escape_string($conn, trim($sheetData[$i][2] ?? ''));
            $penanggung_jawab = mysqli_real_escape_string($conn, trim($sheetData[$i][3] ?? ''));
            $ig               = mysqli_real_escape_string($conn, trim($sheetData[$i][4] ?? ''));
            $tiktok           = mysqli_real_escape_string($conn, trim($sheetData[$i][5] ?? ''));

            // Skip empty rows
            if(empty($nama_site_plan) || empty($lokasi) || empty($penanggung_jawab)) {
                $gagal++;
                continue;
            }

            $query = "INSERT INTO site_plan (nama_site_plan, lokasi, penanggung_jawab, ig, tiktok) VALUES ('$nama_site_plan', '$lokasi', '$penanggung_jawab', '$ig', '$tiktok')";
            if(mysqli_query($conn, $query)) {
                $berhasil++;
            } else {
                $gagal++;
            }
        }

        echo "<script>
            alert('Import Data Selesai. Berhasil: $berhasil, Gagal/Skip: $gagal');
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