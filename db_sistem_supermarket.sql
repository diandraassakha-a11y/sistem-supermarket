-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 16, 2026 at 06:40 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sistem_supermarket`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `id_detail` int UNSIGNED NOT NULL,
  `id_penjualan` int UNSIGNED NOT NULL,
  `id_produk` int UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL DEFAULT '0.00',
  `diskon_item` decimal(12,2) NOT NULL DEFAULT '0.00',
  `subtotal_item` decimal(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_penjualan`
--

INSERT INTO `detail_penjualan` (`id_detail`, `id_penjualan`, `id_produk`, `jumlah`, `harga_satuan`, `diskon_item`, `subtotal_item`) VALUES
(1, 1, 1, 2, '72000.00', '0.00', '144000.00'),
(2, 1, 2, 1, '36000.00', '0.00', '36000.00'),
(3, 2, 3, 3, '18000.00', '0.00', '54000.00'),
(4, 2, 4, 2, '12000.00', '0.00', '24000.00'),
(5, 3, 5, 10, '5000.00', '0.00', '50000.00');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int UNSIGNED NOT NULL,
  `nama_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Kebutuhan Pokok', 'Contoh kategori bahan pokok seperti beras dan minyak.'),
(2, 'Minuman', 'Contoh kategori minuman kemasan.'),
(3, 'Perawatan Diri', 'Contoh kategori kebutuhan perawatan sehari-hari.');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int UNSIGNED NOT NULL,
  `nama_pegawai` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_pegawai` enum('Aktif','Tidak Aktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama_pegawai`, `jenis_kelamin`, `jabatan`, `no_telepon`, `email`, `username`, `password_hash`, `status_pegawai`) VALUES
(1, 'Admin Contoh', 'L', 'Administrator', '081200000201', 'admin@example.com', 'admin.demo', '$2y$10$aYr6PbzzKlq9.vzhRUmWAe3UFo9FF4sia/nXY2.UPpaWzYmURaSyS', 'Aktif'),
(2, 'Kasir Contoh', 'P', 'Kasir', '081200000202', 'kasir@example.com', 'kasir.demo', '$2y$10$aYr6PbzzKlq9.vzhRUmWAe3UFo9FF4sia/nXY2.UPpaWzYmURaSyS', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int UNSIGNED NOT NULL,
  `nama_pelanggan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_daftar` date NOT NULL,
  `poin_member` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `no_telepon`, `email`, `tanggal_daftar`, `poin_member`) VALUES
(1, 'Pelanggan Contoh A', '081200000301', 'pelanggan.a@example.com', '2026-10-01', 120),
(2, 'Pelanggan Contoh B', '081200000302', 'pelanggan.b@example.com', '2026-10-15', 45),
(3, 'Pelanggan Contoh C', '081200000303', 'pelanggan.c@example.com', '2026-11-01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pemasok`
--

CREATE TABLE `pemasok` (
  `id_pemasok` int UNSIGNED NOT NULL,
  `nama_pemasok` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kontak` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pemasok`
--

INSERT INTO `pemasok` (`id_pemasok`, `nama_pemasok`, `nama_kontak`, `no_telepon`, `email`, `alamat`) VALUES
(1, 'PT Pangan Contoh', 'Budi Santoso', '081200000101', 'pangan@example.com', 'Jl. Contoh No. 10, Jakarta'),
(2, 'CV Distributor Contoh', 'Siti Aminah', '081200000102', 'distributor@example.com', 'Jl. Simulasi No. 25, Bandung');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int UNSIGNED NOT NULL,
  `id_penjualan` int UNSIGNED NOT NULL,
  `metode_pembayaran` enum('Tunai','Debit','Kredit','QRIS','E-Wallet') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_bayar` decimal(12,2) NOT NULL DEFAULT '0.00',
  `waktu_pembayaran` datetime NOT NULL,
  `status_pembayaran` enum('Berhasil','Menunggu','Gagal','Dikembalikan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Berhasil'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_penjualan`, `metode_pembayaran`, `jumlah_bayar`, `waktu_pembayaran`, `status_pembayaran`) VALUES
(1, 1, 'Tunai', '175000.00', '2026-11-15 09:16:00', 'Berhasil'),
(2, 2, 'QRIS', '78000.00', '2026-11-15 10:31:00', 'Berhasil'),
(3, 3, 'Debit', '50000.00', '2026-11-16 08:46:00', 'Berhasil');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int UNSIGNED NOT NULL,
  `id_pegawai` int UNSIGNED NOT NULL,
  `id_pelanggan` int UNSIGNED DEFAULT NULL,
  `nomor_struk` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_penjualan` datetime NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `diskon` decimal(12,2) NOT NULL DEFAULT '0.00',
  `pajak` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_bayar` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status_transaksi` enum('Selesai','Dibatalkan','Dikembalikan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Selesai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id_penjualan`, `id_pegawai`, `id_pelanggan`, `nomor_struk`, `tanggal_penjualan`, `subtotal`, `diskon`, `pajak`, `total_bayar`, `status_transaksi`) VALUES
(1, 2, 1, 'DEMO-20261115-001', '2026-11-15 09:15:00', '180000.00', '5000.00', '0.00', '175000.00', 'Selesai'),
(2, 2, 2, 'DEMO-20261115-002', '2026-11-15 10:30:00', '78000.00', '0.00', '0.00', '78000.00', 'Selesai'),
(3, 2, NULL, 'DEMO-20261116-001', '2026-11-16 08:45:00', '50000.00', '0.00', '0.00', '50000.00', 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int UNSIGNED NOT NULL,
  `id_kategori` int UNSIGNED NOT NULL,
  `id_pemasok` int UNSIGNED NOT NULL,
  `kode_produk` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_produk` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merek` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `satuan` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_beli` decimal(12,2) NOT NULL DEFAULT '0.00',
  `harga_jual` decimal(12,2) NOT NULL DEFAULT '0.00',
  `stok` int NOT NULL DEFAULT '0',
  `stok_minimum` int NOT NULL DEFAULT '0',
  `tanggal_kedaluwarsa` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_kategori`, `id_pemasok`, `kode_produk`, `barcode`, `nama_produk`, `merek`, `satuan`, `harga_beli`, `harga_jual`, `stok`, `stok_minimum`, `tanggal_kedaluwarsa`) VALUES
(1, 1, 1, 'DEMO-BRS-001', '8990000000001', 'Beras Premium 5 kg', 'Panen Contoh', 'karung', '65000.00', '72000.00', 48, 10, '2027-11-01'),
(2, 1, 1, 'DEMO-MNY-001', '8990000000002', 'Minyak Goreng 2 L', 'Dapur Contoh', 'botol', '31000.00', '36000.00', 39, 8, '2027-08-01'),
(3, 2, 2, 'DEMO-SUS-001', '8990000000003', 'Susu UHT 1 L', 'Susu Contoh', 'kotak', '14000.00', '18000.00', 57, 12, '2027-03-01'),
(4, 2, 2, 'DEMO-TEH-001', '8990000000004', 'Teh Celup 25 Kantong', 'Teh Contoh', 'kotak', '8000.00', '12000.00', 28, 8, '2027-09-01'),
(5, 3, 1, 'DEMO-SBN-001', '8990000000005', 'Sabun Mandi 90 g', 'Bersih Contoh', 'pcs', '3500.00', '5000.00', 90, 15, '2028-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `stok_masuk`
--

CREATE TABLE `stok_masuk` (
  `id_stok_masuk` int UNSIGNED NOT NULL,
  `id_produk` int UNSIGNED NOT NULL,
  `id_pemasok` int UNSIGNED NOT NULL,
  `id_pegawai` int UNSIGNED NOT NULL,
  `tanggal_masuk` datetime NOT NULL,
  `jumlah_masuk` int NOT NULL,
  `harga_beli_satuan` decimal(12,2) NOT NULL DEFAULT '0.00',
  `catatan` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stok_masuk`
--

INSERT INTO `stok_masuk` (`id_stok_masuk`, `id_produk`, `id_pemasok`, `id_pegawai`, `tanggal_masuk`, `jumlah_masuk`, `harga_beli_satuan`, `catatan`) VALUES
(1, 1, 1, 1, '2026-11-14 08:00:00', 50, '65000.00', 'Contoh penerimaan stok beras.'),
(2, 2, 1, 1, '2026-11-14 08:30:00', 40, '31000.00', 'Contoh penerimaan stok minyak.'),
(3, 3, 2, 1, '2026-11-14 09:00:00', 60, '14000.00', 'Contoh penerimaan stok susu.'),
(4, 4, 2, 1, '2026-11-14 09:30:00', 30, '8000.00', 'Contoh penerimaan stok teh.'),
(5, 5, 1, 1, '2026-11-14 10:00:00', 100, '3500.00', 'Contoh penerimaan stok sabun.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `idx_detail_penjualan` (`id_penjualan`),
  ADD KEY `idx_detail_produk` (`id_produk`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`),
  ADD UNIQUE KEY `uq_pegawai_username` (`username`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `pemasok`
--
ALTER TABLE `pemasok`
  ADD PRIMARY KEY (`id_pemasok`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `idx_pembayaran_penjualan` (`id_penjualan`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`),
  ADD KEY `idx_penjualan_pegawai` (`id_pegawai`),
  ADD KEY `idx_penjualan_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `idx_produk_kategori` (`id_kategori`),
  ADD KEY `idx_produk_pemasok` (`id_pemasok`);

--
-- Indexes for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD PRIMARY KEY (`id_stok_masuk`),
  ADD KEY `idx_stok_masuk_produk` (`id_produk`),
  ADD KEY `idx_stok_masuk_pemasok` (`id_pemasok`),
  ADD KEY `idx_stok_masuk_pegawai` (`id_pegawai`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  MODIFY `id_detail` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pemasok`
--
ALTER TABLE `pemasok`
  MODIFY `id_pemasok` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  MODIFY `id_stok_masuk` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `fk_detail_penjualan_penjualan` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_penjualan_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_penjualan` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON UPDATE CASCADE;

--
-- Constraints for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `fk_penjualan_pegawai` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penjualan_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produk_pemasok` FOREIGN KEY (`id_pemasok`) REFERENCES `pemasok` (`id_pemasok`) ON UPDATE CASCADE;

--
-- Constraints for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD CONSTRAINT `fk_stok_masuk_pegawai` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stok_masuk_pemasok` FOREIGN KEY (`id_pemasok`) REFERENCES `pemasok` (`id_pemasok`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stok_masuk_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
