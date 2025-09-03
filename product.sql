-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2025 at 01:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bs-jewellery`
--

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pro_id` int(11) NOT NULL,
  `cate_id` int(11) DEFAULT NULL,
  `collection_id` int(11) DEFAULT NULL,
  `product_name` varchar(150) DEFAULT NULL,
  `product_code` varchar(20) DEFAULT NULL,
  `product_image` text DEFAULT NULL,
  `product_decsp` varchar(150) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `gender` enum('Male','Female','Unisex') DEFAULT NULL,
  `height` float DEFAULT NULL,
  `width` float DEFAULT NULL,
  `product_wieght` decimal(10,0) NOT NULL,
  `metals_id` int(11) NOT NULL,
  `diamonds_id` int(11) NOT NULL,
  `of_stones` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pro_id`),
  ADD UNIQUE KEY `product_code` (`product_code`),
  ADD KEY `cate_id` (`cate_id`),
  ADD KEY `collection_id` (`collection_id`),
  ADD KEY `metals_products_foreign_key` (`metals_id`),
  ADD KEY `diamonds_products_foreign_key` (`diamonds_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pro_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `collection_product_foreign_key` FOREIGN KEY (`collection_id`) REFERENCES `collection` (`collection_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_product_foreign_key` FOREIGN KEY (`cate_id`) REFERENCES `category` (`cate_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `diamonds_products_foreign_key` FOREIGN KEY (`diamonds_id`) REFERENCES `diamonds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `metals_products_foreign_key` FOREIGN KEY (`metals_id`) REFERENCES `metals` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
