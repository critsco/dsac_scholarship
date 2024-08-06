-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2024 at 05:13 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'fsuu', 'fsuu123');

-- --------------------------------------------------------

--
-- Table structure for table `benefits_filter`
--

CREATE TABLE `benefits_filter` (
  `id` int(11) NOT NULL,
  `option` varchar(255) NOT NULL,
  `date_click` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `benefits_filter`
--

INSERT INTO `benefits_filter` (`id`, `option`, `date_click`) VALUES
(74, 'Full tuition, fees, allowance per semester', '2023-12-20 11:07:08'),
(75, 'Full tuition only per semester', '2023-12-20 11:07:10'),
(76, 'Full tuition and fees only per semester', '2023-12-20 11:07:16'),
(77, 'Full tuition only per semester', '2023-12-20 11:08:08'),
(78, 'Full tuition only per semester', '2023-12-20 11:09:03'),
(79, 'Full tuition, fees, allowance per semester', '2023-12-20 11:09:06'),
(80, 'Php 30,000 - Php 60,000 per semester', '2023-12-20 11:09:11'),
(81, 'Php 10,000 - Php 29,999 per semester', '2023-12-20 11:09:13'),
(82, '15 to 24 units covered per semester', '2023-12-20 11:09:17'),
(83, 'Full tuition, fees, allowance per semester', '2023-12-20 11:56:10'),
(84, 'Full tuition, fees, allowance per semester', '2023-12-20 11:56:12'),
(85, 'Full tuition, fees, allowance per semester', '2023-12-20 12:00:36'),
(86, 'Full tuition only per semester', '2023-12-20 12:29:39'),
(87, 'Full tuition, fees, allowance per semester', '2023-12-20 14:27:57'),
(88, 'Covers miscellaneous fees only', '2023-12-20 14:44:18'),
(89, 'Full tuition, fees, allowance per semester', '2023-12-21 02:06:12'),
(90, 'Full tuition, fees, allowance per semester', '2023-12-21 02:15:41'),
(91, 'Full tuition, fees, allowance per semester', '2023-12-21 05:59:26'),
(92, 'Full tuition, fees, allowance per semester', '2023-12-21 05:59:30'),
(93, 'Full tuition only per semester', '2023-12-21 05:59:33'),
(94, 'Php 60,000 above per semester', '2023-12-21 05:59:37'),
(95, 'Php 30,000 - Php 60,000 per semester', '2023-12-21 05:59:42'),
(96, '15 to 24 units covered per semester', '2023-12-21 05:59:54'),
(97, 'Covers miscellaneous fees only', '2023-12-21 05:59:57'),
(98, 'Living, uniform, book, or transportation allowances', '2023-12-21 06:00:00'),
(99, 'Full tuition, fees, allowance per semester', '2023-12-21 07:37:42'),
(100, 'Full tuition, fees, allowance per semester', '2023-12-21 07:37:57'),
(101, 'Full tuition, fees, allowance per semester', '2024-01-08 08:49:15'),
(102, 'Full tuition and fees only per semester', '2024-01-08 08:49:18'),
(103, 'Full tuition, fees, allowance per semester', '2024-01-08 08:49:23'),
(104, 'Full tuition only per semester', '2024-01-08 08:49:27'),
(105, 'Full tuition, fees, allowance per semester', '2024-05-02 03:08:05'),
(106, 'Php 30,000 - Php 60,000 per semester', '2024-05-02 03:08:20');

-- --------------------------------------------------------

--
-- Table structure for table `details_click`
--

CREATE TABLE `details_click` (
  `id` int(11) NOT NULL,
  `scholarship` varchar(255) NOT NULL,
  `date_click` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `details_click`
--

INSERT INTO `details_click` (`id`, `scholarship`, `date_click`) VALUES
(74, 'Academic Scholarship Program', '2023-12-20 11:56:27'),
(75, 'Academic Scholarship Program', '2023-12-20 11:57:10'),
(76, 'Student Assistant Grant-In Aid Program', '2023-12-20 11:57:23'),
(77, 'Undergraduate Scholarships', '2023-12-20 11:57:26'),
(78, 'CHED-Merit Scholarship Program', '2023-12-20 11:57:28'),
(79, 'SM Foundation Scholarship Program', '2023-12-20 11:57:31'),
(80, 'Equi-Parco Scholarship Program', '2023-12-20 11:57:33'),
(81, 'Diocese of Butuan Scholarship', '2023-12-20 11:57:36'),
(82, 'Holy Cross Scholarship', '2023-12-20 11:57:39'),
(83, 'Academic Scholarship Program', '2023-12-20 11:57:42'),
(84, 'Student Assistant Grant-In Aid Program', '2023-12-20 11:57:45'),
(85, 'Undergraduate Scholarships', '2023-12-20 11:57:51'),
(86, 'Academic Scholarship Program', '2023-12-20 12:01:17'),
(87, 'Academic Scholarship Program', '2023-12-20 12:01:21'),
(88, 'Student Assistant Grant-In Aid Program', '2023-12-20 12:31:15'),
(89, 'Academic Scholarship Program', '2023-12-21 06:03:33'),
(90, 'Holy Cross Scholarship', '2024-05-02 03:08:23');

-- --------------------------------------------------------

--
-- Table structure for table `grade_level_filter`
--

CREATE TABLE `grade_level_filter` (
  `id` int(11) NOT NULL,
  `option` varchar(255) NOT NULL,
  `date_click` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade_level_filter`
--

INSERT INTO `grade_level_filter` (`id`, `option`, `date_click`) VALUES
(64, 'Basic Education', '2023-12-20 11:07:01'),
(65, 'College', '2023-12-20 11:07:03'),
(66, 'Graduate Studies', '2023-12-20 11:07:05'),
(67, 'College', '2023-12-20 11:56:01'),
(68, 'College', '2023-12-20 11:56:04'),
(69, 'College', '2023-12-20 11:56:07'),
(70, 'Basic Education', '2023-12-20 12:00:13'),
(71, 'Basic Education', '2023-12-20 12:00:15'),
(72, 'College', '2023-12-20 12:00:25'),
(73, 'Basic Education', '2023-12-20 12:29:36'),
(74, 'College', '2023-12-20 14:27:54'),
(75, 'Graduate Studies', '2023-12-20 14:44:12'),
(76, 'College', '2023-12-21 02:06:09'),
(77, 'College', '2023-12-21 02:15:35'),
(78, 'Graduate Studies', '2023-12-21 02:15:38'),
(79, 'Basic Education', '2023-12-21 05:59:17'),
(80, 'College', '2023-12-21 05:59:21'),
(81, 'Graduate Studies', '2023-12-21 05:59:23'),
(82, 'College', '2023-12-21 07:37:39'),
(83, 'College', '2024-01-08 08:49:33'),
(84, 'Basic Education', '2024-01-08 08:49:34'),
(85, 'College', '2024-05-02 03:08:03'),
(86, 'Basic Education', '2024-05-02 03:08:13'),
(87, 'College', '2024-05-02 03:08:15');

-- --------------------------------------------------------

--
-- Table structure for table `page_view`
--

CREATE TABLE `page_view` (
  `id` int(11) NOT NULL,
  `view` varchar(60) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page_view`
--

INSERT INTO `page_view` (`id`, `view`, `date`) VALUES
(105, 'fsuu3/index.php', '2023-12-20 02:33:55'),
(106, 'fsuu3/index.php', '2023-12-20 02:35:26'),
(107, 'fsuu3/index.php', '2023-12-20 02:36:03'),
(108, 'fsuu3/index.php', '2023-12-20 02:36:50'),
(109, 'fsuu3/index.php', '2023-12-20 02:38:36'),
(110, 'fsuu3/index.php', '2023-12-20 02:38:46'),
(111, 'fsuu3/index.php', '2023-12-20 02:39:23'),
(112, 'fsuu3/index.php', '2023-12-20 02:54:38'),
(113, 'fsuu3/index.php', '2023-12-20 02:58:22'),
(114, 'fsuu3/index.php', '2023-12-20 02:59:07'),
(115, 'fsuu3/index.php', '2023-12-20 03:01:17'),
(116, 'fsuu3/index.php', '2023-12-20 03:01:47'),
(117, 'fsuu3/index.php', '2023-12-20 03:02:23'),
(118, 'fsuu3/index.php', '2023-12-20 03:02:37'),
(119, 'fsuu3/index.php', '2023-12-20 03:09:10'),
(120, 'fsuu3/index.php', '2023-12-20 03:11:35'),
(121, 'fsuu3/index.php', '2023-12-20 03:13:08'),
(122, 'fsuu3/index.php', '2023-12-20 03:14:16'),
(123, 'fsuu3/index.php', '2023-12-20 03:14:29'),
(124, 'fsuu3/index.php', '2023-12-20 03:15:42'),
(125, 'fsuu3/index.php', '2023-12-20 03:16:19'),
(126, 'fsuu3/index.php', '2023-12-20 03:17:09'),
(127, 'fsuu3/index.php', '2023-12-20 03:17:56'),
(128, 'fsuu3/index.php', '2023-12-20 03:19:12'),
(129, 'fsuu3/index.php', '2023-12-20 03:19:33'),
(130, 'fsuu3/index.php', '2023-12-20 03:20:12'),
(131, 'fsuu3/index.php', '2023-12-20 03:21:47'),
(132, 'fsuu3/index.php', '2023-12-20 03:22:15'),
(133, 'fsuu3/index.php', '2023-12-20 03:23:09'),
(134, 'fsuu3/index.php', '2023-12-20 03:23:32'),
(135, 'fsuu3/index.php', '2023-12-20 03:23:56'),
(136, 'fsuu3/index.php', '2023-12-20 03:24:19'),
(137, 'fsuu3/index.php', '2023-12-20 03:24:38'),
(138, 'fsuu3/index.php', '2023-12-20 03:27:04'),
(139, 'fsuu3/index.php', '2023-12-20 03:28:55'),
(140, 'fsuu3/index.php', '2023-12-20 03:41:42'),
(141, 'fsuu3/index.php', '2023-12-20 03:57:16'),
(142, 'fsuu3/index.php', '2023-12-20 03:57:53'),
(143, 'fsuu3/index.php', '2023-12-20 03:58:49'),
(144, 'fsuu3/index.php', '2023-12-20 04:01:05'),
(145, 'fsuu3/index.php', '2023-12-20 04:01:21'),
(146, 'fsuu3/index.php', '2023-12-20 04:02:51'),
(147, 'fsuu3/index.php', '2023-12-20 04:03:07'),
(148, 'fsuu3/index.php', '2023-12-20 04:03:15'),
(149, 'fsuu3/index.php', '2023-12-20 04:03:46'),
(150, 'fsuu3/index.php', '2023-12-20 04:06:42'),
(151, 'fsuu3/index.php', '2023-12-20 04:08:02'),
(152, 'fsuu3/index.php', '2023-12-20 04:09:00'),
(153, 'fsuu3/index.php', '2023-12-20 04:19:01'),
(154, 'fsuu3/index.php', '2023-12-20 05:06:55'),
(155, 'fsuu3/index.php', '2023-12-20 05:19:28'),
(156, 'fsuu3/index.php', '2023-12-20 05:25:42'),
(157, 'fsuu3/index.php', '2023-12-20 05:26:33'),
(158, 'fsuu3/index.php', '2023-12-20 05:27:00'),
(159, 'fsuu3/index.php', '2023-12-20 05:27:20'),
(160, 'fsuu3/index.php', '2023-12-20 05:27:31'),
(161, 'fsuu3/index.php', '2023-12-20 05:27:56'),
(162, 'fsuu3/index.php', '2023-12-20 05:28:29'),
(163, 'fsuu3/index.php', '2023-12-20 05:29:07'),
(164, 'fsuu3/index.php', '2023-12-20 07:07:19'),
(165, 'fsuu3/index.php', '2023-12-20 07:27:01'),
(166, 'fsuu3/index.php', '2023-12-20 07:33:55'),
(167, 'fsuu3/index.php', '2023-12-20 07:37:10'),
(168, 'fsuu3/index.php', '2023-12-20 07:38:23'),
(169, 'fsuu3/index.php', '2023-12-20 07:50:30'),
(170, 'fsuu3/index.php', '2023-12-20 07:51:25'),
(171, 'fsuu3/index.php', '2023-12-20 18:49:56'),
(172, 'fsuu3/index.php', '2023-12-20 19:11:40'),
(173, 'fsuu3/index.php', '2023-12-20 19:13:02'),
(174, 'fsuu3/index.php', '2023-12-20 19:20:29'),
(175, 'fsuu3/index.php', '2023-12-20 19:21:31'),
(176, 'fsuu3/index.php', '2023-12-20 19:26:22'),
(177, 'fsuu3/index.php', '2023-12-20 22:46:18'),
(178, 'fsuu3/index.php', '2023-12-22 08:07:38'),
(179, 'fsuu3/index.php', '2023-12-22 08:09:27'),
(180, 'fsuu3/index.php', '2023-12-22 08:12:54'),
(181, 'fsuu3/index.php', '2023-12-25 04:01:23'),
(182, 'fsuu3/index.php', '2023-12-25 04:01:40'),
(183, 'fsuu3/index.php', '2024-01-08 01:34:37'),
(184, 'fsuu3/index.php', '2024-01-08 01:36:22'),
(185, 'fsuu3/index.php', '2024-01-08 01:37:06'),
(186, 'fsuu3/index.php', '2024-01-08 01:41:46'),
(187, 'fsuu3/index.php', '2024-01-08 01:42:05'),
(188, 'fsuu3/index.php', '2024-01-08 01:45:37'),
(189, 'fsuu3/index.php', '2024-01-08 01:48:18'),
(190, 'fsuu3/index.php', '2024-01-08 01:59:27'),
(191, 'fsuu3/index.php', '2024-01-08 02:00:57'),
(192, 'fsuu3/index.php', '2024-01-08 02:02:39'),
(193, 'fsuu3/index.php', '2024-01-08 02:07:32'),
(194, 'fsuu3/index.php', '2024-01-08 02:08:12'),
(195, 'fsuu3/index.php', '2024-01-08 02:10:43'),
(196, 'fsuu3/index.php', '2024-01-08 02:12:41'),
(197, 'fsuu3/index.php', '2024-01-08 05:21:28'),
(198, 'fsuu3/index.php', '2024-01-08 05:21:28'),
(199, 'fsuu3/index.php', '2024-01-08 06:04:54'),
(200, 'fsuu3/index.php', '2024-01-08 06:06:38'),
(201, 'fsuu3/index.php', '2024-05-01 21:07:43'),
(202, 'fsuu3/index.php', '2024-05-01 21:07:54'),
(203, 'fsuu3/index.php', '2024-05-01 21:08:53'),
(204, 'fsuu3/index.php', '2024-05-01 21:10:22');

-- --------------------------------------------------------

--
-- Table structure for table `scholarship`
--

CREATE TABLE `scholarship` (
  `id` int(11) NOT NULL,
  `type` varchar(60) NOT NULL,
  `provider` varchar(60) NOT NULL,
  `scholarship` varchar(100) NOT NULL,
  `grade_level` varchar(60) NOT NULL,
  `benefits` varchar(150) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `file_attachment` varchar(60) NOT NULL,
  `status` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scholarship`
--

INSERT INTO `scholarship` (`id`, `type`, `provider`, `scholarship`, `grade_level`, `benefits`, `post_date`, `start_date`, `end_date`, `file_attachment`, `status`) VALUES
(209, 'FSUU Scholarships', 'das', 'dasda', 'Basic Education', 'Full tuition, fees, allowance per semester', '2023-12-15 16:00:42', '2023-12-16', '2023-12-16', '', ''),
(211, 'FSUU Scholarships', 'das', 'das', '', '', '2023-12-15 16:39:52', '2023-01-01', '2023-01-01', '', ''),
(213, 'FSUU Scholarships', 'haha', 'haha', '', '', '2023-12-15 16:41:10', '2023-01-01', '2023-01-01', '', ''),
(215, 'Government Scholarships', 'yesyesyow', 'yessir', '', '', '2023-12-15 16:42:29', '2023-01-01', '2023-01-01', '', ''),
(216, 'FSUU Scholarships', 'ilovetheway', 'youmakemesmile yes sir!', '', '', '2023-12-15 16:43:38', '2023-01-01', '2023-01-01', '', ''),
(217, 'FSUU Scholarships', 'FSUU', 'sample', '', '', '2023-12-15 16:46:34', '2023-01-01', '2023-01-01', '', ''),
(218, 'FSUU Scholarships', 'zxc', 'zxc', '', '', '2023-12-15 16:46:50', '2023-01-01', '2023-01-01', '', ''),
(219, 'Government Scholarships', 'bvbn', 'sda', '', '', '2023-12-15 16:47:58', '2023-01-01', '2023-01-01', '', ''),
(254, 'FSUU Funded Scholarships', 'FSUU', 'Academic Scholarship Program', 'College', 'Full tuition, fees, allowance per semester', '2023-12-20 11:00:01', '2023-12-01', '2023-12-20', 'uploads/6582b846a5943_SAMPLE PDF.pdf', 'Post'),
(255, 'FSUU Funded Scholarships', 'FSUU', 'Student Assistant Grant-In Aid Program', 'College', '15 to 24 units covered per semester', '2023-12-20 11:00:18', '2023-12-02', '2023-12-21', 'uploads/6582b85d6604a_SAMPLE PDF.pdf', 'Post'),
(256, 'Government Funded Scholarships', 'DOST', 'Undergraduate Scholarships', 'College', 'Php 60,000 above per semester', '2023-12-20 11:00:25', '2023-12-03', '2023-12-22', 'uploads/6582b86d320b7_SAMPLE PDF.pdf', 'Post'),
(257, 'Government Funded Scholarships', 'CHED', 'CHED-Merit Scholarship Program', 'College', 'Php 30,000 - Php 60,000 per semester', '2023-12-20 11:00:33', '2023-12-04', '2023-12-23', 'uploads/6582b87c8fe71_SAMPLE PDF.pdf', 'Post'),
(258, 'Private Funded Scholarships', 'Equi-Parco', 'Equi-Parco Scholarship Program', 'College', 'Full tuition, fees, allowance per semester', '2023-12-20 11:05:45', '2023-12-05', '2023-12-24', 'uploads/1703065835_SAMPLE PDF.pdf', 'Post'),
(259, 'Private Funded Scholarships', 'SM', 'SM Foundation Scholarship Program', 'College', 'Php 10,000 - Php 29,999 per semester', '2023-12-20 11:05:37', '2023-12-06', '2023-12-25', 'uploads/1703065906_SAMPLE PDF.pdf', 'Post'),
(260, 'Civic/Religious Funded Scholarships', 'Diocese of Butuan', 'Diocese of Butuan Scholarship', 'Basic Education', 'Full tuition only per semester', '2023-12-20 11:08:48', '2023-12-07', '2023-12-26', 'uploads/1703065956_SAMPLE PDF.pdf', 'Post'),
(261, 'Civic/Religious Funded Scholarships', 'Holy Cross', 'Holy Cross Scholarship', 'Basic Education', 'Full tuition only per semester', '2023-12-20 11:08:58', '2023-12-08', '2023-12-27', 'uploads/1703066025_SAMPLE PDF.pdf', 'Post'),
(270, '', '', '', '', '', '2023-12-20 14:52:21', '2023-01-01', '2023-01-01', '', ''),
(277, 'Government Funded Scholarships', 'DOST', 'Junior Level Science Scholarships (JLSS)', 'College', 'Php 60,000 above per semester', '2024-01-08 13:13:43', '2024-01-08', '2024-01-08', 'uploads/659bf506cd7cf_SAMPLE PDF.pdf', 'Archive'),
(278, 'Civic/Religious Funded Scholarships', '', '', '', '', '2024-01-08 09:56:26', '2023-01-01', '2023-01-01', '', 'Draft'),
(280, 'Government Funded Scholarships', '', '', '', '', '2024-01-08 13:11:13', '2023-01-01', '2023-01-01', '', 'Draft');

-- --------------------------------------------------------

--
-- Table structure for table `type_filter`
--

CREATE TABLE `type_filter` (
  `id` int(11) NOT NULL,
  `option` varchar(255) NOT NULL,
  `date_click` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `type_filter`
--

INSERT INTO `type_filter` (`id`, `option`, `date_click`) VALUES
(78, 'Private Funded Scholarships', '2023-12-20 11:06:46'),
(79, 'FSUU Funded Scholarships', '2023-12-20 11:06:51'),
(80, 'Government Funded Scholarships', '2023-12-20 11:06:53'),
(81, 'Civic/Religious Funded Scholarships', '2023-12-20 11:06:55'),
(82, 'FSUU Funded Scholarships', '2023-12-20 11:55:55'),
(83, 'FSUU Funded Scholarships', '2023-12-20 11:55:58'),
(84, 'Government Funded Scholarships', '2023-12-20 11:59:41'),
(85, 'Government Funded Scholarships', '2023-12-20 11:59:44'),
(86, 'Private Funded Scholarships', '2023-12-20 11:59:46'),
(87, 'FSUU Funded Scholarships', '2023-12-20 11:59:49'),
(88, 'FSUU Funded Scholarships', '2023-12-20 12:00:03'),
(89, 'Government Funded Scholarships', '2023-12-20 12:29:20'),
(90, 'FSUU Funded Scholarships', '2023-12-20 14:27:49'),
(91, 'Government Funded Scholarships', '2023-12-20 14:44:09'),
(92, 'FSUU Funded Scholarships', '2023-12-21 02:06:06'),
(93, 'Government Funded Scholarships', '2023-12-21 02:15:31'),
(94, 'FSUU Funded Scholarships', '2023-12-21 05:59:03'),
(95, 'Government Funded Scholarships', '2023-12-21 05:59:06'),
(96, 'Private Funded Scholarships', '2023-12-21 05:59:09'),
(97, 'Civic/Religious Funded Scholarships', '2023-12-21 05:59:13'),
(98, 'FSUU Funded Scholarships', '2023-12-21 07:37:37'),
(99, 'FSUU Funded Scholarships', '2024-05-02 03:08:00'),
(100, 'Government Funded Scholarships', '2024-05-02 03:08:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `benefits_filter`
--
ALTER TABLE `benefits_filter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `details_click`
--
ALTER TABLE `details_click`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grade_level_filter`
--
ALTER TABLE `grade_level_filter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_view`
--
ALTER TABLE `page_view`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scholarship`
--
ALTER TABLE `scholarship`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `type_filter`
--
ALTER TABLE `type_filter`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `benefits_filter`
--
ALTER TABLE `benefits_filter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `details_click`
--
ALTER TABLE `details_click`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `grade_level_filter`
--
ALTER TABLE `grade_level_filter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `page_view`
--
ALTER TABLE `page_view`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205;

--
-- AUTO_INCREMENT for table `scholarship`
--
ALTER TABLE `scholarship`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=282;

--
-- AUTO_INCREMENT for table `type_filter`
--
ALTER TABLE `type_filter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
