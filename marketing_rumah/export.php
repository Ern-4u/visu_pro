<?php
require '../vendor/autoload.php';
require_once '../database/config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header
$sheet->setCellValue('A1', 'ID Rumah');
$sheet->setCellValue('B1', 'Kode Blok');
$sheet->setCellValue('C1', 'ID Kategori');
$sheet->setCellValue('D1', 'Status');

// Fetch data
$query = mysqli_query($conn, "SELECT * FROM rumah");
$row_num = 2;

while ($row = mysqli_fetch_array($query)) {
    $sheet->setCellValue('A' . $row_num, $row['id_rumah']);
    $sheet->setCellValue('B' . $row_num, $row['kode_blok']);
    $sheet->setCellValue('C' . $row_num, $row['id_kategori']);
    $sheet->setCellValue('D' . $row_num, $row['status']);
    $row_num++;
}

// Auto size columns for each column
foreach(range('A','D') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$filename = 'Data_Rumah_' . date('Ymd') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>

