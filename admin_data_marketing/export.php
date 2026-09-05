<?php
require '../vendor/autoload.php';
require_once '../database/config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header
$sheet->setCellValue('A1', 'ID Karyawan');
$sheet->setCellValue('B1', 'Nama Lengkap');
$sheet->setCellValue('C1', 'Kontak');
$sheet->setCellValue('D1', 'Email');
$sheet->setCellValue('E1', 'Kelamin');
$sheet->setCellValue('F1', 'Foto');

// Fetch data
$query = mysqli_query($conn, "SELECT * FROM marketing");
$row_num = 2;

while ($row = mysqli_fetch_array($query)) {
    $sheet->setCellValue('A' . $row_num, $row['id_karyawan']);
    $sheet->setCellValue('B' . $row_num, $row['nama']);
    $sheet->setCellValue('C' . $row_num, $row['kontak']);
    $sheet->setCellValue('D' . $row_num, $row['email']);
    $sheet->setCellValue('E' . $row_num, $row['kelamin']);
    $sheet->setCellValue('F' . $row_num, $row['foto']);
    $row_num++;
}

// Auto size columns for each column
foreach(range('A','F') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$filename = 'Data_Marketing_' . date('Ymd') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>

