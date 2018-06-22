-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2017 at 06:34 AM
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
-- Table structure for table `ssm_plans`
--

CREATE TABLE `ssm_plans` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ssm_plans`
--

INSERT INTO `ssm_plans` (`id`, `name`, `price`, `created`, `modified`) VALUES
(1, 'ベーシック', 148000, '2017-10-13 04:37:15', '2017-10-13 04:37:15'),
(2, 'プロフェッショナル', 225000, '2017-10-13 04:37:25', '2017-10-13 04:37:25'),
(3, 'エンタープライズ', 300000, '2017-10-13 04:37:35', '2017-10-13 04:37:35'),
(4, 'エンタープライズ（複数サイト）', 400000, '2017-10-13 04:37:44', '2017-10-13 04:37:44'),
(5, '既存', 500000, '2017-10-13 04:37:58', '2017-10-13 04:37:58'),
(6, 'システム利用', 600000, '2017-10-13 04:38:06', '2017-10-13 04:38:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ssm_plans`
--
ALTER TABLE `ssm_plans`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ssm_plans`
--
ALTER TABLE `ssm_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
