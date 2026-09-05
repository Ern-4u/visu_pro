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
            $nama_kategori = mysqli_real_escape_string($conn, trim($sheetData[$i][0] ?? ''));
            $id_site_plan  = mysqli_real_escape_string($conn, trim($sheetData[$i][1] ?? ''));
            $luas_bangunan = mysqli_real_escape_string($conn, trim($sheetData[$i][2] ?? ''));
            $luas_tanah    = mysqli_real_escape_string($conn, trim($sheetData[$i][3] ?? ''));
            $jumlah_kamar  = mysqli_real_escape_string($conn, trim($sheetData[$i][4] ?? ''));
            $harga         = mysqli_real_escape_string($conn, trim($sheetData[$i][5] ?? ''));
            $deskripsi     = mysqli_real_escape_string($conn, trim($sheetData[$i][6] ?? ''));

            if(empty($nama_kategori) || empty($id_site_plan)) {
                $gagal++;
                continue;
            }

            $query = "INSERT INTO kategori_rumah (nama_kategori, id_site_plan, luas_bangunan, luas_tanah, jumlah_kamar, harga, deskripsi) VALUES ('$nama_kategori', '$id_site_plan', '$luas_bangunan', '$luas_tanah', '$jumlah_kamar', '$harga', '$deskripsi')";
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

