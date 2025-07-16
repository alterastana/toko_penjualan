-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2025 at 03:30 PM
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
-- Database: `toko_penjualan`
--

-- --------------------------------------------------------

--
-- Table structure for table `hutang`
--

CREATE TABLE `hutang` (
  `id_hutang` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `jenis` enum('pinjam','bayar') DEFAULT NULL,
  `jumlah` decimal(12,2) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `metode` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_aset_harian`
--

CREATE TABLE `log_aset_harian` (
  `id_log` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `total_saldo` decimal(12,2) DEFAULT NULL,
  `total_piutang` decimal(12,2) DEFAULT NULL,
  `total_aset` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_aset_harian`
--

INSERT INTO `log_aset_harian` (`id_log`, `tanggal`, `total_saldo`, `total_piutang`, `total_aset`) VALUES
(1, '2025-07-15', 0.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `durasi_atau_jumlah` int(11) DEFAULT NULL,
  `harga_beli` decimal(12,2) DEFAULT NULL,
  `harga_jual` decimal(12,2) DEFAULT NULL,
  `no_customer` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `total_untung` decimal(12,2) DEFAULT NULL,
  `pengeluaran` decimal(12,2) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `untung` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `penjualan`
--
DELIMITER $$
CREATE TRIGGER `hitung_untung_insert` BEFORE INSERT ON `penjualan` FOR EACH ROW BEGIN
    SET NEW.untung = NEW.harga_jual - NEW.harga_beli;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hitung_untung_update` BEFORE UPDATE ON `penjualan` FOR EACH ROW BEGIN
    SET NEW.untung = NEW.harga_jual - NEW.harga_beli;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `saldo_digital`
--

CREATE TABLE `saldo_digital` (
  `metode` varchar(20) NOT NULL,
  `saldo` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_dompet`
--

CREATE TABLE `transaksi_dompet` (
  `id_transaksi` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `metode` varchar(20) DEFAULT NULL,
  `jenis` enum('masuk','keluar') DEFAULT NULL,
  `jumlah` decimal(12,2) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `keperluan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `transaksi_dompet`
--
DELIMITER $$
CREATE TRIGGER `update_saldo_dompet` AFTER INSERT ON `transaksi_dompet` FOR EACH ROW BEGIN
    IF NEW.jenis = 'masuk' THEN
        UPDATE saldo_digital
        SET saldo = saldo + NEW.jumlah
        WHERE metode = NEW.metode;
    ELSEIF NEW.jenis = 'keluar' THEN
        UPDATE saldo_digital
        SET saldo = saldo - NEW.jumlah
        WHERE metode = NEW.metode;
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hutang`
--
ALTER TABLE `hutang`
  ADD PRIMARY KEY (`id_hutang`);

--
-- Indexes for table `log_aset_harian`
--
ALTER TABLE `log_aset_harian`
  ADD PRIMARY KEY (`id_log`),
  ADD UNIQUE KEY `tanggal` (`tanggal`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indexes for table `saldo_digital`
--
ALTER TABLE `saldo_digital`
  ADD PRIMARY KEY (`metode`);

--
-- Indexes for table `transaksi_dompet`
--
ALTER TABLE `transaksi_dompet`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hutang`
--
ALTER TABLE `hutang`
  MODIFY `id_hutang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_aset_harian`
--
ALTER TABLE `log_aset_harian`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi_dompet`
--
ALTER TABLE `transaksi_dompet`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `simpan_log_aset_harian` ON SCHEDULE EVERY 1 DAY STARTS '2025-07-15 23:59:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
  INSERT INTO log_aset_harian (tanggal, total_saldo, total_piutang, total_aset)
  SELECT
    CURDATE(),
    IFNULL((SELECT SUM(saldo) FROM saldo_digital), 0),
    IFNULL((SELECT SUM(jumlah) FROM hutang WHERE jenis = 'pinjam'), 0)
    - IFNULL((SELECT SUM(jumlah) FROM hutang WHERE jenis = 'bayar'), 0),
    IFNULL((SELECT SUM(saldo) FROM saldo_digital), 0) +
    (IFNULL((SELECT SUM(jumlah) FROM hutang WHERE jenis = 'pinjam'), 0)
    - IFNULL((SELECT SUM(jumlah) FROM hutang WHERE jenis = 'bayar'), 0));
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
