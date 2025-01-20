-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2025 at 04:35 PM
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
-- Database: `mikj2431_mikada-laundry`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_paket` int(11) NOT NULL,
  `qty` double NOT NULL,
  `total_harga` double NOT NULL,
  `keterangan` text DEFAULT NULL,
  `total_bayar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_paket`, `qty`, `total_harga`, `keterangan`, `total_bayar`) VALUES
(161, 184, 28, 4, 24000, NULL, 24000),
(162, 185, 28, 19999, 119994000, NULL, 0),
(163, 186, 31, 136, 1496000, NULL, 0),
(164, 187, 31, 136, 1496000, NULL, 1496000),
(165, 188, 36, 4, 44000, NULL, 44000),
(166, 189, 34, 22, 352000, NULL, 352000),
(167, 190, 34, 22, 352000, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `outlet`
--

CREATE TABLE `outlet` (
  `id_outlet` int(11) NOT NULL,
  `nama_outlet` varchar(228) DEFAULT NULL,
  `alamat_outlet` text DEFAULT NULL,
  `telp_outlet` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `outlet`
--

INSERT INTO `outlet` (`id_outlet`, `nama_outlet`, `alamat_outlet`, `telp_outlet`) VALUES
(14, 'Mikada Laundry', 'Jl. Minangkabau Dalam II No.4 9, RT.9/RW.6, Menteng Atas, Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12960', '628561463864');

-- --------------------------------------------------------

--
-- Table structure for table `paket_cuci`
--

CREATE TABLE `paket_cuci` (
  `id_paket` int(11) NOT NULL,
  `jenis_paket` enum('Paket Normal','Paket Besok Ambil [ PAGI ANTAR BESOK SORE DI AMBIL ]','Paket 1 Hari Selesai [ PAGI ANTAR SORE DI AMBIL ]') NOT NULL,
  `nama_paket` varchar(228) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `paket_cuci`
--

INSERT INTO `paket_cuci` (`id_paket`, `jenis_paket`, `nama_paket`, `harga`) VALUES
(28, 'Paket Normal', 'Cuci Setrika [ 	Paket Normal ]', 6000),
(29, 'Paket Normal', 'Cuci Biasa  [ Paket Normal ]', 6000),
(30, 'Paket Normal', 'Setrika Biasa [ Paket Normal ]', 6000),
(31, 'Paket Besok Ambil [ PAGI ANTAR BESOK SORE DI AMBIL ]', 'Cuci Setrika [ Paket Besok Ambil [ PAGI ANTAR BESOK SORE DI AMBIL ] ]', 11000),
(32, 'Paket Besok Ambil [ PAGI ANTAR BESOK SORE DI AMBIL ]', 'Cuci Biasa [ Paket Besok Ambil [ PAGI ANTAR BESOK SORE DI AMBIL ] ]', 8000),
(33, 'Paket Besok Ambil [ PAGI ANTAR BESOK SORE DI AMBIL ]', 'Setrika Biasa [ Paket Besok Ambil [ PAGI ANTAR BESOK SORE DI AMBIL ] ]', 8000),
(34, 'Paket 1 Hari Selesai [ PAGI ANTAR SORE DI AMBIL ]', 'Cuci Setrika [ Paket 1 Hari Selesai [ PAGI ANTAR SORE DI AMBIL ] ]', 16000),
(35, 'Paket 1 Hari Selesai [ PAGI ANTAR SORE DI AMBIL ]', 'Cuci Biasa [ Paket 1 Hari Selesai [ PAGI ANTAR SORE DI AMBIL ] ]', 11000),
(36, 'Paket 1 Hari Selesai [ PAGI ANTAR SORE DI AMBIL ]', 'Setrika Biasa [ Paket 1 Hari Selesai [ PAGI ANTAR SORE DI AMBIL ] ]', 11000);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama_pelanggan` varchar(228) NOT NULL,
  `alamat_pelanggan` text NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `telp_pelanggan` varchar(15) NOT NULL,
  `no_ktp` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `alamat_pelanggan`, `jenis_kelamin`, `telp_pelanggan`, `no_ktp`) VALUES
(50, 'Costumer Pertama', 'Jl, Kepo Lah', 'L', '08xxx', '0'),
(55, 'Kenny Josiah', 'Jl. SMA 14 No.57', 'L', '081212345678', '367456982356');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `reviewer` varchar(255) NOT NULL,
  `bintang` int(11) NOT NULL,
  `paket` varchar(255) NOT NULL,
  `review` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `reviewer`, `bintang`, `paket`, `review`, `created_at`) VALUES
(13, 'costumer', 5, 'normal', 'Testing aja', '2025-01-19 09:30:25'),
(14, 'kensm', 5, 'besok_ambil', 'kelass', '2025-01-19 09:46:41');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `kode_invoice` varchar(228) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `tgl` datetime DEFAULT NULL,
  `batas_waktu` datetime DEFAULT NULL,
  `tgl_pembayaran` datetime DEFAULT NULL,
  `biaya_tambahan` int(11) DEFAULT NULL,
  `diskon` double DEFAULT NULL,
  `pajak` int(11) DEFAULT NULL,
  `status` enum('Baru','Sedang Proses','Selesai','Diambil') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_bayar` enum('Dibayar','Belum','Pending','Gagal') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_paket` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `kode_invoice`, `id_pelanggan`, `tgl`, `batas_waktu`, `tgl_pembayaran`, `biaya_tambahan`, `diskon`, `pajak`, `status`, `status_bayar`, `id_user`, `id_paket`) VALUES
(184, 'CLN20678E249F12741', 50, '2025-01-20 17:25:35', '2025-01-23 17:25:35', '2025-01-20 17:26:02', 0, 0, 0, 'Sedang Proses', 'Dibayar', 50, 28),
(185, 'CLN20678E288539492', 50, '2025-01-20 17:42:13', '2025-01-23 17:42:13', NULL, 0, 0, 0, 'Sedang Proses', 'Gagal', 50, 28),
(186, 'CLN20678E28CDADCCC', 50, '2025-01-20 17:43:25', '2025-01-23 17:43:25', NULL, 0, 0, 0, 'Sedang Proses', 'Gagal', 50, 31),
(187, 'CLN20678E28E104A34', 50, '2025-01-20 17:43:45', '2025-01-23 17:43:45', '2025-01-20 17:43:54', 0, 0, 0, 'Sedang Proses', 'Dibayar', 50, 31),
(188, 'CLN20678E2DAC5B294', 55, '2025-01-20 18:04:12', '2025-01-23 18:04:12', '2025-01-20 18:04:46', 0, 0, 0, 'Sedang Proses', 'Dibayar', 55, 36),
(189, 'CLN20678E2E1F67B7F', 55, '2025-01-20 18:06:07', '2025-01-23 18:06:07', '2025-01-20 18:06:33', 0, 0, 0, 'Sedang Proses', 'Dibayar', 55, 34),
(190, 'CLN20678E2E44F0561', 55, '2025-01-20 18:06:44', '2025-01-23 18:06:44', '0000-00-00 00:00:00', 0, 0, 0, 'Sedang Proses', 'Pending', 55, 34);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(228) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(228) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` text DEFAULT NULL,
  `password` varchar(228) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `salt` varchar(64) NOT NULL,
  `role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_user`, `username`, `email`, `password`, `salt`, `role`) VALUES
(49, 'Admin Mikada', 'mikadalaundry', 'Mikadalaundry@gmail.com', '2faa87f184c8dfb463670ea05b659e2a6d65b7446aaf31560b41457a50f1ccc28f419a4a1bba1513e87d474e14b7bcfebe50d544478a19b6b08fc93330b4a0d1', '38f23e1a0e1921943eda60855561d50773ced2c4fa1a3381e6062053fbd26a7e', 'admin'),
(50, 'costumer testing', 'costumer01', 'cerberus404x@gmail.com', '4134d0b3d71a0f48986c6dc8eeec629a069800a0e1daf3ad3cf8a2b05910669ca8f404c3b649eda140a7fe60ef30cf1f6220b16308d017fac5b9026aa814725e', 'dfdb79d1b03de7642d38bdd3ba98c66c9fea7ad74644bd72ff2fdbb3830e2676', 'user'),
(51, 'costumer02', 'costumer02', 'cos2@gmail.com', 'a14928b6f1ca155f98b48957c55d32bb2163678165e8ac64afc42470124d815d1c435d9a8c8462be535b02ef7f1c05a24fddc1e3b9b62189277b857951298e3a', 'b5f8746a4ecb765dd440e6fbf7e57bdc', 'user'),
(52, 'costumer03', 'costumer03', 'cos3@gmail.com', '5a9231fa85ee3df3a27b93231c46ab1b22aca324f6fbd7c650d04a76bd3a46455918723d1367bf47992897868019314fefb941b0fbe26e90d1c134305aaea2c7', 'd2dfef1a2e22adfe7fc3df47aab4a747cab455f2e926fe5f14330a618025b98a', 'user'),
(54, 'budi', 'budi1', 'admin@gmail.com', '91b3f833fe5c1bba11643ce454631af97a0322d2b23442ced82dba88bcbed0b14531d5d6e35734556b4414245e4396d632bf7c1a37ce467f1aef8c20c733a21a', 'a94bb0334a8b6da10128a7aae99ab3b30c0cf9454df765990c8bff2a8fa9c9aa', 'user'),
(55, 'Ken', 'kensm', 'kensmoba29@gmail.com', '98620fccdab70b5405ab9efe6073a0dfbcf39d64f4a442f465ec09460be34070b22275e2421efa1b73b63185221ca7557050272a8947604cb7e2d9651950b91a', 'a9b31a3dd92515ba69a1e2c59741a80b3755128e335a8885b7991091d781f001', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_paket` (`id_paket`);

--
-- Indexes for table `outlet`
--
ALTER TABLE `outlet`
  ADD PRIMARY KEY (`id_outlet`);

--
-- Indexes for table `paket_cuci`
--
ALTER TABLE `paket_cuci`
  ADD PRIMARY KEY (`id_paket`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `outlet`
--
ALTER TABLE `outlet`
  MODIFY `id_outlet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `paket_cuci`
--
ALTER TABLE `paket_cuci`
  MODIFY `id_paket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_3` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_4` FOREIGN KEY (`id_paket`) REFERENCES `paket_cuci` (`id_paket`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
