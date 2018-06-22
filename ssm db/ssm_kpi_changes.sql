-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 01, 2017 lúc 04:30 SA
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
-- Cấu trúc bảng cho bảng `ssm_kpi_changes`
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
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `ssm_kpi_changes`
--

INSERT INTO `ssm_kpi_changes` (`ssm_kpi_change_id`, `year`, `month`, `week`, `day`, `transactionRevenue`, `pageviews`, `pageviewsPerSession`, `sessions`, `avgSessionDuration`, `uniqueUsers`, `transactions`, `transactionsPerSession`, `revenuePerTransaction`, `bounceRate`, `topBounceRate`, `site_id`, `created_user_id`, `created_at`, `updated_at`) VALUES
(1, 2017, 8, 1, 0, '592486', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 2017, 8, 1, 0, '0', '0', '0', '0', '0', '308', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 2017, 8, 1, 0, '-549244', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 2017, 8, 2, 0, '89506', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 2017, 8, 3, 0, '-17587', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 2017, 8, 4, 0, '100000', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 2017, 8, 4, 0, '-100000', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 2017, 8, 1, 0, '0', '0', '0', '0', '0', '-1000', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 2017, 9, 1, 0, '19436', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 2017, 9, 1, 0, '-19436', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `ssm_kpi_changes`
--
ALTER TABLE `ssm_kpi_changes`
  ADD PRIMARY KEY (`ssm_kpi_change_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `ssm_kpi_changes`
--
ALTER TABLE `ssm_kpi_changes`
  MODIFY `ssm_kpi_change_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
