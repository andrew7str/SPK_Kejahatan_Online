-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2025 at 07:39 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dss_online_crime`
--

-- --------------------------------------------------------

--
-- Table structure for table `ahp_comparisons`
--

CREATE TABLE `ahp_comparisons` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `criteria1_id` int(11) NOT NULL,
  `criteria2_id` int(11) NOT NULL,
  `comparison_value` decimal(8,6) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ahp_matrix`
--

CREATE TABLE `ahp_matrix` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `row_criteria` int(11) NOT NULL,
  `col_criteria` int(11) NOT NULL,
  `value` decimal(10,8) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ahp_matrix`
--

INSERT INTO `ahp_matrix` (`id`, `user_id`, `row_criteria`, `col_criteria`, `value`, `created_at`) VALUES
(97, 9, 0, 0, 1.00000000, '2025-09-05 13:24:58'),
(98, 9, 0, 1, 5.00000000, '2025-09-05 13:24:58'),
(99, 9, 0, 2, 7.00000000, '2025-09-05 13:24:58'),
(100, 9, 0, 3, 5.00000000, '2025-09-05 13:24:58'),
(101, 9, 1, 0, 0.20000000, '2025-09-05 13:24:58'),
(102, 9, 1, 1, 1.00000000, '2025-09-05 13:24:58'),
(103, 9, 1, 2, 1.00000000, '2025-09-05 13:24:58'),
(104, 9, 1, 3, 5.00000000, '2025-09-05 13:24:58'),
(105, 9, 2, 0, 0.14285714, '2025-09-05 13:24:58'),
(106, 9, 2, 1, 1.00000000, '2025-09-05 13:24:58'),
(107, 9, 2, 2, 1.00000000, '2025-09-05 13:24:58'),
(108, 9, 2, 3, 9.00000000, '2025-09-05 13:24:58'),
(109, 9, 3, 0, 0.20000000, '2025-09-05 13:24:58'),
(110, 9, 3, 1, 0.20000000, '2025-09-05 13:24:58'),
(111, 9, 3, 2, 0.11111111, '2025-09-05 13:24:58'),
(112, 9, 3, 3, 1.00000000, '2025-09-05 13:24:58'),
(113, 8, 0, 0, 1.00000000, '2025-09-06 12:29:50'),
(114, 8, 0, 1, 9.00000000, '2025-09-06 12:29:50'),
(115, 8, 0, 2, 9.00000000, '2025-09-06 12:29:50'),
(116, 8, 0, 3, 3.00000000, '2025-09-06 12:29:50'),
(117, 8, 1, 0, 0.11111111, '2025-09-06 12:29:50'),
(118, 8, 1, 1, 1.00000000, '2025-09-06 12:29:50'),
(119, 8, 1, 2, 3.00000000, '2025-09-06 12:29:50'),
(120, 8, 1, 3, 5.00000000, '2025-09-06 12:29:50'),
(121, 8, 2, 0, 0.11111111, '2025-09-06 12:29:50'),
(122, 8, 2, 1, 0.33333333, '2025-09-06 12:29:50'),
(123, 8, 2, 2, 1.00000000, '2025-09-06 12:29:50'),
(124, 8, 2, 3, 3.00000000, '2025-09-06 12:29:50'),
(125, 8, 3, 0, 0.33333333, '2025-09-06 12:29:50'),
(126, 8, 3, 1, 0.20000000, '2025-09-06 12:29:50'),
(127, 8, 3, 2, 0.33333333, '2025-09-06 12:29:50'),
(128, 8, 3, 3, 1.00000000, '2025-09-06 12:29:50'),
(385, 3, 0, 0, 1.00000000, '2025-09-06 16:19:01'),
(386, 3, 0, 1, 5.00000000, '2025-09-06 16:19:01'),
(387, 3, 0, 2, 5.00000000, '2025-09-06 16:19:01'),
(388, 3, 0, 3, 3.00000000, '2025-09-06 16:19:01'),
(389, 3, 1, 0, 0.20000000, '2025-09-06 16:19:01'),
(390, 3, 1, 1, 1.00000000, '2025-09-06 16:19:01'),
(391, 3, 1, 2, 3.00000000, '2025-09-06 16:19:01'),
(392, 3, 1, 3, 1.00000000, '2025-09-06 16:19:01'),
(393, 3, 2, 0, 0.20000000, '2025-09-06 16:19:01'),
(394, 3, 2, 1, 0.33333333, '2025-09-06 16:19:01'),
(395, 3, 2, 2, 1.00000000, '2025-09-06 16:19:01'),
(396, 3, 2, 3, 1.00000000, '2025-09-06 16:19:01'),
(397, 3, 3, 0, 0.33333333, '2025-09-06 16:19:01'),
(398, 3, 3, 1, 1.00000000, '2025-09-06 16:19:01'),
(399, 3, 3, 2, 1.00000000, '2025-09-06 16:19:01'),
(400, 3, 3, 3, 1.00000000, '2025-09-06 16:19:01'),
(433, 1, 0, 0, 1.00000000, '2025-09-06 17:21:27'),
(434, 1, 0, 1, 4.00000000, '2025-09-06 17:21:27'),
(435, 1, 0, 2, 5.00000000, '2025-09-06 17:21:27'),
(436, 1, 0, 3, 6.00000000, '2025-09-06 17:21:27'),
(437, 1, 1, 0, 0.25000000, '2025-09-06 17:21:27'),
(438, 1, 1, 1, 1.00000000, '2025-09-06 17:21:27'),
(439, 1, 1, 2, 3.00000000, '2025-09-06 17:21:27'),
(440, 1, 1, 3, 4.00000000, '2025-09-06 17:21:27'),
(441, 1, 2, 0, 0.20000000, '2025-09-06 17:21:27'),
(442, 1, 2, 1, 0.33333333, '2025-09-06 17:21:27'),
(443, 1, 2, 2, 1.00000000, '2025-09-06 17:21:27'),
(444, 1, 2, 3, 3.00000000, '2025-09-06 17:21:27'),
(445, 1, 3, 0, 0.16666667, '2025-09-06 17:21:27'),
(446, 1, 3, 1, 0.25000000, '2025-09-06 17:21:27'),
(447, 1, 3, 2, 0.33333333, '2025-09-06 17:21:27'),
(448, 1, 3, 3, 1.00000000, '2025-09-06 17:21:27');

-- --------------------------------------------------------

--
-- Table structure for table `ahp_results`
--

CREATE TABLE `ahp_results` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `weight` decimal(8,6) NOT NULL,
  `consistency_ratio` decimal(8,6) DEFAULT NULL,
  `is_consistent` tinyint(1) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ahp_results`
--

INSERT INTO `ahp_results` (`id`, `session_id`, `criteria_id`, `weight`, `consistency_ratio`, `is_consistent`, `created_by`, `created_at`) VALUES
(57, 'ahp_3_1757175541', 1, 0.564685, 0.071204, 1, 3, '2025-09-06 16:19:01'),
(58, 'ahp_3_1757175541', 2, 0.179604, 0.071204, 1, 3, '2025-09-06 16:19:01'),
(59, 'ahp_3_1757175541', 3, 0.106876, 0.071204, 1, 3, '2025-09-06 16:19:01'),
(60, 'ahp_3_1757175541', 4, 0.148834, 0.071204, 1, 3, '2025-09-06 16:19:01'),
(69, 'ahp_1_1757179287', 1, 0.574815, 0.077538, 1, 1, '2025-09-06 17:21:27'),
(70, 'ahp_1_1757179287', 2, 0.235222, 0.077538, 1, 1, '2025-09-06 17:21:27'),
(71, 'ahp_1_1757179287', 3, 0.126210, 0.077538, 1, 1, '2025-09-06 17:21:27'),
(72, 'ahp_1_1757179287', 4, 0.063753, 0.077538, 1, 1, '2025-09-06 17:21:27');

-- --------------------------------------------------------

--
-- Table structure for table `alternatives`
--

CREATE TABLE `alternatives` (
  `id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `alternative_name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alternative_scores`
--

CREATE TABLE `alternative_scores` (
  `id` int(11) NOT NULL,
  `alternative_id` int(11) DEFAULT NULL,
  `criteria_id` int(11) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `id` int(11) NOT NULL,
  `case_number` varchar(50) NOT NULL,
  `case_name` varchar(200) NOT NULL,
  `case_type` enum('phishing','hacking','fraud','cyberbullying','identity_theft','online_scam','malware','other') NOT NULL,
  `description` text DEFAULT NULL,
  `reporter_name` varchar(100) DEFAULT NULL,
  `reporter_contact` varchar(100) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','investigating','resolved','closed') DEFAULT 'pending',
  `priority_level` enum('low','medium','high','critical') DEFAULT 'medium',
  `assigned_officer` varchar(100) DEFAULT NULL,
  `estimated_loss` decimal(15,2) DEFAULT 0.00,
  `victim_count` int(11) DEFAULT 1,
  `urgency_level` int(11) DEFAULT 1 CHECK (`urgency_level` between 1 and 5),
  `spread_potential` int(11) DEFAULT 1 CHECK (`spread_potential` between 1 and 5),
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`id`, `case_number`, `case_name`, `case_type`, `description`, `reporter_name`, `reporter_contact`, `incident_date`, `report_date`, `status`, `priority_level`, `assigned_officer`, `estimated_loss`, `victim_count`, `urgency_level`, `spread_potential`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'KASUS001/2025', 'Korupsi', 'other', 'DPRD', 'Upin', '0812345678', '2025-09-06', '2025-09-06 15:23:45', 'pending', 'critical', 'Upil', 999999900000.00, 19999, 5, 5, 1, '2025-09-06 15:23:45', '2025-09-06 15:23:45'),
(2, 'KASUS002/2025', 'Korupsi', 'phishing', 'Pertamini', 'ucok', '0812345679', '2025-09-06', '2025-09-06 16:15:11', 'pending', 'low', 'butet', 90000.00, 90, 1, 1, 3, '2025-09-06 16:15:11', '2025-09-06 16:15:11');

-- --------------------------------------------------------

--
-- Table structure for table `case_evaluations`
--

CREATE TABLE `case_evaluations` (
  `id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `score` decimal(8,4) NOT NULL,
  `normalized_score` decimal(8,6) DEFAULT NULL,
  `weighted_score` decimal(8,6) DEFAULT NULL,
  `evaluator_id` int(11) DEFAULT NULL,
  `evaluation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `criteria`
--

CREATE TABLE `criteria` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('benefit','cost') DEFAULT 'benefit',
  `weight` decimal(8,6) DEFAULT 0.000000,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `criteria`
--

INSERT INTO `criteria` (`id`, `code`, `name`, `description`, `type`, `weight`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'C1', 'Tingkat Kerugian', 'Besarnya kerugian materiil (uang/barang) yang dialami korban akibat kasus kejahatan online', 'benefit', 0.574800, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(2, 'C2', 'Tingkat Dampak', 'Sejauh mana kasus berdampak pada masyarakat/instansi (keresahan publik, reputasi)', 'benefit', 0.235200, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(3, 'C3', 'Urgensi Penanganan', 'Tingkat kepentingan atau seberapa cepat kasus harus segera ditangani', 'benefit', 0.126200, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(4, 'C4', 'Ketersediaan Sumber Daya', 'Kesiapan personel, teknologi, dan fasilitas untuk menangani kasus', 'benefit', 0.063800, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44');

-- --------------------------------------------------------

--
-- Table structure for table `criteria_weights`
--

CREATE TABLE `criteria_weights` (
  `id` int(11) NOT NULL,
  `criteria_id` int(11) DEFAULT NULL,
  `weight` decimal(5,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `criteria_weights`
--

INSERT INTO `criteria_weights` (`id`, `criteria_id`, `weight`) VALUES
(1, 1, 0.0556),
(2, 2, 0.0556),
(3, 3, 0.0556),
(4, 4, 0.0556),
(5, 5, 0.0556),
(6, 6, 0.0556),
(7, 7, 0.0556),
(8, 8, 0.0556),
(9, 9, 0.0556),
(10, 10, 0.0556),
(11, 11, 0.0556),
(12, 12, 0.0556),
(13, 13, 0.0556),
(14, 14, 0.0556),
(15, 15, 0.0556),
(16, 16, 0.0556),
(17, 17, 0.0556),
(18, 18, 0.0556);

-- --------------------------------------------------------

--
-- Table structure for table `decision_sessions`
--

CREATE TABLE `decision_sessions` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `session_name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `method` enum('ahp','topsis','ahp_topsis') DEFAULT 'ahp_topsis',
  `status` enum('draft','calculating','completed','archived') DEFAULT 'draft',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pairwise_comparisons`
--

CREATE TABLE `pairwise_comparisons` (
  `id` int(11) NOT NULL,
  `criteria1_id` int(11) DEFAULT NULL,
  `criteria2_id` int(11) DEFAULT NULL,
  `value` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pairwise_comparisons`
--

INSERT INTO `pairwise_comparisons` (`id`, `criteria1_id`, `criteria2_id`, `value`) VALUES
(1, 1, 2, 1.00),
(2, 1, 3, 1.00),
(3, 1, 4, 1.00),
(4, 1, 5, 1.00),
(5, 1, 6, 1.00),
(6, 1, 7, 1.00),
(7, 1, 8, 1.00),
(8, 2, 1, 1.00),
(9, 2, 3, 1.00),
(10, 2, 4, 1.00),
(11, 2, 5, 1.00),
(12, 2, 6, 1.00),
(13, 2, 7, 1.00),
(14, 2, 8, 1.00),
(15, 3, 1, 1.00),
(16, 3, 2, 1.00),
(17, 3, 4, 1.00),
(18, 3, 5, 1.00),
(19, 3, 6, 1.00),
(20, 3, 7, 1.00),
(21, 3, 8, 1.00),
(22, 4, 1, 1.00),
(23, 4, 2, 1.00),
(24, 4, 3, 1.00),
(25, 4, 5, 1.00),
(26, 4, 6, 1.00),
(27, 4, 7, 1.00),
(28, 4, 8, 1.00),
(29, 5, 1, 1.00),
(30, 5, 2, 1.00),
(31, 5, 3, 1.00),
(32, 5, 4, 1.00),
(33, 5, 6, 1.00),
(34, 5, 7, 1.00),
(35, 5, 8, 1.00),
(36, 6, 1, 1.00),
(37, 6, 2, 1.00),
(38, 6, 3, 1.00),
(39, 6, 4, 1.00),
(40, 6, 5, 1.00),
(41, 6, 7, 1.00),
(42, 6, 8, 1.00),
(43, 7, 1, 1.00),
(44, 7, 2, 1.00),
(45, 7, 3, 1.00),
(46, 7, 4, 1.00),
(47, 7, 5, 1.00),
(48, 7, 6, 1.00),
(49, 7, 8, 1.00),
(50, 8, 1, 1.00),
(51, 8, 2, 1.00),
(52, 8, 3, 1.00),
(53, 8, 4, 1.00),
(54, 8, 5, 1.00),
(55, 8, 6, 1.00),
(56, 8, 7, 1.00),
(64, 1, 9, 1.00),
(65, 1, 10, 1.00),
(66, 1, 11, 1.00),
(67, 1, 12, 1.00),
(68, 1, 13, 1.00),
(69, 1, 14, 1.00),
(70, 1, 15, 1.00),
(71, 1, 16, 1.00),
(72, 1, 17, 1.00),
(73, 1, 18, 1.00),
(81, 2, 9, 1.00),
(82, 2, 10, 1.00),
(83, 2, 11, 1.00),
(84, 2, 12, 1.00),
(85, 2, 13, 1.00),
(86, 2, 14, 1.00),
(87, 2, 15, 1.00),
(88, 2, 16, 1.00),
(89, 2, 17, 1.00),
(90, 2, 18, 1.00),
(98, 3, 9, 1.00),
(99, 3, 10, 1.00),
(100, 3, 11, 1.00),
(101, 3, 12, 1.00),
(102, 3, 13, 1.00),
(103, 3, 14, 1.00),
(104, 3, 15, 1.00),
(105, 3, 16, 1.00),
(106, 3, 17, 1.00),
(107, 3, 18, 1.00),
(115, 4, 9, 1.00),
(116, 4, 10, 1.00),
(117, 4, 11, 1.00),
(118, 4, 12, 1.00),
(119, 4, 13, 1.00),
(120, 4, 14, 1.00),
(121, 4, 15, 1.00),
(122, 4, 16, 1.00),
(123, 4, 17, 1.00),
(124, 4, 18, 1.00),
(132, 5, 9, 1.00),
(133, 5, 10, 1.00),
(134, 5, 11, 1.00),
(135, 5, 12, 1.00),
(136, 5, 13, 1.00),
(137, 5, 14, 1.00),
(138, 5, 15, 1.00),
(139, 5, 16, 1.00),
(140, 5, 17, 1.00),
(141, 5, 18, 1.00),
(149, 6, 9, 1.00),
(150, 6, 10, 1.00),
(151, 6, 11, 1.00),
(152, 6, 12, 1.00),
(153, 6, 13, 1.00),
(154, 6, 14, 1.00),
(155, 6, 15, 1.00),
(156, 6, 16, 1.00),
(157, 6, 17, 1.00),
(158, 6, 18, 1.00),
(166, 7, 9, 1.00),
(167, 7, 10, 1.00),
(168, 7, 11, 1.00),
(169, 7, 12, 1.00),
(170, 7, 13, 1.00),
(171, 7, 14, 1.00),
(172, 7, 15, 1.00),
(173, 7, 16, 1.00),
(174, 7, 17, 1.00),
(175, 7, 18, 1.00),
(183, 8, 9, 1.00),
(184, 8, 10, 1.00),
(185, 8, 11, 1.00),
(186, 8, 12, 1.00),
(187, 8, 13, 1.00),
(188, 8, 14, 1.00),
(189, 8, 15, 1.00),
(190, 8, 16, 1.00),
(191, 8, 17, 1.00),
(192, 8, 18, 1.00),
(193, 9, 1, 1.00),
(194, 9, 2, 1.00),
(195, 9, 3, 1.00),
(196, 9, 4, 1.00),
(197, 9, 5, 1.00),
(198, 9, 6, 1.00),
(199, 9, 7, 1.00),
(200, 9, 8, 1.00),
(201, 9, 10, 1.00),
(202, 9, 11, 1.00),
(203, 9, 12, 1.00),
(204, 9, 13, 1.00),
(205, 9, 14, 1.00),
(206, 9, 15, 1.00),
(207, 9, 16, 1.00),
(208, 9, 17, 1.00),
(209, 9, 18, 1.00),
(210, 10, 1, 1.00),
(211, 10, 2, 1.00),
(212, 10, 3, 1.00),
(213, 10, 4, 1.00),
(214, 10, 5, 1.00),
(215, 10, 6, 1.00),
(216, 10, 7, 1.00),
(217, 10, 8, 1.00),
(218, 10, 9, 1.00),
(219, 10, 11, 1.00),
(220, 10, 12, 1.00),
(221, 10, 13, 1.00),
(222, 10, 14, 1.00),
(223, 10, 15, 1.00),
(224, 10, 16, 1.00),
(225, 10, 17, 1.00),
(226, 10, 18, 1.00),
(227, 11, 1, 1.00),
(228, 11, 2, 1.00),
(229, 11, 3, 1.00),
(230, 11, 4, 1.00),
(231, 11, 5, 1.00),
(232, 11, 6, 1.00),
(233, 11, 7, 1.00),
(234, 11, 8, 1.00),
(235, 11, 9, 1.00),
(236, 11, 10, 1.00),
(237, 11, 12, 1.00),
(238, 11, 13, 1.00),
(239, 11, 14, 1.00),
(240, 11, 15, 1.00),
(241, 11, 16, 1.00),
(242, 11, 17, 1.00),
(243, 11, 18, 1.00),
(244, 12, 1, 1.00),
(245, 12, 2, 1.00),
(246, 12, 3, 1.00),
(247, 12, 4, 1.00),
(248, 12, 5, 1.00),
(249, 12, 6, 1.00),
(250, 12, 7, 1.00),
(251, 12, 8, 1.00),
(252, 12, 9, 1.00),
(253, 12, 10, 1.00),
(254, 12, 11, 1.00),
(255, 12, 13, 1.00),
(256, 12, 14, 1.00),
(257, 12, 15, 1.00),
(258, 12, 16, 1.00),
(259, 12, 17, 1.00),
(260, 12, 18, 1.00),
(261, 13, 1, 1.00),
(262, 13, 2, 1.00),
(263, 13, 3, 1.00),
(264, 13, 4, 1.00),
(265, 13, 5, 1.00),
(266, 13, 6, 1.00),
(267, 13, 7, 1.00),
(268, 13, 8, 1.00),
(269, 13, 9, 1.00),
(270, 13, 10, 1.00),
(271, 13, 11, 1.00),
(272, 13, 12, 1.00),
(273, 13, 14, 1.00),
(274, 13, 15, 1.00),
(275, 13, 16, 1.00),
(276, 13, 17, 1.00),
(277, 13, 18, 1.00),
(278, 14, 1, 1.00),
(279, 14, 2, 1.00),
(280, 14, 3, 1.00),
(281, 14, 4, 1.00),
(282, 14, 5, 1.00),
(283, 14, 6, 1.00),
(284, 14, 7, 1.00),
(285, 14, 8, 1.00),
(286, 14, 9, 1.00),
(287, 14, 10, 1.00),
(288, 14, 11, 1.00),
(289, 14, 12, 1.00),
(290, 14, 13, 1.00),
(291, 14, 15, 1.00),
(292, 14, 16, 1.00),
(293, 14, 17, 1.00),
(294, 14, 18, 1.00),
(295, 15, 1, 1.00),
(296, 15, 2, 1.00),
(297, 15, 3, 1.00),
(298, 15, 4, 1.00),
(299, 15, 5, 1.00),
(300, 15, 6, 1.00),
(301, 15, 7, 1.00),
(302, 15, 8, 1.00),
(303, 15, 9, 1.00),
(304, 15, 10, 1.00),
(305, 15, 11, 1.00),
(306, 15, 12, 1.00),
(307, 15, 13, 1.00),
(308, 15, 14, 1.00),
(309, 15, 16, 1.00),
(310, 15, 17, 1.00),
(311, 15, 18, 1.00),
(312, 16, 1, 1.00),
(313, 16, 2, 1.00),
(314, 16, 3, 1.00),
(315, 16, 4, 1.00),
(316, 16, 5, 1.00),
(317, 16, 6, 1.00),
(318, 16, 7, 1.00),
(319, 16, 8, 1.00),
(320, 16, 9, 1.00),
(321, 16, 10, 1.00),
(322, 16, 11, 1.00),
(323, 16, 12, 1.00),
(324, 16, 13, 1.00),
(325, 16, 14, 1.00),
(326, 16, 15, 1.00),
(327, 16, 17, 1.00),
(328, 16, 18, 1.00),
(329, 17, 1, 1.00),
(330, 17, 2, 1.00),
(331, 17, 3, 1.00),
(332, 17, 4, 1.00),
(333, 17, 5, 1.00),
(334, 17, 6, 1.00),
(335, 17, 7, 1.00),
(336, 17, 8, 1.00),
(337, 17, 9, 1.00),
(338, 17, 10, 1.00),
(339, 17, 11, 1.00),
(340, 17, 12, 1.00),
(341, 17, 13, 1.00),
(342, 17, 14, 1.00),
(343, 17, 15, 1.00),
(344, 17, 16, 1.00),
(345, 17, 18, 1.00),
(346, 18, 1, 1.00),
(347, 18, 2, 1.00),
(348, 18, 3, 1.00),
(349, 18, 4, 1.00),
(350, 18, 5, 1.00),
(351, 18, 6, 1.00),
(352, 18, 7, 1.00),
(353, 18, 8, 1.00),
(354, 18, 9, 1.00),
(355, 18, 10, 1.00),
(356, 18, 11, 1.00),
(357, 18, 12, 1.00),
(358, 18, 13, 1.00),
(359, 18, 14, 1.00),
(360, 18, 15, 1.00),
(361, 18, 16, 1.00),
(362, 18, 17, 1.00);

-- --------------------------------------------------------

--
-- Table structure for table `rankings`
--

CREATE TABLE `rankings` (
  `id` int(11) NOT NULL,
  `alternative_name` varchar(255) DEFAULT NULL,
  `score` decimal(10,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('string','number','boolean','json') DEFAULT 'string',
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 0,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `is_public`, `updated_by`, `updated_at`) VALUES
(1, 'system_name', 'Sistem Pendukung Keputusan Prioritas Penanganan Kejahatan Online', 'string', 'Nama sistem aplikasi', 1, NULL, '2025-09-06 15:20:44'),
(2, 'organization_name', 'Polsek Saribudolok', 'string', 'Nama organisasi', 1, NULL, '2025-09-06 15:20:44'),
(3, 'max_consistency_ratio', '0.1', 'number', 'Batas maksimal rasio konsistensi AHP', 0, NULL, '2025-09-06 15:20:44'),
(4, 'default_session_timeout', '3600', 'number', 'Timeout sesi dalam detik', 0, NULL, '2025-09-06 15:20:44'),
(5, 'enable_logging', 'true', 'boolean', 'Aktifkan logging sistem', 0, NULL, '2025-09-06 15:20:44');

-- --------------------------------------------------------

--
-- Table structure for table `sub_criteria`
--

CREATE TABLE `sub_criteria` (
  `id` int(11) NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `score_range` varchar(50) DEFAULT '1-5',
  `weight` decimal(8,6) DEFAULT 0.000000,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_criteria`
--

INSERT INTO `sub_criteria` (`id`, `criteria_id`, `code`, `name`, `description`, `score_range`, `weight`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'C1.1', 'Kerugian Sangat Rendah', 'Kerugian < Rp 10.000.000', '1', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(2, 1, 'C1.2', 'Kerugian Rendah', 'Kerugian Rp 10.000.000 - Rp 25.000.000', '2', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(3, 1, 'C1.3', 'Kerugian Sedang', 'Kerugian Rp 25.000.000 - Rp 50.000.000', '3', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(4, 1, 'C1.4', 'Kerugian Tinggi', 'Kerugian Rp 50.000.000 - Rp 100.000.000', '4', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(5, 1, 'C1.5', 'Kerugian Sangat Tinggi', 'Kerugian > Rp 100.000.000', '5', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(6, 2, 'C2.1', 'Dampak Sangat Rendah', 'Hanya mempengaruhi korban langsung', '1', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(7, 2, 'C2.2', 'Dampak Rendah', 'Mempengaruhi keluarga korban', '2', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(8, 2, 'C2.3', 'Dampak Sedang', 'Mempengaruhi komunitas kecil', '3', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(9, 2, 'C2.4', 'Dampak Tinggi', 'Mempengaruhi masyarakat luas', '4', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(10, 2, 'C2.5', 'Dampak Sangat Tinggi', 'Mempengaruhi stabilitas sosial/ekonomi', '5', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(11, 3, 'C3.1', 'Urgensi Sangat Rendah', 'Dapat ditangani dalam 1 bulan', '1', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(12, 3, 'C3.2', 'Urgensi Rendah', 'Perlu ditangani dalam 2 minggu', '2', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(13, 3, 'C3.3', 'Urgensi Sedang', 'Perlu ditangani dalam 1 minggu', '3', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(14, 3, 'C3.4', 'Urgensi Tinggi', 'Perlu ditangani dalam 3 hari', '4', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(15, 3, 'C3.5', 'Urgensi Sangat Tinggi', 'Perlu ditangani segera (< 24 jam)', '5', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(16, 4, 'C4.1', 'Sumber Daya Sangat Terbatas', 'Memerlukan bantuan eksternal', '1', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(17, 4, 'C4.2', 'Sumber Daya Terbatas', 'Memerlukan alokasi khusus', '2', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(18, 4, 'C4.3', 'Sumber Daya Cukup', 'Dapat ditangani dengan tim standar', '3', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(19, 4, 'C4.4', 'Sumber Daya Baik', 'Tim dan peralatan tersedia', '4', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44'),
(20, 4, 'C4.5', 'Sumber Daya Sangat Baik', 'Tim ahli dan peralatan lengkap tersedia', '5', 0.000000, 1, '2025-09-06 15:20:44', '2025-09-06 15:20:44');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `topsis_analysis_cases`
--

CREATE TABLE `topsis_analysis_cases` (
  `id` int(11) NOT NULL,
  `case_id` varchar(50) NOT NULL,
  `case_name` varchar(200) NOT NULL,
  `kerugian` bigint(20) NOT NULL DEFAULT 0,
  `korban` int(11) NOT NULL DEFAULT 1,
  `urgensi` int(11) NOT NULL DEFAULT 1,
  `penyebaran` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `topsis_analysis_cases`
--

INSERT INTO `topsis_analysis_cases` (`id`, `case_id`, `case_name`, `kerugian`, `korban`, `urgensi`, `penyebaran`, `created_by`, `created_at`, `updated_at`) VALUES
(4, 'KJO-2025-001', 'Penipuan Online Marketplace', 49944304, 3, 4, 3, 3, '2025-09-06 16:19:15', '2025-09-06 16:19:15'),
(5, 'KJO-2025-002', 'Investasi Bodong Online', 55000000, 4, 5, 3, 3, '2025-09-06 16:19:15', '2025-09-06 16:19:15'),
(6, 'KJO-2025-003', 'Phishing Banking', 63000000, 3, 3, 2, 3, '2025-09-06 16:19:15', '2025-09-06 16:19:15'),
(15, 'KJO-2025-001', 'Penipuan Online Marketplace', 49944304, 3, 4, 3, 1, '2025-09-06 17:24:25', '2025-09-06 17:24:25'),
(16, 'KJO-2025-002', 'Investasi Bodong Online', 55000000, 4, 5, 3, 1, '2025-09-06 17:24:25', '2025-09-06 17:24:25'),
(17, 'KJO-2025-003', 'Phishing Banking', 63000000, 3, 3, 2, 1, '2025-09-06 17:24:25', '2025-09-06 17:24:25');

-- --------------------------------------------------------

--
-- Table structure for table `topsis_calculations`
--

CREATE TABLE `topsis_calculations` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `case_id` varchar(50) NOT NULL,
  `positive_distance` decimal(10,8) DEFAULT NULL,
  `negative_distance` decimal(10,8) DEFAULT NULL,
  `closeness_coefficient` decimal(8,6) DEFAULT NULL,
  `rank_position` int(11) DEFAULT NULL,
  `calculated_by` int(11) DEFAULT NULL,
  `calculated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `topsis_calculations`
--

INSERT INTO `topsis_calculations` (`id`, `session_id`, `case_id`, `positive_distance`, `negative_distance`, `closeness_coefficient`, `rank_position`, `calculated_by`, `calculated_at`) VALUES
(3, 'topsis_3_1757176357', 'KJO-2025-003', -99.99999999, 99.99999999, 99.999999, 1, 3, '2025-09-06 16:32:37'),
(4, 'topsis_3_1757176357', 'KJO-2025-002', -99.99999999, 99.99999999, 99.999999, 2, 3, '2025-09-06 16:32:37'),
(5, 'topsis_3_1757176357', 'KJO-2025-001', -99.99999999, 99.99999999, 99.999999, 3, 3, '2025-09-06 16:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `topsis_results`
--

CREATE TABLE `topsis_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `alternative_id` varchar(50) NOT NULL,
  `alternative_name` varchar(255) NOT NULL,
  `kerugian` bigint(20) NOT NULL,
  `korban` int(11) NOT NULL,
  `urgensi` int(11) NOT NULL,
  `penyebaran` int(11) NOT NULL,
  `closeness_coefficient` decimal(10,8) NOT NULL,
  `ranking` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `topsis_results`
--

INSERT INTO `topsis_results` (`id`, `user_id`, `alternative_id`, `alternative_name`, `kerugian`, `korban`, `urgensi`, `penyebaran`, `closeness_coefficient`, `ranking`, `created_at`) VALUES
(16, 1, 'KJO-2025-002', 'Investasi Bodong Online', 55000000, 4, 5, 3, 0.62317448, 1, '2025-09-06 14:59:04'),
(17, 1, 'KJO-2025-003', 'Phishing Banking', 63000000, 3, 3, 2, 0.51641124, 2, '2025-09-06 14:59:04'),
(18, 1, 'KJO-2025-001', 'Penipuan Online Marketplace', 49944304, 3, 4, 3, 0.25687401, 3, '2025-09-06 14:59:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','client','officer') DEFAULT 'client',
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `full_name`, `phone`, `status`, `created_at`, `updated_at`, `last_login`) VALUES
(1, 'admin', 'admin@polseksaribudolok.go.id', '$2y$10$ecqBQUlBBsV5IpCaTb8kveypRr2fHLtEEEafGRTu9uYQyk8BLNeEO', 'admin', 'Administrator', NULL, 'active', '2025-09-06 15:20:44', '2025-09-06 15:20:44', NULL),
(2, 'officer1', 'officer1@polseksaribudolok.go.id', '$2y$10$rxjUr0u5HmQNEAbYzaJCo.Mg8kCVJuz.19xkNel1zD0chKABfH7LS', 'officer', 'Petugas Reskrim', NULL, 'active', '2025-09-06 15:20:44', '2025-09-06 15:20:44', NULL),
(3, 'anggota', 'anggota@dpr.cok', '$2y$10$RXuVA48qMvYNO8jQFGRLDueieE7BMCeoj.D7kU5t8c7/J.1GUl73O', 'client', NULL, NULL, 'active', '2025-09-06 15:36:06', '2025-09-06 15:36:06', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_case_summary`
-- (See below for the actual view)
--
CREATE TABLE `v_case_summary` (
`id` int(11)
,`case_number` varchar(50)
,`case_name` varchar(200)
,`case_type` enum('phishing','hacking','fraud','cyberbullying','identity_theft','online_scam','malware','other')
,`status` enum('pending','investigating','resolved','closed')
,`priority_level` enum('low','medium','high','critical')
,`estimated_loss` decimal(15,2)
,`victim_count` int(11)
,`report_date` timestamp
,`created_by_name` varchar(100)
,`evaluation_count` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_criteria_weights`
-- (See below for the actual view)
--
CREATE TABLE `v_criteria_weights` (
`id` int(11)
,`code` varchar(10)
,`name` varchar(100)
,`description` text
,`type` enum('benefit','cost')
,`weight` decimal(8,6)
,`is_active` tinyint(1)
);

-- --------------------------------------------------------

--
-- Structure for view `v_case_summary`
--
DROP TABLE IF EXISTS `v_case_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_case_summary`  AS SELECT `c`.`id` AS `id`, `c`.`case_number` AS `case_number`, `c`.`case_name` AS `case_name`, `c`.`case_type` AS `case_type`, `c`.`status` AS `status`, `c`.`priority_level` AS `priority_level`, `c`.`estimated_loss` AS `estimated_loss`, `c`.`victim_count` AS `victim_count`, `c`.`report_date` AS `report_date`, `u`.`full_name` AS `created_by_name`, count(`ce`.`id`) AS `evaluation_count` FROM ((`cases` `c` left join `users` `u` on(`c`.`created_by` = `u`.`id`)) left join `case_evaluations` `ce` on(`c`.`id` = `ce`.`case_id`)) GROUP BY `c`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `v_criteria_weights`
--
DROP TABLE IF EXISTS `v_criteria_weights`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_criteria_weights`  AS SELECT `c`.`id` AS `id`, `c`.`code` AS `code`, `c`.`name` AS `name`, `c`.`description` AS `description`, `c`.`type` AS `type`, `c`.`weight` AS `weight`, `c`.`is_active` AS `is_active` FROM `criteria` AS `c` WHERE `c`.`is_active` = 1 ORDER BY `c`.`weight` DESC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ahp_comparisons`
--
ALTER TABLE `ahp_comparisons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_comparison` (`session_id`,`criteria1_id`,`criteria2_id`),
  ADD KEY `criteria1_id` (`criteria1_id`),
  ADD KEY `criteria2_id` (`criteria2_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_ahp_session` (`session_id`);

--
-- Indexes for table `ahp_matrix`
--
ALTER TABLE `ahp_matrix`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ahp_results`
--
ALTER TABLE `ahp_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `criteria_id` (`criteria_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `alternatives`
--
ALTER TABLE `alternatives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `case_id` (`case_id`);

--
-- Indexes for table `alternative_scores`
--
ALTER TABLE `alternative_scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_score` (`alternative_id`,`criteria_id`),
  ADD KEY `criteria_id` (`criteria_id`);

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `case_number` (`case_number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_cases_status` (`status`),
  ADD KEY `idx_cases_type` (`case_type`),
  ADD KEY `idx_cases_date` (`report_date`);

--
-- Indexes for table `case_evaluations`
--
ALTER TABLE `case_evaluations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_evaluation` (`case_id`,`criteria_id`),
  ADD KEY `criteria_id` (`criteria_id`),
  ADD KEY `evaluator_id` (`evaluator_id`),
  ADD KEY `idx_case_evaluations_case` (`case_id`);

--
-- Indexes for table `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `criteria_weights`
--
ALTER TABLE `criteria_weights`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_weight` (`criteria_id`);

--
-- Indexes for table `decision_sessions`
--
ALTER TABLE `decision_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_id` (`session_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `pairwise_comparisons`
--
ALTER TABLE `pairwise_comparisons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_comparison` (`criteria1_id`,`criteria2_id`),
  ADD KEY `criteria2_id` (`criteria2_id`);

--
-- Indexes for table `rankings`
--
ALTER TABLE `rankings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ranking` (`alternative_name`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `sub_criteria`
--
ALTER TABLE `sub_criteria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `criteria_id` (`criteria_id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_logs_user` (`user_id`),
  ADD KEY `idx_logs_date` (`created_at`);

--
-- Indexes for table `topsis_analysis_cases`
--
ALTER TABLE `topsis_analysis_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created_by` (`created_by`),
  ADD KEY `idx_case_id` (`case_id`),
  ADD KEY `idx_topsis_analysis_created_by` (`created_by`),
  ADD KEY `idx_topsis_analysis_case_id` (`case_id`);

--
-- Indexes for table `topsis_calculations`
--
ALTER TABLE `topsis_calculations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_topsis_session` (`session_id`),
  ADD KEY `idx_topsis_calculated_by` (`calculated_by`),
  ADD KEY `fk_topsis_case` (`case_id`);

--
-- Indexes for table `topsis_results`
--
ALTER TABLE `topsis_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_username` (`username`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ahp_comparisons`
--
ALTER TABLE `ahp_comparisons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ahp_matrix`
--
ALTER TABLE `ahp_matrix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=449;

--
-- AUTO_INCREMENT for table `ahp_results`
--
ALTER TABLE `ahp_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `alternatives`
--
ALTER TABLE `alternatives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `alternative_scores`
--
ALTER TABLE `alternative_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `case_evaluations`
--
ALTER TABLE `case_evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `criteria_weights`
--
ALTER TABLE `criteria_weights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `decision_sessions`
--
ALTER TABLE `decision_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pairwise_comparisons`
--
ALTER TABLE `pairwise_comparisons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=363;

--
-- AUTO_INCREMENT for table `rankings`
--
ALTER TABLE `rankings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sub_criteria`
--
ALTER TABLE `sub_criteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `topsis_analysis_cases`
--
ALTER TABLE `topsis_analysis_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `topsis_calculations`
--
ALTER TABLE `topsis_calculations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `topsis_results`
--
ALTER TABLE `topsis_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ahp_comparisons`
--
ALTER TABLE `ahp_comparisons`
  ADD CONSTRAINT `ahp_comparisons_ibfk_1` FOREIGN KEY (`criteria1_id`) REFERENCES `criteria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ahp_comparisons_ibfk_2` FOREIGN KEY (`criteria2_id`) REFERENCES `criteria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ahp_comparisons_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ahp_results`
--
ALTER TABLE `ahp_results`
  ADD CONSTRAINT `ahp_results_ibfk_1` FOREIGN KEY (`criteria_id`) REFERENCES `criteria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ahp_results_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `alternatives`
--
ALTER TABLE `alternatives`
  ADD CONSTRAINT `alternatives_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `alternative_scores`
--
ALTER TABLE `alternative_scores`
  ADD CONSTRAINT `alternative_scores_ibfk_1` FOREIGN KEY (`alternative_id`) REFERENCES `alternatives` (`id`),
  ADD CONSTRAINT `alternative_scores_ibfk_2` FOREIGN KEY (`criteria_id`) REFERENCES `criteria` (`id`);

--
-- Constraints for table `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `cases_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `case_evaluations`
--
ALTER TABLE `case_evaluations`
  ADD CONSTRAINT `case_evaluations_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `case_evaluations_ibfk_2` FOREIGN KEY (`criteria_id`) REFERENCES `criteria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `case_evaluations_ibfk_3` FOREIGN KEY (`evaluator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `criteria_weights`
--
ALTER TABLE `criteria_weights`
  ADD CONSTRAINT `criteria_weights_ibfk_1` FOREIGN KEY (`criteria_id`) REFERENCES `criteria` (`id`);

--
-- Constraints for table `decision_sessions`
--
ALTER TABLE `decision_sessions`
  ADD CONSTRAINT `decision_sessions_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pairwise_comparisons`
--
ALTER TABLE `pairwise_comparisons`
  ADD CONSTRAINT `pairwise_comparisons_ibfk_1` FOREIGN KEY (`criteria1_id`) REFERENCES `criteria` (`id`),
  ADD CONSTRAINT `pairwise_comparisons_ibfk_2` FOREIGN KEY (`criteria2_id`) REFERENCES `criteria` (`id`);

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sub_criteria`
--
ALTER TABLE `sub_criteria`
  ADD CONSTRAINT `sub_criteria_ibfk_1` FOREIGN KEY (`criteria_id`) REFERENCES `criteria` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `topsis_analysis_cases`
--
ALTER TABLE `topsis_analysis_cases`
  ADD CONSTRAINT `topsis_analysis_cases_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `topsis_calculations`
--
ALTER TABLE `topsis_calculations`
  ADD CONSTRAINT `fk_topsis_case` FOREIGN KEY (`case_id`) REFERENCES `topsis_analysis_cases` (`case_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `topsis_calculations_ibfk_2` FOREIGN KEY (`calculated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
