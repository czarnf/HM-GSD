-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 05, 2024 at 05:45 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments_tb`
--

CREATE TABLE `appointments_tb` (
  `appointment_id` bigint(20) NOT NULL,
  `appointment_slug` varchar(255) NOT NULL,
  `appointment_name` varchar(255) NOT NULL,
  `appointment_team_id` bigint(20) NOT NULL,
  `appointment_phone` varchar(255) NOT NULL,
  `appointment_email` varchar(255) NOT NULL,
  `appointment_date` varchar(15) NOT NULL,
  `appointment_time` varchar(15) NOT NULL,
  `appointment_comment` longtext NOT NULL,
  `appointment_status` int(11) NOT NULL DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments_tb`
--

INSERT INTO `appointments_tb` (`appointment_id`, `appointment_slug`, `appointment_name`, `appointment_team_id`, `appointment_phone`, `appointment_email`, `appointment_date`, `appointment_time`, `appointment_comment`, `appointment_status`, `createdAt`, `updatedAt`) VALUES
(3, '171484938066368664c3520', 'Fitle Eman', 3, '+44 20 7123 4567', 'info@stmarysschoolilorin.com', '2024-05-16', '12PM-1PM', 'We dey here dey joke since', 0, '2024-05-04 19:03:00', '2024-05-04 19:03:00'),
(5, '1714906807663766b7e13bf', 'Joseph Amaka ', 3, '+44 32 2123 4097', 'amaka@gmail.com', '2024-05-25', '11AM-12PM', 'Lorem lorem', 0, '2024-05-05 11:00:07', '2024-05-05 11:00:07');

-- --------------------------------------------------------

--
-- Table structure for table `patients_tb`
--

CREATE TABLE `patients_tb` (
  `patient_id` bigint(20) NOT NULL,
  `patient_slug` varchar(300) NOT NULL,
  `patient_number` varchar(30) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `patient_age` int(11) NOT NULL,
  `patient_gender` varchar(255) NOT NULL,
  `patient_ward_id` bigint(20) NOT NULL,
  `patient_team_id` bigint(20) NOT NULL,
  `patient_bed_no` int(11) NOT NULL,
  `patient_password` varchar(300) NOT NULL,
  `patient_status` int(11) NOT NULL DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients_tb`
--

INSERT INTO `patients_tb` (`patient_id`, `patient_slug`, `patient_number`, `patient_name`, `patient_age`, `patient_gender`, `patient_ward_id`, `patient_team_id`, `patient_bed_no`, `patient_password`, `patient_status`, `createdAt`, `updatedAt`) VALUES
(2, '17148584556636a9d7a0a4b', 'PHMS174045', 'Peti Okoro', 23, 'female', 2, 4, 1, '$2y$10$QK8MtcgLJufTMXVtr4Ac5e22bPM3rhdQ5xheHF4VKzf7t41C0CpRy', 0, '2024-05-04 21:34:15', '2024-05-04 21:34:15');

-- --------------------------------------------------------

--
-- Table structure for table `records_tb`
--

CREATE TABLE `records_tb` (
  `record_id` bigint(20) NOT NULL,
  `record_slug` varchar(255) NOT NULL,
  `record_tr_id` bigint(20) NOT NULL,
  `record_staff_id` bigint(20) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `records_tb`
--

INSERT INTO `records_tb` (`record_id`, `record_slug`, `record_tr_id`, `record_staff_id`, `createdAt`, `updatedAt`) VALUES
(7, '171491641166378c3b9f53a', 2, 9, '2024-05-05 13:40:11', '2024-05-05 13:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `staffs_tb`
--

CREATE TABLE `staffs_tb` (
  `staff_id` bigint(20) NOT NULL,
  `staff_slug` varchar(300) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `staff_team_id` bigint(20) NOT NULL,
  `staff_number` varchar(40) NOT NULL,
  `staff_password` varchar(255) NOT NULL,
  `staff_role` varchar(20) NOT NULL,
  `staff_grade` varchar(20) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staffs_tb`
--

INSERT INTO `staffs_tb` (`staff_id`, `staff_slug`, `staff_name`, `staff_team_id`, `staff_number`, `staff_password`, `staff_role`, `staff_grade`, `createdAt`, `updatedAt`) VALUES
(2, '', 'Oyinu Edache Martins', 1, 'HMS001001', '$2y$10$lmD7GFp/GqMW1VciNR6U.e5cXCgLdA901OpARIFBp9y2anbIn5w/6', 'admin', NULL, '2024-05-02 16:52:24', '2024-05-02 16:52:24'),
(3, '17147467576634f5855225f', 'George Adegoke Moses', 2, 'HMS160078', '$2y$10$T8f2wEofbsQW0shL7jgsz.SFfHNtCKRXtU7tAB8EGz0pWE3ZYXJ4W', 'doctor', '', '2024-05-03 14:32:37', '2024-05-03 14:32:37'),
(4, '1714763770663537faaf2ea', 'Sardar Ali Paracha', 2, 'HMS845829', '$2y$10$49ThY3rnhodcqNXHLSXbxu/5JJ7RfTqjjn/exucgvDtNd966YvYQK', 'consultant', '', '2024-05-03 19:16:10', '2024-05-03 19:16:10'),
(5, '17147638736635386163c65', 'Emmanuel Ebitimi Chiefson', 1, 'HMS947121', '$2y$10$5rXPlBh77SaNvQlN9L488uy.r7qs10AySFQMLdx2aQ4f/zsYEqddG', 'Front Desk', '', '2024-05-03 19:17:53', '2024-05-03 19:17:53'),
(6, '171484072166366491569ba', 'Vitalis Chizoba Blessing', 3, 'HMS902309', '$2y$10$sqMuViKrTbaaHNLG0yyrcu2lZNEDnD4uhA8OPObGHLyRZ7rkLddXC', 'consultant', '', '2024-05-04 16:38:41', '2024-05-04 16:38:41'),
(7, '17148580406636a8381308d', 'Emmanuel Ebitimi', 3, 'HMS031605', '$2y$10$L7RW.enVmJ57CBANM6Pv5ugwm.KevEaBzQ.83YssF6U3VP.jDIP7a', 'doctor', '', '2024-05-04 21:27:20', '2024-05-04 21:27:20'),
(8, '17148580766636a85c60b54', 'Samuel Olawale', 3, 'HMS526143', '$2y$10$oWsle30SjP0I7vh0rPADJ.hyl8ukdtePV8CANzNHH024n0vJxzvGG', 'doctor', 'AB', '2024-05-04 21:27:56', '2024-05-04 21:27:56'),
(9, '17148582256636a8f1b23f9', 'Joseph Emeka ', 4, 'HMS910704', '$2y$10$Evsl6Tu6MIG4oedvVW.ufudnpbWdugJ2jLTdsByWtGVBKtq.8yMW2', 'consultant', '', '2024-05-04 21:30:25', '2024-05-04 21:30:25');

-- --------------------------------------------------------

--
-- Table structure for table `teams_tb`
--

CREATE TABLE `teams_tb` (
  `team_id` bigint(20) NOT NULL,
  `team_slug` varchar(300) NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `team_desc` text NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams_tb`
--

INSERT INTO `teams_tb` (`team_id`, `team_slug`, `team_name`, `team_desc`, `createdAt`, `updatedAt`) VALUES
(1, '', 'administrator', '', '2024-05-02 16:57:20', '2024-05-02 16:48:37'),
(2, '17147451566634ef44ae7ee', 'Team Epic', 'Orthopedics', '2024-05-03 14:05:56', '2024-05-03 14:05:56'),
(3, '171483820166365ab9971dd', 'Eye Team', 'Eye Care', '2024-05-04 15:56:41', '2024-05-04 15:56:41'),
(4, '17148581836636a8c72948f', 'Neurologist Team', 'Neurologist', '2024-05-04 21:29:43', '2024-05-04 21:29:43');

-- --------------------------------------------------------

--
-- Table structure for table `treatment_records_tb`
--

CREATE TABLE `treatment_records_tb` (
  `tr_id` bigint(20) NOT NULL,
  `tr_slug` varchar(300) NOT NULL,
  `tr_patient_id` bigint(20) NOT NULL,
  `tr_staff_id` bigint(20) NOT NULL,
  `tr_status` int(11) NOT NULL DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treatment_records_tb`
--

INSERT INTO `treatment_records_tb` (`tr_id`, `tr_slug`, `tr_patient_id`, `tr_staff_id`, `tr_status`, `createdAt`, `updatedAt`) VALUES
(2, '17148587626636ab0a09607', 2, 9, 0, '2024-05-04 21:39:22', '2024-05-04 21:39:22');

-- --------------------------------------------------------

--
-- Table structure for table `wards_tb`
--

CREATE TABLE `wards_tb` (
  `ward_id` bigint(20) NOT NULL,
  `ward_slug` varchar(300) NOT NULL,
  `ward_name` varchar(255) NOT NULL,
  `ward_total_bed` int(11) NOT NULL,
  `ward_type` varchar(255) NOT NULL,
  `ward_gender` varchar(30) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wards_tb`
--

INSERT INTO `wards_tb` (`ward_id`, `ward_slug`, `ward_name`, `ward_total_bed`, `ward_type`, `ward_gender`, `createdAt`, `updatedAt`) VALUES
(1, '17147452246634ef883de03', 'Ward 051', 15, 'Orthopedics', 'both', '2024-05-03 14:07:04', '2024-05-03 14:07:04'),
(2, '17148583676636a97fc8677', 'Ward 002', 10, 'Neurologist', 'female', '2024-05-04 21:32:47', '2024-05-04 21:32:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments_tb`
--
ALTER TABLE `appointments_tb`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `appointment_team_id` (`appointment_team_id`);

--
-- Indexes for table `patients_tb`
--
ALTER TABLE `patients_tb`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `records_tb`
--
ALTER TABLE `records_tb`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `record_tr_id` (`record_tr_id`),
  ADD KEY `record_staff_id` (`record_staff_id`);

--
-- Indexes for table `staffs_tb`
--
ALTER TABLE `staffs_tb`
  ADD PRIMARY KEY (`staff_id`),
  ADD KEY `staff_team_id` (`staff_team_id`);

--
-- Indexes for table `teams_tb`
--
ALTER TABLE `teams_tb`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `treatment_records_tb`
--
ALTER TABLE `treatment_records_tb`
  ADD PRIMARY KEY (`tr_id`),
  ADD KEY `tr_patient_id` (`tr_patient_id`),
  ADD KEY `tr_staff_id` (`tr_staff_id`);

--
-- Indexes for table `wards_tb`
--
ALTER TABLE `wards_tb`
  ADD PRIMARY KEY (`ward_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments_tb`
--
ALTER TABLE `appointments_tb`
  MODIFY `appointment_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `patients_tb`
--
ALTER TABLE `patients_tb`
  MODIFY `patient_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `records_tb`
--
ALTER TABLE `records_tb`
  MODIFY `record_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `staffs_tb`
--
ALTER TABLE `staffs_tb`
  MODIFY `staff_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `teams_tb`
--
ALTER TABLE `teams_tb`
  MODIFY `team_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `treatment_records_tb`
--
ALTER TABLE `treatment_records_tb`
  MODIFY `tr_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wards_tb`
--
ALTER TABLE `wards_tb`
  MODIFY `ward_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments_tb`
--
ALTER TABLE `appointments_tb`
  ADD CONSTRAINT `appointments_tb_ibfk_1` FOREIGN KEY (`appointment_team_id`) REFERENCES `teams_tb` (`team_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `records_tb`
--
ALTER TABLE `records_tb`
  ADD CONSTRAINT `records_tb_ibfk_1` FOREIGN KEY (`record_tr_id`) REFERENCES `treatment_records_tb` (`tr_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `records_tb_ibfk_2` FOREIGN KEY (`record_staff_id`) REFERENCES `staffs_tb` (`staff_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `staffs_tb`
--
ALTER TABLE `staffs_tb`
  ADD CONSTRAINT `staffs_tb_ibfk_1` FOREIGN KEY (`staff_team_id`) REFERENCES `teams_tb` (`team_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `treatment_records_tb`
--
ALTER TABLE `treatment_records_tb`
  ADD CONSTRAINT `treatment_records_tb_ibfk_1` FOREIGN KEY (`tr_patient_id`) REFERENCES `patients_tb` (`patient_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `treatment_records_tb_ibfk_2` FOREIGN KEY (`tr_staff_id`) REFERENCES `staffs_tb` (`staff_id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
