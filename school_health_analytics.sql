-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2026 at 12:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_health_analytics`
--

-- --------------------------------------------------------

--
-- Table structure for table `keluhan`
--

CREATE TABLE `keluhan` (
  `id` int(11) NOT NULL,
  `nama_keluhan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keluhan`
--

INSERT INTO `keluhan` (`id`, `nama_keluhan`) VALUES
(1, 'Demam'),
(2, 'Batuk'),
(3, 'Pilek'),
(4, 'Pusing'),
(5, 'Sakit Perut'),
(6, 'Mual'),
(7, 'Luka Ringan'),
(8, 'Sesak Napas'),
(9, 'Alergi'),
(10, 'Lainnya');

-- --------------------------------------------------------

--
-- Table structure for table `kunjungan`
--

CREATE TABLE `kunjungan` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) DEFAULT NULL,
  `obat_id` int(11) DEFAULT NULL,
  `keluhan_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam` time DEFAULT NULL,
  `suhu` decimal(4,1) DEFAULT NULL,
  `tekanan_darah` varchar(20) DEFAULT NULL,
  `tindakan` text DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `kondisi` enum('Pulang','Istirahat','Rujuk') DEFAULT NULL,
  `petugas_id` int(11) DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kunjungan`
--

INSERT INTO `kunjungan` (`id`, `siswa_id`, `obat_id`, `keluhan_id`, `tanggal`, `jam`, `suhu`, `tekanan_darah`, `tindakan`, `catatan`, `kondisi`, `petugas_id`, `jam_keluar`) VALUES
(7, 10, 1, 1, '2026-10-21', '08:07:00', 38.5, '120/65', 'diberi obat (paracetamol) dan istirahat di uks', 'istirahat jangan terlalu banyak aktivitas', 'Istirahat', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id` int(11) NOT NULL,
  `nama_obat` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `satuan` varchar(30) DEFAULT NULL,
  `expired` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id`, `nama_obat`, `kategori`, `stok`, `satuan`, `expired`, `created_at`) VALUES
(1, 'Paracetamol', 'Obat pereda nyeri', 10, 'butir', '2030-03-01', '2026-07-29 06:19:59'),
(2, 'Antasida', 'Obat asam lambung', 10, 'butir', '2028-05-01', '2026-07-29 06:36:44'),
(4, 'Betadine', 'Antiseptik', 6, 'Cair', '2029-09-09', '2026-07-29 06:39:53'),
(10, 'hansaplast', 'penutup luka', 100, 'pcs', '2030-10-19', '2026-10-16 03:23:29'),
(11, 'alkohol', 'pembersih luka', 4, 'botol', '2025-05-01', '2026-10-19 11:08:56'),
(12, 'tabung oksigen', 'oksigen', 3, 'tabung', '2028-11-07', '2026-10-19 11:12:30'),
(13, 'minyak kayu putih', 'wewangian', 5, 'botol', '2031-03-01', '2026-10-19 11:13:27'),
(14, 'rivanol', 'pembersih luka', 4, 'botol', '2027-06-01', '2026-10-19 11:14:42'),
(15, 'freshcare', 'wewangian', 2, 'botol 10ml', '2029-06-01', '2026-10-19 11:16:10'),
(16, 'insto', 'pembersih mata', 2, 'botol 7,5ml', '2029-07-01', '2026-10-19 11:16:54'),
(17, 'geliga ', 'balsen', 2, 'tube', '2031-06-01', '2026-10-19 11:17:49'),
(18, 'kasa steril', 'penutup luka', 7, 'kotak', '2090-01-01', '2026-10-19 11:18:39'),
(19, 'plester', 'penutup luka', 1, 'roll', '0001-01-01', '2026-10-19 11:19:09'),
(20, 'kapas', 'kapas', 2, 'bungkus', '0000-00-00', '2026-10-19 11:21:12'),
(21, 'bedak alergi', 'bedak tabur untuk gatal', 2, 'botol', '2028-11-01', '2026-10-19 11:22:03'),
(22, 'kasa gulung pajang', 'penutup luka', 29, 'roll', '0000-00-00', '2026-10-19 11:25:23'),
(23, 'kasa gulung pendek', 'penutup luka', 16, 'roll', '0000-00-00', '2026-10-19 11:25:49'),
(24, 'pembalut wanita', 'kebutuhan wanita', 28, 'pcs', '0000-00-00', '2026-10-19 11:27:21'),
(25, 'tiissue', 'pembersih', 1, 'pack', '0000-00-00', '2026-10-19 11:28:50');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `kelas` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nis`, `nama`, `jenis_kelamin`, `kelas`, `tanggal_lahir`, `alamat`) VALUES
(1, '24970008', 'Budi Safta Nugraha nugoro', 'L', 'X RPL 2', '1997-09-18', 'Bandar Lampung'),
(2, '00000001', 'Joel Jarren', 'L', 'XI RPL 2', '1007-09-18', 'GadingRejo Pringsewu'),
(3, '00000002', 'Adam Alfatih', 'L', 'XI RPL 1', '1007-09-18', 'Pesawaran'),
(4, '00000003', 'Anaya', 'P', 'XI RPL 1', '1007-09-18', 'Waylima'),
(6, '00000007', 'Kholilatul Asti', 'P', 'XI RPL 1', '2010-04-15', 'Wiyono'),
(7, '00000006', 'zulfa rahadatul aisy', 'P', 'XI RPL 1', '2010-09-29', 'Gedung Tataan'),
(8, '00000005', 'Mardika Arum Melati', 'P', 'XI RPL 1', '2010-06-10', 'Mataram'),
(9, '180901300598', 'Ihwan Hamid Huzain', 'L', 'XII RPL 1', '2026-08-07', 'Gedong Tataan, Sukaraja 1'),
(10, '553251125', 'Natasya Nur Kholifa', 'P', 'XI RPL 2', '2010-07-04', 'Bengkulu');

-- --------------------------------------------------------

--
-- Table structure for table `surat_uks`
--

CREATE TABLE `surat_uks` (
  `id` int(11) NOT NULL,
  `kunjungan_id` int(11) NOT NULL,
  `nomor_surat` varchar(50) NOT NULL,
  `jenis_surat` enum('pulang','rujukan') NOT NULL,
  `tanggal_surat` date NOT NULL,
  `alasan` text NOT NULL,
  `tujuan_rujukan` varchar(150) DEFAULT NULL,
  `petugas_id` int(11) NOT NULL,
  `nama_guru_piket` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin', '$2y$10$0AU8MNBJIJ5/SnPJjzhyJeW1UPI6nQBzluMaMByX83TN0aypD5ocy', 'admin', '2026-07-28 09:04:39'),
(2, 'Petugas UKS', 'petugas', '$2y$10$02rbSSj0LAn.flkwlY24KOfhZl5an236VYHJdn1oKi/ZBdZhl3EGi', 'user', '2026-07-28 22:01:49'),
(3, 'Natasya Nur Kholifah', 'Natasya Imyut', '$2y$10$AhCsWxRWkJvlkpjVzkjs0eQSjXauRZlJbET/KsdCUGn9iBYAjne9O', 'user', '2026-10-21 01:13:34'),
(4, 'Zivia Yulinda', 'Zivia', '$2y$10$zqLrF9HvHVo7OJI59IvHveL7h8Rtvxl3/HXbBsLsoFIkfYpN61rRS', 'user', '2026-10-21 01:14:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `keluhan`
--
ALTER TABLE `keluhan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `obat_id` (`obat_id`),
  ADD KEY `keluhan_id` (`keluhan_id`),
  ADD KEY `petugas_id` (`petugas_id`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nis` (`nis`);

--
-- Indexes for table `surat_uks`
--
ALTER TABLE `surat_uks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_surat` (`nomor_surat`),
  ADD KEY `idx_surat_kunjungan` (`kunjungan_id`),
  ADD KEY `idx_surat_tanggal` (`tanggal_surat`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `keluhan`
--
ALTER TABLE `keluhan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kunjungan`
--
ALTER TABLE `kunjungan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `surat_uks`
--
ALTER TABLE `surat_uks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD CONSTRAINT `kunjungan_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`),
  ADD CONSTRAINT `kunjungan_ibfk_2` FOREIGN KEY (`obat_id`) REFERENCES `obat` (`id`),
  ADD CONSTRAINT `kunjungan_ibfk_3` FOREIGN KEY (`keluhan_id`) REFERENCES `keluhan` (`id`),
  ADD CONSTRAINT `kunjungan_ibfk_4` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
