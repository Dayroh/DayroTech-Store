-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2025 at 02:03 PM
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
-- Database: `railway`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'Kennedy Vicky', 'kendayroh1@gmail.com', 'testing one tow', '2025-07-07 14:01:04'),
(2, 'Kennedy Vicky', 'kendayroh1@gmail.com', 'testing one tow', '2025-07-07 14:01:08'),
(4, 'Tilen Ochieng', 'ochiengtilen5@gmail.com', 'I need a motherboard for 850 G3 Elitebook', '2025-07-10 11:06:03');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `phone`, `address`, `total_price`, `order_date`, `user_id`) VALUES
(1, 'Kennedy Vicky', '0705556565', '254', 55000.00, '2025-07-07 12:06:56', 3),
(2, 'Tilen Ochieng', '0111324234', '80100\r\nFort Jesus', 38000.00, '2025-07-10 11:07:01', 5);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `quantity`, `price`) VALUES
(1, 1, 'HP EliteBook', 1, 55000.00),
(2, NULL, 'HP EliteBook', 1, 55000.00),
(3, NULL, 'Lenovo IdeaPad', 1, 35000.00),
(4, NULL, 'HP EliteBook', 1, 55000.00),
(5, NULL, 'HP EliteBook', 1, 55000.00),
(6, NULL, 'HP EliteBook', 1, 55000.00),
(7, 2, 'Laptop', 1, 28000.00),
(8, 2, 'Laptop', 1, 10000.00);

-- --------------------------------------------------------

--
-- Table structure for table `plan_requests`
--

CREATE TABLE `plan_requests` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `package` varchar(50) NOT NULL,
  `request_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plan_requests`
--

INSERT INTO `plan_requests` (`id`, `name`, `email`, `phone`, `package`, `request_date`) VALUES
(1, 'Kennedy Vicky', 'kendayroh1@gmail.com', '0705556565', 'Basic Plan - 5 Mbps / Ksh 1,299', '2025-07-08 10:53:55'),
(2, 'Rodgers Muthomi', 'kendayroh1@gmail.com', '0705556565', 'Basic Plan - 10 Mbps / Ksh 1,999', '2025-07-08 11:55:15');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `brand`, `category`, `image`, `is_featured`) VALUES
(1, 'HP EliteBook', 'Core i5, 8GB RAM, 256GB SSD', 55000, 'hp', 'laptop', 'assets/images/hp-laptop.jpg', 0),
(2, 'Lenovo IdeaPad', 'Core i3, 4GB RAM, 1TB HDD', 35000, 'lenovo', 'laptop', 'assets/images/lenovo-laptop.jpg', 0),
(3, 'HP Mouse', 'Wireless USB Mouse', 1200, 'hp', 'accessory', 'assets/images/hp-mouse.jpg', 0),
(4, 'Lenovo Bag', '15.6 inch laptop backpack', 1900, 'HP', 'accessory', 'assets/images/lenovo-bag.jpg', 0),
(5, 'Laptop', 'New one', 10000, 'Acer', 'laptop', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRO2DUiPsYlm8xfPDK8cEywx-ElRXm0VqWxhax85tWUCj7W9t8tnRnHfqa3cTgsmchlYU-jliFyzApygDwsrLEGah0zkbL7fKfPY9A8zcK9tw4vT37ytL_egj8tePIQorfa&usqp=CAc', 0),
(6, 'Laptop', 'Processor: 6th Gen Intel Core i5 processor\r\nMemory: 8GB RAM\r\nStorage: 256GB SSD\r\nDisplay: 14-inch 4K UHD display\r\nAudio: Bang & Olufsen\r\nKeyboard: Backlit Keyboard', 28000, 'HP', 'laptop', 'https://shoptech.co.ke/wp-content/uploads/2025/03/840-G4-3-600x600.jpeg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `related_images`
--

CREATE TABLE `related_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'Dayroh', 'kendayroh1@gmail.com', '6803', '2025-07-07 12:22:40', 'admin'),
(3, 'Ken', 'kenero@gmail.com', '1111', '2025-07-07 12:30:41', 'user'),
(4, 'Tilen', 'tilen@gmail.com', '1111', '2025-07-07 12:22:40', 'admin'),
(5, 'Master', 'master@gmail.com', '123456', '2025-07-10 11:04:33', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `plan_requests`
--
ALTER TABLE `plan_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `related_images`
--
ALTER TABLE `related_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `plan_requests`
--
ALTER TABLE `plan_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `related_images`
--
ALTER TABLE `related_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `related_images`
--
ALTER TABLE `related_images`
  ADD CONSTRAINT `related_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
