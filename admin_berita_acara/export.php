<?php
require '../vendor/autoload.php';
require_once '../database/config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'NIK');
$sheet->setCellValue('C1', 'Nama Pembeli');
$sheet->setCellValue('D1', 'Pasangan');
$sheet->setCellValue('E1', 'Alamat');
$sheet->setCellValue('F1', 'Kontak');

// Fetch data
$query = mysqli_query($conn, "SELECT * FROM pembeli");
$row_num = 2;
$no = 1;

while ($row = mysqli_fetch_array($query)) {
    $sheet->setCellValue('A' . $row_num, $no++);
    $sheet->setCellValue('B' . $row_num, $row['nik']);
    $sheet->setCellValue('C' . $row_num, $row['nama_pembeli']);
    $sheet->setCellValue('D' . $row_num, $row['pasangan']);
    $sheet->setCellValue('E' . $row_num, $row['alamat']);
    $sheet->setCellValue('F' . $row_num, $row['kontak']);
    $row_num++;
}

// Auto size columns for each column
foreach(range('A','G') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$filename = 'Data_Pembeli_' . date('Ymd') . '.xls';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>

