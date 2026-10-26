-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 01, 2026 at 01:51 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_sunscreen2`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) DEFAULT 'Administrator'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama`) VALUES
(1, 'admin', '$2y$10$SYRPuyCuETiuyacVvoeR0OmQcuGX7H8kc1sYKz4yervllZLuRtXYS', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `hasil_rekomendasi`
--

CREATE TABLE `hasil_rekomendasi` (
  `id` int NOT NULL,
  `konsultasi_id` int NOT NULL,
  `sunscreen_id` int NOT NULL,
  `nilai_akhir` decimal(10,5) NOT NULL,
  `ranking` int NOT NULL,
  `alasan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hasil_rekomendasi`
--

INSERT INTO `hasil_rekomendasi` (`id`, `konsultasi_id`, `sunscreen_id`, `nilai_akhir`, `ranking`, `alasan`) VALUES
(1, 1, 1, 1.00000, 1, 'Termasuk physical (mineral) sunscreen dengan risiko iritasi lebih rendah, cocok untuk kulit sensitif. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(2, 1, 5, 0.88272, 2, 'Termasuk physical (mineral) sunscreen dengan risiko iritasi lebih rendah, cocok untuk kulit sensitif. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(3, 2, 1, 0.83485, 1, 'Cocok digunakan untuk kulit normal. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(4, 2, 7, 0.68812, 2, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(5, 2, 3, 0.66182, 3, 'Cocok digunakan untuk kulit normal. Perlindungan UVA sangat baik (PA+++). Bebas salah satu dari fragrance atau alkohol. Tahan air, cocok untuk aktivitas outdoor.'),
(6, 2, 8, 0.65829, 4, 'Cocok digunakan untuk kulit normal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic.'),
(7, 2, 2, 0.63634, 5, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(8, 2, 5, 0.61464, 6, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(9, 2, 6, 0.40491, 7, 'Cocok digunakan untuk kulit normal. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(10, 2, 4, 0.37227, 8, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(11, 3, 10, 1.00000, 1, 'SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(12, 3, 7, 0.80410, 2, 'Formula physical, nyaman digunakan untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(13, 3, 4, 0.66896, 3, 'Formula hybrid, memberikan kelembapan dan kenyamanan ekstra untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(14, 3, 15, 0.63545, 4, 'SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas salah satu dari fragrance atau alkohol. Tahan air, cocok untuk aktivitas outdoor.'),
(15, 3, 14, 0.60998, 5, 'Formula physical, nyaman digunakan untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(16, 3, 6, 0.60342, 6, 'Formula physical, nyaman digunakan untuk kulit kering. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(17, 3, 2, 0.52758, 7, 'Formula hybrid, memberikan kelembapan dan kenyamanan ekstra untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(18, 3, 3, 0.52545, 8, 'Formula physical, nyaman digunakan untuk kulit kering. Perlindungan UVA sangat baik (PA+++). Bebas salah satu dari fragrance atau alkohol. Tahan air, cocok untuk aktivitas outdoor.'),
(19, 3, 11, 0.52033, 9, 'Formula hybrid, memberikan kelembapan dan kenyamanan ekstra untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(20, 3, 12, 0.51179, 10, 'Formula physical, nyaman digunakan untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas salah satu dari fragrance atau alkohol. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(21, 3, 16, 0.50976, 11, 'SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak.'),
(22, 3, 8, 0.50147, 12, 'Formula physical, nyaman digunakan untuk kulit kering. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic.'),
(23, 3, 1, 0.49089, 13, 'Formula physical, nyaman digunakan untuk kulit kering. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(24, 3, 5, 0.48870, 14, 'Formula physical, nyaman digunakan untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(25, 3, 13, 0.42310, 15, 'Formula hybrid, memberikan kelembapan dan kenyamanan ekstra untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak.'),
(26, 4, 10, 1.00000, 1, 'SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(27, 4, 7, 0.80410, 2, 'Formula physical, nyaman digunakan untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(28, 4, 4, 0.66896, 3, 'Formula hybrid, memberikan kelembapan dan kenyamanan ekstra untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(29, 4, 15, 0.63545, 4, 'SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas salah satu dari fragrance atau alkohol. Tahan air, cocok untuk aktivitas outdoor.'),
(30, 4, 14, 0.60998, 5, 'Formula physical, nyaman digunakan untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(31, 4, 6, 0.60342, 6, 'Formula physical, nyaman digunakan untuk kulit kering. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(32, 4, 2, 0.52758, 7, 'Formula hybrid, memberikan kelembapan dan kenyamanan ekstra untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(33, 4, 3, 0.52545, 8, 'Formula physical, nyaman digunakan untuk kulit kering. Perlindungan UVA sangat baik (PA+++). Bebas salah satu dari fragrance atau alkohol. Tahan air, cocok untuk aktivitas outdoor.'),
(34, 4, 11, 0.52033, 9, 'Formula hybrid, memberikan kelembapan dan kenyamanan ekstra untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(35, 4, 12, 0.51179, 10, 'Formula physical, nyaman digunakan untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas salah satu dari fragrance atau alkohol. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(36, 4, 16, 0.50976, 11, 'SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak.'),
(37, 4, 8, 0.50147, 12, 'Formula physical, nyaman digunakan untuk kulit kering. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic.'),
(38, 4, 1, 0.49089, 13, 'Formula physical, nyaman digunakan untuk kulit kering. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(39, 4, 5, 0.48870, 14, 'Formula physical, nyaman digunakan untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(40, 4, 13, 0.42310, 15, 'Formula hybrid, memberikan kelembapan dan kenyamanan ekstra untuk kulit kering. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak.'),
(41, 5, 10, 1.00000, 1, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(42, 5, 7, 0.80410, 2, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(43, 5, 4, 0.66896, 3, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(44, 5, 15, 0.63545, 4, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas salah satu dari fragrance atau alkohol. Tahan air, cocok untuk aktivitas outdoor.'),
(45, 5, 14, 0.60998, 5, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(46, 5, 6, 0.60342, 6, 'Cocok digunakan untuk kulit normal. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(47, 5, 2, 0.52758, 7, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(48, 5, 3, 0.52545, 8, 'Cocok digunakan untuk kulit normal. Perlindungan UVA sangat baik (PA+++). Bebas salah satu dari fragrance atau alkohol. Tahan air, cocok untuk aktivitas outdoor.'),
(49, 5, 11, 0.52033, 9, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(50, 5, 12, 0.51179, 10, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas salah satu dari fragrance atau alkohol. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(51, 5, 16, 0.50976, 11, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak.'),
(52, 5, 8, 0.50147, 12, 'Cocok digunakan untuk kulit normal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic.'),
(53, 5, 1, 0.49089, 13, 'Cocok digunakan untuk kulit normal. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(54, 5, 5, 0.48870, 14, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(55, 5, 13, 0.42310, 15, 'Cocok digunakan untuk kulit normal. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak.'),
(56, 6, 10, 1.00000, 1, 'SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(57, 6, 7, 0.80410, 2, 'Termasuk physical (mineral) sunscreen dengan risiko iritasi lebih rendah, cocok untuk kulit sensitif. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(58, 6, 4, 0.66896, 3, 'SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(59, 6, 15, 0.63545, 4, 'SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas salah satu dari fragrance atau alkohol. Tahan air, cocok untuk aktivitas outdoor.'),
(60, 6, 14, 0.60998, 5, 'Termasuk physical (mineral) sunscreen dengan risiko iritasi lebih rendah, cocok untuk kulit sensitif. SPF tinggi (50) untuk proteksi optimal. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(61, 6, 6, 0.60342, 6, 'Termasuk physical (mineral) sunscreen dengan risiko iritasi lebih rendah, cocok untuk kulit sensitif. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(62, 6, 2, 0.52758, 7, 'SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(63, 6, 3, 0.52545, 8, 'Termasuk physical (mineral) sunscreen dengan risiko iritasi lebih rendah, cocok untuk kulit sensitif. Perlindungan UVA sangat baik (PA+++). Bebas salah satu dari fragrance atau alkohol. Tahan air, cocok untuk aktivitas outdoor.'),
(64, 6, 11, 0.52033, 9, 'SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(65, 6, 12, 0.51179, 10, 'Termasuk physical (mineral) sunscreen dengan risiko iritasi lebih rendah, cocok untuk kulit sensitif. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas salah satu dari fragrance atau alkohol. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(66, 6, 16, 0.50976, 11, 'SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak.'),
(67, 6, 8, 0.50147, 12, 'Termasuk physical (mineral) sunscreen dengan risiko iritasi lebih rendah, cocok untuk kulit sensitif. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic.'),
(68, 6, 1, 0.49089, 13, 'Termasuk physical (mineral) sunscreen dengan risiko iritasi lebih rendah, cocok untuk kulit sensitif. Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(69, 6, 5, 0.48870, 14, 'Termasuk physical (mineral) sunscreen dengan risiko iritasi lebih rendah, cocok untuk kulit sensitif. SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA++++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak. Sudah teruji hypoallergenic. Tahan air, cocok untuk aktivitas outdoor.'),
(70, 6, 13, 0.42310, 15, 'SPF tinggi (50) untuk proteksi optimal. Perlindungan UVA sangat baik (PA+++). Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak.');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_kulit`
--

CREATE TABLE `jenis_kulit` (
  `id` int NOT NULL,
  `nama_jenis_kulit` varchar(50) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `prioritas_sunscreen` varchar(50) NOT NULL,
  `deskripsi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jenis_kulit`
--

INSERT INTO `jenis_kulit` (`id`, `nama_jenis_kulit`, `kode`, `prioritas_sunscreen`, `deskripsi`) VALUES
(1, 'Sensitif', 'sensitif', 'Physical', 'Mengandung zinc oxide atau titanium dioxide dengan risiko iritasi dan alergi kontak yang lebih rendah.'),
(2, 'Normal', 'normal', 'Physical,Chemical,Hybrid', 'Tidak memiliki kebutuhan perlindungan khusus sehingga dapat menggunakan berbagai jenis sunscreen sesuai formulasi produk.'),
(3, 'Kering', 'kering', 'Physical,Hybrid', 'Dapat dipilih sesuai formulasi produk yang memberikan kelembapan dan kenyamanan pada kulit.'),
(5, 'Berminyak', 'berminyak', 'Chemical,Hybrid', 'Umumnya memiliki tekstur yang lebih ringan.');

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id` int NOT NULL,
  `nama_anak` varchar(100) NOT NULL,
  `umur_anak` int NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `jenis_kulit_id` int NOT NULL,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `konsultasi`
--

INSERT INTO `konsultasi` (`id`, `nama_anak`, `umur_anak`, `jenis_kelamin`, `jenis_kulit_id`, `tanggal`) VALUES
(1, 'Jil', 3, 'Laki-laki', 1, '2026-06-15 09:24:06'),
(2, 'bela', 4, 'Perempuan', 2, '2026-06-16 04:53:50'),
(3, 'ipin', 5, 'Laki-laki', 3, '2026-07-11 14:15:58'),
(4, 'upin', 5, 'Laki-laki', 3, '2026-07-12 02:12:04'),
(5, 'apin', 5, 'Perempuan', 2, '2026-07-13 02:02:00'),
(6, 'Rey', 8, 'Laki-laki', 1, '2026-07-13 05:26:49');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id` int NOT NULL,
  `kode` varchar(5) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `tipe` enum('benefit','cost') NOT NULL,
  `bobot` decimal(5,3) DEFAULT '0.111',
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id`, `kode`, `nama_kriteria`, `tipe`, `bobot`, `keterangan`) VALUES
(2, 'C1', 'SPF', 'benefit', 0.150, 'Semakin tinggi nilai SPF, semakin baik perlindungan dari sinar UVB'),
(3, 'C2', 'PA', 'benefit', 0.145, 'Tingkat proteksi dari sinar UVA (PA+ s.d PA++++)'),
(4, 'C3', 'Kandungan', 'benefit', 0.144, 'Kandungan pewangi. Tidak mengandung fragrance lebih baik, terutama kulit sensitif'),
(6, 'C4', 'Hypoallergenic', 'benefit', 0.158, 'Produk teruji hypoallergenic lebih aman untuk kulit anak'),
(7, 'C5', 'Water Resistant', 'benefit', 0.140, 'Produk tahan air lebih baik untuk aktivitas outdoor anak'),
(8, 'C6', 'Harga', 'cost', 0.133, 'Semakin rendah harga, semakin baik (efisiensi biaya)'),
(9, 'C7', 'Ukuran', 'benefit', 0.130, 'Ukuran kemasan (ml), semakin besar dianggap lebih ekonomis');

-- --------------------------------------------------------

--
-- Table structure for table `sunscreen`
--

CREATE TABLE `sunscreen` (
  `id` int NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `merek` varchar(100) NOT NULL,
  `jenis_sunscreen` enum('Physical','Chemical','Hybrid') NOT NULL,
  `spf` int NOT NULL,
  `pa` varchar(10) NOT NULL,
  `kandungan_fa` varchar(30) NOT NULL DEFAULT 'bebas_semua',
  `hypoallergenic` enum('Ya','Tidak') NOT NULL DEFAULT 'Ya',
  `water_resistant` enum('Ya','Tidak') NOT NULL DEFAULT 'Tidak',
  `harga` decimal(12,2) NOT NULL,
  `ukuran` int NOT NULL,
  `foto` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sunscreen`
--

INSERT INTO `sunscreen` (`id`, `nama_produk`, `merek`, `jenis_sunscreen`, `spf`, `pa`, `kandungan_fa`, `hypoallergenic`, `water_resistant`, `harga`, `ukuran`, `foto`, `created_at`) VALUES
(1, 'Sunplay Baby Mild Sunscreen Lotion ', 'Sunplay', 'Physical', 39, 'PA++', 'bebas_semua', 'Ya', 'Ya', 82488.00, 30, 'default.png', '2026-06-15 09:14:51'),
(2, 'Bebiotic Hydra Barrier Sunscreen', 'Bebiotic', 'Hybrid', 50, 'PA++++', 'bebas_semua', 'Ya', 'Ya', 71250.00, 45, 'default.png', '2026-06-15 09:14:51'),
(3, 'Buds Organics Solar Care Lotion', 'Buds', 'Physical', 25, 'PA+++', 'bebas_sebagian', 'Tidak', 'Ya', 427410.00, 75, 'default.png', '2026-06-15 09:14:51'),
(4, 'Mustela High Protection Sun Lotion', 'Mustela', 'Hybrid', 50, 'PA+++', 'bebas_semua', 'Ya', 'Ya', 288900.00, 40, 'default.png', '2026-06-15 09:14:51'),
(5, 'Moell Physical Sunscreen Anak & Bayi', 'Moell', 'Physical', 50, 'PA++++', 'bebas_semua', 'Ya', 'Ya', 70000.00, 30, 'default.png', '2026-06-15 09:14:51'),
(6, 'Mama\'s Choice Baby Gentle Sunscreen', 'Mama\'s Choice', 'Physical', 30, 'PA++', 'bebas_semua', 'Ya', 'Ya', 65000.00, 100, 'default.png', '2026-06-15 09:14:51'),
(7, 'Banana Boat Kids Sensitive Sunscreen Lotion', 'Banana Boat', 'Physical', 50, 'PA++++', 'bebas_semua', 'Ya', 'Ya', 142004.00, 90, 'default.png', '2026-06-15 09:14:51'),
(8, 'PURE BABY Sunblock', 'Pure Baby', 'Physical', 25, 'PA++++', 'bebas_semua', 'Ya', 'Tidak', 125125.00, 50, 'default.png', '2026-06-15 09:14:51'),
(10, 'Bioderma Photoderm Kid', 'Bioderma', 'Chemical', 50, 'PA++++', 'bebas_semua', 'Ya', 'Ya', 314806.00, 100, 'default.png', '2026-07-11 14:00:30'),
(11, 'Mo Ma Mi Sun Stick', 'Mo Ma Mi', 'Hybrid', 50, 'PA+++', 'bebas_semua', 'Ya', 'Ya', 92355.00, 15, 'default.png', '2026-07-11 14:01:56'),
(12, 'Expert Care Light Physical Sunscreen Anak & Bayi', 'Expert Care', 'Physical', 50, 'PA++++', 'bebas_sebagian', 'Ya', 'Ya', 99900.00, 30, 'default.png', '2026-07-11 14:03:08'),
(13, 'Beeme Honey Sunscreen Lotion with Niacinamide', 'Beeme', 'Hybrid', 50, 'PA+++', 'bebas_semua', 'Tidak', 'Tidak', 159900.00, 30, 'default.png', '2026-07-11 14:05:32'),
(14, 'Bambi Baby Sunscreen Lotion', 'Bambi', 'Physical', 50, 'PA++', 'bebas_semua', 'Ya', 'Ya', 129300.00, 50, 'default.png', '2026-07-11 14:07:27'),
(15, 'NIVEA Sun Kids Sensitive Spray SPF 50', 'NIVEA', 'Chemical', 50, 'PA+++', 'bebas_sebagian', 'Tidak', 'Ya', 266600.00, 200, 'default.png', '2026-07-11 14:08:37'),
(16, 'PYUNKANG YUL Kids & Baby Sun Cream', 'PYUNKANG YUL', 'Chemical', 50, 'PA++++', 'bebas_semua', 'Tidak', 'Tidak', 182400.00, 75, 'default.png', '2026-07-11 14:09:39');

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `hasil_rekomendasi`
--
ALTER TABLE `hasil_rekomendasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `konsultasi_id` (`konsultasi_id`),
  ADD KEY `sunscreen_id` (`sunscreen_id`);

--
-- Indexes for table `jenis_kulit`
--
ALTER TABLE `jenis_kulit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jenis_kulit_id` (`jenis_kulit_id`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sunscreen`
--
ALTER TABLE `sunscreen`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hasil_rekomendasi`
--
ALTER TABLE `hasil_rekomendasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `jenis_kulit`
--
ALTER TABLE `jenis_kulit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sunscreen`
--
ALTER TABLE `sunscreen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hasil_rekomendasi`
--
ALTER TABLE `hasil_rekomendasi`
  ADD CONSTRAINT `hasil_rekomendasi_ibfk_1` FOREIGN KEY (`konsultasi_id`) REFERENCES `konsultasi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_rekomendasi_ibfk_2` FOREIGN KEY (`sunscreen_id`) REFERENCES `sunscreen` (`id`);

--
-- Constraints for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD CONSTRAINT `konsultasi_ibfk_2` FOREIGN KEY (`jenis_kulit_id`) REFERENCES `jenis_kulit` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
