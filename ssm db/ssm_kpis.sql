-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 01, 2017 lúc 04:29 SA
-- Phiên bản máy phục vụ: 5.7.14
-- Phiên bản PHP: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shishimai`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ssm_kpis`
--

CREATE TABLE `ssm_kpis` (
  `ssm_kpi_id` int(11) NOT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `week` int(2) NOT NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Đang đổ dữ liệu cho bảng `ssm_kpis`
--

INSERT INTO `ssm_kpis` (`ssm_kpi_id`, `year`, `month`, `week`, `transactionRevenue`, `pageviews`, `pageviewsPerSession`, `sessions`, `avgSessionDuration`, `uniqueUsers`, `transactions`, `transactionsPerSession`, `revenuePerTransaction`, `bounceRate`, `topBounceRate`, `site_id`, `created_user_id`, `created_at`, `updated_at`) VALUES
(1, 2017, 8, 1, 874324, 45997, '3.00', 15332, 90, 9000, 30, '0.30', 20000, '50.00', '50.00', 1, 0, '2017-08-24 07:21:16', '2017-08-21 03:18:38'),
(2, 2017, 8, 2, 874322, 45997, '3.00', 15332, 100, 9000, 50, '0.40', 20000, '50.00', '50.00', 1, 0, '2017-08-25 06:25:59', '2017-08-21 03:40:41'),
(3, 2017, 8, 3, 874322, 45997, '3.00', 15332, 90, 10000, 0, '0.30', 20000, '50.00', '50.00', 1, 0, '2017-08-25 06:26:13', '2017-08-21 03:57:06'),
(4, 2017, 8, 4, 874322, 45997, '3.00', 15332, 90, 10000, 0, '0.30', 20000, '50.00', '50.00', 1, 0, '2017-09-01 03:44:28', '0000-00-00 00:00:00'),
(5, 2017, 8, 5, 374709, 19713, '3.00', 6571, 90, 10000, 0, '0.30', 20000, '50.00', '50.00', 1, 0, '2017-09-01 03:44:37', '0000-00-00 00:00:00');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `ssm_kpis`
--
ALTER TABLE `ssm_kpis`
  ADD PRIMARY KEY (`ssm_kpi_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `ssm_kpis`
--
ALTER TABLE `ssm_kpis`
  MODIFY `ssm_kpi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
