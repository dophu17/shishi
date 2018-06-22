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
-- Table structure for table `ssm_kpi_changes`
--

CREATE TABLE `ssm_kpi_changes` (
  `ssm_kpi_change_id` int(11) NOT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `week` int(2) NOT NULL,
  `day` int(2) NOT NULL,
  `transactionRevenue` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '売上',
  `pageviews` decimal(10,0) NOT NULL DEFAULT '0' COMMENT 'PV数',
  `pageviewsPerSession` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '一人あたりPV数',
  `sessions` decimal(10,0) NOT NULL DEFAULT '0' COMMENT 'セッション数',
  `avgSessionDuration` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '平均セッション時間',
  `uniqueUsers` decimal(10,0) NOT NULL DEFAULT '0' COMMENT 'UU',
  `transactions` decimal(10,0) NOT NULL,
  `transactionsPerSession` decimal(10,0) NOT NULL DEFAULT '0' COMMENT 'CVR',
  `revenuePerTransaction` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '客単価',
  `bounceRate` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '直帰率',
  `topBounceRate` decimal(10,0) NOT NULL DEFAULT '0' COMMENT 'Top直帰率',
  `site_id` int(11) NOT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ssm_kpi_changes`
--

INSERT INTO `ssm_kpi_changes` (`ssm_kpi_change_id`, `year`, `month`, `week`, `day`, `transactionRevenue`, `pageviews`, `pageviewsPerSession`, `sessions`, `avgSessionDuration`, `uniqueUsers`, `transactions`, `transactionsPerSession`, `revenuePerTransaction`, `bounceRate`, `topBounceRate`, `site_id`, `created_user_id`, `created_at`, `updated_at`) VALUES
(1, 2017, 8, 1, 0, '592486', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 2017, 8, 1, 0, '0', '0', '0', '0', '0', '308', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 2017, 8, 1, 0, '-549244', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 2017, 8, 2, 0, '89506', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 2017, 8, 3, 0, '-17587', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 2017, 8, 4, 0, '100000', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 2017, 8, 4, 0, '-100000', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 2017, 8, 1, 0, '0', '0', '0', '0', '0', '-1000', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ssm_kpi_changes`
--
ALTER TABLE `ssm_kpi_changes`
  ADD PRIMARY KEY (`ssm_kpi_change_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ssm_kpi_changes`
--
ALTER TABLE `ssm_kpi_changes`
  MODIFY `ssm_kpi_change_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
