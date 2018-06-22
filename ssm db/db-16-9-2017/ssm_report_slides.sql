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
-- Table structure for table `ssm_report_slides`
--

CREATE TABLE `ssm_report_slides` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `data` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `options` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `order_num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ssm_report_slides`
--

INSERT INTO `ssm_report_slides` (`id`, `report_id`, `title`, `description`, `data`, `options`, `order_num`) VALUES
(10, 21, 'Titte 1', 'Description 1', NULL, 'title_slide', 1),
(11, 21, 'Titte 2', 'Description 2', NULL, 'chart_slide', 2),
(12, 21, 'Titte 3', 'Description 3', NULL, 'kpi1_slide', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ssm_report_slides`
--
ALTER TABLE `ssm_report_slides`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ssm_report_slides`
--
ALTER TABLE `ssm_report_slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
