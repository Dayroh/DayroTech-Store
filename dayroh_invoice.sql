-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 28, 2025 at 11:11 AM
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
-- Database: `dayroh_invoice`
--

-- --------------------------------------------------------

--
-- Table structure for table `business_types`
--

CREATE TABLE `business_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_types`
--

INSERT INTO `business_types` (`id`, `name`, `description`) VALUES
(1, 'Computer Sales', 'Computer hardware and software sales'),
(2, 'ISP', 'Internet Service Provider');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `name`, `email`, `phone`, `address`, `company`, `created_at`) VALUES
(6, 'Tilen Ochieng', 'ochiengtilen5@gmail.com', '0111324234', '80100\r\nFort Jesus', '', '2025-07-28 08:00:11');

-- --------------------------------------------------------

--
-- Table structure for table `computer_sales_items`
--

CREATE TABLE `computer_sales_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `computer_sales_items`
--

INSERT INTO `computer_sales_items` (`id`, `invoice_id`, `description`, `quantity`, `unit_price`) VALUES
(0, 13, 'Dell', 1, 30000.00),
(0, 15, 'Hp Elitebook 830 G8 8/256 8th GEN', 1, 29000.00);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `business_type_id` int(11) NOT NULL,
  `invoice_number` varchar(20) NOT NULL,
  `date_issued` date NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('sent','paid','overdue') DEFAULT 'sent',
  `notes` text DEFAULT NULL,
  `tax_rate` decimal(5,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `client_id`, `business_type_id`, `invoice_number`, `date_issued`, `due_date`, `status`, `notes`, `tax_rate`, `discount`, `created_at`) VALUES
(12, 6, 1, 'CS-202507-0001', '2025-07-28', '2025-08-04', 'paid', '', 0.00, 0.00, '2025-07-28 08:02:02'),
(13, 6, 1, 'CS-202507-0002', '2025-07-28', '2025-08-04', 'sent', '', 0.00, 1200.00, '2025-07-28 08:09:11'),
(14, 6, 2, 'ISP-202507-0001', '2025-07-28', '2025-08-04', 'sent', '', 0.00, 0.00, '2025-07-28 08:32:05'),
(15, 6, 1, 'CS-202507-0003', '2025-07-28', '2025-08-04', 'sent', 'Desktop sales', 0.00, 0.00, '2025-07-28 08:35:09');

-- --------------------------------------------------------

--
-- Table structure for table `isp_items`
--

CREATE TABLE `isp_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `subscription_period` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `isp_items`
--

INSERT INTO `isp_items` (`id`, `invoice_id`, `description`, `subscription_period`, `amount`) VALUES
(2, 14, '8MBPs ', '2 Months', 3800.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `business_types`
--
ALTER TABLE `business_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `computer_sales_items`
--
ALTER TABLE `computer_sales_items`
  ADD KEY `computer_sales_items_ibfk_1` (`invoice_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `business_type_id` (`business_type_id`);

--
-- Indexes for table `isp_items`
--
ALTER TABLE `isp_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `business_types`
--
ALTER TABLE `business_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `isp_items`
--
ALTER TABLE `isp_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `computer_sales_items`
--
ALTER TABLE `computer_sales_items`
  ADD CONSTRAINT `computer_sales_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`),
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`business_type_id`) REFERENCES `business_types` (`id`);

--
-- Constraints for table `isp_items`
--
ALTER TABLE `isp_items`
  ADD CONSTRAINT `isp_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
