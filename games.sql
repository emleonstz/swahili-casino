-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 20, 2023 at 12:32 AM
-- Server version: 10.5.19-MariaDB-0+deb11u2
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `games`
--

-- --------------------------------------------------------

--
-- Table structure for table `encrypt`
--

CREATE TABLE `encrypt` (
  `id` int(11) NOT NULL,
  `private_key` text DEFAULT NULL,
  `public_key` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `encrypt`
--

INSERT INTO `encrypt` (`id`, `private_key`, `public_key`) VALUES
(2, '-----BEGIN RSA PRIVATE KEY-----\r\nMIICXgIBAAKBgQCM+3hxesft94IVRhaaQxDImT6Q/GkpMtgq4Oj77pisHlbX8BWN\r\nz8w0RaD418LgIoVKSg3KrdI9oBxaVLkeY7vulI03pkiKZQ7GeVFKQyFDX67Z3Qzj\r\nM768tGAlExRcRx2OlPnWm+0fMY62ZPtXqwj1+KR3cs6BYImyFPX7O6gDWwIDAQAB\r\nAoGAM72Gsn8RKPCROSWD3hGG81fqcmNwZMJYO0RXvT4Poftwe8eM4YLFutli/nCR\r\nAJkqudz7usqYQi2kujIlzlVj/tsIbbh6X0iTXB2Zg5K7p+6FHrnaZI8ZpqxXqnkv\r\njAKdayBgCApV24xhTCcTu6v9pRyYqbSetG3U+6tD4Whh9IECQQDUrXQ2pcXRCDN8\r\n74s/ewJBcdgBpkOrNWWEF4M5twoilTxZP3JZPYoW8KT2cv0IXx3kHdRENwX3xTwh\r\nUeAUIeUrAkEAqbNOxcNMGpNGeCu/0g71hn3rhDJQ7NF8P+0cHR8bXnYJy42vH6tj\r\n3TdUkVEw33IaN4u4y9CQLgrR/rcWTFCikQJBAL9UuJs9MaSnOyLnoh9e+52C3LBH\r\nMtOA+y4FtSGkIPMRzTQBV08fhO60WcP5mNgCMjL5/u/LjIvmHf1f2jZic/cCQQCi\r\nfISdST0yaPfdko5NtHMU/ramUoM37fFZSFBV9fX/wWfpllUrLrElHJiFt8bi/F7r\r\n9oXP6XhUDZo6mNXp5n9xAkEAwnrYZcwSTPWxgtT59i4Bzs+Hh7KvroSV62P3tX2M\r\nRS7OofmCVHFEbIfjB96hOY9K0SC2U/JD5GOxn6LyBOhTIQ==\r\n-----END RSA PRIVATE KEY-----', '-----BEGIN PUBLIC KEY-----\r\nMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCM+3hxesft94IVRhaaQxDImT6Q\r\n/GkpMtgq4Oj77pisHlbX8BWNz8w0RaD418LgIoVKSg3KrdI9oBxaVLkeY7vulI03\r\npkiKZQ7GeVFKQyFDX67Z3QzjM768tGAlExRcRx2OlPnWm+0fMY62ZPtXqwj1+KR3\r\ncs6BYImyFPX7O6gDWwIDAQAB\r\n-----END PUBLIC KEY-----');

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `id` int(11) NOT NULL,
  `game_name` varchar(100) NOT NULL,
  `image` varchar(300) NOT NULL,
  `path` varchar(100) NOT NULL,
  `cost_per_reel` int(11) NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id`, `game_name`, `image`, `path`, `cost_per_reel`, `category`) VALUES
(1, 'Matunda Bonanza', 'matunda/img/banner.jpg', 'matunda', 200, 'slots');

-- --------------------------------------------------------

--
-- Table structure for table `opt_tmp`
--

CREATE TABLE `opt_tmp` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `expire_time` int(11) NOT NULL,
  `code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `opt_tmp`
--

INSERT INTO `opt_tmp` (`id`, `user_id`, `expire_time`, `code`) VALUES
(7, 8, 1689429603, 677012),
(9, 10, 1689500635, 822551),
(10, 11, 1689501915, 470548),
(11, 12, 1689502058, 773998),
(12, 13, 1689502225, 680667),
(13, 14, 1689503305, 872258),
(14, 15, 1689514572, 171613),
(15, 16, 1689515032, 113201);

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `account_balance` int(11) NOT NULL DEFAULT 0,
  `api_key` varchar(250) NOT NULL,
  `account_status` varchar(100) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reels`
--

CREATE TABLE `reels` (
  `id` int(11) NOT NULL,
  `reel_name` varchar(100) NOT NULL,
  `unit` int(11) NOT NULL,
  `game_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reels`
--

INSERT INTO `reels` (`id`, `reel_name`, `unit`, `game_id`) VALUES
(1, 'Banana', 2, 1),
(2, 'Lemon', 4, 1),
(3, 'Orange', 6, 1),
(4, 'Cherry', 8, 1),
(5, 'Grape', 10, 1),
(6, 'Pear', 12, 1),
(7, 'Apple', 20, 1),
(8, 'Strawberry', 24, 1),
(9, 'Watermelon', 54, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `encrypt`
--
ALTER TABLE `encrypt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `opt_tmp`
--
ALTER TABLE `opt_tmp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reels`
--
ALTER TABLE `reels`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `encrypt`
--
ALTER TABLE `encrypt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `opt_tmp`
--
ALTER TABLE `opt_tmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `reels`
--
ALTER TABLE `reels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
