<?php
require_once '../database/config.php';
require_once '../includes/tanggal.php';

// Pastikan lokasi autoload mPDF sesuai dengan instalasi composer Anda
require_once '../vendor/autoload.php';

$query_web = mysqli_query($conn, "SELECT * FROM web WHERE id = '1'")or die(mysqli_error($conn));
  $web = mysqli_fetch_array($query_web); 

if (@$_SESSION['peran'] != 'A') {
    echo '<script> alert("Anda Tidak boleh masuk ke halaman ini!!!!"); window.location.href="../logout.php" </script>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. TANGKAP DATA DARI FORM
    $id_transaksi       = $_POST['id_transaksi'];
    $no_kwitansi        = $_POST['no_kwitansi'];
    $tanggal_pembayaran = $_POST['tanggal_pembayaran'];
    $jenis_pembayaran   = $_POST['jenis_pembayaran'];
    
    // Hilangkan titik dari format rupiah JS agar bisa masuk ke kolom INT (dibayarakan)
    $dibayarakan = str_replace('.', '', $_POST['dibayarkan']); 
    
    // 2. AMBIL DATA TRANSAKSI UNTUK DI TAMPILKAN KE PDF
    $query_data = mysqli_query($conn, "SELECT t.*, r.*, p.* 
                                       FROM transaksi t
                                       LEFT JOIN rumah r ON t.id_rumah = r.id_rumah
                                       LEFT JOIN pembeli p ON t.id_pembeli = p.id_pembeli
                                       WHERE t.id_transaksi = '$id_transaksi'");
    $data = mysqli_fetch_array($query_data);

    $nama_pembeli = $data['nama_pembeli'];
    $kontak       = $data['kontak'];
    $alamat       = $data['alamat'];
    $pembuat_nota = $_SESSION['nama'];

    // 3. GENERATE FUNGSI TERBILANG VERSI PHP UNTUK PDF
    function terbilang($x) {
        $angka = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        if ($x < 12) return " " . $angka[$x];
        elseif ($x < 20) return terbilang($x - 10) . " Belas";
        elseif ($x < 100) return terbilang($x / 10) . " Puluh" . terbilang($x % 10);
        elseif ($x < 200) return " Seratus" . terbilang($x - 100);
        elseif ($x < 1000) return terbilang($x / 100) . " Ratus" . terbilang($x % 100);
        elseif ($x < 2000) return " Seribu" . terbilang($x - 1000);
        elseif ($x < 1000000) return terbilang($x / 1000) . " Ribu" . terbilang($x % 1000);
        elseif ($x < 1000000000) return terbilang($x / 1000000) . " Juta" . terbilang($x % 1000000);
    }
    
    $teks_terbilang = trim(terbilang($dibayarakan)) . ' Rupiah';
    $format_uang    = 'Rp ' . number_format($dibayarakan, 0, ',', '.');
    $sebesar_teks   = $format_uang . ' ( ' . $teks_terbilang . ' )';

    // 4. PERSIAPAN FILE PDF
    $nama_file_pdf = 'Kwitansi_' . str_replace('-', '_', $no_kwitansi) . '.pdf';
    
    // Pastikan Anda sudah membuat folder "kwitansi" di dalam folder "assets"
    $path_simpan   = '../assets/kwitansi/' . $nama_file_pdf; 

    // 5. INSERT KE DATABASE (Sesuai kolom di gambar DB: dibayarakan)
    $query_insert = "INSERT INTO detail_transaksi 
                    (id_transaksi, no_kwitansi, jenis_pembayaran, dibayarkan, tanggal_pembayaran, kwitansi) 
                    VALUES 
                    ('$id_transaksi', '$no_kwitansi', '$jenis_pembayaran', '$dibayarakan', '$tanggal_pembayaran', '$nama_file_pdf')";
    
    if (mysqli_query($conn, $query_insert)) {
        
        // 6. RANCANG DESAIN HTML UNTUK PDF (Pakai Tabel agar kompatibel di mPDF)
        $html = '
        <style>
            body { font-family: Arial, sans-serif; color: #000; }
            .kwitansi-wrapper { padding: 30px; }
            .header-table { width: 100%; margin-bottom: 20px; }
            .kwitansi-title { font-weight: 900; font-size: 2.2rem; line-height: 1; margin: 0; }
            .logo-container { background-color: #0072c6; color: white; text-align: center; padding: 25px 10px; font-weight: bold; }
            .blue-line { height: 8px; background-color: #0072c6; width: 100%; margin-bottom: 30px; }
            .form-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
            .form-table td { padding: 8px 0; vertical-align: bottom; }
            .label-td { width: 180px; font-size: 14px; }
            .input-td { border-bottom: 1px solid #000; font-size: 14px; padding-left: 5px; }
            .input-box { border: 1px solid #000; padding: 6px; font-size: 14px; width: 100%; }
            .footer-text { font-weight: bold; text-align: center; margin-top: 60px; font-size: 1.1rem; }
            .pembuat-nota { text-align: right; margin-top: 50px; font-size: 14px; }
        </style>
        <div class="kwitansi-wrapper">
            <table class="header-table">
                <tr>
                    <td style="width: 70%; vertical-align: top;">
                        <h1 class="kwitansi-title">KWITANSI<br>PEMBAYARAN</h1>
                        <p style="font-size: 13px; margin-top: 15px; line-height: 1.5;">
                            +123-456-7890<br>
                            REALLYGREATSITE.COM
                        </p>
                    </td>
                    <td style="width: 30%; vertical-align: top;">
                        <div class="logo-container">
                            <img src="../assets/logo/'.$web['logo'].'" width="200px" height="100px" alt="">
                        </div>
                    </td>
                </tr>
            </table>
            
            <div class="blue-line"></div>

            <table class="form-table" style="margin-bottom: 25px;">
                <tr>
                    <td style="width: 110px;">NO KWITANSI:</td>
                    <td style="width: 250px;"><div class="input-box">'.$no_kwitansi.'</div></td>
                    <td style="width: 90px; padding-left:30px;">TANGGAL:</td>
                    <td><div class="input-box">'.tanggal_indonesia($tanggal_pembayaran).'</div></td>
                </tr>
            </table>

            <table class="form-table">
                <tr>
                    <td style="width: 70px;">NAMA:</td>
                    <td class="input-td" style="width: 320px;">'.$nama_pembeli.'</td>
                    <td style="width: 80px; padding-left:30px;">KONTAK:</td>
                    <td class="input-td">'.$kontak.'</td>
                </tr>
            </table>

            <table class="form-table">
                <tr>
                    <td class="label-td">ALAMAT :</td>
                    <td class="input-td">'.$alamat.'</td>
                </tr>
                <tr>
                    <td class="label-td">JENIS PEMBAYARAN :</td>
                    <td class="input-td">'.$jenis_pembayaran.'</td>
                </tr>
                <tr>
                    <td class="label-td">SEBESAR :</td>
                    <td class="input-td" style="font-style: italic;">'.$sebesar_teks.'</td>
                </tr>
            </table>

            <div class="pembuat-nota">
                PEMBUAT NOTA : 
                <span style="display:inline-block; width: 250px; border-bottom: 1px solid #000; text-align:left; padding-left:10px;">
                    '.$pembuat_nota.'
                </span>
            </div>

            <div class="footer-text">TRIMAKASIH ATAS PEMBAYARAN ANDA</div>
        </div>';

        // 7. INISIALISASI DAN CETAK DENGAN mPDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8', 
            'format' => 'A4', 
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_left' => 15,
            'margin_right' => 15
        ]);
        
        $mpdf->WriteHTML($html);
        
        // Simpan file ke server terlebih dahulu
        $mpdf->Output($path_simpan, \Mpdf\Output\Destination::FILE);

        // Langsung render PDF di browser (Tab Baru)
        $mpdf->Output($nama_file_pdf, \Mpdf\Output\Destination::INLINE);

    } else {
        echo "<script>alert('Gagal menyimpan ke database: " . mysqli_error($conn) . "'); window.close();</script>";
    }
} else {
    echo "Metode tidak diizinkan.";
}
?>