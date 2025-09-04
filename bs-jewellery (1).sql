-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 10:16 AM
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
-- Table structure for table `addtocart`
--

CREATE TABLE `addtocart` (
  `id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cate_id` int(11) NOT NULL,
  `category_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cate_id`, `category_name`) VALUES
(2, 'Bangles');

-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

CREATE TABLE `collection` (
  `collection_id` int(11) NOT NULL,
  `collection_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collection`
--

INSERT INTO `collection` (`collection_id`, `collection_name`) VALUES
(1, 'Antra Collection');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `cust_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile_number` varchar(11) NOT NULL,
  `terms_cond` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`cust_id`, `name`, `email`, `password`, `mobile_number`, `terms_cond`) VALUES
(1, 'Ramesh Kumar', 'ramesh123@gmail.com', 'de7cbbdd1b746025d12317fb93389b61', '1478523390', 1);

-- --------------------------------------------------------

--
-- Table structure for table `diamonds`
--

CREATE TABLE `diamonds` (
  `diamond_id` int(11) NOT NULL,
  `diamonds_type` varchar(50) NOT NULL,
  `diamond_weight` varchar(50) NOT NULL,
  `diamond_price` decimal(10,0) NOT NULL,
  `diamond_shape` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diamonds`
--

INSERT INTO `diamonds` (`diamond_id`, `diamonds_type`, `diamond_weight`, `diamond_price`, `diamond_shape`) VALUES
(1, 'SI IJ', '1 carat', 75000, 'Round\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `metals`
--

CREATE TABLE `metals` (
  `id` int(11) NOT NULL,
  `metal_name` varchar(150) NOT NULL,
  `metal_type` varchar(100) NOT NULL,
  `metal_color` enum('Yellow','White') NOT NULL DEFAULT 'Yellow',
  `metal_weight` varchar(50) NOT NULL,
  `metal_price` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `metals`
--

INSERT INTO `metals` (`id`, `metal_name`, `metal_type`, `metal_color`, `metal_weight`, `metal_price`) VALUES
(2, 'Gold', '18K', 'Yellow', '1 carat', 7901.250),
(3, 'Gold', '14K', 'Yellow', '1 Carat', 33000.000);

-- --------------------------------------------------------

--
-- Table structure for table `offer`
--

CREATE TABLE `offer` (
  `offer_id` int(11) NOT NULL,
  `offer_title` varchar(50) NOT NULL,
  `offer_description` varchar(100) NOT NULL,
  `offer_code` varchar(10) NOT NULL,
  `discount_type` enum('percentage','flat') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('Open','Close','','') NOT NULL DEFAULT 'Open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offer`
--

INSERT INTO `offer` (`offer_id`, `offer_title`, `offer_description`, `offer_code`, `discount_type`, `discount_value`, `start_date`, `end_date`, `created_at`, `status`) VALUES
(2, 'Summer Sale', 'Get flat 20% off on all food items.', 'SUMMER20', 'percentage', 20.00, '2025-09-01', '2025-09-15', '2025-09-01 09:07:28', 'Open');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `pro_id` int(11) DEFAULT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `payment_mode` enum('COD','CARD','UPI','NETBANKING') DEFAULT 'UPI',
  `address` varchar(255) NOT NULL,
  `state` varchar(150) NOT NULL,
  `city` varchar(150) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `delivery_charge` decimal(10,2) DEFAULT 0.00,
  `grand_total` decimal(10,2) NOT NULL,
  `status` enum('Deliverd','Pending','Cancel','') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `product_size` int(11) DEFAULT NULL,
  `of_stones` int(11) DEFAULT NULL,
  `total_diamond_weight` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`pro_id`, `cate_id`, `collection_id`, `product_name`, `product_code`, `product_image`, `product_decsp`, `price`, `gender`, `height`, `width`, `product_wieght`, `metals_id`, `diamonds_id`, `product_size`, `of_stones`, `total_diamond_weight`) VALUES
(17, 2, 1, 'example', 'e0277', '1756819875_pexels-efrem-efre-2786187-29463224.jpg,1756819875_pexels-minan1398-906150.jpg,1756819875_pexels-francesco-ungaro-2325447.jpg', 'deepak', 15000.00, 'Male', 14.55, 15.55, 15, 2, 1, NULL, 2, 10),
(18, 2, 1, 'example', 'e03', '1756820067_pexels-efrem-efre-2786187-29463224.jpg,1756820067_pexels-minan1398-906150.jpg,1756820067_pexels-francesco-ungaro-2325447.jpg', 'deepak', 15000.00, 'Male', 14.55, 15.55, 15, 2, 1, NULL, 2, 10),
(19, 2, 1, 'example', 'e015', '1756880377_pexels-efrem-efre-2786187-29463224.jpg,1756880377_pexels-minan1398-906150.jpg,1756880377_pexels-francesco-ungaro-2325447.jpg', 'deepak', 15000.00, 'Male', 14.55, 15.55, 15, 2, 1, NULL, 2, 10);

-- --------------------------------------------------------

--
-- Table structure for table `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` int(11) NOT NULL,
  `product_type` enum('ring','bangle') NOT NULL,
  `size_label` varchar(20) NOT NULL,
  `diameter_mm` decimal(6,2) DEFAULT NULL,
  `circumference_mm` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `product_type`, `size_label`, `diameter_mm`, `circumference_mm`) VALUES
(1, 'bangle', '2-2', 57.20, 179.70),
(2, 'bangle', '2-4', 60.30, 189.40),
(3, 'bangle', '2-6', 63.50, 199.50),
(4, 'bangle', '2-8', 66.70, 209.60),
(5, 'ring', '6', 16.50, 51.80),
(6, 'ring', '7', 17.30, 54.40),
(7, 'ring', '8', 18.10, 57.00),
(8, 'ring', '9', 18.90, 59.50),
(9, 'ring', '10', 19.80, 62.10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addtocart`
--
ALTER TABLE `addtocart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pro_id` (`pro_id`),
  ADD KEY `cust_id` (`cust_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cate_id`);

--
-- Indexes for table `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`collection_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`cust_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `diamonds`
--
ALTER TABLE `diamonds`
  ADD PRIMARY KEY (`diamond_id`);

--
-- Indexes for table `metals`
--
ALTER TABLE `metals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`offer_id`),
  ADD UNIQUE KEY `offer_title` (`offer_title`),
  ADD UNIQUE KEY `offer_description` (`offer_description`),
  ADD UNIQUE KEY `offer_code` (`offer_code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `pro_id` (`pro_id`),
  ADD KEY `cust_id` (`cust_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pro_id`),
  ADD UNIQUE KEY `product_code` (`product_code`),
  ADD KEY `cate_id` (`cate_id`),
  ADD KEY `collection_id` (`collection_id`),
  ADD KEY `metals_products_foreign_key` (`metals_id`),
  ADD KEY `diamonds_products_foreign_key` (`diamonds_id`),
  ADD KEY `fk_product_sizes_products` (`product_size`);

--
-- Indexes for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addtocart`
--
ALTER TABLE `addtocart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `collection`
--
ALTER TABLE `collection`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `cust_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `diamonds`
--
ALTER TABLE `diamonds`
  MODIFY `diamond_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `metals`
--
ALTER TABLE `metals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `offer`
--
ALTER TABLE `offer`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addtocart`
--
ALTER TABLE `addtocart`
  ADD CONSTRAINT `customer_addtocart_foreign_key` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_addtocart_foreign_key` FOREIGN KEY (`pro_id`) REFERENCES `product` (`pro_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `customer_orders_foreign_key` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_orders_foreign_key` FOREIGN KEY (`pro_id`) REFERENCES `product` (`pro_id`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `collection_product_foreign_key` FOREIGN KEY (`collection_id`) REFERENCES `collection` (`collection_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_product_foreign_key` FOREIGN KEY (`cate_id`) REFERENCES `category` (`cate_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `diamonds_products_foreign_key` FOREIGN KEY (`diamonds_id`) REFERENCES `diamonds` (`diamond_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_product_sizes_products` FOREIGN KEY (`product_size`) REFERENCES `product_sizes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `metals_products_foreign_key` FOREIGN KEY (`metals_id`) REFERENCES `metals` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
