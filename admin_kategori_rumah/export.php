<?php
require '../vendor/autoload.php';
require_once '../database/config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header
$sheet->setCellValue('A1', 'ID Kategori');
$sheet->setCellValue('B1', 'Nama Kategori');
$sheet->setCellValue('C1', 'ID Site Plan');
$sheet->setCellValue('D1', 'Luas Bangunan');
$sheet->setCellValue('E1', 'Luas Tanah');
$sheet->setCellValue('F1', 'Jumlah Kamar');
$sheet->setCellValue('G1', 'Harga');
$sheet->setCellValue('H1', 'Deskripsi');

// Fetch data
$query = mysqli_query($conn, "SELECT * FROM kategori_rumah");
$row_num = 2;

while ($row = mysqli_fetch_array($query)) {
    $sheet->setCellValue('A' . $row_num, $row['id_kategori']);
    $sheet->setCellValue('B' . $row_num, $row['nama_kategori']);
    $sheet->setCellValue('C' . $row_num, $row['id_site_plan']);
    $sheet->setCellValue('D' . $row_num, $row['luas_bangunan']);
    $sheet->setCellValue('E' . $row_num, $row['luas_tanah']);
    $sheet->setCellValue('F' . $row_num, $row['jumlah_kamar']);
    $sheet->setCellValue('G' . $row_num, $row['harga']);
    $sheet->setCellValue('H' . $row_num, $row['deskripsi']);
    $row_num++;
}

// Auto size columns for each column
foreach(range('A','H') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$filename = 'Data_Kategori_Rumah_' . date('Ymd') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>

