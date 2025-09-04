-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2025 at 10:27 AM
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
-- Database: `my-rfid`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_logs`
--

CREATE TABLE `access_logs` (
  `id` int(11) NOT NULL,
  `rfid_tag` varchar(50),
  `owner_name` varchar(255) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access_logs`
--

INSERT INTO `access_logs` (`id`, `rfid_tag`, `owner_name`, `plate_number`, `vehicle_type`, `time_in`, `time_out`, `timestamp`) VALUES
(26, '4180430052', 'Gandev', '123ABC', 'Motorcycle', '2025-05-07 12:10:22', '2025-05-07 12:13:39', '2025-05-07 04:10:22'),
(27, '4180430052', 'Gandev', '123ABC', 'Motorcycle', '2025-05-07 12:13:50', '2025-05-07 17:39:34', '2025-05-07 04:13:50'),
(28, '3676157712', 'Johsua Rey M. Burce', 'Vh2013', 'Motorcycle', '2025-05-07 12:57:45', NULL, '2025-05-07 04:57:45'),
(29, '3749799956', 'Shaira Quines', 'DJN1038', 'Motorcycle', '2025-05-07 12:58:00', '2025-05-07 12:59:16', '2025-05-07 04:58:00'),
(30, '4180283060', 'Marieane Mundia', 'WTY726', 'Motorcycle', '2025-05-07 15:43:36', NULL, '2025-05-07 07:43:36'),
(31, '3749799956', 'Johsua Rey M. Burce', 'Vh2013', 'Motorcycle', '2025-05-07 17:19:58', '2025-05-07 17:20:05', '2025-05-07 09:19:58'),
(32, '36761577162', 'Shaira Quines', 'DEU411', 'Motorcycle', '2025-05-07 19:45:06', NULL, '2025-05-07 11:45:06'),
(33, '4180430052', 'Jomel Garcia', 'HGA175', 'Motorcycle', '2025-05-07 19:45:32', '2025-05-07 19:45:41', '2025-05-07 11:45:32'),
(34, '4180430052', 'Jomel Garcia', 'HGA175', 'Motorcycle', '2025-05-07 19:45:42', '2025-05-07 19:46:24', '2025-05-07 11:45:42'),
(35, '4180430052', 'Jomel Garcia', 'HGA175', 'Motorcycle', '2025-05-07 19:46:27', '2025-05-07 19:46:33', '2025-05-07 11:46:27'),
(36, '4180430052', 'Jomel Garcia', 'HGA175', 'Motorcycle', '2025-05-07 19:47:32', '2025-05-07 20:32:29', '2025-05-07 11:47:32'),
(37, '4181160948', 'Shingaling', 'EJHF742', 'Motorcycle', '2025-05-07 20:32:12', '2025-05-07 20:32:26', '2025-05-07 12:32:12'),
(38, '3749799956', 'Rheagan Villarin V.', 'DBEIE123', 'Motorcycle', '2025-05-07 20:32:16', '2025-05-07 20:32:18', '2025-05-07 12:32:16'),
(39, '3749799956', 'Rheagan Villarin V.', 'DBEIE123', 'Motorcycle', '2025-05-07 20:32:19', '2025-05-07 20:32:25', '2025-05-07 12:32:19'),
(40, '4181160948', 'Shingaling', 'EJHF742', 'Motorcycle', '2025-05-07 20:32:27', '2025-05-07 20:33:11', '2025-05-07 12:32:27'),
(41, '4180430052', 'Jomel Garcia', 'HGA175', 'Motorcycle', '2025-05-07 20:32:30', '2025-05-07 20:32:32', '2025-05-07 12:32:30'),
(42, '3749799956', 'Rheagan Villarin V.', 'DBEIE123', 'Motorcycle', '2025-05-07 20:32:49', '2025-05-07 20:32:55', '2025-05-07 12:32:49'),
(43, '4180430052', 'Jomel Garcia', 'HGA175', 'Motorcycle', '2025-05-07 20:33:01', '2025-05-07 20:33:02', '2025-05-07 12:33:01'),
(44, '4180430052', 'Jomel Garcia', 'HGA175', 'Motorcycle', '2025-05-07 20:33:04', '2025-05-07 20:33:09', '2025-05-07 12:33:04'),
(45, '3749799956', 'Rheagan Villarin V.', 'DBEIE123', 'Motorcycle', '2025-05-07 20:33:10', '2025-05-07 20:33:15', '2025-05-07 12:33:10'),
(46, '4181160948', 'Shingaling', 'EJHF742', 'Motorcycle', '2025-05-07 20:33:13', '2025-05-07 20:33:14', '2025-05-07 12:33:13'),
(47, '4181160948', 'Shingaling', 'EJHF742', 'Motorcycle', '2025-05-07 20:33:16', '2025-05-07 20:33:51', '2025-05-07 12:33:16'),
(48, '4181160948', 'Shingaling', 'EJHF742', 'Motorcycle', '2025-05-07 20:34:02', NULL, '2025-05-07 12:34:02'),
(49, '127627173', 'Darren', 'AGT715', 'Motorcycle', '2025-05-09 10:16:37', '2025-05-09 10:19:01', '2025-05-09 02:16:37'),
(50, '36761577123', 'Mary Asia', '321021', 'Motorcycle', '2025-05-09 10:16:46', '2025-05-09 10:53:14', '2025-05-09 02:16:46'),
(51, '4180430052', 'Jomel Garcia', 'HGA175', 'Motorcycle', '2025-05-09 10:16:54', NULL, '2025-05-09 02:16:54'),
(52, '4180283060', 'Marieane Mundia', 'WTY726', 'Motorcycle', '2025-05-09 10:17:02', NULL, '2025-05-09 02:17:02'),
(53, '3676157711', 'Recca Mae', 'JHDG0219', 'Motorcycle', '2025-05-09 10:17:23', '2025-05-09 10:17:28', '2025-05-09 02:17:23'),
(54, '3749799952', 'Toper mundia', 'DJN1038', 'Car', '2025-05-09 10:18:56', '2025-05-09 10:55:24', '2025-05-09 02:18:56'),
(55, '127627173', 'Darren', 'AGT715', 'Motorcycle', '2025-05-09 10:20:55', '2025-05-09 10:20:57', '2025-05-09 02:20:55'),
(56, '3676157712', 'Barbie Repalda', 'DGT715', 'Motorcycle', '2025-05-09 10:53:18', NULL, '2025-05-09 02:53:18'),
(57, '36761577123', 'Mary Asia', '321021', 'Motorcycle', '2025-05-09 10:53:25', NULL, '2025-05-09 02:53:25'),
(58, '3676157712', 'Barbie Repalda', 'DGT715', 'Motorcycle', '2025-05-12 13:26:16', NULL, '2025-05-12 05:26:16'),
(59, '36761577123', 'Mary Asia', '321021', 'Motorcycle', '2025-05-12 13:26:59', '2025-05-12 13:29:24', '2025-05-12 05:26:59'),
(60, '3749799952', 'Toper mundia', 'DJN1038', 'Car', '2025-05-12 13:29:46', NULL, '2025-05-12 05:29:46'),
(61, '4180430052', 'Jomel Garcia', 'HGA175', 'Motorcycle', '2025-05-12 13:29:58', '2025-05-12 13:35:14', '2025-05-12 05:29:58'),
(62, '3676157711', 'Recca Mae', 'JHDG0219', 'Motorcycle', '2025-05-12 13:34:56', '2025-05-12 13:35:00', '2025-05-12 05:34:56'),
(63, '4181160948', 'Clyde Ebilane', 'WYT75', 'Motorcycle', '2025-05-12 13:38:39', NULL, '2025-05-12 05:38:39'),
(64, '4181160948', 'Clyde Ebilane', 'WYT75', 'Motorcycle', '2025-05-13 12:10:17', '2025-05-13 12:11:28', '2025-05-13 04:10:17'),
(65, '4180430052', 'Jomel Garcia', 'HGA175', 'Motorcycle', '2025-05-13 12:10:48', '2025-05-13 12:11:35', '2025-05-13 04:10:48'),
(66, '4180283060', 'Marieane Mundia', 'WTY726', 'Motorcycle', '2025-05-13 12:10:55', '2025-05-13 12:11:15', '2025-05-13 04:10:55'),
(67, '36761577123', 'Mary Asia', '321021', 'Motorcycle', '2025-05-13 12:11:01', NULL, '2025-05-13 04:11:01'),
(68, '3676157712', 'Barbie Repalda', 'DGT715', 'Motorcycle', '2025-05-13 12:11:09', NULL, '2025-05-13 04:11:09'),
(69, '367615771', 'Barbie Repalda', 'DGT715', 'Motorcycle', '2025-05-13 12:17:06', '2025-05-13 12:17:09', '2025-05-13 04:17:06'),
(70, '4180283061', 'Marieane Mundia', 'WTY726', 'Motorcycle', '2025-05-13 12:19:08', NULL, '2025-05-13 04:19:08'),
(71, '36761577161', 'Joseph Barera', 'AKB715', 'Car', '2025-05-13 12:40:35', NULL, '2025-05-13 04:40:35'),
(72, '367615771', 'Barbie Repalda', 'DGT715', 'Motorcycle', '2025-05-13 12:40:52', NULL, '2025-05-13 04:40:52'),
(73, '3749799952', 'Toper mundia', 'DJN1038', 'Car', '2025-05-13 13:52:55', NULL, '2025-05-13 05:52:55'),
(74, '3676157716', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-05-13 20:53:15', '2025-05-13 20:54:15', '2025-05-13 12:53:15'),
(75, '4180430052', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-05-13 20:53:24', '2025-05-13 20:54:12', '2025-05-13 12:53:24'),
(76, '4181160948', 'Shaira Quines', 'ABC1238', 'Motorcycle', '2025-05-13 20:53:36', '2025-05-13 20:54:09', '2025-05-13 12:53:36'),
(77, '3749799956', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-05-13 20:53:45', '2025-05-13 20:54:06', '2025-05-13 12:53:45'),
(78, '4180283060', 'Rheagan Villarin', 'ABC1234', 'Car', '2025-05-13 20:53:53', '2025-05-13 20:54:02', '2025-05-13 12:53:53'),
(79, '3676157716', 'Recca Mae', 'HDH274', 'Motorcycle', '2025-05-14 06:53:41', '2025-05-14 10:51:40', '2025-05-13 22:53:41'),
(80, '3676157716', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-05-14 10:51:45', '2025-05-14 10:51:51', '2025-05-14 02:51:45'),
(81, '4181160948', 'Shaira Quines', 'ABC1238', 'Motorcycle', '2025-05-14 10:52:00', '2025-05-14 10:52:21', '2025-05-14 02:52:00'),
(82, '4180430052', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-05-14 10:52:08', '2025-05-14 10:52:17', '2025-05-14 02:52:08'),
(83, '3749799956', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-05-14 13:04:31', NULL, '2025-05-14 05:04:31'),
(84, '3676157716', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 14:35:52', '2025-07-10 14:36:50', '2025-07-10 06:35:52'),
(85, '3749799956', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 14:36:05', '2025-07-10 14:36:45', '2025-07-10 06:36:05'),
(86, '4180430052', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 14:36:12', '2025-07-10 14:36:57', '2025-07-10 06:36:12'),
(87, '4181160948', 'Shaira Quines', 'ABC1238', 'Motorcycle', '2025-07-10 14:36:22', '2025-07-10 14:36:39', '2025-07-10 06:36:22'),
(88, '4180430052', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 14:36:59', '2025-07-10 14:39:48', '2025-07-10 06:36:59'),
(89, '4180430052', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 14:39:56', NULL, '2025-07-10 06:39:56'),
(90, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 15:02:07', '2025-07-10 15:02:43', '2025-07-10 07:02:07'),
(91, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 15:04:19', '2025-07-10 15:07:15', '2025-07-10 07:04:19'),
(92, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 15:04:27', '2025-07-10 15:07:22', '2025-07-10 07:04:27'),
(93, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 15:14:38', '2025-07-10 15:17:57', '2025-07-10 07:14:38'),
(94, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 15:17:56', '2025-07-10 15:18:02', '2025-07-10 07:17:56'),
(95, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 15:17:58', '2025-07-10 15:18:03', '2025-07-10 07:17:58'),
(96, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 15:18:16', '2025-07-10 15:18:45', '2025-07-10 07:18:16'),
(97, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 15:18:44', '2025-07-10 15:19:01', '2025-07-10 07:18:44'),
(98, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 15:18:53', '2025-07-10 15:19:09', '2025-07-10 07:18:53'),
(99, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 15:19:17', '2025-07-10 15:19:36', '2025-07-10 07:19:17'),
(100, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 15:19:26', '2025-07-10 15:19:50', '2025-07-10 07:19:26'),
(101, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 15:19:33', '2025-07-10 15:19:59', '2025-07-10 07:19:33'),
(102, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 15:19:55', '2025-07-10 15:20:03', '2025-07-10 07:19:55'),
(103, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 15:26:06', '2025-07-10 15:30:59', '2025-07-10 07:26:06'),
(104, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 15:26:10', '2025-07-10 15:33:08', '2025-07-10 07:26:10'),
(105, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 15:30:51', '2025-07-10 15:57:26', '2025-07-10 07:30:51'),
(106, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 15:32:47', '2025-07-10 15:32:52', '2025-07-10 07:32:47'),
(107, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 15:33:20', '2025-07-10 15:36:55', '2025-07-10 07:33:20'),
(108, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 15:45:26', '2025-07-10 15:50:17', '2025-07-10 07:45:26'),
(109, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 15:45:58', '2025-07-10 16:04:18', '2025-07-10 07:45:58'),
(110, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 15:57:27', '2025-07-10 15:57:28', '2025-07-10 07:57:27'),
(111, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 15:57:29', '2025-07-10 15:57:30', '2025-07-10 07:57:29'),
(112, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 15:58:04', '2025-07-10 15:58:06', '2025-07-10 07:58:04'),
(113, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 15:58:07', '2025-07-10 15:58:09', '2025-07-10 07:58:07'),
(114, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 15:58:23', '2025-07-10 15:58:42', '2025-07-10 07:58:23'),
(115, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:00:20', '2025-07-10 16:00:42', '2025-07-10 08:00:20'),
(116, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 16:00:25', '2025-07-10 16:10:02', '2025-07-10 08:00:25'),
(117, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:07:12', '2025-07-10 16:07:14', '2025-07-10 08:07:12'),
(118, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:07:13', '2025-07-10 16:07:16', '2025-07-10 08:07:13'),
(119, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:07:14', '2025-07-10 16:07:15', '2025-07-10 08:07:14'),
(120, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:07:16', '2025-07-10 16:09:06', '2025-07-10 08:07:16'),
(121, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:07:17', '2025-07-10 16:07:18', '2025-07-10 08:07:17'),
(122, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:07:18', '2025-07-10 16:07:19', '2025-07-10 08:07:18'),
(123, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:07:20', '2025-07-10 16:07:21', '2025-07-10 08:07:20'),
(124, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:07:21', '2025-07-10 16:08:01', '2025-07-10 08:07:21'),
(125, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:08:02', '2025-07-10 16:08:03', '2025-07-10 08:08:02'),
(126, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:08:03', '2025-07-10 16:08:04', '2025-07-10 08:08:03'),
(127, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:08:05', '2025-07-10 16:08:05', '2025-07-10 08:08:05'),
(128, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:08:06', '2025-07-10 16:08:07', '2025-07-10 08:08:06'),
(129, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:09:07', '2025-07-10 16:10:00', '2025-07-10 08:09:07'),
(130, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:09:07', '2025-07-10 16:09:08', '2025-07-10 08:09:07'),
(131, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:09:09', '2025-07-10 16:09:09', '2025-07-10 08:09:09'),
(132, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:09:10', '2025-07-10 16:09:11', '2025-07-10 08:09:10'),
(133, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:09:12', '2025-07-10 16:10:01', '2025-07-10 08:09:12'),
(134, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:10:03', '2025-07-10 16:10:22', '2025-07-10 08:10:03'),
(135, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:10:17', '2025-07-10 16:10:47', '2025-07-10 08:10:17'),
(136, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:10:51', '2025-07-10 16:10:55', '2025-07-10 08:10:51'),
(137, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:10:54', '2025-07-10 16:11:01', '2025-07-10 08:10:54'),
(138, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:11:04', '2025-07-10 16:11:09', '2025-07-10 08:11:04'),
(139, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:11:08', '2025-07-10 16:11:10', '2025-07-10 08:11:08'),
(140, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:11:11', '2025-07-10 16:11:52', '2025-07-10 08:11:11'),
(141, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:11:12', '2025-07-10 16:11:19', '2025-07-10 08:11:12'),
(142, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:11:33', '2025-07-10 16:11:39', '2025-07-10 08:11:33'),
(143, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 16:11:35', '2025-07-10 16:12:24', '2025-07-10 08:11:35'),
(144, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:12:03', '2025-07-10 16:12:32', '2025-07-10 08:12:03'),
(145, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:12:16', '2025-07-10 16:12:37', '2025-07-10 08:12:16'),
(146, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:13:15', '2025-07-10 16:15:50', '2025-07-10 08:13:15'),
(147, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:15:18', '2025-07-10 16:15:52', '2025-07-10 08:15:18'),
(148, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:16:04', '2025-07-10 16:16:08', '2025-07-10 08:16:04'),
(149, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:16:06', '2025-07-10 16:16:13', '2025-07-10 08:16:06'),
(150, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:16:40', '2025-07-10 16:16:55', '2025-07-10 08:16:40'),
(151, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:17:38', '2025-07-10 16:17:45', '2025-07-10 08:17:38'),
(152, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:17:51', '2025-07-10 16:17:57', '2025-07-10 08:17:51'),
(153, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:18:05', '2025-07-10 16:18:10', '2025-07-10 08:18:05'),
(154, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:20:06', '2025-07-10 16:20:15', '2025-07-10 08:20:06'),
(155, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:20:23', '2025-07-10 16:20:44', '2025-07-10 08:20:23'),
(156, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:20:26', '2025-07-10 16:20:45', '2025-07-10 08:20:26'),
(157, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:20:48', '2025-07-10 16:20:56', '2025-07-10 08:20:48'),
(158, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:20:57', '2025-07-10 16:21:19', '2025-07-10 08:20:57'),
(159, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:21:15', '2025-07-10 16:21:40', '2025-07-10 08:21:15'),
(160, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 16:21:16', '2025-07-10 16:24:59', '2025-07-10 08:21:16'),
(161, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:21:22', '2025-07-10 16:21:25', '2025-07-10 08:21:22'),
(162, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:24:03', '2025-07-10 16:24:35', '2025-07-10 08:24:03'),
(163, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:24:36', '2025-07-10 16:24:44', '2025-07-10 08:24:36'),
(164, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:24:38', '2025-07-10 16:24:45', '2025-07-10 08:24:38'),
(165, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 16:25:02', '2025-07-10 16:25:18', '2025-07-10 08:25:02'),
(166, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 16:25:22', '2025-07-10 16:25:40', '2025-07-10 08:25:22'),
(167, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:25:32', '2025-07-10 16:27:10', '2025-07-10 08:25:32'),
(168, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-10 16:25:39', NULL, '2025-07-10 08:25:39'),
(169, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:27:15', '2025-07-10 16:27:21', '2025-07-10 08:27:15'),
(170, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:27:28', '2025-07-10 16:27:32', '2025-07-10 08:27:28'),
(171, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-10 16:27:29', NULL, '2025-07-10 08:27:29'),
(172, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:27:36', '2025-07-10 16:27:56', '2025-07-10 08:27:36'),
(173, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:28:00', '2025-07-10 16:28:02', '2025-07-10 08:28:00'),
(174, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:28:05', '2025-07-10 16:28:09', '2025-07-10 08:28:05'),
(175, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-10 16:28:12', NULL, '2025-07-10 08:28:12'),
(176, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-11 09:26:41', '2025-07-11 09:26:44', '2025-07-11 01:26:41'),
(177, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-11 09:26:47', '2025-07-11 09:26:51', '2025-07-11 01:26:47'),
(178, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-11 10:25:20', '2025-07-11 10:25:22', '2025-07-11 02:25:20'),
(179, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-11 10:25:21', '2025-07-11 10:25:28', '2025-07-11 02:25:21'),
(180, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-11 10:25:25', '2025-07-11 10:30:11', '2025-07-11 02:25:25'),
(181, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-11 10:25:31', '2025-07-11 10:35:50', '2025-07-11 02:25:31'),
(182, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:25:52', '2025-07-11 10:25:55', '2025-07-11 02:25:52'),
(183, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:25:58', '2025-07-11 10:26:06', '2025-07-11 02:25:58'),
(184, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:26:09', '2025-07-11 10:26:18', '2025-07-11 02:26:09'),
(185, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:26:21', '2025-07-11 10:27:16', '2025-07-11 02:26:21'),
(186, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:27:20', '2025-07-11 10:28:03', '2025-07-11 02:27:20'),
(187, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:28:06', '2025-07-11 10:29:08', '2025-07-11 02:28:06'),
(188, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:29:52', '2025-07-11 10:33:57', '2025-07-11 02:29:52'),
(189, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-11 10:33:31', '2025-07-11 10:34:06', '2025-07-11 02:33:31'),
(190, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:34:15', '2025-07-11 10:34:30', '2025-07-11 02:34:15'),
(191, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-11 10:34:20', '2025-07-11 10:34:32', '2025-07-11 02:34:20'),
(192, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:34:34', '2025-07-11 10:35:06', '2025-07-11 02:34:34'),
(193, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-11 10:35:07', '2025-07-11 10:35:33', '2025-07-11 02:35:07'),
(194, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:35:15', '2025-07-11 10:35:22', '2025-07-11 02:35:15'),
(195, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:35:40', '2025-07-11 10:35:48', '2025-07-11 02:35:40'),
(196, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-11 10:35:54', '2025-07-11 10:35:59', '2025-07-11 02:35:54'),
(197, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-11 10:36:04', '2025-07-11 10:36:15', '2025-07-11 02:36:04'),
(198, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:36:09', '2025-07-11 10:36:13', '2025-07-11 02:36:09'),
(199, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-11 10:36:14', '2025-07-11 10:36:31', '2025-07-11 02:36:14'),
(200, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-11 10:36:21', '2025-07-11 10:36:26', '2025-07-11 02:36:21'),
(201, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:36:38', '2025-07-11 10:36:49', '2025-07-11 02:36:38'),
(202, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-11 10:36:43', NULL, '2025-07-11 02:36:43'),
(203, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:36:58', '2025-07-11 10:37:01', '2025-07-11 02:36:58'),
(204, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:37:05', '2025-07-11 10:37:08', '2025-07-11 02:37:05'),
(205, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:37:12', '2025-07-11 10:37:28', '2025-07-11 02:37:12'),
(206, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-11 10:37:40', '2025-07-11 10:37:45', '2025-07-11 02:37:40'),
(207, 'E28069150000401911D9697B', 'Recca Mae Dilapdilap', 'ABC1237', 'Motorcycle', '2025-07-26 13:13:04', '2025-07-26 13:19:48', '2025-07-26 05:13:04'),
(208, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-26 13:13:30', '2025-07-26 13:20:01', '2025-07-26 05:13:30'),
(209, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-26 13:13:45', '2025-07-26 13:14:00', '2025-07-26 05:13:45'),
(210, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-26 13:14:19', '2025-07-26 13:22:44', '2025-07-26 05:14:19'),
(211, 'E28069150000401911D9657B', 'Joshua Burce', 'ABC1235', 'Motorcycle', '2025-07-26 13:20:13', NULL, '2025-07-26 05:20:13'),
(212, 'E2806894000040320D38D54F', 'Jomel Garcia', 'ABC1236', 'Motorcycle', '2025-07-26 13:23:03', '2025-07-26 15:42:36', '2025-07-26 05:23:03');

-- --------------------------------------------------------

--
-- Table structure for table `admin_accounts`
--

CREATE TABLE `admin_accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_accounts`
--

INSERT INTO `admin_accounts` (`id`, `username`, `password_hash`, `full_name`, `email`, `created_at`) VALUES
(2, 'admin', '$2y$10$oTYyrgKByUM4tbdkQ9usX.BkPtZoeOj3x0lcunP.h01YSBtNicBc.', 'Johsua Rey M. Burce', 'burcejosh19@gmail.com', '2025-04-06 04:59:29');

-- --------------------------------------------------------

--
-- Table structure for table `admin_actions`
--

CREATE TABLE `admin_actions` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action` enum('Approved','Rejected') NOT NULL,
  `action_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faculty_vehicles`
--

CREATE TABLE `faculty_vehicles` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `faculty_name` varchar(255) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `email` varchar(254) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_vehicles`
--

INSERT INTO `faculty_vehicles` (`id`, `vehicle_id`, `faculty_name`, `department`, `position`, `email`) VALUES
(14, 80, 'Recca Mae Dilapdilap', 'CCIT', 'Instractor', 'dilapdilapreccamae@gmail.com'),
(15, 86, 'Clyde Ebilane', 'CCIT', 'Instructor', 'burcejosh19@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `ojt_vehicles`
--

CREATE TABLE `ojt_vehicles` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `ojt_name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `supervisor_name` varchar(255) DEFAULT NULL,
  `email` varchar(254) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ojt_vehicles`
--

INSERT INTO `ojt_vehicles` (`id`, `vehicle_id`, `ojt_name`, `company_name`, `supervisor_name`, `email`) VALUES
(5, 84, 'JAMES FAMISAN', 'CCIT', 'Michael Eale', 'burcejosh19@gmail.com'),
(6, 85, 'Christian Oliver', 'CCIT', 'Michael Eala', 'burcejosh19@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `pre_registered_vehicles`
--

CREATE TABLE `pre_registered_vehicles` (
  `id` int(11) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `vehicle_image` varchar(255) DEFAULT NULL,
  `rfid_tag` varchar(50) DEFAULT NULL,
  `email` varchar(254) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `student_id_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pre_registered_vehicles`
--

INSERT INTO `pre_registered_vehicles` (`id`, `plate_number`, `vehicle_type`, `vehicle_image`, `rfid_tag`, `email`, `status`, `registered_at`, `student_id_number`) VALUES
(70, 'DJN1038', 'Motorcycle', '1747174153_ChatGPT Image May 14, 2025, 12_42_36 AM.png', NULL, 'burcejosh19@gmail.com', 'Rejected', '2025-05-13 22:09:13', '22-1-3-0062'),
(71, 'JHDG0219', 'Motorcycle', '1747174210_Usecase.drawio (1).png', '4180283060', 'burcejosh19@gmail.com', 'Approved', '2025-05-13 22:10:10', '22-1-3-0063'),
(72, 'DEU410', 'Motorcycle', '1747175165_dataflow.drawio.png', NULL, 'burcejosh19@gmail.com', 'Rejected', '2025-05-13 22:26:05', '22-1-3-0061'),
(73, 'Vh2013', 'Motorcycle', '1747175532_ChatGPT Image May 14, 2025, 12_42_36 AM.png', '4181160948', 'burcejosh19@gmail.com', 'Approved', '2025-05-13 22:32:12', '22-1-3-0064'),
(74, 'HDH274', 'Motorcycle', '1747175879_A-diagram-of-the-middleware-architecture.png', '3676157716', 'burcejosh19@gmail.com', 'Approved', '2025-05-13 22:37:59', '22-1-3-0061'),
(75, 'ABC123', 'Motorcycle', '1747188678_IMG_5798.jpeg', NULL, 'rheganmhaculitz@gmail.com', 'Rejected', '2025-05-14 02:11:18', '22130013'),
(76, 'ABC123', 'Motorcycle', '1747188858_IMG_5831.jpeg', NULL, 'fbdjs@fmsos', 'Rejected', '2025-05-14 02:14:18', '22130013'),
(77, 'ABC1238', 'Motorcycle', '1747189298_434494864_1110229156927642_7617865043293945955_n (1).jpg', '4181160948', 'shai@email.com', 'Approved', '2025-05-14 02:21:38', '22130018'),
(78, 'ABC1235', 'Motorcycle', '1747189377_434494864_1110229156927642_7617865043293945955_n.jpg', '4180430052', 'burce@email.com', 'Approved', '2025-05-14 02:22:57', '22130014'),
(80, 'ABC1237', 'Motorcycle', '1747190799_434494864_1110229156927642_7617865043293945955_n.jpg', '3676157716', 'dilapdilapreccamae@gmail.com', 'Approved', '2025-05-14 02:46:39', NULL),
(81, 'ABC1239', 'Motorcycle', '1747190947_ChatGPT Image May 14, 2025, 12_42_36 AM.png', NULL, 'marclemon@gmail.com', 'Rejected', '2025-05-14 02:49:07', '22-1-3-0063'),
(82, 'ABC1236', 'Motorcycle', '1747196646_ChatGPT Image May 14, 2025, 12_42_36 AM.png', '3749799956', 'jomelgarcia@gmail.com', 'Approved', '2025-05-14 04:24:06', '22130017'),
(83, 'KFN395', 'Car', '1752051498_0aa42717-ef6a-49fe-8745-d14cabc04b6b.jpg', NULL, 'burcejosh19@gmail.com', 'Rejected', '2025-07-09 08:58:18', '22-1-3-0061'),
(84, 'DIJ-234', 'Motorcycle', '1752202248_Screenshot 2023-12-14 201255.png', NULL, 'burcejosh19@gmail.com', 'Pending', '2025-07-11 02:50:48', NULL),
(85, 'JAG-872', 'Car', '1753507070_IMG_7930.png', NULL, 'burcejosh19@gmail.com', 'Pending', '2025-07-26 05:17:50', NULL),
(86, 'QUY-871', 'Motorcycle', '1753507121_IMG_7929.png', NULL, 'burcejosh19@gmail.com', 'Pending', '2025-07-26 05:18:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `registered_vehicles`
--

CREATE TABLE `registered_vehicles` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `rfid_tag` varchar(50) DEFAULT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registered_vehicles`
--

INSERT INTO `registered_vehicles` (`id`, `vehicle_id`, `plate_number`, `vehicle_type`, `rfid_tag`, `registered_at`) VALUES
(45, 78, 'ABC1235', 'Motorcycle', 'E28069150000401911D9657B', '2025-05-14 02:40:35'),
(46, 80, 'ABC1237', 'Motorcycle', 'E28069150000401911D9697B', '2025-05-14 02:48:13'),
(47, 82, 'ABC1236', 'Motorcycle', 'E2806894000040320D38D54F', '2025-05-14 04:58:05');

-- --------------------------------------------------------

--
-- Table structure for table `student_vehicles`
--

CREATE TABLE `student_vehicles` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_id_number` varchar(50) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `year_level` varchar(10) DEFAULT NULL,
  `email` varchar(254) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_vehicles`
--

INSERT INTO `student_vehicles` (`id`, `vehicle_id`, `student_name`, `student_id_number`, `course`, `year_level`, `email`) VALUES
(50, 70, 'Shaira Quines', NULL, 'BSIT', '3rd year', 'burcejosh19@gmail.com'),
(53, 72, 'Toper mundia', '22-1-3-0061', 'BSIT', '3rd year', 'burcejosh19@gmail.com'),
(56, 75, 'Rheagan Villanueva', '22130013', 'BSIT', 'Fourth yea', 'rheganmhaculitz@gmail.com'),
(57, 76, 'Rheagan Villarin', '22130013', 'BSIT', '2nd year', 'fbdjs@fmsos'),
(59, 78, 'Joshua Burce', '22130014', 'BSIT', '2nd year', 'burce@email.com'),
(60, 81, 'Rheagan ', '22-1-3-0063', 'BSIT', 'First year', 'marclemon@gmail.com'),
(61, 82, 'Jomel Garcia', '22130017', 'BSIT', '3rd year', 'jomelgarcia@gmail.com'),
(62, 83, 'Christian Oliver ', '22-1-3-0061', 'BSIT', 'Fourth yea', 'burcejosh19@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_entries`
--

CREATE TABLE `visitor_entries` (
  `id` int(11) NOT NULL,
  `license_plate` varchar(10) NOT NULL,
  `owner_name` varchar(50) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `vehicle_type` enum('Sedan','SUV','Truck','Van','Motorcycle') NOT NULL,
  `vehicle_color` varchar(20) NOT NULL,
  `visit_purpose` varchar(100) DEFAULT NULL,
  `entry_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_logs`
--
ALTER TABLE `access_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_actions`
--
ALTER TABLE `admin_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `faculty_vehicles`
--
ALTER TABLE `faculty_vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculty_vehicles_fk` (`vehicle_id`);

--
-- Indexes for table `ojt_vehicles`
--
ALTER TABLE `ojt_vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ojt_vehicles_fk` (`vehicle_id`);

--
-- Indexes for table `pre_registered_vehicles`
--
ALTER TABLE `pre_registered_vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registered_vehicles`
--
ALTER TABLE `registered_vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicle_id` (`vehicle_id`),
  ADD UNIQUE KEY `rfid_tag` (`rfid_tag`),
  ADD KEY `plate_number` (`plate_number`);

--
-- Indexes for table `student_vehicles`
--
ALTER TABLE `student_vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_vehicles_fk` (`vehicle_id`);

--
-- Indexes for table `visitor_entries`
--
ALTER TABLE `visitor_entries`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_logs`
--
ALTER TABLE `access_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;

--
-- AUTO_INCREMENT for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin_actions`
--
ALTER TABLE `admin_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faculty_vehicles`
--
ALTER TABLE `faculty_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ojt_vehicles`
--
ALTER TABLE `ojt_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pre_registered_vehicles`
--
ALTER TABLE `pre_registered_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `registered_vehicles`
--
ALTER TABLE `registered_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `student_vehicles`
--
ALTER TABLE `student_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `visitor_entries`
--
ALTER TABLE `visitor_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_actions`
--
ALTER TABLE `admin_actions`
  ADD CONSTRAINT `admin_actions_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `pre_registered_vehicles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_actions_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `faculty_vehicles`
--
ALTER TABLE `faculty_vehicles`
  ADD CONSTRAINT `faculty_vehicles_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `pre_registered_vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ojt_vehicles`
--
ALTER TABLE `ojt_vehicles`
  ADD CONSTRAINT `ojt_vehicles_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `pre_registered_vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `registered_vehicles`
--
ALTER TABLE `registered_vehicles`
  ADD CONSTRAINT `registered_vehicles_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `pre_registered_vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_vehicles`
--
ALTER TABLE `student_vehicles`
  ADD CONSTRAINT `student_vehicles_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `pre_registered_vehicles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
