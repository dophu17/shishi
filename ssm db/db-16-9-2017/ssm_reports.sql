-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2017 at 11:50 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shishimai`
--

-- --------------------------------------------------------

--
-- Table structure for table `ssm_reports`
--

CREATE TABLE `ssm_reports` (
  `id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `year` year(4) NOT NULL,
  `month` int(2) NOT NULL,
  `week` int(1) NOT NULL DEFAULT '0',
  `last_day_of_month` int(2) DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_user` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ssm_reports`
--

INSERT INTO `ssm_reports` (`id`, `site_id`, `type`, `name`, `year`, `month`, `week`, `last_day_of_month`, `status`, `created_at`, `created_user`) VALUES
(18, 2, 'type_week', '', 2017, 8, 1, 31, 0, '2017-09-16 02:30:44', '2'),
(19, 2, 'type_week', '', 2017, 8, 4, 31, 0, '2017-09-16 02:31:54', '2'),
(21, 90, 'type_month', '', 2017, 8, 0, 0, 0, '2017-09-16 04:25:34', '1'),
(22, 90, 'type_week', '', 2017, 8, 2, 31, 0, '2017-09-16 04:25:51', '1'),
(23, 90, 'type_week', '', 2017, 10, 4, 31, 0, '2017-09-16 04:26:00', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ssm_reports`
--
ALTER TABLE `ssm_reports`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ssm_reports`
--
ALTER TABLE `ssm_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
