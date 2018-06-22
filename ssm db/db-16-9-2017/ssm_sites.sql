-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2017 at 11:51 AM
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
-- Table structure for table `ssm_sites`
--

CREATE TABLE `ssm_sites` (
  `id` int(11) NOT NULL,
  `site_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `site_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `site_manage_user` int(11) NOT NULL DEFAULT '0',
  `site_worker_user` int(11) NOT NULL DEFAULT '0',
  `site_satisfaction` int(1) NOT NULL,
  `site_note` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ga_view_id` int(11) NOT NULL,
  `ga_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ga_ecommerce` tinyint(1) DEFAULT '0',
  `chatwork_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chatwork_api` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `report_range` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `report_kpi` text COLLATE utf8_unicode_ci,
  `report_kpi_view2` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ssm_sites`
--

INSERT INTO `ssm_sites` (`id`, `site_name`, `site_url`, `site_manage_user`, `site_worker_user`, `site_satisfaction`, `site_note`, `ga_view_id`, `ga_email`, `ga_ecommerce`, `chatwork_id`, `chatwork_api`, `report_range`, `report_kpi`, `report_kpi_view2`) VALUES
(1, 'KANRIKI', 'https://example.com', 1, 0, 1, 'ggfdgdgdffdg\ndfgfdfg\ndgfs\nsds\nfds\nf\ndfs\ndfs\ndfs\ndfs\ndsf\nfs', 19132084, '', 0, NULL, '', 'a:2:{i:1;s:4:\"5_10\";i:2;s:4:\"11_4\";}', 'a:9:{i:0;s:9:\"pageviews\";i:1;s:8:\"sessions\";i:2;s:18:\"avgSessionDuration\";i:3;s:11:\"uniqueUsers\";i:4;s:12:\"transactions\";i:5;s:22:\"transactionsPerSession\";i:6;s:21:\"revenuePerTransaction\";i:7;s:10:\"bounceRate\";i:8;s:13:\"topBounceRate\";}', NULL),
(2, 'MOR', 'https://mor.com', 1, 0, 2, '4000', 19132084, '', 0, NULL, '', 'a:2:{i:1;s:4:\"5_10\";i:2;s:4:\"11_4\";}', 'a:11:{i:0;s:18:\"transactionRevenue\";i:1;s:9:\"pageviews\";i:2;s:19:\"pageviewsPerSession\";i:3;s:8:\"sessions\";i:4;s:18:\"avgSessionDuration\";i:5;s:11:\"uniqueUsers\";i:6;s:12:\"transactions\";i:7;s:22:\"transactionsPerSession\";i:8;s:21:\"revenuePerTransaction\";i:9;s:10:\"bounceRate\";i:10;s:13:\"topBounceRate\";}', NULL),
(88, 'tran', 'https://kha.com', 3, 0, 1, '', 19132084, '', NULL, '0', '0', 'a:2:{i:1;s:4:\"5_10\";i:2;s:4:\"11_4\";}', 'a:9:{i:0;s:9:\"pageviews\";i:1;s:8:\"sessions\";i:2;s:18:\"avgSessionDuration\";i:3;s:11:\"uniqueUsers\";i:4;s:12:\"transactions\";i:5;s:22:\"transactionsPerSession\";i:6;s:21:\"revenuePerTransaction\";i:7;s:10:\"bounceRate\";i:8;s:13:\"topBounceRate\";}', NULL),
(89, 'tung', 'https://tung.com', 4, 0, 2, '', 0, '', NULL, '0', '0', NULL, NULL, NULL),
(90, 'taudfd', 'https://tauuuu.com', 5, 0, 3, '', 0, '', NULL, '0', '0', NULL, NULL, NULL),
(91, 'trang ttrang', 'https://trang.com', 4, 0, 1, '', 0, '', NULL, '0', '0', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ssm_sites`
--
ALTER TABLE `ssm_sites`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ssm_sites`
--
ALTER TABLE `ssm_sites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
