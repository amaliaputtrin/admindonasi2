-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2024 at 09:21 AM
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
-- Database: `donasipeduly`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama_admin` varchar(225) NOT NULL,
  `email` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `email`, `password`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin'),
(2, 'admin2', 'admin2@gmail.com', 'admin2'),
(3, 'trl super admin 11', '2@211', '123');

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `id` int(11) NOT NULL,
  `payment` varchar(225) NOT NULL,
  `no_rekening` int(11) NOT NULL,
  `nama_akun` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`id`, `payment`, `no_rekening`, `nama_akun`) VALUES
(1, 'BCA', 123, 'BCAA'),
(2, 'BRII', 222, 'BRII'),
(3, 'Mandiri', 333, 'Mandiriii');

-- --------------------------------------------------------

--
-- Table structure for table `detail_volunteer`
--

CREATE TABLE `detail_volunteer` (
  `id_detail_volunteer` int(11) NOT NULL,
  `id_volunteer` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal_daftar` date NOT NULL,
  `nominal_donasi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_volunteer`
--

INSERT INTO `detail_volunteer` (`id_detail_volunteer`, `id_volunteer`, `id_user`, `tanggal_daftar`, `nominal_donasi`) VALUES
(2, 6, 4, '2024-11-29', 10000);

-- --------------------------------------------------------

--
-- Table structure for table `donasi`
--

CREATE TABLE `donasi` (
  `id_donasi` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `id_bank` int(11) NOT NULL,
  `gambar` text NOT NULL,
  `judul` text NOT NULL,
  `kategori` text NOT NULL,
  `target` int(11) NOT NULL,
  `lokasi` int(11) NOT NULL,
  `tanggal_tenggat` date NOT NULL,
  `keterangan` text NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donasi`
--

INSERT INTO `donasi` (`id_donasi`, `id_admin`, `id_bank`, `gambar`, `judul`, `kategori`, `target`, `lokasi`, `tanggal_tenggat`, `keterangan`, `status`) VALUES
(7, 3, 1, 'uploads/donasi_674af46a0a84b.jpg', '1', 'Lingkungan', 1, 1, '2024-11-30', '1', 1),
(13, 3, 1, 'uploads/donasi_674b019fb743f.png', 'e', 'Bencana Alam', 6, 0, '2024-12-07', 'e', 1);

-- --------------------------------------------------------

--
-- Table structure for table `donasi_detail`
--

CREATE TABLE `donasi_detail` (
  `id_detail_donasi` int(11) NOT NULL,
  `id_donasi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal_donasi` date NOT NULL,
  `nominal_donasi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donasi_detail`
--

INSERT INTO `donasi_detail` (`id_detail_donasi`, `id_donasi`, `id_user`, `tanggal_donasi`, `nominal_donasi`) VALUES
(1, 7, 3, '2024-11-28', 10000);

-- --------------------------------------------------------

--
-- Table structure for table `pencairan_dana_donasi`
--

CREATE TABLE `pencairan_dana_donasi` (
  `id_pencairan_dana_donasi` int(11) NOT NULL,
  `id_donasi` int(11) NOT NULL,
  `nominal` int(11) NOT NULL,
  `tanggal_pencairan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pencairan_dana_donasi`
--

INSERT INTO `pencairan_dana_donasi` (`id_pencairan_dana_donasi`, `id_donasi`, `nominal`, `tanggal_pencairan`) VALUES
(6, 7, 10000, '2024-12-01');

-- --------------------------------------------------------

--
-- Table structure for table `pencairan_dana_volunteer`
--

CREATE TABLE `pencairan_dana_volunteer` (
  `id_pencairan_dana_volunteer` int(11) NOT NULL,
  `id_volunteer` int(11) NOT NULL,
  `nominal` int(11) NOT NULL,
  `tanggal_pencairan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pencairan_dana_volunteer`
--

INSERT INTO `pencairan_dana_volunteer` (`id_pencairan_dana_volunteer`, `id_volunteer`, `nominal`, `tanggal_pencairan`) VALUES
(3, 6, 10000, '2024-12-01');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `email` varchar(225) NOT NULL,
  `username` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `no_telp` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `email`, `username`, `password`, `no_telp`) VALUES
(3, '123@mail.com', 'admin', '123', '081236214124'),
(4, 'user1@mail.com', 'user', 'user123', '09123231');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer`
--

CREATE TABLE `volunteer` (
  `id_volunteer` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `id_bank` int(11) NOT NULL,
  `gambar` text NOT NULL,
  `judul` text NOT NULL,
  `kategori` text NOT NULL,
  `kuota` int(11) NOT NULL,
  `waktu_pelaksanaan` date NOT NULL,
  `lokasi` text NOT NULL,
  `keterangan` text NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer`
--

INSERT INTO `volunteer` (`id_volunteer`, `id_admin`, `id_bank`, `gambar`, `judul`, `kategori`, `kuota`, `waktu_pelaksanaan`, `lokasi`, `keterangan`, `status`) VALUES
(6, 3, 2, 'uploads/volunteer_674b2155b4b82.png', '123', 'Pendidikan', 0, '2024-12-07', '123', '123', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_volunteer`
--
ALTER TABLE `detail_volunteer`
  ADD PRIMARY KEY (`id_detail_volunteer`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_volunteer` (`id_volunteer`);

--
-- Indexes for table `donasi`
--
ALTER TABLE `donasi`
  ADD PRIMARY KEY (`id_donasi`),
  ADD KEY `id_bank` (`id_bank`),
  ADD KEY `id_admin` (`id_admin`) USING BTREE;

--
-- Indexes for table `donasi_detail`
--
ALTER TABLE `donasi_detail`
  ADD PRIMARY KEY (`id_detail_donasi`),
  ADD KEY `id_donasi` (`id_donasi`),
  ADD KEY `donasi_detail_ibfk_2` (`id_user`);

--
-- Indexes for table `pencairan_dana_donasi`
--
ALTER TABLE `pencairan_dana_donasi`
  ADD PRIMARY KEY (`id_pencairan_dana_donasi`),
  ADD KEY `pencairan_dana_donasi_ibfk_1` (`id_donasi`);

--
-- Indexes for table `pencairan_dana_volunteer`
--
ALTER TABLE `pencairan_dana_volunteer`
  ADD PRIMARY KEY (`id_pencairan_dana_volunteer`),
  ADD KEY `pencairan_dana_volunteer_ibfk_1` (`id_volunteer`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `volunteer`
--
ALTER TABLE `volunteer`
  ADD PRIMARY KEY (`id_volunteer`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `id_bank` (`id_bank`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `detail_volunteer`
--
ALTER TABLE `detail_volunteer`
  MODIFY `id_detail_volunteer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `donasi`
--
ALTER TABLE `donasi`
  MODIFY `id_donasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `donasi_detail`
--
ALTER TABLE `donasi_detail`
  MODIFY `id_detail_donasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pencairan_dana_donasi`
--
ALTER TABLE `pencairan_dana_donasi`
  MODIFY `id_pencairan_dana_donasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pencairan_dana_volunteer`
--
ALTER TABLE `pencairan_dana_volunteer`
  MODIFY `id_pencairan_dana_volunteer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `volunteer`
--
ALTER TABLE `volunteer`
  MODIFY `id_volunteer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_volunteer`
--
ALTER TABLE `detail_volunteer`
  ADD CONSTRAINT `detail_volunteer_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_volunteer_ibfk_2` FOREIGN KEY (`id_volunteer`) REFERENCES `volunteer` (`id_volunteer`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `donasi`
--
ALTER TABLE `donasi`
  ADD CONSTRAINT `donasi_ibfk_3` FOREIGN KEY (`id_bank`) REFERENCES `bank` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `donasi_ibfk_4` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);

--
-- Constraints for table `donasi_detail`
--
ALTER TABLE `donasi_detail`
  ADD CONSTRAINT `donasi_detail_ibfk_1` FOREIGN KEY (`id_donasi`) REFERENCES `donasi` (`id_donasi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `donasi_detail_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pencairan_dana_donasi`
--
ALTER TABLE `pencairan_dana_donasi`
  ADD CONSTRAINT `pencairan_dana_donasi_ibfk_1` FOREIGN KEY (`id_donasi`) REFERENCES `donasi` (`id_donasi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pencairan_dana_volunteer`
--
ALTER TABLE `pencairan_dana_volunteer`
  ADD CONSTRAINT `pencairan_dana_volunteer_ibfk_1` FOREIGN KEY (`id_volunteer`) REFERENCES `volunteer` (`id_volunteer`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `volunteer`
--
ALTER TABLE `volunteer`
  ADD CONSTRAINT `volunteer_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `volunteer_ibfk_2` FOREIGN KEY (`id_bank`) REFERENCES `bank` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
