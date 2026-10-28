-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 15, 2026 at 06:58 PM
-- Server version: 10.11.19-MariaDB-cll-lve
-- PHP Version: 8.4.26

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
(2, 'superadmin', '!dewaadmin560_CV#', 'Super Admin', '08123456789', 'superadmin@gmail.com', 'superadmin', NULL, '2026-09-25 02:22:35'),
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
(5, '10126548', 'Rianthy Hasnah Mumtaziyah', '1KA04', 'adelliacans2005@gmail.com', '081977240070', '2026-10-07 13:12:39', '20:12:39', 1, 1),
(6, '10126540', 'Reva Jovankaviva', '1KA02', 'revajovanka730@gmail.com', '081949938337', '2026-10-08 03:20:17', '10:20:17', 2, 1),
(7, '10126400', 'Muhammad Galih Saputra', '1KA11', 'galihsaputra0310@gmail.com', '081382510838', '2026-10-08 03:43:42', '10:43:42', 1, 1),
(8, '10126742', 'Alya Zafira', '1KA04', 'zafeera953@gmail.com', '085772153481', '2026-10-08 04:49:44', '11:49:44', 2, 1),
(9, '10126078', 'Ashyla Nailah Alifah', '1ka02', 'ashylanailah@gmail.com', '082318227382', '2026-10-08 07:30:36', '14:30:36', 2, 1),
(10, '10126597', 'Steven Emmanuel', '1KA20', 'nevetsemmanuel@gmail.com', '0881011302104', '2026-10-08 14:02:30', '21:02:30', 1, 1),
(11, '10126052', 'Andhika Nurriksa Pradja', '1KA21', 'andhikanurriksanurriksa@gmail.com', '089696346601', '2026-10-08 14:57:14', '21:57:14', 1, 1),
(12, '10126376', 'Muhammad Aldhi Pratama', '1KA11', 'aldhi.509@gmail.com', '089691336983', '2026-10-09 02:25:13', '09:25:13', 2, 1),
(13, '10126257', 'Hans Blic Panusunan Silitonga', '1KA11', 'hannss0001@gmail.com', '082144951633', '2026-10-09 02:48:25', '09:48:25', 2, 1),
(14, '10126070', 'Arliana Zipfhara', '1KA13', 'zipfhara@gmail.com', '087868958804', '2026-10-09 05:28:19', '12:28:19', 2, 1),
(15, '10126333', 'M EDWAR REIFAN', '1KA09', 'MUHAMMADEDWAR302@GMAIL.COM', '081260171005', '2026-10-09 05:55:48', '12:55:48', 2, 1),
(16, '10126512', 'Raihan Hermayra', '1KA05', 'raihanhermayra@gmail.com', '085776020775', '2026-10-09 11:16:14', '18:16:14', 2, 1),
(17, '50426246', 'Dimas', '1IA06', 'dimasadrnmhswra@gmail.com', '083836743415', '2026-10-09 14:13:24', '21:13:24', 2, 2),
(18, '10126335', 'M. Zachran Rasya Supriyadi', '1KA11', 'zachranrasya07@gmail.com', '081377906538', '2026-10-09 16:07:08', '23:07:08', 2, 1),
(19, '10126158', 'Doddi Kurniawan', '1KA06', 'alveriaz@gmail.com', '08973646462', '2026-10-09 18:49:39', '01:49:39', 2, 1),
(20, '10126732', 'Ahmad Rafi Ramadhan', '1KA11', 'rafiramadhan0927@gmail.com', '085777223640', '2026-10-10 03:56:22', '10:56:22', 2, 1),
(21, '10126602', 'Syahwa Nurinisa', '1KA06', 'nisasyahwa8@gmail.com', '081541102632', '2026-10-10 04:01:52', '11:01:52', 1, 1),
(22, '10126185', 'Fadila Fitri Nuraini Al gaus', '1KA06', 'algausfadilafitri@gmail.com', '082118316759', '2026-10-10 04:08:42', '11:08:42', 2, 1),
(23, '10126488', 'Purnama Raya Zakir', '1ka06', 'rayazakir8@gmail.com', '082298693623', '2026-10-10 04:30:10', '11:30:10', 2, 1),
(25, '10126222', 'Fazli Satrio Widodo', '1KA11', 'fazly.satrio.widodo1@gmail.com', '081290830402', '2026-10-10 05:42:59', '12:42:59', 2, 1),
(26, '10126144', 'Dhafin Najwan', '1KA11', 'dhafinnajwan2@gmail.com', '081310406794', '2026-10-10 05:43:10', '12:43:10', 2, 1),
(27, '10126470', 'Neomy Naysilla Rachman', '1KA06', 'neomyrachman@gmail.com', '083818337091', '2026-10-10 07:18:42', '14:18:42', 2, 1),
(28, '10126323', 'KEYTA NASYA AULIANI', '1KA06', 'keytanasyaauliani@gmail.com', '081295123063', '2026-10-10 07:27:55', '14:27:55', 2, 1),
(29, '10126420', 'Muhammad Raihan', '1KA08', 'm.raihan1011@gmail.com', '081946071011', '2026-10-10 08:24:08', '15:24:08', 1, 1),
(30, '50426011', 'Abrisam Zaki Kautsar', '1IA03', 'not.dzaky@gmail.com', '085763650264', '2026-10-10 08:27:27', '15:27:27', 2, 2),
(31, '50426882', 'Sandy Faiz Hutomo', '1IA03', 'sandyfaizh@gmail.com', '08111399249', '2026-10-10 10:43:51', '17:43:51', 1, 2),
(32, '10126544', 'Revandio Haidary Noor Nababan', '1KA04', 'revandionababan@gmail.com', '081386013126', '2026-10-11 04:03:07', '11:03:07', 2, 1),
(33, '50426436', 'James Cahyadi', '1IA02', 'jamescahyadi651@gmail.com', '089617907373', '2026-10-14 07:07:37', '14:07:37', 1, 1),
(34, '50426208', 'DAFFA HAUZAN RACHMADI', '1IA02', 'daferahehe@gmail.com', '082121294919', '2026-10-14 07:08:18', '14:08:18', 1, 2),
(35, '10126272', 'I Gede Aditya Wikrama Wardana', '1KA17', 'gedeaditya166@gmail.com', '081219826470', '2026-10-14 07:08:25', '14:08:25', 1, 1),
(36, '50426237', 'Dhafin sechar erlino utama', '1|A02', 'keksiapa09@gmail.com', '087771647953', '2026-10-14 07:12:12', '14:12:12', 2, 2),
(37, '10126374', 'MUHAMMAD AKBAR ABRISAM', '1KA23', 'muhammadakbarabrisam@gmail.com', '081284811464', '2026-10-14 07:16:38', '14:16:38', 1, 1),
(38, '50426899', 'Sharfan Karl putra', '1iA05', 'sharfankarlbayyer78@gmail.com', '083147783257', '2026-10-14 07:30:05', '14:30:05', 2, 2),
(39, '10126195', 'Fakhri Akmal Dzakwan', '1KA13', 'shodzprblx@gmail.com', '085694181701', '2026-10-14 07:36:06', '14:36:06', 2, 1),
(40, '10126126', 'DAFINO ANDIKA FIRMANSYAH', '1KA13', 'dafinoandika@gmail.com', '081299281564', '2026-10-14 07:41:56', '14:41:56', 1, 1),
(41, '50426388', 'Habibie Putra Hidayat', '1IA13', 'habibieputrah@gmail.com', '087703930733', '2026-10-14 08:24:50', '15:24:50', 2, 2),
(42, '10126019', 'Agiel Fairuz Al Bachri', '1KA11', 'agielfairuz4@gmail.com', '081928230002', '2026-10-14 08:49:10', '15:49:10', 1, 1),
(43, '50426083', 'Alif Fauzi', '1IA03', 'fzicikiwir@gmail.com', '081903599057', '2026-10-14 09:11:20', '16:11:20', 1, 2),
(44, '50426794', 'Rama adama sofyan', '1IA05', 'chintyaayu789@gmail.com', '081311973427', '2026-10-14 09:15:27', '16:15:27', 1, 2),
(45, '10126008', 'Achmad Maulana', '1KA05', 'skygywalker77@gmail.com', '081226267791', '2026-10-14 09:18:46', '16:18:46', 2, 1),
(46, '10126487', 'Prassya Rifkyza Attaramya', '1KA05', 'prassya2007@gmail.com', '081314912997', '2026-10-14 09:20:41', '16:20:41', 2, 1),
(47, '10126099', 'BARICK FAQILIU', '1KA05', 'barickfakiliu@gmail.com', '087886621883', '2026-10-14 09:31:46', '16:31:46', 2, 1),
(48, '50426490', 'Luthfi Rasendriya Hanasta', '1|A03', 'upyhanasta@gmail.com', '083838333707', '2026-10-14 09:39:56', '16:39:56', 1, 2),
(49, '10126549', 'Ridwan Pramudya', '1KA05', 'ridwanpramudya550@gmail.com', '081388728117', '2026-10-14 09:40:13', '16:40:13', 2, 1),
(50, '50426614', 'Muhammad Farras Yudi Pratama', '1IA09', 'mfarrasyudipratama@gmail.com', '081276702451', '2026-10-14 09:42:03', '16:42:03', 2, 2),
(51, '10126501', 'Rafael Arfa Milano Prayogi', '1KA10', 'rafaprayogisatu@gmail.com', '081281161661', '2026-10-14 09:46:40', '16:46:40', 1, 1),
(52, '50426643', 'Muhammad Qalbi Anasrullah', '1IA02', 'mqalbi670@gmail.com', '085888216510', '2026-10-14 09:46:54', '16:46:54', 2, 2),
(53, '10126252', 'Haekal Faizi Haqqi', '1KA09', 'haekalfaizihaqqi101007@gmail.com', '088211755144', '2026-10-14 10:28:01', '17:28:01', 2, 1),
(54, '50426508', 'MARCHEL RAPHA CHRISTIAN WINERUNGAN', '1IA13', 'marchelswinerungan@gmail.com', '081258086141', '2026-10-14 10:47:16', '17:47:16', 1, 2),
(55, '50426970', 'ZECONIA FABIAN GOKLAS SIAHAAN', '1ia13', 'zecogoklas@gmail.com', '081381054095', '2026-10-14 11:24:10', '18:24:10', 2, 2),
(56, '10126175', 'Ernest Batuta susanto', '1KA13', 'ernestbatutas@gmail.com', '082124444231', '2026-10-14 11:27:42', '18:27:42', 1, 1),
(57, '50426078', 'Alfahri Pratama Jatmika', '1IA09', 'alfahripratamajatmika@gmail.com', '081280404658', '2026-10-14 12:11:50', '19:11:50', 2, 2),
(58, '10126307', 'JOSHUA PARULIAN PRATAMA', '1KA05', 'joshuaparulianlumbangaol@gmail.com', '085262063539', '2026-10-14 12:22:50', '19:22:50', 2, 1),
(59, '50426636', 'Muhammad Lucky Febriyanto', '1IA09', 'luckyfebrianto891@gmail.com', '081210386795', '2026-10-14 12:29:15', '19:29:15', 2, 2),
(60, '10126380', 'Muhammad Auriyal Baharuddin Putra', '1KA02', 'auriyalraflisky@gmail.com', '081990538461', '2026-10-14 12:35:57', '19:35:57', 2, 1),
(61, '10126064', 'Arian fattar argiansyah', '1KA02', 'arianfattara@gmail.com', '085882864942', '2026-10-14 12:40:23', '19:40:23', 1, 1),
(62, '50426618', 'MUHAMMAD FATRIS ALVIAN', '1IA02', 'fatris2522@gmail.com', '089638157946', '2026-10-14 12:52:43', '19:52:43', 2, 2),
(63, '10126545', 'Revangga Ghiffary Putra', '1KA16', 'farysyah22807@gmail.com', '08111623279', '2026-10-14 13:26:50', '20:26:50', 1, 1),
(64, '10126230', 'Firza Roid Pratama', '1KA14', 'firzaroidpratama05@gmail.com', '082233327159', '2026-10-14 13:31:51', '20:31:51', 1, 1),
(65, '10126588', 'Shofi Danisa Hernanda', '1KA13', 'shofidanisa@gmail.com', '085697099421', '2026-10-14 13:53:19', '20:53:19', 2, 1),
(66, '10126614', 'Thahir Hanif', '1KA06', 'thahir1805@gmail.com', '081317182274', '2026-10-14 13:55:40', '20:55:40', 1, 1),
(67, '10126553', 'RIFQI MUDZAFFAR', '1KA06', 'rifqimudzaffar99@gmail.com', '08126771105', '2026-10-14 13:56:27', '20:56:27', 1, 1),
(68, '10126137', 'Denis Kianaldi', '1KA06', 'denizzknldy@gmail.com', '085353657366', '2026-10-14 14:03:25', '21:03:25', 1, 1),
(69, '50426656', 'Muhammad Rasya Alfarizi', '1IA13', 'rasyaalfarizi1807@gmail.com', '081213915833', '2026-10-14 14:35:31', '21:35:31', 1, 2),
(70, '10126121', 'Dafa Adnan Afianto', '1KA18', 'adnanafianto103@gmail.com', '083850211664', '2026-10-14 15:04:02', '22:04:02', 2, 1),
(71, '10126534', 'Razel Michelle Dude Nagara', '1KA02', 'razeltamtia@gmail.com', '089603213927', '2026-10-14 15:45:10', '22:45:10', 2, 1),
(72, '10126589', 'silmi fatha mubina', '1ka11', 'silmifatham@gmail.com', '081384044502', '2026-10-14 15:48:10', '22:48:10', 2, 1),
(73, '10126238', 'galuh febriansyah', '1kA02', 'galuhfeb03@gmail.com', '085717482983', '2026-10-14 16:46:41', '23:46:41', 2, 1),
(74, '50426661', 'Muhammad Reivan', '1IA09', 'mhmmdrvn07@gmail.com', '089516317149', '2026-10-15 00:49:02', '07:49:02', 2, 2),
(75, '50426843', 'Risma Huljannah', '1IA13', 'rismajannahwoofy@gmail.com', '081283189534', '2026-10-15 00:51:34', '07:51:34', 2, 2),
(76, '50426754', 'Raden Gavin anbya Himawan', '1IA13', 'gvinaanbya@gmail.com', '089676055562', '2026-10-15 06:11:38', '13:11:38', 2, 2),
(77, '50426867', 'RYUGA NUKMAN NURRAHMAN', '1IA09', 'rynnn0909@gmail.com', '081289204860', '2026-10-15 07:02:43', '14:02:43', 2, 2),
(78, '10126159', 'Duta manggala amrullah', '1KA07', 'dutamanggala100@gmail.com', '081383094463', '2026-10-15 10:40:20', '17:40:20', 1, 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

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
