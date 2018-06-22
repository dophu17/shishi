-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2017 at 12:48 PM
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
-- Table structure for table `ssm_users`
--

CREATE TABLE `ssm_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `avatar` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ssm_users`
--

INSERT INTO `ssm_users` (`id`, `avatar`, `first_name`, `last_name`, `username`, `password`, `role`, `department`, `position`, `status`, `created`, `modified`) VALUES
(1, 'avatar.jpg', 'ctmor', NULL, 'mor', 'emdFMzVhc1JOU05oTXRQTk9ZMUtHUT09', 'admin', '', NULL, 0, '2017-09-13 09:04:03', '2017-09-13 09:04:03'),
(2, '', 'nhan vien 1', NULL, 'nv1', 'emdFMzVhc1JOU05oTXRQTk9ZMUtHUT09', 'staff', 'dept ', NULL, 0, '2017-09-13 09:04:13', '2017-09-13 09:04:13'),
(3, '', 'nhan vien 2', NULL, 'nv2', 'emdFMzVhc1JOU05oTXRQTk9ZMUtHUT09', 'staff', 'it', NULL, 0, '2017-09-13 09:04:17', '2017-09-13 09:04:17'),
(4, '', 'nhan vien 3', NULL, 'nv3', 'emdFMzVhc1JOU05oTXRQTk9ZMUtHUT09', 'staff', 'pr', NULL, 0, '2017-09-13 09:04:38', '2017-09-13 09:04:38'),
(5, '', 'nhan vien 4', NULL, 'nv4', 'emdFMzVhc1JOU05oTXRQTk9ZMUtHUT09', 'staff', 'lc', NULL, 0, '2017-09-13 09:04:41', '2017-09-13 09:04:41'),
(6, '', 'nhan vien 5', NULL, 'nv5', 'emdFMzVhc1JOU05oTXRQTk9ZMUtHUT09', 'staff', 'fix', NULL, 0, '2017-09-13 09:04:43', '2017-09-13 09:04:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ssm_users`
--
ALTER TABLE `ssm_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ssm_users`
--
ALTER TABLE `ssm_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
