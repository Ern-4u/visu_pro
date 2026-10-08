<?php
require_once '../database/config.php';
require_once '../includes/tanggal.php';
$id_berita_acara = @$_GET['id'];

$nama_admin = $_SESSION['nama'];


$query_ambil_data = mysqli_query($conn, "SELECT b.*,t.*,p.*,r.*,s.*,k.* FROM berita_acara b
                                        LEFT JOIN transaksi t ON b.id_transaksi = t.id_transaksi
                                        LEFT JOIN pembeli p ON t.id_pembeli = p.id_pembeli 
                                        LEFT JOIN rumah r ON t.id_rumah = r.id_rumah
                                        LEFT JOIN kategori_rumah k ON r.id_kategori = k.id_kategori
                                        LEFT JOIN site_plan s ON r.id_site_plan = s.id_site_plan
                                        WHERE b.id_berita_acara = '$id_berita_acara'") or die(mysqli_error($conn));
$dt_berita = mysqli_fetch_array($query_ambil_data);

$query_data_web = mysqli_query($conn,"SELECT * FROM web WHERE id = '1'")or die(mysqli_error($conn));
$dt_web = mysqli_fetch_array($query_data_web);

require_once '../vendor/autoload.php';

$data = [
    'perusahaan'   => $dt_web['nama_proyek'],
    'alamat_1'     => $dt_web['alamat'],
    'alamat_2'     => 'Kabupaten Brebes, Jawa Tengah',
    'logo'         => '../assets/logo/'.$dt_web['logo'],
    'kontak'       => $dt_web['cp'] ,

    'nomor'        => $dt_berita['no_berita_acara'],
    'hari_tanggal' => tanggal_indonesia($dt_berita['tanggal_serah_terima']),

    'pihak1_nama'    => $nama_admin,
    'pihak1_jabatan' => 'Estate Manager '.$dt_web['nama_proyek'],

    'pihak2_nama' => $dt_berita['nama_pembeli'],
    'pihak2_alamat'  => $dt_berita['alamat'],
    'pihak2_hp'   => $dt_berita['kontak'],

    'perumahan' => $dt_berita['nama_site_plan'],
    'unit'      => 'Blok '.$dt_berita['kode_blok'],
    'jumlah_kunci'      => $dt_berita['jumlah_kunci'].' Kunci',
    'tipe_luas' => $dt_berita['nama_kategori'].' / '.'LT '.$dt_berita['luas_tanah'].'  M²'.' LB '.$dt_berita['luas_bangunan'].'  M²' ,

    

    'ketentuan' => [
        'Tanggung jawab fisik bangunan, penggunaan listrik, air, dan iuran lingkungan (IPL) beralih kepada PIHAK KEDUA sejak BAST ini ditandatangani.',
        'Garansi pemeliharaan bangunan berlaku selama 100 hari kalender sejak tanggal penyerahan ini.',
    ],
];

$e = fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

// Kop Surat
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
        <div style="font-size:11pt; line-height:1.15; margin-top:1mm;">' . $e($data['alamat_1']) . '<br>' .'Kontak : '. $e($data['kontak']) . '</div>
      </td>
    </tr>
  </table>
</div>';

// CSS
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

// Halaman 1
$html = '<div class="wrap">';

$html .= '<p class="judul">BERITA ACARA SERAH TERIMA KUNCI RUMAH</p>';
$html .= '<p class="nomor">Nomor : ' . $e($data['nomor']) . '</p>';
$html .= '<p class="pembuka">Pada hari ini, ' . $e($data['hari_tanggal']) . ', kami yang bertanda tangan di bawah ini:</p>';

// Pihak pertama
$html .= '<p class="row">1. PIHAK PERTAMA (Developer):</p>';
$html .= '<table class="t">'
    . '<tr><td width="6mm">-</td><td width="32mm">Nama</td><td width="6mm">:</td><td>' . $e($data['pihak1_nama']) . '</td></tr>'
    . '<tr><td>-</td><td>Jabatan</td><td>:</td><td>' . $e($data['pihak1_jabatan']) . '</td></tr>'
    . '</table>';

// Pihak kedua
$html .= '<p class="row">2. PIHAK KEDUA (Pembeli):</p>';
$html .= '<table class="t">'
    . '<tr><td width="6mm">-</td><td width="32mm">Nama</td><td width="6mm">:</td><td>' . $e($data['pihak2_nama']) . '</td></tr>'
    . '<tr><td>-</td><td>Alamat</td><td>:</td><td>' . $e($data['pihak2_alamat']) . '</td></tr>'
    . '<tr><td>-</td><td>No. HP</td><td>:</td><td>' . $e($data['pihak2_hp']) . '</td></tr>'
    . '</table>';

$html .= '<p class="isi">PIHAK PERTAMA menyerahkan kunci dan fisik bangunan rumah kepada PIHAK KEDUA dengan rincian sebagai berikut:</p>';

// A. Objek rumah
$html .= '<p class="sub" style="margin-top:5mm;">A.&nbsp;&nbsp;OBJEK RUMAH</p>';
$html .= '<table class="t">'
    . '<tr><td width="6mm">-</td><td width="32mm">Perumahan</td><td width="6mm">:</td><td>' . $e($data['perumahan']) . '</td></tr>'
    . '<tr><td>-</td><td>Unit</td><td>:</td><td>' . $e($data['unit']) . '</td></tr>'
    . '<tr><td>-</td><td>Tipe / Luas</td><td>:</td><td>' . $e($data['tipe_luas']) . '</td></tr>'
    . '<tr><td>-</td><td>Jumlah Kunci</td><td>:</td><td>' . $e($data['jumlah_kunci']) . '</td></tr>'
    . '</table>';

// // B. Kelengkapan
// $html .= '<p class="sub" style="margin-top:2mm;">B.&nbsp;&nbsp;&nbsp;KELENGKAPAN YANG DISERAHKAN</p>';
// $html .= '<table class="t">';
// $no = 1;
// foreach ($data['kelengkapan'] as $nama => $jumlah) {
//     $html .= '<tr><td width="7mm">' . $no++ . '.</td><td width="52mm">' . $e($nama) . '</td><td width="6mm">:</td><td>' . $e($jumlah) . '</td></tr>';
// }
// $html .= '</table>';

// halaman 2
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