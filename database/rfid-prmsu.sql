-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2025 at 08:36 AM
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
-- Database: `rfid-prmsu`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_logs`
--

CREATE TABLE `access_logs` (
  `id` int(11) NOT NULL,
  `rfid_tag` varchar(50) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `time_in` DATETIME DEFAULT NULL,
  `time_out`DATETIME DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 'Johsua_rey', '$2y$10$PCsHG4E0wDLxa6yk214Gou/mnpm/W5/lpCtQBW8VbRqPLz/yMYq7C', 'Johsua Rey Mundia', 'burcejosh19@gmail.com', '2025-04-06 04:59:29'),
(3, 'recca_mae', '$2y$10$R.pQh.56kset9cgH54ho6.JpIS7k/Ob4lcg8PKaVrWcK3ZE..asa6', 'Recca Dilapdilap', 'dilapdilapreccamae@gmail.com', '2025-04-06 05:22:47');

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
  `position` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_vehicles`
--

INSERT INTO `faculty_vehicles` (`id`, `vehicle_id`, `faculty_name`, `department`, `position`) VALUES
(2, 4, 'Michael Eala', 'CCIT', 'Program Chair');

-- --------------------------------------------------------

--
-- Table structure for table `ojt_vehicles`
--

CREATE TABLE `ojt_vehicles` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `ojt_name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `supervisor_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ojt_vehicles`
--

INSERT INTO `ojt_vehicles` (`id`, `vehicle_id`, `ojt_name`, `company_name`, `supervisor_name`) VALUES
(1, 6, 'Shaira Quines', 'CCIT', 'Michael Eale');

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
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pre_registered_vehicles`
--

INSERT INTO `pre_registered_vehicles` (`id`, `plate_number`, `vehicle_type`, `vehicle_image`, `rfid_tag`, `status`, `registered_at`) VALUES
(4, 'Vh2013', 'Motorcycle', '1743864566_17274111757416547979325110618544.jpg', '4180283060', 'Approved', '2025-04-05 14:49:26'),
(5, 'DJN1038', 'Motorcycle', '1743864923_17274111757416547979325110618544.jpg', '3678447332', 'Approved', '2025-04-05 14:55:23'),
(6, 'JHDG0219', 'Motorcycle', '1743869848_17274111757416547979325110618544.jpg', '3746247220', 'Approved', '2025-04-05 16:17:28'),
(7, 'DEU410', 'Car', '1743914232_Screenshot 2023-12-14 201255.png', '0284672990', 'Approved', '2025-04-06 04:37:12');

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
(1, 4, 'Vh2013', 'Motorcycle', '4180283060', '2025-04-05 14:50:58'),
(2, 5, 'DJN1038', 'Motorcycle', '3678447332', '2025-04-05 14:56:46'),
(3, 6, 'JHDG0219', 'Motorcycle', '3746247220', '2025-04-05 16:18:26'),
(4, 7, 'DEU410', 'Car', '0284672990', '2025-04-06 04:38:42');

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
  `year_level` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_vehicles`
--

INSERT INTO `student_vehicles` (`id`, `vehicle_id`, `student_name`, `student_id_number`, `course`, `year_level`) VALUES
(3, 5, 'Johsua Rey M. Burce', '22-1-3-0061', 'BSIT', '3rd year'),
(4, 7, 'Rheagan ', '22-1-3-0032', 'BSIT', '3rd year');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_logs`
--
ALTER TABLE `access_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admin_actions`
--
ALTER TABLE `admin_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faculty_vehicles`
--
ALTER TABLE `faculty_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ojt_vehicles`
--
ALTER TABLE `ojt_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pre_registered_vehicles`
--
ALTER TABLE `pre_registered_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `registered_vehicles`
--
ALTER TABLE `registered_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_vehicles`
--
ALTER TABLE `student_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
