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
-- Table structure for table `ssm_kpi_ga_months`
--

CREATE TABLE `ssm_kpi_ga_months` (
  `ssm_kpi_ga_month_id` int(11) NOT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `transactionRevenue` int(11) NOT NULL DEFAULT '0' COMMENT '売上',
  `pageviews` int(11) NOT NULL DEFAULT '0' COMMENT 'PV数',
  `pageviewsPerSession` decimal(4,2) DEFAULT NULL,
  `sessions` int(11) NOT NULL DEFAULT '0' COMMENT 'セッション数',
  `avgSessionDuration` int(11) NOT NULL DEFAULT '0' COMMENT '平均セッション時間',
  `uniqueUsers` int(11) NOT NULL DEFAULT '0' COMMENT 'UU',
  `transactions` int(11) NOT NULL DEFAULT '0',
  `transactionsPerSession` decimal(4,2) DEFAULT NULL,
  `revenuePerTransaction` int(11) NOT NULL DEFAULT '0' COMMENT '客単価',
  `bounceRate` decimal(4,2) DEFAULT NULL,
  `topBounceRate` decimal(4,2) DEFAULT NULL,
  `site_id` int(11) NOT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ssm_kpi_ga_months`
--

INSERT INTO `ssm_kpi_ga_months` (`ssm_kpi_ga_month_id`, `year`, `month`, `transactionRevenue`, `pageviews`, `pageviewsPerSession`, `sessions`, `avgSessionDuration`, `uniqueUsers`, `transactions`, `transactionsPerSession`, `revenuePerTransaction`, `bounceRate`, `topBounceRate`, `site_id`, `created_user_id`, `created_at`, `updated_at`) VALUES
(3, 2017, 7, 1718048, 93187, '2.34', 39864, 88, 34663, 84, '0.21', 20453, '62.15', '42.78', 1, 0, '2017-08-24 09:15:24', '0000-00-00 00:00:00'),
(4, 2017, 6, 1619083, 92060, '2.40', 38311, 90, 32959, 81, '0.21', 19989, '60.75', '41.61', 1, 0, '2017-08-24 09:32:49', '0000-00-00 00:00:00'),
(5, 2016, 7, 4300962, 170181, '2.86', 59575, 111, 48129, 204, '0.34', 21083, '49.00', '35.01', 1, 0, '2017-08-24 09:32:50', '0000-00-00 00:00:00'),
(6, 2017, 1, 3671754, 222572, '2.80', 79555, 112, 68250, 176, '0.22', 20862, '53.39', '35.74', 1, 0, '2017-08-25 08:17:23', '0000-00-00 00:00:00'),
(7, 2016, 2, 5254867, 208627, '2.70', 77140, 108, 59902, 265, '0.34', 19830, '54.69', '36.97', 1, 0, '2017-08-25 08:17:24', '0000-00-00 00:00:00'),
(8, 2018, 1, 0, 0, '0.00', 0, 0, 0, 0, NULL, 0, NULL, NULL, 1, 0, '2017-08-25 08:45:04', '0000-00-00 00:00:00'),
(9, 2017, 2, 3040362, 191242, '2.73', 70157, 106, 60442, 153, '0.22', 19872, '54.98', '36.68', 1, 0, '2017-08-25 08:45:05', '0000-00-00 00:00:00'),
(10, 2017, 8, 1926799, 96510, '2.25', 42984, 80, 38127, 91, '0.21', 21174, '64.71', '44.54', 1, 0, '2017-09-01 04:15:55', '0000-00-00 00:00:00'),
(11, 2016, 8, 3671758, 188014, '2.76', 68221, 103, 55427, 182, '0.27', 20174, '50.10', '36.28', 1, 0, '2017-09-01 04:15:56', '0000-00-00 00:00:00'),
(12, 2017, 5, 2099359, 107383, '2.51', 42815, 93, 37144, 106, '0.25', 19805, '58.08', '39.87', 1, 0, '2017-09-01 04:40:59', '0000-00-00 00:00:00'),
(13, 2017, 4, 2462772, 123564, '2.65', 46697, 102, 40863, 119, '0.25', 20696, '55.32', '37.79', 1, 0, '2017-09-01 04:41:03', '0000-00-00 00:00:00'),
(14, 2016, 5, 4922955, 188624, '2.63', 71829, 101, 55004, 236, '0.33', 20860, '55.20', '38.08', 1, 0, '2017-09-01 04:41:04', '0000-00-00 00:00:00'),
(15, 2016, 1, 6598258, 407478, '3.34', 122001, 117, 95241, 338, '0.28', 19521, '43.44', '29.94', 1, 0, '2017-09-01 06:08:15', '0000-00-00 00:00:00'),
(16, 2017, 3, 2735299, 166903, '2.54', 65695, 99, 58126, 135, '0.21', 20261, '58.64', '39.36', 1, 0, '2017-09-01 06:28:40', '0000-00-00 00:00:00'),
(17, 2016, 3, 5675423, 217375, '2.68', 81106, 108, 63612, 283, '0.35', 20054, '54.64', '37.31', 1, 0, '2017-09-01 06:28:40', '0000-00-00 00:00:00'),
(18, 2016, 4, 5573617, 190811, '2.58', 73879, 104, 56766, 273, '0.37', 20416, '55.51', '38.72', 1, 0, '2017-09-01 06:44:13', '0000-00-00 00:00:00'),
(19, 2016, 6, 4560958, 169968, '2.69', 63134, 110, 50838, 219, '0.35', 20826, '53.25', '37.14', 1, 0, '2017-09-05 03:20:23', '0000-00-00 00:00:00'),
(20, 2017, 8, 1926799, 96510, '2.25', 42984, 80, 38127, 57, '0.21', 33803, '64.71', '44.54', 2, 0, '2017-09-15 08:48:11', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ssm_kpi_ga_months`
--
ALTER TABLE `ssm_kpi_ga_months`
  ADD PRIMARY KEY (`ssm_kpi_ga_month_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ssm_kpi_ga_months`
--
ALTER TABLE `ssm_kpi_ga_months`
  MODIFY `ssm_kpi_ga_month_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
