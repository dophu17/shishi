-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2017 at 12:47 PM
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
-- Table structure for table `ssm_contracts`
--

CREATE TABLE `ssm_contracts` (
  `id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `start_day` date DEFAULT NULL,
  `end_day` date DEFAULT NULL,
  `plan_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ssm_contracts`
--

INSERT INTO `ssm_contracts` (`id`, `site_id`, `start_day`, `end_day`, `plan_id`, `created_at`, `updated_at`) VALUES
(1, 96, '2017-08-01', '2017-08-25', 6, '2017-09-18 12:08:19', '0000-00-00 00:00:00'),
(2, 96, '2017-09-01', '2017-09-10', 2, '2017-09-18 12:08:19', '0000-00-00 00:00:00'),
(3, 97, '2017-09-04', '2017-09-22', 3, '2017-09-18 14:22:41', '0000-00-00 00:00:00'),
(4, 98, '2017-09-04', '2017-09-22', 6, '2017-09-18 14:23:27', '0000-00-00 00:00:00'),
(5, 99, '2017-09-10', '2017-09-20', 1, '2017-09-20 04:46:51', '0000-00-00 00:00:00'),
(18, 112, '2017-09-12', '2017-09-23', 1, '2017-09-20 09:58:33', '0000-00-00 00:00:00'),
(25, 113, '2017-09-04', '2017-09-16', 1, '2017-09-20 10:57:46', '0000-00-00 00:00:00'),
(36, 96, '2017-09-13', '2017-09-30', 1, '2017-09-20 12:20:54', '0000-00-00 00:00:00'),
(37, 114, '2017-09-03', '2017-09-23', 1, '2017-09-20 12:46:30', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ssm_contracts`
--
ALTER TABLE `ssm_contracts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ssm_contracts`
--
ALTER TABLE `ssm_contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
