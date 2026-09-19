<?php 
function tanggal_indonesia($tanggal) {
    $bulan = array (
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    
    $tgl_angka = date('d', strtotime($tanggal));
    $bulan_angka = date('n', strtotime($tanggal));
    $tahun = date('Y', strtotime($tanggal));

    return $tgl_angka . ' ' . $bulan[$bulan_angka] . ' ' . $tahun;
}
?>