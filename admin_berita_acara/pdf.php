<?php
require_once '../database/config.php';
$id_berita_acara = @$_GET['id'];

$query_ambil_data = mysqli_query($conn, "SELECT b.*,t.*,p.* FROM berita_acara b
                                        LEFT JOIN transaksi t ON b.id_transaksi = t.id_transaksi
                                        LEFT JOIN pembeli p ON t.id_pembeli = p.id_pembeli 
                                        WHERE b.id_berita_acara = '$id_berita_acara'") or die(mysqli_error($conn));


/**
 * Generate PDF "Berita Acara Serah Terima Kunci Rumah" dengan mPDF.
 *
 * Instalasi : composer require mpdf/mpdf
 * Logo      : letakkan file logo di folder yang sama dengan nama "logo.png"
 * Jalankan  : php generate_bast.php   (simpan ke file)
 *             atau akses lewat browser (tampil di browser)
 */

require_once '../vendor/autoload.php';

// ======================== DATA (ubah sesuai kebutuhan) ========================
$data = [
    'perusahaan'   => 'PT BANGUN INDAH NEGERI',
    'alamat_1'     => 'Alamat : Jalan Raya Grengseng RT 04 RW 10 Desa Taraban Kec.Paguyangan',
    'alamat_2'     => 'Kabupaten Brebes, Jawa Tengah',
    'logo'         => __DIR__ . '/logo.png',

    'nomor'        => 'BAST/GH-RES/2026/10/001',
    'hari_tanggal' => 'Senin, 05 Oktober 2026',

    'pihak1_nama'    => 'Budi Santoso, S.T.',
    'pihak1_jabatan' => 'Estate Manager PT Grand Nusantara Land',
    'pihak1_alamat'  => 'Jl. Jend. Sudirman No. 88, Jakarta',

    'pihak2_nama' => 'Ahmad Rizky Pratama, S.E.',
    'pihak2_ktp'  => '3174051208880003',
    'pihak2_hp'   => '0812-3456-7890',

    'perumahan' => 'Grand Harmony Residence',
    'unit'      => 'Blok E No. 12',
    'tipe_luas' => 'Tipe 70 / LT 120 m² / LB 70 m²',
    'listrik'   => '2.200 VA / PDAM',

    'kelengkapan' => [
        'Kunci Pintu Utama'       => '3 Set (6 Buah)',
        'Kunci Pintu Kamar'       => '3 Set (6 Buah)',
        'Kunci Pintu Dapur/Mandi' => '3 Set (4 Buah)',
    ],

    'ketentuan' => [
        'Tanggung jawab fisik bangunan, penggunaan listrik, air, dan iuran lingkungan (IPL) beralih kepada PIHAK KEDUA sejak BAST ini ditandatangani.',
        'Garansi pemeliharaan bangunan berlaku selama 100 hari kalender sejak tanggal penyerahan ini.',
    ],
];

$e = fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

// ======================== KOP SURAT (muncul di setiap halaman) ========================
$logoHtml = file_exists($data['logo'])
    ? '<img src="' . $data['logo'] . '" style="width:22mm; height:22mm;" />'
    : '';

$header = '
<div style="border-bottom: 1.6pt solid #000000; padding-bottom: 6mm;">
  <table width="100%" style="border-collapse:collapse;">
    <tr>
      <td width="26mm" style="vertical-align:middle; padding:0 0 0 0;">' . $logoHtml . '</td>
      <td style="vertical-align:middle; text-align:center; padding:0 26mm 0 0;">
        <div style="font-size:23pt; font-weight:bold; line-height:1.1;">' . $e($data['perusahaan']) . '</div>
        <div style="font-size:11pt; line-height:1.15; margin-top:1mm;">' . $e($data['alamat_1']) . '<br>' . $e($data['alamat_2']) . '</div>
      </td>
    </tr>
  </table>
</div>';

// ======================== CSS ========================
$css = '
body { font-family: freeserif; font-size: 12pt; color: #000000; }
.wrap { padding: 0 4mm 0 5mm; }
p { margin: 0; }
.judul { text-align:center; font-weight:bold; font-size:13.5pt; text-decoration:none; margin-top:2mm; }
.nomor { text-align:center; font-size:12pt; margin-top:5mm; }
.pembuka { margin-top:6mm; }
.row { margin-left:12mm; margin-top:4mm; }
.det  { margin-left:15.5mm; margin-top:4mm; }
.isi  { text-align:justify; text-indent:12mm; margin-top:5mm; line-height:1.5; }
.sub  { margin-left:6mm; margin-top:3mm; }
table.t { border-collapse:collapse; margin-left:20mm; }
table.t td { padding:1.2mm 0; vertical-align:top; font-size:12pt; }
table.ttd { width:100%; border-collapse:collapse; margin-top:3mm; }
table.ttd td { text-align:center; vertical-align:top; font-size:12pt; }
';

// ======================== HALAMAN 1 ========================
$html = '<div class="wrap">';

$html .= '<p class="judul">BERITA ACARA SERAH TERIMA KUNCI RUMAH</p>';
$html .= '<p class="nomor">Nomor: ' . $e($data['nomor']) . '</p>';
$html .= '<p class="pembuka">Pada hari ini, ' . $e($data['hari_tanggal']) . ', kami yang bertanda tangan di bawah ini:</p>';

// Pihak pertama
$html .= '<p class="row">1. PIHAK PERTAMA (Developer):</p>';
$html .= '<p class="det">Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $e($data['pihak1_nama']) . '</p>';
$html .= '<p class="det">Jabatan&nbsp;: ' . $e($data['pihak1_jabatan']) . '</p>';
$html .= '<p class="det">Alamat&nbsp;&nbsp;&nbsp;: ' . $e($data['pihak1_alamat']) . '</p>';

// Pihak kedua
$html .= '<p class="row">2. PIHAK KEDUA (Pembeli):</p>';
$html .= '<p class="det">Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $e($data['pihak2_nama']) . '</p>';
$html .= '<p class="det">No. KTP&nbsp;&nbsp;: ' . $e($data['pihak2_ktp']) . '</p>';
$html .= '<p class="det">No. HP&nbsp;&nbsp;&nbsp;&nbsp;: ' . $e($data['pihak2_hp']) . '</p>';

$html .= '<p class="isi">PIHAK PERTAMA menyerahkan kunci dan fisik bangunan rumah kepada PIHAK KEDUA dengan rincian sebagai berikut:</p>';

// A. Objek rumah
$html .= '<p class="sub" style="margin-top:5mm;">A.&nbsp;&nbsp;OBJEK RUMAH</p>';
$html .= '<table class="t">'
    . '<tr><td width="6mm">-</td><td width="32mm">Perumahan</td><td width="6mm">:</td><td>' . $e($data['perumahan']) . '</td></tr>'
    . '<tr><td>-</td><td>Unit</td><td>:</td><td>' . $e($data['unit']) . '</td></tr>'
    . '<tr><td>-</td><td>Tipe / Luas</td><td>:</td><td>' . $e($data['tipe_luas']) . '</td></tr>'
    . '<tr><td>-</td><td>Listrik / Air</td><td>:</td><td>' . $e($data['listrik']) . '</td></tr>'
    . '</table>';

// B. Kelengkapan
$html .= '<p class="sub" style="margin-top:2mm;">B.&nbsp;&nbsp;&nbsp;KELENGKAPAN YANG DISERAHKAN</p>';
$html .= '<table class="t">';
$no = 1;
foreach ($data['kelengkapan'] as $nama => $jumlah) {
    $html .= '<tr><td width="7mm">' . $no++ . '.</td><td width="52mm">' . $e($nama) . '</td><td width="6mm">:</td><td>' . $e($jumlah) . '</td></tr>';
}
$html .= '</table>';

// ======================== HALAMAN 2 ========================
$html .= '<pagebreak />';

$html .= '<p style="margin-top:2mm;">KETENTUAN:</p>';
$html .= '<table style="border-collapse:collapse; margin-left:18.5mm; margin-top:4mm; width:155mm;">';
$no = 1;
foreach ($data['ketentuan'] as $k) {
    $html .= '<tr>'
        . '<td width="7mm" style="vertical-align:top; line-height:1.5; font-size:12pt;">' . $no++ . '.</td>'
        . '<td style="text-align:justify; line-height:1.5; font-size:12pt;">' . $e($k) . '</td>'
        . '</tr>';
}
$html .= '</table>';

$html .= '<p class="isi" style="margin-top:14mm;">Demikian Berita Acara ini dibuat dalam 2 (dua) rangkap bermaterai cukup dan memiliki kekuatan hukum yang sama.</p>';

$html .= '<table class="ttd">
  <tr>
    <td width="33%">PIHAK PERTAMA</td><td width="32%"></td><td width="35%">PIHAK KEDUA</td>
  </tr>
  <tr>
    <td>(Developer)</td><td></td><td>(Pembeli)</td>
  </tr>
  <tr>
    <td style="height:38mm;"></td><td></td><td></td>
  </tr>
  <tr>
    <td>( ' . $e($data['pihak1_nama']) . ' )</td><td></td><td>( ' . $e($data['pihak2_nama']) . ' )</td>
  </tr>
  <tr>
    <td>Estate Manager</td><td></td><td>Pemilik / Konsumen</td>
  </tr>
</table>';

$html .= '</div>';

// ======================== GENERATE ========================
try {
    $mpdf = new \Mpdf\Mpdf([
        'mode'          => 'utf-8',
        'format'        => 'A4',
        'orientation'   => 'P',
        'margin_left'   => 20,
        'margin_right'  => 19,
        'margin_top'    => 47,
        'margin_bottom' => 15,
        'margin_header' => 11,
        'margin_footer' => 5,
        'default_font'  => 'freeserif', // mirip Times New Roman
    ]);

    $mpdf->SetTitle('Berita Acara Serah Terima Kunci Rumah');
    $mpdf->SetAuthor($data['perusahaan']);
    $mpdf->SetHTMLHeader($header);   // kop surat di semua halaman
    $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

    // Tampilkan di browser. Untuk menyimpan ke file ganti 'I' dengan 'F' dan beri nama file:
    // $mpdf->Output(__DIR__ . '/BAST_Kunci_Rumah.pdf', \Mpdf\Output\Destination::FILE);
    $mpdf->Output('BAST_Kunci_Rumah.pdf', \Mpdf\Output\Destination::INLINE);
} catch (\Mpdf\MpdfException $ex) {
    echo 'Gagal membuat PDF: ' . $ex->getMessage();
}

?>