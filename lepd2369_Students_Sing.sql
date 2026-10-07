-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 07, 2026 at 03:43 PM
-- Server version: 10.11.19-MariaDB-cll-lve
-- PHP Version: 8.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lepd2369_Students_Sing`
--

-- --------------------------------------------------------

--
-- Table structure for table `daftarasis`
--

CREATE TABLE `daftarasis` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `pass` varchar(20) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `nohp` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` enum('superadmin','admin_kursus','editor') NOT NULL DEFAULT 'admin_kursus',
  `kursus_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daftarasis`
--

INSERT INTO `daftarasis` (`id`, `username`, `pass`, `nama`, `nohp`, `email`, `role`, `kursus_id`, `created_at`) VALUES
(1, 'var', 'ram', 'Varrel Tampan', '08781549099', 'iwanivan@gmail.com', 'admin_kursus', 1, '2026-08-14 07:14:23'),
(2, 'superadmin', 'admin123', 'Super Admin', '08123456789', 'superadmin@gmail.com', 'superadmin', NULL, '2026-09-25 02:22:35'),
(3, 'admin_lppc', 'lppc123', 'Admin LPPC', '08123456788', 'lppc@gmail.com', 'admin_kursus', 1, '2026-09-25 02:22:35'),
(4, 'admin_mm', 'mm123', 'Admin Multimedia', '08123456787', 'multimedia@gmail.com', 'admin_kursus', 2, '2026-09-25 02:22:35');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `slug`, `title`, `description`, `created_at`) VALUES
(1, 'leptek', 'Pendaftaran LEPTEK', 'Form pendaftaran LEPTEK.', '2026-08-14 15:45:01'),
(2, 'lppc', 'Pendaftaran LPPC', 'Form pendaftaran LPPC.', '2026-08-14 15:45:01');

-- --------------------------------------------------------

--
-- Table structure for table `form_fields`
--

CREATE TABLE `form_fields` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `required` tinyint(1) DEFAULT 0,
  `options` text DEFAULT NULL,
  `validation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`validation`)),
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_fields`
--

INSERT INTO `form_fields` (`id`, `form_id`, `name`, `label`, `type`, `required`, `options`, `validation`, `sort_order`) VALUES
(1, 1, 'nama', 'Nama', 'text', 1, NULL, NULL, 1),
(2, 1, 'npm', 'NPM', 'text', 1, NULL, '{\"pattern\":\"^[0-9]{8}$\"}', 2),
(3, 1, 'kelas', 'Kelas', 'text', 0, NULL, NULL, 3),
(4, 1, 'email', 'Email', 'email', 1, NULL, NULL, 4),
(5, 1, 'nohp', 'No.HP', 'text', 1, NULL, '{\"pattern\":\"^[0-9]{10,13}$\"}', 5),
(6, 2, 'nama', 'Nama', 'text', 1, NULL, NULL, 1),
(7, 2, 'npm', 'NPM', 'text', 1, NULL, '{\"pattern\":\"^[0-9]{8}$\"}', 2),
(8, 2, 'kelas', 'Kelas', 'text', 0, NULL, NULL, 3),
(9, 2, 'email', 'Email', 'email', 1, NULL, NULL, 4),
(10, 2, 'nohp', 'No.HP', 'text', 1, NULL, '{\"pattern\":\"^[0-9]{10,13}$\"}', 5);

-- --------------------------------------------------------

--
-- Table structure for table `form_submissions`
--

CREATE TABLE `form_submissions` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int(11) NOT NULL,
  `nama_jurusan` varchar(25) NOT NULL,
  `program_jurusan` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `nama_jurusan`, `program_jurusan`) VALUES
(1, 'Sistem Informasi', 'S1'),
(2, 'Teknologi Industri', 'S1'),
(3, 'Manajemen Industri', 'S1');

-- --------------------------------------------------------

--
-- Table structure for table `kursus`
--

CREATE TABLE `kursus` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `slug` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kursus`
--

INSERT INTO `kursus` (`id`, `nama`, `slug`) VALUES
(1, 'Citra', 'lppc'),
(2, 'Multimedia', 'multimedia');

-- --------------------------------------------------------

--
-- Table structure for table `page_content`
--

CREATE TABLE `page_content` (
  `id` int(11) NOT NULL,
  `page_name` varchar(50) NOT NULL DEFAULT 'landing_page',
  `html_content` longtext DEFAULT NULL,
  `css_content` longtext DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page_content`
--

INSERT INTO `page_content` (`id`, `page_name`, `html_content`, `css_content`, `updated_at`) VALUES
(1, 'landing_page', '<h1>Selamat Datang di Web Kami</h1>', 'h1 { text-align: center; color: #fff; }', '2026-09-25 02:44:28');

-- --------------------------------------------------------

--
-- Table structure for table `pendaftar`
--

CREATE TABLE `pendaftar` (
  `id` int(11) NOT NULL,
  `npm` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `nohp` varchar(50) NOT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp(),
  `waktu_daftar` time NOT NULL DEFAULT curtime(),
  `kursus_id` int(11) DEFAULT NULL,
  `id_jurusan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendaftar`
--

INSERT INTO `pendaftar` (`id`, `npm`, `nama`, `kelas`, `email`, `nohp`, `tanggal_daftar`, `waktu_daftar`, `kursus_id`, `id_jurusan`) VALUES
(1, '34523425', 'MUEHEHEE', '8UA02', 'lppcasisten.bot@gmail.com', '0875456565656', '2026-10-05 15:36:02', '22:36:02', 1, 1),
(2, '10126789', 'Muhammad Abduh', '1KA09', 'abduh12@gmail.com', '0813456987', '2026-10-05 15:36:04', '22:36:04', 1, 1),
(3, '50426794', 'Aryu Igoy', '1IA09', 'igoy@gmail.com', '083458769043', '2026-10-06 09:41:26', '16:41:26', 2, 2),
(4, '31326789', 'Urban Denny', '1DB07', 'UD@gmail.com', '0892345789', '2026-10-06 10:29:59', '17:29:59', 2, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daftarasis`
--
ALTER TABLE `daftarasis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `form_fields`
--
ALTER TABLE `form_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `form_submissions`
--
ALTER TABLE `form_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indexes for table `kursus`
--
ALTER TABLE `kursus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `page_content`
--
ALTER TABLE `page_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pendaftar`
--
ALTER TABLE `pendaftar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `npm` (`npm`),
  ADD KEY `fk_pendaftar_kursus` (`kursus_id`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daftarasis`
--
ALTER TABLE `daftarasis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `form_fields`
--
ALTER TABLE `form_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `form_submissions`
--
ALTER TABLE `form_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kursus`
--
ALTER TABLE `kursus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `page_content`
--
ALTER TABLE `page_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pendaftar`
--
ALTER TABLE `pendaftar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `form_fields`
--
ALTER TABLE `form_fields`
  ADD CONSTRAINT `form_fields_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `form_submissions`
--
ALTER TABLE `form_submissions`
  ADD CONSTRAINT `form_submissions_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pendaftar`
--
ALTER TABLE `pendaftar`
  ADD CONSTRAINT `fk_pendaftar_jurusan` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id_jurusan`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pendaftar_kursus` FOREIGN KEY (`kursus_id`) REFERENCES `kursus` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
