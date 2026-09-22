<?php
/**
 * janji_bayar.php
 *
 * Logika fitur "Janji Bayar":
 * - Setiap transaksi hanya boleh punya SATU janji bayar berstatus 'aktif' pada satu waktu.
 * - Saat admin input janji baru untuk transaksi yang sama, janji lama (jika masih 'aktif')
 *   otomatis diubah statusnya jadi 'gagal' (dianggap tidak ditepati), lalu janji baru
 *   disimpan sebagai baris baru berstatus 'aktif'. Riwayat janji lama tetap ada di tabel.
 * - Saat pembayaran benar-benar masuk (dicatat di detail_transaksi), janji aktif untuk
 *   transaksi tsb ditandai 'terpenuhi'.
 *
 * $koneksi adalah instance mysqli yang sudah terkoneksi ke database.
 */

/**
 * Membuat / memperbarui janji bayar untuk sebuah transaksi.
 *
 * @param mysqli $koneksi
 * @param int    $id_transaksi
 * @param string $tanggal_dijanjikan  format 'YYYY-MM-DD'
 * @param string $id_karyawan         id admin/marketing yang menginput
 * @param string|null $keterangan     catatan opsional
 * @return array ['sukses' => bool, 'pesan' => string, 'id_janji_bayar' => int|null]
 */
function buatJanjiBayar(mysqli $koneksi, int $id_transaksi, string $tanggal_dijanjikan, string $id_karyawan, ?string $keterangan = null): array
{
    // Validasi dasar: tanggal dijanjikan tidak boleh di masa lalu
    if (strtotime($tanggal_dijanjikan) < strtotime(date('Y-m-d'))) {
        return ['sukses' => false, 'pesan' => 'Tanggal janji tidak boleh sebelum hari ini.', 'id_janji_bayar' => null];
    }

    $koneksi->begin_transaction();

    try {
        // 1. Nonaktifkan janji aktif sebelumnya (jika ada) untuk transaksi ini -> jadi 'gagal'
        $stmtUpdate = $koneksi->prepare(
            "UPDATE janji_bayar SET status = 'gagal' WHERE id_transaksi = ? AND status = 'aktif'"
        );
        $stmtUpdate->bind_param('i', $id_transaksi);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        // 2. Insert janji bayar baru sebagai 'aktif'
        $tanggalJanji = date('Y-m-d'); // tanggal kesepakatan ini diinput
        $stmtInsert = $koneksi->prepare(
            "INSERT INTO janji_bayar (id_transaksi, tanggal_janji, tanggal_dijanjikan, id_karyawan, status, keterangan)
             VALUES (?, ?, ?, ?, 'aktif', ?)"
        );
        $stmtInsert->bind_param(
            'issss',
            $id_transaksi,
            $tanggalJanji,
            $tanggal_dijanjikan,
            $id_karyawan,
            $keterangan
        );
        $stmtInsert->execute();
        $idBaru = $stmtInsert->insert_id;
        $stmtInsert->close();

        $koneksi->commit();

        return ['sukses' => true, 'pesan' => 'Janji bayar berhasil disimpan.', 'id_janji_bayar' => $idBaru];
    } catch (mysqli_sql_exception $e) {
        $koneksi->rollback();
        return ['sukses' => false, 'pesan' => 'Gagal menyimpan janji bayar: ' . $e->getMessage(), 'id_janji_bayar' => null];
    }
}

/**
 * Menandai janji bayar aktif sebuah transaksi sebagai 'terpenuhi'.
 * Panggil fungsi ini tepat setelah pembayaran berhasil dicatat ke tabel detail_transaksi.
 *
 * @param mysqli $koneksi
 * @param int    $id_transaksi
 * @return bool
 */
function tandaiJanjiTerpenuhi(mysqli $koneksi, int $id_transaksi): bool
{
    $stmt = $koneksi->prepare(
        "UPDATE janji_bayar SET status = 'terpenuhi' WHERE id_transaksi = ? AND status = 'aktif'"
    );
    $stmt->bind_param('i', $id_transaksi);
    $sukses = $stmt->execute();
    $stmt->close();

    return $sukses;
}

/**
 * Mengambil janji bayar yang sedang aktif untuk sebuah transaksi (untuk ditampilkan di UI).
 *
 * @param mysqli $koneksi
 * @param int    $id_transaksi
 * @return array|null
 */
function ambilJanjiAktif(mysqli $koneksi, int $id_transaksi): ?array
{
    $stmt = $koneksi->prepare(
        "SELECT id_janji_bayar, tanggal_janji, tanggal_dijanjikan, id_karyawan, keterangan
         FROM janji_bayar
         WHERE id_transaksi = ? AND status = 'aktif'
         LIMIT 1"
    );
    $stmt->bind_param('i', $id_transaksi);
    $stmt->execute();
    $hasil = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $hasil ?: null;
}

/**
 * Mengambil daftar transaksi yang janji bayarnya sudah lewat tanggal tapi belum terpenuhi
 * (berguna untuk dashboard "pembeli yang berpotensi gagal bayar").
 *
 * @param mysqli $koneksi
 * @return array daftar baris janji_bayar yang terlambat
 */
function ambilJanjiTerlambat(mysqli $koneksi): array
{
    $sql = "SELECT jb.id_janji_bayar, jb.id_transaksi, jb.tanggal_dijanjikan, jb.id_karyawan, jb.keterangan
            FROM janji_bayar jb
            WHERE jb.status = 'aktif' AND jb.tanggal_dijanjikan < CURDATE()
            ORDER BY jb.tanggal_dijanjikan ASC";
    $hasil = $koneksi->query($sql);

    return $hasil ? $hasil->fetch_all(MYSQLI_ASSOC) : [];
}