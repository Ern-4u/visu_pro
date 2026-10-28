# 🏠 VISU Pro — Sistem Manajemen Real Estate & Pemasaran Perumahan

> Sistem manajemen perumahan terpadu berbasis web yang dirancang untuk membantu developer properti mengelola data unit rumah, transaksi penjualan, tim marketing, pembeli, hingga pelaporan dokumen secara digital dan efisien.

---

## 📋 Daftar Isi

- [Tentang Proyek](#-tentang-proyek)
- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Struktur Direktori](#-struktur-direktori)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi & Konfigurasi](#-instalasi--konfigurasi)
- [Alur Autentikasi](#-alur-autentikasi)
- [Peran Pengguna](#-peran-pengguna)
- [Modul Admin](#-modul-admin)
- [Modul Marketing](#-modul-marketing)
- [Ekspor & Laporan](#-ekspor--laporan)
- [Integrasi WhatsApp](#-integrasi-whatsapp)

---

## 📌 Tentang Proyek

**VISU Pro** adalah aplikasi manajemen properti berbasis PHP & MySQL yang dikembangkan untuk mempermudah operasional perusahaan real estate. Sistem ini memiliki dua panel utama:

- **Panel Admin** — Kontrol penuh terhadap seluruh data sistem: unit rumah, transaksi, pembeli, marketing, site plan, laporan, dll.
- **Panel Marketing** — Akses terbatas untuk staf penjualan: lihat stok unit, kelola pembeli, catat transaksi, dan follow-up janji bayar.

Aplikasi ini dibangun dalam kerangka proyek PKL (Praktik Kerja Lapangan) dengan nama internal `Bangun Indah Negeri` yang dapat dikonfigurasi melalui pengaturan web.

---

## ✨ Fitur Utama

### 🔐 Keamanan
- Login dua langkah: **Username & Password** → **PIN 6-digit (2FA)**
- Manajemen peran berbasis sesi PHP (`A` = Admin, `M` = Marketing)
- Proteksi halaman per-peran; akses ilegal diarahkan ke halaman logout
- Ganti password dan PIN mandiri melalui halaman keamanan

### 🏡 Manajemen Unit Rumah
- CRUD lengkap data unit rumah (nomor kavling, luas tanah, luas bangunan, harga, status)
- Status unit: **Tersedia**, **Inden**, **Terjual**
- Manajemen foto/galeri per unit rumah
- History perubahan status rumah
- Riwayat progres/pembangunan fisik rumah

### 🗺️ Site Plan & Cluster
- Kelola **Site Plan** (denah kawasan perumahan) beserta gambar denah dan brosur
- Manajemen **Kategori/Cluster Rumah** (tipe rumah, spesifikasi, skema pembayaran)
- Tambah beberapa foto per kategori

### 👥 Manajemen Pembeli & Marketing
- CRUD data pembeli (nama, alamat, kontak, identitas)
- CRUD data tim marketing (nama, foto, kontak, nomor karyawan)
- Import data pembeli & marketing via file Excel

### 💳 Manajemen Transaksi
- Proses pembelian rumah (Cash, Cash Tempo, Kredit)
- Detail transaksi bertahap (angsuran/cicilan)
- Unggah & tampilkan bukti pembayaran
- Manajemen status transaksi: **Berlangsung**, **Selesai**, **Gagal Bayar**
- Fitur **Janji Bayar** — catat jadwal pembayaran & ingatkan via WhatsApp

### 📄 Dokumen & Laporan
- **Berita Acara Serah Terima (BAST)** — buat, unduh PDF, upload file pendukung
- **Kwitansi Pembayaran** — cetak kwitansi per transaksi (PDF)
- **Brosur** — lihat & cetak brosur digital per Site Plan
- Export data ke **Excel (.xlsx)** dan **PDF** pada seluruh modul utama

### ⚙️ Pengaturan Sistem
- Konfigurasi nama proyek, alamat, contact person, dan logo melalui panel admin
- Manajemen akun pengguna (users) dari panel admin

---

## 🛠️ Teknologi yang Digunakan

| Komponen | Detail |
|---|---|
| **Backend** | PHP (Native / Procedural) |
| **Database** | MySQL |
| **Frontend** | HTML5, CSS3, Bootstrap 5.3 |
| **UI Framework** | AdminLTE 3 |
| **Icons** | Bootstrap Icons 1.11 |
| **Font** | Google Fonts (Montserrat, Roboto) |
| **Tabel Interaktif** | DataTables.js |
| **Ekspor Excel** | PhpSpreadsheet `^5.9` |
| **Ekspor PDF** | mPDF `^8.3` |
| **Dependency Manager** | Composer (PSR-4 Autoload) |
| **Server Lokal** | Laragon (Apache + PHP + MySQL) |

---

## 📁 Struktur Direktori

```
real_estate/
│
├── 📂 admin_home/              # Dashboard utama Admin
├── 📂 admin_rumah/             # Kelola unit rumah
├── 📂 admin_kategori_rumah/    # Kelola cluster/tipe rumah
├── 📂 admin_site_plan/         # Kelola site plan & denah
├── 📂 admin_pembeli/           # Kelola data pembeli
├── 📂 admin_data_marketing/    # Kelola data tim marketing
├── 📂 admin_transaksi/         # Kelola transaksi penjualan
├── 📂 admin_berita_acara/      # Kelola BAST (Berita Acara Serah Terima)
├── 📂 admin_history_rumah/     # Riwayat status & progres rumah
├── 📂 admin_keamanan/          # Ganti password & PIN akun
├── 📂 admin_setingan/          # Pengaturan informasi web/proyek
├── 📂 admin_users/             # Manajemen akun pengguna
│
├── 📂 marketing_home/          # Dashboard utama Marketing
├── 📂 marketing_rumah/         # Lihat stok unit (Marketing)
├── 📂 marketing_kategori_rumah/# Lihat kategori/cluster (Marketing)
├── 📂 marketing_site_plan/     # Lihat site plan & brosur (Marketing)
├── 📂 marketing_pembeli/       # Kelola pembeli (Marketing)
├── 📂 marketing_transaksi/     # Kelola transaksi (Marketing)
├── 📂 marketing_keamanan/      # Ganti password & PIN (Marketing)
│
├── 📂 database/
│   └── config.php              # Konfigurasi koneksi database & base_url
│
├── 📂 includes/
│   └── tanggal.php             # Helper format tanggal ke Bahasa Indonesia
│
├── 📂 layout_admin/            # Shared layout: navbar, sidebar, css, js, footer (Admin)
├── 📂 layout_marketing/        # Shared layout: navbar, sidebar, css, js, footer (Marketing)
│
├── 📂 assets/                  # Aset statis (logo, gambar background)
├── 📂 AdminLTE/                # Library AdminLTE 3
├── 📂 vendor/                  # Library Composer (PhpSpreadsheet, mPDF, dll)
│
├── 📂 fto_rumah/               # Upload foto unit rumah
├── 📂 foto_site_plan/          # Upload foto site plan
├── 📂 foto_marketing/          # Upload foto marketing
├── 📂 fto_history/             # Upload foto history/progres rumah
├── 📂 bukti_bayar/             # Upload bukti pembayaran
├── 📂 bast/                    # Upload file BAST
├── 📂 kwitansi/                # File kwitansi yang digenerate
├── 📂 brosur/                  # File brosur PDF
│
├── login.php                   # Halaman login (Langkah 1)
├── pin.php                     # Halaman verifikasi PIN 2FA (Langkah 2)
├── logout.php                  # Proses logout & hapus sesi
├── composer.json               # Konfigurasi dependensi Composer
└── real_estate_fx.sql          # File dump database MySQL
```

---

## ⚙️ Persyaratan Sistem

- **PHP** >= 7.4 (disarankan PHP 8.x)
- **MySQL** >= 5.7 / MariaDB >= 10.3
- **Composer** (untuk instalasi library PhpSpreadsheet & mPDF)
- **Web Server** Apache/Nginx (direkomendasikan: **Laragon**)
- **Browser** Modern (Chrome, Edge, Firefox)

---

## 🚀 Instalasi & Konfigurasi

### 1. Clone / Salin Proyek

Tempatkan folder proyek di direktori web server lokal Anda:
```
C:\laragon\www\real_estate\
```
atau
```
/var/www/html/real_estate/
```

### 2. Install Dependensi Composer

Buka terminal di folder proyek dan jalankan:
```bash
composer install
```

### 3. Import Database

1. Buka **phpMyAdmin** atau MySQL client favorit Anda.
2. Buat database baru dengan nama `real_estate`.
3. Import file `real_estate_fx.sql` yang tersedia di root proyek.

### 4. Konfigurasi Koneksi Database

Edit file `database/config.php` dan sesuaikan dengan lingkungan Anda:

```php
<?php
date_default_timezone_set('Asia/Jakarta');
session_start();

$conn = mysqli_connect("localhost", "root", "", "real_estate");

function base_url($url = null) {
    $base_url = "http://localhost/real_estate/"; // Sesuaikan path ini
    if ($url) {
        return $base_url . ltrim($url, '/');
    }
    return $base_url;
}
?>
```

> **Catatan:** Pastikan nilai `$base_url` sesuai dengan URL akses lokal Anda. Jika proyek dijalankan di subfolder `pkl`, maka ubah menjadi `http://localhost/pkl/`.

### 5. Akses Aplikasi

Buka browser dan akses:
```
http://localhost/real_estate/
```

---

## 🔐 Alur Autentikasi

Sistem menggunakan autentikasi **dua langkah** untuk meningkatkan keamanan:

```
Halaman Landing (index.php)
        ↓
[1] Login: Username + Password  →  login.php
        ↓ (jika berhasil)
[2] Verifikasi PIN 2FA (6 digit) →  pin.php
        ↓ (jika berhasil)
[A] Peran Admin   → admin_home/
[M] Peran Marketing → marketing_home/
```

- Password disimpan dalam bentuk hash **SHA1**.
- PIN disimpan sebagai plaintext di database (dapat ditingkatkan ke hash di masa mendatang).

---

## 👤 Peran Pengguna

| Kode Peran | Nama | Akses |
|:---:|---|---|
| `A` | **Admin** | Akses penuh ke semua modul, termasuk pengaturan, manajemen user, dan laporan |
| `M` | **Marketing** | Akses terbatas: lihat stok, kelola pembeli & transaksi di bawah supervisinya |

---

## 🖥️ Modul Admin

| Modul | Fungsi |
|---|---|
| **Dashboard** | Ringkasan statistik: unit tersedia, transaksi, pendapatan, janji bayar, top marketing |
| **Data Rumah** | CRUD unit, upload foto, ubah status, beli rumah |
| **Kategori Rumah** | CRUD tipe/cluster, skema pembayaran, galeri foto |
| **Site Plan** | CRUD kawasan, denah, dan brosur digital |
| **Data Pembeli** | CRUD pembeli, import Excel |
| **Data Marketing** | CRUD karyawan marketing, foto profil, import Excel |
| **Transaksi** | CRUD transaksi, detail pembayaran, bukti bayar, kwitansi, janji bayar |
| **Berita Acara (BAST)** | Buat, lihat, export PDF, upload dokumen BAST |
| **History Rumah** | Riwayat progres/status fisik setiap unit |
| **Pengaturan** | Ubah nama proyek, alamat, CP, logo |
| **Manajemen User** | Tambah/hapus akun Admin & Marketing |
| **Keamanan** | Ganti password & PIN akun sendiri |

---

## 📱 Modul Marketing

| Modul | Fungsi |
|---|---|
| **Dashboard** | Statistik yang sama dengan Admin (read-only summary) |
| **Rumah** | Lihat ketersediaan unit |
| **Kategori Rumah** | Lihat tipe & detail cluster |
| **Site Plan** | Lihat denah & brosur kawasan |
| **Pembeli** | Kelola data calon & pembeli aktif |
| **Transaksi** | Catat & kelola transaksi yang ditangani |
| **Keamanan** | Ganti password & PIN akun sendiri |

---

## 📊 Ekspor & Laporan

Setiap modul data utama mendukung ekspor dalam format:

| Format | Library | Modul yang Mendukung |
|---|---|---|
| **Excel (.xlsx)** | PhpSpreadsheet | Rumah, Kategori, Site Plan, Pembeli, Marketing, Transaksi, BAST |
| **PDF** | mPDF | BAST, Kwitansi, Brosur |
| **Import Excel** | PhpSpreadsheet | Rumah, Kategori, Site Plan, Pembeli, Marketing |

---

## 💬 Integrasi WhatsApp

Fitur **Janji Bayar** pada Dashboard (Admin & Marketing) menyediakan tombol **WhatsApp langsung** untuk follow-up pembayaran ke pembeli.

Sistem secara otomatis menyusun pesan template yang berisi:
- Nama pembeli
- Tipe & nama perumahan
- Tanggal jatuh tempo
- Instruksi konfirmasi pembayaran
- Tanda tangan nama staf yang login

Klik tombol → langsung membuka WhatsApp Web/App dengan pesan yang sudah terisi.

---

## 📝 Catatan Pengembangan

- Proyek ini dikembangkan menggunakan **PHP Native** tanpa framework MVC.
- Namespace Composer: `Visupro\RealEstate` (prefix `src/`).
- Zona waktu default: `Asia/Jakarta`.
- File `.gitignore` mengecualikan folder `vendor/`, file konfigurasi sensitif, dan file upload gambar agar tidak ikut di-commit ke repositori.

---

> © 2026 VISU Pro — Sistem Manajemen Real Estate. Dikembangkan oleh **Visupro**.

