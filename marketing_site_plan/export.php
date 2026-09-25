<?php
require '../vendor/autoload.php';
require_once '../database/config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header
$sheet->setCellValue('A1', 'ID Site Plan');
$sheet->setCellValue('B1', 'Nama Perumahan');
$sheet->setCellValue('C1', 'Lokasi');
$sheet->setCellValue('D1', 'Kontak PJ');
$sheet->setCellValue('E1', 'Instagram');
$sheet->setCellValue('F1', 'Tiktok');
$sheet->setCellValue('G1', 'File Brosur');

// Fetch data
$query = mysqli_query($conn, "SELECT * FROM site_plan");
$row_num = 2;

while ($row = mysqli_fetch_array($query)) {
    $sheet->setCellValue('A' . $row_num, $row['id_site_plan']);
    $sheet->setCellValue('B' . $row_num, $row['nama_site_plan']);
    $sheet->setCellValue('C' . $row_num, $row['lokasi']);
    $sheet->setCellValue('D' . $row_num, $row['penanggung_jawab']);
    $sheet->setCellValue('E' . $row_num, $row['ig']);
    $sheet->setCellValue('F' . $row_num, $row['tiktok']);
    $sheet->setCellValue('G' . $row_num, $row['brosur']);
    $row_num++;
}

// Auto size columns for each column
foreach(range('A','G') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$filename = 'Data_Site_Plan_' . date('Ymd') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>

