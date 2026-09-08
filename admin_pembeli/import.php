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
        $double = 0;

        for($i = 1; $i < count($sheetData); $i++) {
            $nik   = mysqli_real_escape_string($conn, trim($sheetData[$i][1] ?? ''));
            $nama_pembeli           = mysqli_real_escape_string($conn, trim($sheetData[$i][2] ?? ''));
            $pasangan = mysqli_real_escape_string($conn, trim($sheetData[$i][3] ?? ''));
            $alamat               = mysqli_real_escape_string($conn, trim($sheetData[$i][4] ?? ''));
            $kontak           = mysqli_real_escape_string($conn, trim($sheetData[$i][5] ?? ''));

            // Skip empty rows
            if(empty($nik) || empty($nama_pembeli) || empty($alamat) || empty($kontak)) {
                $gagal++;
                continue;
            }

            $cek_data_ganda = mysqli_query($conn, "SELECT * FROM pembeli WHERE 
            nik = '$nik'
            ") or die (mysqli_error($conn));
            $rv = mysqli_num_rows($cek_data_ganda);


            if ($rv == 0) {
                $query = "INSERT INTO pembeli (nik, nama_pembeli, pasangan, alamat, kontak) VALUES ('$nik', '$nama_pembeli', '$pasangan', '$alamat', '$kontak')";
                if(mysqli_query($conn, $query)) {
                    $berhasil++;
                } else {
                    $gagal++;
                }
            } else {
                $double++;
                continue;
            }
            


        }

        echo "<script>
            alert('Import Data Selesai. Berhasil: $berhasil, Gagal/Skip: $gagal, Data Ganda: $double');
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