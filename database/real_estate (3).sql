-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 24, 2026 at 08:56 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `real_estate`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail_transaksi` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `no_kwitansi` varchar(255) NOT NULL,
  `jenis_pembayaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `dibayarkan` int NOT NULL,
  `bukti_pembayaran` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `tanggal_pembayaran` date NOT NULL,
  `kwitansi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail_transaksi`, `id_transaksi`, `no_kwitansi`, `jenis_pembayaran`, `dibayarkan`, `bukti_pembayaran`, `tanggal_pembayaran`, `kwitansi`) VALUES
(9, 11, '26-09-21-0001', 'Booking Fee', 5000000, NULL, '2026-09-21', 'Kwitansi_26_09_21_0001.pdf'),
(10, 11, '26-09-21-0002', 'Booking Fee', 5000000, NULL, '2026-09-21', 'Kwitansi_26_09_21_0002.pdf'),
(11, 12, '26-09-23-0001', 'Booking Fee', 5000000, NULL, '2026-09-23', 'Kwitansi_26_09_23_0001.pdf'),
(12, 11, '26-09-23-0002', 'Booking Fee', 5000000, NULL, '2026-09-23', 'Kwitansi_26_09_23_0002.pdf'),
(13, 12, '26-09-23-0003', 'Pembangunan Rumah', 5000000, NULL, '2026-09-23', 'Kwitansi_26_09_23_0003.pdf'),
(14, 12, '26-09-23-0004', 'Pembangunan Rumah', 5000000, NULL, '2026-09-23', 'Kwitansi_26_09_23_0004.pdf'),
(15, 12, '26-09-23-0005', 'Pembangunan Rumah', 5000000, NULL, '2026-09-23', 'Kwitansi_26_09_23_0005.pdf'),
(16, 12, '26-09-23-0006', 'Booking Fee', 5000000, NULL, '2026-09-23', 'Kwitansi_26_09_23_0006.pdf'),
(17, 12, '26-09-23-0006', 'Pembangunan Rumah', 100000000, NULL, '2026-09-23', 'Kwitansi_26_09_23_0006.pdf'),
(18, 11, '26-09-23-0007', 'Notaris', 5000000, NULL, '2026-09-23', 'Kwitansi_26_09_23_0007.pdf'),
(19, 11, '26-09-24-0001', 'Pembangunan Rumah', 850000000, NULL, '2026-09-24', 'Kwitansi_26_09_24_0001.pdf'),
(20, 11, '26-09-24-0002', 'Pajak Bangunan', 2000000, NULL, '2026-09-24', 'Kwitansi_26_09_24_0002.pdf'),
(21, 11, '26-09-24-0003', 'Akte Jual Beli', 3000000, NULL, '2026-09-24', 'Kwitansi_26_09_24_0003.pdf'),
(22, 11, '26-09-24-0004', 'Lahan Makam', 3000000, NULL, '2026-09-24', 'Kwitansi_26_09_24_0004.pdf'),
(23, 11, '26-09-24-0005', 'Lahan Makam', 1000000, NULL, '2026-09-24', 'Kwitansi_26_09_24_0005.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `foto_rumah`
--

CREATE TABLE `foto_rumah` (
  `id_foto_rumah` int NOT NULL,
  `id_kategori` int NOT NULL,
  `foto` text NOT NULL,
  `keterangan_foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `janji_bayar`
--

CREATE TABLE `janji_bayar` (
  `id_janji_bayar` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `tanggal_janji` date NOT NULL,
  `tanggal_dijanjikan` date NOT NULL,
  `status` enum('Aktif','Gagal','Terpenuhi') NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `janji_bayar`
--

INSERT INTO `janji_bayar` (`id_janji_bayar`, `id_transaksi`, `tanggal_janji`, `tanggal_dijanjikan`, `status`, `keterangan`) VALUES
(1, 11, '2026-09-22', '2026-09-22', 'Gagal', 'bayar AJB'),
(2, 11, '2026-09-22', '2026-09-30', 'Terpenuhi', 'ga ada');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pembayaran`
--

CREATE TABLE `jenis_pembayaran` (
  `id_jenis_pembayaran` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `jenis_pembayaran` varchar(255) NOT NULL,
  `harga` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jenis_pembayaran`
--

INSERT INTO `jenis_pembayaran` (`id_jenis_pembayaran`, `id_transaksi`, `jenis_pembayaran`, `harga`) VALUES
(29, 11, 'Booking Fee', 15000000),
(30, 11, 'Pembangunan Rumah', 850000000),
(31, 11, 'Pajak Bangunan', 2000000),
(32, 11, 'Akte Jual Beli', 3000000),
(33, 11, 'Notaris', 5000000),
(34, 11, 'Lahan Makam', 4000000),
(35, 11, 'Hook', 0),
(36, 12, 'Booking Fee', 5000000),
(37, 12, 'Pembangunan Rumah', 1000000000),
(38, 12, 'Pajak Bangunan', 10000000),
(39, 12, 'Akte Jual Beli', 2000000),
(40, 12, 'Notaris', 500000),
(41, 12, 'Lahan Makam', 3000000),
(42, 12, 'Hook', 0);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_rumah`
--

CREATE TABLE `kategori_rumah` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `luas_bangunan` varchar(30) NOT NULL,
  `luas_tanah` varchar(30) NOT NULL,
  `jumlah_kamar` varchar(30) NOT NULL,
  `harga` int NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori_rumah`
--

INSERT INTO `kategori_rumah` (`id_kategori`, `nama_kategori`, `luas_bangunan`, `luas_tanah`, `jumlah_kamar`, `harga`, `deskripsi`) VALUES
(2, 'Cluster A Hook', '100', '120', '3', 100000000, 'gada\r\n'),
(14, 'Cluster Z', '80', '100', '3', 1000000000, 'kosong'),
(15, 'Cluster A Standar', '85', '105', '2', 850000000, 'Desain minimalis modern'),
(16, 'Cluster B Premium', '120', '150', '4', 1450000000, 'Fasilitas smart home terintegrasi'),
(17, 'Cluster B Standar', '90', '110', '3', 950000000, 'Cocok untuk keluarga kecil'),
(18, 'Cluster C Hook', '115', '140', '3', 1250000000, 'Sirkulasi udara sangat baik, dekat taman'),
(19, 'Cluster C Standar', '80', '100', '2', 800000000, 'Dekat dengan fasilitas clubhouse'),
(20, 'Cluster D Exclusive', '150', '200', '4', 1900000000, 'Posisi depan menghadap gerbang utama'),
(21, 'Cluster D Standar', '100', '120', '3', 1100000000, 'Carport luas muat 2 mobil'),
(22, 'Cluster Z Hook', '95', '130', '3', 1150000000, 'Sisa lahan belakang masih luas untuk dibangun');

-- --------------------------------------------------------

--
-- Table structure for table `marketing`
--

CREATE TABLE `marketing` (
  `id_karyawan` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kontak` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `kelamin` enum('L','P') NOT NULL,
  `foto` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `marketing`
--

INSERT INTO `marketing` (`id_karyawan`, `nama`, `kontak`, `email`, `kelamin`, `foto`) VALUES
('KMAR0001', 'Muhammad Isa Irawanto', '082329221051', 'sabig1984@gmail.com', 'L', 'marketing1788194123.jpeg'),
('KMAR0002', 'Najwa Shabira', '082329221051', 'najwa@gmail.com', 'P', 'marketing1788403542.jpeg'),
('KMAR0003', 'zelia', '082348348394', 'sabig1984@gmail.com', 'P', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `no_pembayaran` varchar(255) NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `jumlah_pembayaran` int NOT NULL,
  `metode_pembayaran` varchar(255) NOT NULL,
  `bukti_bayar` text,
  `kwitansi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembeli`
--

CREATE TABLE `pembeli` (
  `id_pembeli` int NOT NULL,
  `nik` varchar(255) NOT NULL,
  `nama_pembeli` varchar(255) NOT NULL,
  `pasangan` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `kontak` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembeli`
--

INSERT INTO `pembeli` (`id_pembeli`, `nik`, `nama_pembeli`, `pasangan`, `alamat`, `kontak`) VALUES
(2, '3329041908040001', 'Muhammad Isa Irawanto', 'Belum Nemu', 'Taraban RT04 RW 110', '082328936457'),
(3, '3329041908040000', 'Najwa Shabira', 'Tidak Tahu', 'Winduaji', '082328936457'),
(4, '3329041908040000', 'Alfi Resti Zelia', 'Tidak Tahu', 'Salem', '082328936457');

-- --------------------------------------------------------

--
-- Table structure for table `rumah`
--

CREATE TABLE `rumah` (
  `id_rumah` int NOT NULL,
  `kode_blok` varchar(15) NOT NULL,
  `id_kategori` int NOT NULL,
  `status` enum('0','1','2','3') NOT NULL,
  `id_site_plan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rumah`
--

INSERT INTO `rumah` (`id_rumah`, `kode_blok`, `id_kategori`, `status`, `id_site_plan`) VALUES
(1, 'A1', 2, '0', 4),
(2, 'A2', 14, '1', 4),
(3, 'A3', 15, '1', 4),
(4, 'A4', 16, '0', 4),
(5, 'A5', 17, '0', 4),
(6, 'A6', 18, '0', 4),
(7, 'A7', 19, '0', 4),
(8, 'A8', 20, '0', 4),
(9, 'A9', 21, '0', 4),
(10, 'A10', 22, '0', 4),
(11, 'A11', 2, '0', 4),
(12, 'A12', 14, '0', 4),
(13, 'A13', 15, '0', 4),
(14, 'A14', 16, '0', 4),
(15, 'A15', 17, '0', 4),
(16, 'A16', 18, '0', 4),
(17, 'A17', 19, '0', 4),
(18, 'A18', 20, '0', 4),
(19, 'A19', 21, '0', 4),
(20, 'A20', 22, '0', 4),
(21, 'B1', 2, '0', 13),
(22, 'B2', 14, '0', 13),
(23, 'B3', 15, '0', 13),
(24, 'B4', 16, '0', 13),
(25, 'B5', 17, '0', 13),
(26, 'B6', 18, '0', 13),
(27, 'B7', 19, '0', 13),
(28, 'B8', 20, '0', 13),
(29, 'B9', 21, '0', 13),
(30, 'B10', 22, '0', 13),
(31, 'B11', 2, '0', 13),
(32, 'B12', 14, '0', 13),
(33, 'B13', 15, '0', 13),
(34, 'B14', 16, '0', 13),
(35, 'B15', 17, '0', 13),
(36, 'B16', 18, '0', 13),
(37, 'B17', 19, '0', 13),
(38, 'B18', 20, '0', 13),
(39, 'B19', 21, '0', 13),
(40, 'B20', 22, '0', 13),
(41, 'C1', 2, '0', 14),
(42, 'C2', 14, '0', 14),
(43, 'C3', 15, '0', 14),
(44, 'C4', 16, '0', 14),
(45, 'C5', 17, '0', 14),
(46, 'C6', 18, '0', 14),
(47, 'C7', 19, '0', 14),
(48, 'C8', 20, '0', 14),
(49, 'C9', 21, '0', 14),
(50, 'C10', 22, '0', 14),
(51, 'C11', 2, '0', 14),
(52, 'C12', 14, '0', 14),
(53, 'C13', 15, '0', 14),
(54, 'C14', 16, '0', 14),
(55, 'C15', 17, '0', 14),
(56, 'C16', 18, '0', 14),
(57, 'C17', 19, '0', 14),
(58, 'C18', 20, '0', 14),
(59, 'C19', 21, '0', 14),
(60, 'C20', 22, '0', 14),
(61, 'D1', 2, '0', 17),
(62, 'D2', 14, '0', 17),
(63, 'D3', 15, '0', 17),
(64, 'D4', 16, '0', 17),
(65, 'D5', 17, '0', 17),
(66, 'D6', 18, '0', 17),
(67, 'D7', 19, '0', 17),
(68, 'D8', 20, '0', 17),
(69, 'D9', 21, '0', 17),
(70, 'D10', 22, '0', 17),
(71, 'D11', 2, '0', 17),
(72, 'D12', 14, '0', 17),
(73, 'D13', 15, '0', 17),
(74, 'D14', 16, '0', 17),
(75, 'D15', 17, '0', 17),
(76, 'D16', 18, '0', 17),
(77, 'D17', 19, '0', 17),
(78, 'D18', 20, '0', 17),
(79, 'D19', 21, '0', 17),
(80, 'D20', 22, '0', 17),
(81, 'E1', 2, '0', 18),
(82, 'E2', 14, '0', 18),
(83, 'E3', 15, '0', 18),
(84, 'E4', 16, '0', 18),
(85, 'E5', 17, '0', 18),
(86, 'E6', 18, '0', 18),
(87, 'E7', 19, '0', 18),
(88, 'E8', 20, '0', 18),
(89, 'E9', 21, '0', 18),
(90, 'E10', 22, '0', 18),
(91, 'E11', 2, '0', 18),
(92, 'E12', 14, '0', 18),
(93, 'E13', 15, '0', 18),
(94, 'E14', 16, '0', 18),
(95, 'E15', 17, '0', 18),
(96, 'E16', 18, '0', 18),
(97, 'E17', 19, '0', 18),
(98, 'E18', 20, '0', 18),
(99, 'E19', 21, '0', 18),
(100, 'E20', 22, '0', 18);

-- --------------------------------------------------------

--
-- Table structure for table `site_plan`
--

CREATE TABLE `site_plan` (
  `id_site_plan` int NOT NULL,
  `nama_site_plan` varchar(100) NOT NULL,
  `lokasi` text NOT NULL,
  `penanggung_jawab` varchar(15) NOT NULL,
  `ig` text NOT NULL,
  `tiktok` text NOT NULL,
  `brosur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `site_plan`
--

INSERT INTO `site_plan` (`id_site_plan`, `nama_site_plan`, `lokasi`, `penanggung_jawab`, `ig`, `tiktok`, `brosur`) VALUES
(4, 'See Hill', 'Hotel Grand Kanaya, Jl. Raya Barat No.163, RT.06/RW.07, Dusun I Karangmangu, Ketenger, Kec. Baturaden, Kabupaten Banyumas, Jawa Tengah 53151', '082328936456', 'isse19_', '@isee19_', 'brosur1788613494.png'),
(13, 'SEE BEACH', 'Hotel Grand Kanaya, Jl. Raya Barat No.163, RT.06/RW.07, Dusun I Karangmangu, Ketenger, Kec. Baturaden, Kabupaten Banyumas, Jawa Tengah 53151', '082328936457', 'isse19_', '@isee19_', NULL),
(14, 'SEE CITY', 'Hotel Grand Kanaya, Jl. Raya Barat No.163, RT.06/RW.07, Dusun I Karangmangu, Ketenger, Kec. Baturaden, Kabupaten Banyumas, Jawa Tengah 53151', '082328936457', 'isse19_', '@isee19_', NULL),
(17, 'Waa Hill', 'Hotel Grand Kanaya, Jl. Raya Barat No.163, RT.06/RW.07, Dusun I Karangmangu, Ketenger, Kec. Baturaden, Kabupaten Banyumas, Jawa Tengah 53152', '0882007394240', '_najwashabira', '@__nanaa17', NULL),
(18, 'Zell Beach', 'Hotel Grand Kanaya, Jl. Raya Barat No.163, RT.06/RW.07, Dusun I Karangmangu, Ketenger, Kec. Baturaden, Kabupaten Banyumas, Jawa Tengah 53153', '085231968586', 'zeliaaalia_s', '@ini_iaaaaa', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL,
  `no_transaksi` varchar(255) NOT NULL,
  `id_pembeli` int NOT NULL,
  `id_karyawan` varchar(50) NOT NULL,
  `id_rumah` int NOT NULL,
  `status_transaksi` varchar(30) NOT NULL,
  `total` int NOT NULL,
  `tanggal_transaksi` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `no_transaksi`, `id_pembeli`, `id_karyawan`, `id_rumah`, `status_transaksi`, `total`, `tanggal_transaksi`) VALUES
(11, '26-09-21-0001', 4, 'KMAR0002', 3, 'Selesai', 0, '2026-09-21'),
(12, '26-09-23-0001', 2, 'KMAR0002', 2, 'Berlangsung', 0, '2026-09-23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(255) NOT NULL,
  `sandi` varchar(255) NOT NULL,
  `pin` varchar(255) NOT NULL,
  `peran` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `sandi`, `pin`, `peran`, `nama`) VALUES
('admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '123456', 'A', 'Admin'),
('KMAR0001', '1f72e09d0784331913df6ab1eca877e03ae1f2cc', '123456', 'M', 'Muhammad Isa Irawanto'),
('KMAR0002', 'a5f798b8a46059b10dd74c287cf990c9bb58cde6', '123456', 'M', 'Najwa Shabira'),
('KMAR0003', 'b665da672af9b6bf7581c4d7f2d58e6d223029ee', '123456', 'M', 'zelia');

-- --------------------------------------------------------

--
-- Table structure for table `web`
--

CREATE TABLE `web` (
  `id` varchar(10) NOT NULL,
  `nama_proyek` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `cp` varchar(30) NOT NULL,
  `instagram` text NOT NULL,
  `tiktok` text NOT NULL,
  `logo` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `web`
--

INSERT INTO `web` (`id`, `nama_proyek`, `alamat`, `cp`, `instagram`, `tiktok`, `logo`) VALUES
('1', 'Perumahan Green Villages', 'P2J8+VF Jatisawit, Kabupaten Brebes, Jawa Tengah', '082329221056', 'isse19_', '@isee19_', 'visupro.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail_transaksi`);

--
-- Indexes for table `foto_rumah`
--
ALTER TABLE `foto_rumah`
  ADD PRIMARY KEY (`id_foto_rumah`);

--
-- Indexes for table `janji_bayar`
--
ALTER TABLE `janji_bayar`
  ADD PRIMARY KEY (`id_janji_bayar`);

--
-- Indexes for table `jenis_pembayaran`
--
ALTER TABLE `jenis_pembayaran`
  ADD PRIMARY KEY (`id_jenis_pembayaran`);

--
-- Indexes for table `kategori_rumah`
--
ALTER TABLE `kategori_rumah`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `marketing`
--
ALTER TABLE `marketing`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `pembeli`
--
ALTER TABLE `pembeli`
  ADD PRIMARY KEY (`id_pembeli`);

--
-- Indexes for table `rumah`
--
ALTER TABLE `rumah`
  ADD PRIMARY KEY (`id_rumah`);

--
-- Indexes for table `site_plan`
--
ALTER TABLE `site_plan`
  ADD PRIMARY KEY (`id_site_plan`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `web`
--
ALTER TABLE `web`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `foto_rumah`
--
ALTER TABLE `foto_rumah`
  MODIFY `id_foto_rumah` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `janji_bayar`
--
ALTER TABLE `janji_bayar`
  MODIFY `id_janji_bayar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jenis_pembayaran`
--
ALTER TABLE `jenis_pembayaran`
  MODIFY `id_jenis_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `kategori_rumah`
--
ALTER TABLE `kategori_rumah`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pembeli`
--
ALTER TABLE `pembeli`
  MODIFY `id_pembeli` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rumah`
--
ALTER TABLE `rumah`
  MODIFY `id_rumah` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `site_plan`
--
ALTER TABLE `site_plan`
  MODIFY `id_site_plan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
