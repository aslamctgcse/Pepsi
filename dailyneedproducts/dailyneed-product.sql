-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2020 at 01:46 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dailyneed-product`
--

-- --------------------------------------------------------

--
-- Table structure for table `orderproducts`
--

CREATE TABLE `orderproducts` (
  `id` int(50) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_price` int(100) NOT NULL,
  `product_qty` int(100) NOT NULL,
  `order_id` int(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orderproducts`
--

INSERT INTO `orderproducts` (`id`, `product_name`, `product_price`, `product_qty`, `order_id`, `created_at`, `updated_at`) VALUES
(1, 'Harvest Bread az', 50, 2, 2, '2020-10-07 15:41:02', '2020-10-07 15:41:02'),
(2, 'mother dairy curd az', 58, 2, 2, '2020-10-07 15:41:02', '2020-10-07 15:41:02'),
(3, 'mother dairy curd az', 58, 1, 3, '2020-10-07 17:01:30', '2020-10-07 17:01:30'),
(4, 'paneer az', 300, 1, 3, '2020-10-07 17:01:30', '2020-10-07 17:01:30'),
(5, 'mother dairy curd az', 58, 2, 4, '2020-10-07 17:28:55', '2020-10-07 17:28:55'),
(6, 'paneer az', 300, 3, 4, '2020-10-07 17:28:56', '2020-10-07 17:28:56'),
(7, 'APPLE FRESH', 96, 1, 4, '2020-10-07 17:28:56', '2020-10-07 17:28:56'),
(8, 'Harvest Bread ', 50, 1, 5, '2020-10-07 18:12:32', '2020-10-07 18:12:32'),
(9, 'WATERMELON', 29, 1, 5, '2020-10-07 18:12:32', '2020-10-07 18:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(50) NOT NULL,
  `customer_name` varchar(200) NOT NULL,
  `customer_email` varchar(200) NOT NULL,
  `customer_address` text NOT NULL,
  `total` int(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_address`, `total`, `created_at`, `updated_at`) VALUES
(1, 'azwar salal', 'azwar.salal92@gmail.com', 'abul fazal jamia nagar okhla, New Delhi', 216, '2020-10-07 15:39:55', '2020-10-07 15:39:55'),
(2, 'azwar salal', 'azwar.salal92@gmail.com', 'abul fazal jamia nagar okhla, New Delhi', 216, '2020-10-07 15:41:01', '2020-10-07 15:41:01'),
(3, 'yogesh joshi', 'azwar.salal92@gmail.com', 'test address noida electorinc city noida', 358, '2020-10-07 17:01:30', '2020-10-07 17:01:30'),
(4, 'azwar salal', 'azwar.salal92@gmail.com', 'test address', 1112, '2020-10-07 17:28:55', '2020-10-07 17:28:55'),
(5, 'ravi salal', 'azwar.salal92@gmail.com', 'noida electorinc city noidda second address', 79, '2020-10-07 18:12:32', '2020-10-07 18:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(50) NOT NULL,
  `pname` varchar(100) NOT NULL,
  `pdesc` varchar(100) DEFAULT NULL,
  `pprice` int(100) NOT NULL,
  `pimage` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `pname`, `pdesc`, `pprice`, `pimage`, `created_at`, `updated_at`) VALUES
(1, 'Harvest Bread ', 'lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum ', 50, 'img/product/1.png', '2020-10-07 19:25:26', '2020-10-07 19:25:26'),
(2, 'mother dairy curd ', 'fdhbhjfbjfd', 58, 'img/product/2.jpg', '2020-10-07 19:25:26', '2020-10-07 19:25:26'),
(3, 'paneer ', 'fdhbhjfbjfd', 300, 'img/product/3.jpg', '2020-10-07 19:25:26', '2020-10-07 19:25:26'),
(5, 'APPLE FRESH', NULL, 96, 'img/product/1602099420_12.jpg', '2020-10-07 14:07:00', '2020-10-07 14:07:00'),
(6, 'Apple Delux', NULL, 137, 'img/product/1602099598_world youth day .jpg', '2020-10-07 14:09:58', '2020-10-07 14:09:58'),
(7, 'test nproduct', NULL, 96, 'img/product/1602108327_cc2.jpg', '2020-10-07 16:35:27', '2020-10-07 16:35:27'),
(8, 'test default', NULL, 96, NULL, '2020-10-07 17:37:51', '2020-10-07 17:37:51'),
(9, 'ANAR', NULL, 145, 'img/product/1602113091_12.jpg', '2020-10-07 17:54:51', '2020-10-07 17:54:51'),
(10, 'BANANA', NULL, 45, 'img/product/1602113227_8.jpg', '2020-10-07 17:57:07', '2020-10-07 17:57:07'),
(11, 'WATERMELON', NULL, 29, 'img/product/1602113314_7.jpg', '2020-10-07 17:58:34', '2020-10-07 17:58:34'),
(12, 'PAPAYA', NULL, 55, 'img/product/1602113366_9.jpg', '2020-10-07 17:59:26', '2020-10-07 17:59:26'),
(13, 'MOSAMBHI', NULL, 48, 'img/product/1602113424_10.jpg', '2020-10-07 18:00:24', '2020-10-07 18:00:24'),
(14, 'BHINDI', NULL, 34, 'img/product/1602113484_20.jpg', '2020-10-07 18:01:24', '2020-10-07 18:01:24'),
(15, 'BHUTTA', NULL, 56, 'img/product/1602113539_22.jpg', '2020-10-07 18:02:19', '2020-10-07 18:02:19'),
(16, 'COCONUT', NULL, 33, 'img/product/1602113580_23.jpg', '2020-10-07 18:03:00', '2020-10-07 18:03:00'),
(17, 'DHANIYA', NULL, 62, 'img/product/1602113625_25.jpg', '2020-10-07 18:03:45', '2020-10-07 18:03:45'),
(18, 'GARLIC LASUN', NULL, 51, 'img/product/1602113668_28.jpg', '2020-10-07 18:04:28', '2020-10-07 18:04:28'),
(19, 'GOBHI', NULL, 89, 'img/product/1602113715_30.png', '2020-10-07 18:05:15', '2020-10-07 18:05:15'),
(20, 'HARI MIRCH', NULL, 29, 'img/product/1602113751_31.png', '2020-10-07 18:05:51', '2020-10-07 18:05:51'),
(21, 'KARELA', NULL, 44, 'img/product/1602113783_33.jpg', '2020-10-07 18:06:23', '2020-10-07 18:06:23'),
(22, 'KHEERA', NULL, 29, 'img/product/1602113812_34.jpg', '2020-10-07 18:06:52', '2020-10-07 18:06:52'),
(23, 'LAUKI', NULL, 29, 'img/product/1602113844_36.jpg', '2020-10-07 18:07:24', '2020-10-07 18:07:24'),
(24, 'LEMON', NULL, 22, 'img/product/1602113888_37.jpg', '2020-10-07 18:08:08', '2020-10-07 18:08:08'),
(25, 'MUSHROOM', NULL, 22, 'img/product/1602113920_38.jpg', '2020-10-07 18:08:40', '2020-10-07 18:08:40'),
(26, 'Nasik onion', NULL, 44, 'img/product/1602113974_39.png', '2020-10-07 18:09:34', '2020-10-07 18:09:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orderproducts`
--
ALTER TABLE `orderproducts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orderproducts`
--
ALTER TABLE `orderproducts`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
