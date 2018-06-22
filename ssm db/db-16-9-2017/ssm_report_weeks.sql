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
-- Table structure for table `ssm_report_weeks`
--

CREATE TABLE `ssm_report_weeks` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `month` int(2) DEFAULT NULL,
  `day` int(2) DEFAULT NULL,
  `hour` int(2) DEFAULT NULL,
  `from_user` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ssm_report_weeks`
--

INSERT INTO `ssm_report_weeks` (`id`, `report_id`, `content`, `month`, `day`, `hour`, `from_user`, `status`) VALUES
(15, 18, NULL, NULL, NULL, NULL, NULL, 0),
(16, 19, NULL, NULL, NULL, NULL, NULL, 0),
(18, 22, NULL, NULL, NULL, NULL, NULL, 0),
(19, 23, NULL, NULL, NULL, NULL, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ssm_report_weeks`
--
ALTER TABLE `ssm_report_weeks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ssm_report_weeks`
--
ALTER TABLE `ssm_report_weeks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
