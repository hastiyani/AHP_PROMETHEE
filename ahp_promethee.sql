-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2020 at 08:57 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ahp_promethee`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE IF NOT EXISTS `tb_admin` (
  `user` varchar(16) NOT NULL,
  `pass` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`user`, `pass`) VALUES
('admin', 'admin'),
('user', '1user1');

-- --------------------------------------------------------

--
-- Table structure for table `tb_akses`
--

CREATE TABLE IF NOT EXISTS `tb_akses` (
`id` int(5) NOT NULL,
  `akses` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_akses`
--

INSERT INTO `tb_akses` (`id`, `akses`) VALUES
(1, 'Admin'),
(2, 'Kaprodi'),
(3, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `tb_alternatif`
--

CREATE TABLE IF NOT EXISTS `tb_alternatif` (
`id_alternatif` int(11) NOT NULL,
  `kode_alternatif` varchar(16) NOT NULL,
  `nama_alternatif` varchar(256) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_alternatif`
--

INSERT INTO `tb_alternatif` (`id_alternatif`, `kode_alternatif`, `nama_alternatif`, `keterangan`) VALUES
(1, 'A1', 'Standar 1', 'Visi & Misi'),
(2, 'A2', 'Standar 2', 'Tata Pamong'),
(3, 'A3', 'Standar 3', 'Mahasiswa & Lulusan'),
(4, 'A4', 'Standar 4', 'Sumber Daya Manusia'),
(5, 'A5', 'Standar 5', 'Akademik'),
(6, 'A6', 'Standar 6', 'Sarana & Prasarana'),
(7, 'A7', 'Standar 7', 'Penelitian & Pengabdian');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kriteria`
--

CREATE TABLE IF NOT EXISTS `tb_kriteria` (
`id_kriteria` int(11) NOT NULL,
  `kode_kriteria` varchar(16) NOT NULL,
  `nama_kriteria` varchar(255) NOT NULL,
  `bobot` decimal(10,2) NOT NULL,
  `minmax` varchar(16) NOT NULL,
  `tipe` varchar(16) NOT NULL,
  `par_q` double NOT NULL,
  `par_p` double NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_kriteria`
--

INSERT INTO `tb_kriteria` (`id_kriteria`, `kode_kriteria`, `nama_kriteria`, `bobot`, `minmax`, `tipe`, `par_q`, `par_p`) VALUES
(1, 'C1', 'KETERSEDIAAN DANA', '0.24', 'Maksimasi', '2', 1, 5),
(2, 'C2', 'KETERSEDIAAN SUMBER DAYA', '0.22', 'Maksimasi', '2', 1, 5),
(3, 'C3', 'RENTANG WAKTU PELAKSANAAN', '0.20', 'Maksimasi', '3', 5, 5),
(4, 'C4', 'BOBOT PENILAIAN BAN-PT', '0.10', 'Maksimasi', '3', 5, 5),
(9, 'C5', 'KELAYAKAN PENINGKATAN', '0.24', 'Maksimasi', '2', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tb_rel_alternatif`
--

CREATE TABLE IF NOT EXISTS `tb_rel_alternatif` (
`ID` int(11) NOT NULL,
  `id_alternatif` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `nilai` double NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_rel_alternatif`
--

INSERT INTO `tb_rel_alternatif` (`ID`, `id_alternatif`, `id_kriteria`, `nilai`) VALUES
(56, 4, 4, 0.219),
(52, 4, 3, 4),
(48, 4, 2, 3),
(55, 3, 4, 0.156),
(51, 3, 3, 5),
(47, 3, 2, 3),
(54, 2, 4, 0.062),
(50, 2, 3, 3),
(46, 2, 2, 4),
(53, 1, 4, 0.031),
(49, 1, 3, 4),
(45, 1, 2, 3),
(44, 4, 1, 3),
(43, 3, 1, 3),
(42, 2, 1, 4),
(41, 1, 1, 3),
(73, 5, 1, 4),
(74, 5, 2, 3),
(75, 5, 3, 4),
(76, 5, 4, 0.188),
(81, 6, 1, 3),
(82, 6, 2, 4),
(83, 6, 3, 4),
(84, 6, 4, 0.156),
(89, 7, 1, 3),
(90, 7, 2, 3),
(91, 7, 3, 3),
(92, 7, 4, 0.188),
(97, 1, 9, 3),
(98, 2, 9, 3),
(99, 3, 9, 4),
(100, 4, 9, 5),
(101, 5, 9, 4),
(102, 6, 9, 4),
(103, 7, 9, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tb_rel_kriteria`
--

CREATE TABLE IF NOT EXISTS `tb_rel_kriteria` (
`ID` int(11) NOT NULL,
  `ID1` varchar(16) DEFAULT NULL,
  `ID2` varchar(16) DEFAULT NULL,
  `nilai` double DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_rel_kriteria`
--

INSERT INTO `tb_rel_kriteria` (`ID`, `ID1`, `ID2`, `nilai`) VALUES
(2, '1', '1', 1),
(3, '2', '1', 1),
(4, '2', '2', 1),
(5, '1', '2', 1),
(6, '3', '1', 1),
(7, '3', '2', 2),
(8, '3', '3', 1),
(9, '1', '3', 1),
(10, '2', '3', 0.5),
(11, '4', '1', 0.25),
(12, '4', '2', 0.25),
(13, '4', '3', 1),
(14, '4', '4', 1),
(15, '1', '4', 4),
(16, '2', '4', 4),
(17, '3', '4', 1),
(66, '9', '1', 1),
(67, '9', '2', 1),
(68, '9', '3', 2),
(69, '9', '4', 2),
(70, '9', '9', 1),
(71, '1', '9', 1),
(72, '2', '9', 1),
(73, '3', '9', 0.5),
(74, '4', '9', 0.5);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE IF NOT EXISTS `tb_user` (
  `user` varchar(30) NOT NULL,
  `pass` varchar(30) NOT NULL,
  `akses` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`user`, `pass`, `akses`) VALUES
('admin', 'admin', 1),
('dafi', 'admin', 1),
('hasti', '123', 1),
('kaprodi', 'kaprodi', 2),
('ono', '123', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
 ADD PRIMARY KEY (`user`);

--
-- Indexes for table `tb_akses`
--
ALTER TABLE `tb_akses`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_alternatif`
--
ALTER TABLE `tb_alternatif`
 ADD KEY `id_alternatif` (`id_alternatif`);

--
-- Indexes for table `tb_kriteria`
--
ALTER TABLE `tb_kriteria`
 ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `tb_rel_alternatif`
--
ALTER TABLE `tb_rel_alternatif`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tb_rel_kriteria`
--
ALTER TABLE `tb_rel_kriteria`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
 ADD PRIMARY KEY (`user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_akses`
--
ALTER TABLE `tb_akses`
MODIFY `id` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tb_alternatif`
--
ALTER TABLE `tb_alternatif`
MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tb_kriteria`
--
ALTER TABLE `tb_kriteria`
MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tb_rel_alternatif`
--
ALTER TABLE `tb_rel_alternatif`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=104;
--
-- AUTO_INCREMENT for table `tb_rel_kriteria`
--
ALTER TABLE `tb_rel_kriteria`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=75;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
