-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 29, 2021 at 03:29 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fkundipl_logbook_koas`
--

-- --------------------------------------------------------

--
-- Table structure for table `dummy_evaluasi_stase`
--

CREATE TABLE `dummy_evaluasi_stase` (
  `id` int(10) NOT NULL,
  `nim` varchar(15) DEFAULT NULL,
  `stase` varchar(4) DEFAULT NULL,
  `tgl_isi` date DEFAULT NULL,
  `input_111` int(1) DEFAULT NULL,
  `input_112` int(1) DEFAULT NULL,
  `input_113` int(1) DEFAULT NULL,
  `input_114` int(1) DEFAULT NULL,
  `input_115` int(1) DEFAULT NULL,
  `input_116` int(1) DEFAULT NULL,
  `input_117` int(1) DEFAULT NULL,
  `input_118` int(1) DEFAULT NULL,
  `input_119` int(1) DEFAULT NULL,
  `input_1110` int(1) DEFAULT NULL,
  `input_1111` int(1) DEFAULT NULL,
  `input_1112` int(1) DEFAULT NULL,
  `input_1113` int(1) DEFAULT NULL,
  `input_1114` int(1) DEFAULT NULL,
  `refleksi` varchar(250) DEFAULT NULL,
  `komentar` varchar(250) DEFAULT NULL,
  `input_12` int(1) DEFAULT NULL,
  `input_13` int(1) DEFAULT NULL,
  `dosen1` varchar(40) DEFAULT NULL,
  `tatap_muka1` int(2) DEFAULT NULL,
  `input_21A1` int(1) DEFAULT NULL,
  `input_21A2` int(1) DEFAULT NULL,
  `input_21A3` int(1) DEFAULT NULL,
  `input_21A4` int(1) DEFAULT NULL,
  `input_21A5` int(1) DEFAULT NULL,
  `input_21A6` int(1) DEFAULT NULL,
  `input_21A7` int(1) DEFAULT NULL,
  `input_21B1` int(1) DEFAULT NULL,
  `input_21B2` int(1) DEFAULT NULL,
  `input_21B3` int(1) DEFAULT NULL,
  `input_21B4` int(1) DEFAULT NULL,
  `input_21B5` int(1) DEFAULT NULL,
  `input_21B6` int(1) DEFAULT NULL,
  `input_21B7` int(1) DEFAULT NULL,
  `input_21B8` int(1) DEFAULT NULL,
  `input_21B9` int(1) DEFAULT NULL,
  `input_21B10` int(1) DEFAULT NULL,
  `input_21B11` int(1) DEFAULT NULL,
  `input_21B12` int(1) DEFAULT NULL,
  `input_21C1` int(1) DEFAULT NULL,
  `input_21C2` int(1) DEFAULT NULL,
  `input_21C3` int(1) DEFAULT NULL,
  `komentar_dosen1` varchar(250) DEFAULT NULL,
  `dosen2` varchar(40) DEFAULT NULL,
  `tatap_muka2` int(2) DEFAULT NULL,
  `input_22A1` int(1) DEFAULT NULL,
  `input_22A2` int(1) DEFAULT NULL,
  `input_22A3` int(1) DEFAULT NULL,
  `input_22A4` int(1) DEFAULT NULL,
  `input_22A5` int(1) DEFAULT NULL,
  `input_22A6` int(1) DEFAULT NULL,
  `input_22A7` int(1) DEFAULT NULL,
  `input_22B1` int(1) DEFAULT NULL,
  `input_22B2` int(1) DEFAULT NULL,
  `input_22B3` int(1) DEFAULT NULL,
  `input_22B4` int(1) DEFAULT NULL,
  `input_22B5` int(1) DEFAULT NULL,
  `input_22B6` int(1) DEFAULT NULL,
  `input_22B7` int(1) DEFAULT NULL,
  `input_22B8` int(1) DEFAULT NULL,
  `input_22B9` int(1) DEFAULT NULL,
  `input_22B10` int(1) DEFAULT NULL,
  `input_22B11` int(1) DEFAULT NULL,
  `input_22B12` int(1) DEFAULT NULL,
  `input_22C1` int(1) DEFAULT NULL,
  `input_22C2` int(1) DEFAULT NULL,
  `input_22C3` int(1) DEFAULT NULL,
  `komentar_dosen2` varchar(250) DEFAULT NULL,
  `dosen3` varchar(40) DEFAULT NULL,
  `tatap_muka3` int(2) DEFAULT NULL,
  `input_23A1` int(1) DEFAULT NULL,
  `input_23A2` int(1) DEFAULT NULL,
  `input_23A3` int(1) DEFAULT NULL,
  `input_23A4` int(1) DEFAULT NULL,
  `input_23A5` int(1) DEFAULT NULL,
  `input_23A6` int(1) DEFAULT NULL,
  `input_23A7` int(1) DEFAULT NULL,
  `input_23B1` int(1) DEFAULT NULL,
  `input_23B2` int(1) DEFAULT NULL,
  `input_23B3` int(1) DEFAULT NULL,
  `input_23B4` int(1) DEFAULT NULL,
  `input_23B5` int(1) DEFAULT NULL,
  `input_23B6` int(1) DEFAULT NULL,
  `input_23B7` int(1) DEFAULT NULL,
  `input_23B8` int(1) DEFAULT NULL,
  `input_23B9` int(1) DEFAULT NULL,
  `input_23B10` int(1) DEFAULT NULL,
  `input_23B11` int(1) DEFAULT NULL,
  `input_23B12` int(1) DEFAULT NULL,
  `input_23C1` int(1) DEFAULT NULL,
  `input_23C2` int(1) DEFAULT NULL,
  `input_23C3` int(1) DEFAULT NULL,
  `komentar_dosen3` varchar(250) DEFAULT NULL,
  `lokasi_luar` varchar(150) DEFAULT NULL,
  `lama_luar` int(1) DEFAULT NULL,
  `input_3A1` int(1) DEFAULT NULL,
  `input_3A2` int(1) DEFAULT NULL,
  `input_3A3` int(1) DEFAULT NULL,
  `input_3A4` int(1) DEFAULT NULL,
  `input_3A5` int(1) DEFAULT NULL,
  `input_3A6` int(1) DEFAULT NULL,
  `input_3A7` int(1) DEFAULT NULL,
  `input_3A8` int(1) DEFAULT NULL,
  `input_3A9` int(1) DEFAULT NULL,
  `input_3A10` int(1) DEFAULT NULL,
  `like_luar` varchar(250) DEFAULT NULL,
  `unlike_luar` varchar(250) DEFAULT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dummy_evaluasi_stase`
--
ALTER TABLE `dummy_evaluasi_stase`
  ADD KEY `nim` (`nim`),
  ADD KEY `stase` (`stase`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
