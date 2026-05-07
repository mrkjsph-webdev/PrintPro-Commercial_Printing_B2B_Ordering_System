-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2026 at 07:26 AM
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
-- Database: `printprodb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `order_completed` int(11) NOT NULL,
  `order_processing` int(11) NOT NULL,
  `order_cancelled` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customization`
--

CREATE TABLE `customization` (
  `customization_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `paper_size` varchar(50) NOT NULL,
  `gsm` varchar(50) NOT NULL,
  `paper_texture` varchar(50) NOT NULL,
  `copies` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customization`
--

INSERT INTO `customization` (`customization_id`, `file_id`, `paper_size`, `gsm`, `paper_texture`, `copies`, `price`) VALUES
(1, 1, 'A0 (33.11 x 46.81 in)', '120', 'Glossy', 2, 310.00),
(2, 6, 'A0 (33.11 x 46.81 in)', '120', 'Glossy', 1, 155.00),
(3, 10, 'A0 (33.11 x 46.81 in)', '120', 'Glossy', 1, 155.00),
(4, 11, 'A0 (33.11 x 46.81 in)', '120', 'Glossy', 2, 310.00),
(5, 12, 'A0 (33.11 x 46.81 in)', '115', 'Glossy', 1, 152.50),
(6, 13, 'A0 (33.11 x 46.81 in)', '120', 'Glossy', 1, 155.00),
(7, 14, 'A1 (29.7 x 42 in)', '120', 'Glossy', 1, 135.00),
(8, 16, 'A1 (29.7 x 42 in)', '120', 'Glossy', 1, 135.00),
(9, 19, 'A0 (33.11 x 46.81 in)', '115', 'Glossy', 1, 152.50),
(10, 21, 'A0 (33.11 x 46.81 in)', '120', 'Glossy', 1, 155.00),
(11, 23, 'A1 (29.7 x 42 in)', '115', 'Glossy', 1, 132.50),
(12, 24, 'A1 (29.7 x 42 in)', '120', 'Glossy', 1, 135.00),
(13, 26, 'A0 (33.11 x 46.81 in)', '115', 'Matte Finish', 1, 150.50),
(14, 27, 'A0 (33.11 x 46.81 in)', '115', 'Glossy', 1, 152.50),
(15, 28, 'A1 (29.7 x 42 in)', '120', 'Glossy', 1, 135.00),
(16, 29, 'A1 (29.7 x 42 in)', '110', 'Matte Finish', 1, 128.00),
(17, 30, 'A1 (29.7 x 42 in)', '110', 'Glossy', 1, 130.00),
(18, 31, 'A0 (33.11 x 46.81 in)', '120', 'Glossy', 1, 155.00),
(19, 32, 'A2 (21 x 29.7 in)', '120', 'Glossy', 1, 115.00),
(20, 33, 'A1 (29.7 x 42 in)', '120', 'Glossy', 1, 135.00),
(21, 34, 'A0 (33.11 x 46.81 in)', '115', 'Glossy', 1, 152.50),
(22, 35, 'A0 (33.11 x 46.81 in)', '115', 'Glossy', 1, 152.50),
(23, 36, 'A1 (29.7 x 42 in)', '110', 'Glossy', 1, 130.00);

-- --------------------------------------------------------

--
-- Table structure for table `file_upload`
--

CREATE TABLE `file_upload` (
  `file_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `upload_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file_upload`
--

INSERT INTO `file_upload` (`file_id`, `user_id`, `image`, `upload_date`) VALUES
(1, 1, 'uploaded_files/img_69fc15cf788024.07593640.png', '2026-05-07 12:32:15'),
(2, 1, 'uploaded_files/img_69fc16d048bb94.62209525.png', '2026-05-07 12:36:32'),
(3, 1, 'uploaded_files/img_69fc1716354f51.12823818.png', '2026-05-07 12:37:42'),
(4, 1, 'uploaded_files/img_69fc1730538912.94107476.png', '2026-05-07 12:38:08'),
(5, 1, 'uploaded_files/img_69fc1766531ce4.99683721.png', '2026-05-07 12:39:02'),
(6, 1, 'uploaded_files/img_69fc184505a914.13535442.png', '2026-05-07 12:42:45'),
(7, 1, 'uploaded_files/img_69fc185c3e29d1.65172675.png', '2026-05-07 12:43:08'),
(8, 1, 'uploaded_files/img_69fc18c76b1221.51413097.png', '2026-05-07 12:44:55'),
(9, 1, 'uploaded_files/img_69fc18d03ff149.55236920.png', '2026-05-07 12:45:04'),
(10, 1, 'uploaded_files/img_69fc18dc457e61.04842553.png', '2026-05-07 12:45:16'),
(11, 1, 'uploaded_files/img_69fc18f7893409.20135300.png', '2026-05-07 12:45:43'),
(12, 1, 'uploaded_files/img_69fc191fe124f9.86037030.png', '2026-05-07 12:46:23'),
(13, 1, 'uploaded_files/img_69fc1985bd1bb2.62542777.png', '2026-05-07 12:48:05'),
(14, 1, 'uploaded_files/img_69fc199bca83c5.95891783.png', '2026-05-07 12:48:27'),
(15, 1, 'uploaded_files/img_69fc1a1a5f4235.76892303.png', '2026-05-07 12:50:34'),
(16, 1, 'uploaded_files/img_69fc1a435cdc30.06223139.png', '2026-05-07 12:51:15'),
(17, 1, 'uploaded_files/img_69fc1bcdea88f3.75905927.png', '2026-05-07 12:57:49'),
(18, 1, 'uploaded_files/img_69fc1bf77d8272.50704489.png', '2026-05-07 12:58:31'),
(19, 1, 'uploaded_files/img_69fc1c1851def4.48557919.png', '2026-05-07 12:59:04'),
(20, 1, 'uploaded_files/img_69fc1c675f30f1.85755542.png', '2026-05-07 13:00:23'),
(21, 1, 'uploaded_files/img_69fc1c9e5947e9.27518653.png', '2026-05-07 13:01:18'),
(22, 1, 'uploaded_files/img_69fc1da5dc9a04.88224149.png', '2026-05-07 13:05:41'),
(23, 1, 'uploaded_files/img_69fc1db4c7a403.45200463.png', '2026-05-07 13:05:56'),
(24, 1, 'uploaded_files/img_69fc1dc2032ed4.04651696.png', '2026-05-07 13:06:10'),
(25, 1, 'uploaded_files/img_69fc1dd3c250e6.33672152.png', '2026-05-07 13:06:27'),
(26, 1, 'uploaded_files/img_69fc1f7452f7b3.77669913.png', '2026-05-07 13:13:24'),
(27, 1, 'uploaded_files/img_69fc1f84a716d7.23248086.png', '2026-05-07 13:13:40'),
(28, 1, 'uploaded_files/img_69fc1fb07301d5.45558242.png', '2026-05-07 13:14:24'),
(29, 2, 'uploaded_files/img_69fc1fcbb0d070.81721629.png', '2026-05-07 13:14:51'),
(30, 2, 'uploaded_files/img_69fc1fe716fac2.20096358.png', '2026-05-07 13:15:19'),
(31, 2, 'uploaded_files/img_69fc1ff8b42ca7.92876338.png', '2026-05-07 13:15:36'),
(32, 2, 'uploaded_files/img_69fc20039a4aa0.66971642.png', '2026-05-07 13:15:47'),
(33, 2, 'uploaded_files/img_69fc200d3f52c3.74199949.png', '2026-05-07 13:15:57'),
(34, 2, 'uploaded_files/img_69fc202341c794.90276851.png', '2026-05-07 13:16:19'),
(35, 2, 'uploaded_files/img_69fc202b6637e2.96423704.png', '2026-05-07 13:16:27'),
(36, 2, 'uploaded_files/img_69fc203556fcd6.11685015.png', '2026-05-07 13:16:37');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `order_status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('unpaid','paid','refunded') NOT NULL DEFAULT 'unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_check_out`
--

CREATE TABLE `order_check_out` (
  `checkout_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` enum('cash','credit card') NOT NULL,
  `payment_reference` varchar(100) NOT NULL,
  `checkout_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customization_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_summary`
--

CREATE TABLE `order_summary` (
  `summary_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `product_status` enum('available','unavailable') NOT NULL DEFAULT 'available',
  `is_customizable` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `stock_quantity`, `product_status`, `is_customizable`) VALUES
(1, 1, 'Flyer', 10, 'available', 1),
(2, 2, 'Postcard', 10, 'available', 1),
(3, 3, 'Poster', 10, 'available', 1),
(4, 4, 'Business Card', 10, 'available', 1),
(5, 5, 'Brochure', 10, 'available', 1),
(6, 6, 'Invitations', 10, 'available', 1),
(7, 7, 'Magazine Cover', 10, 'available', 1),
(8, 8, 'Resume', 10, 'available', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`category_id`, `category_name`) VALUES
(1, 'Flyers'),
(2, 'Postcards'),
(3, 'Posters'),
(4, 'Business Cards'),
(5, 'Brochures'),
(6, 'Invitations'),
(7, 'Magazine Covers'),
(8, 'Resume');

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `receipt_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `receipt_number` varchar(50) NOT NULL,
  `payment_date` datetime NOT NULL,
  `amount_paid` datetime NOT NULL,
  `payment_status` enum('paid','pending') NOT NULL DEFAULT 'paid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_and_analysis`
--

CREATE TABLE `report_and_analysis` (
  `report_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `report_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `cart_status` enum('active','checked_out') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shopping_cart`
--

INSERT INTO `shopping_cart` (`cart_id`, `user_id`, `created_at`, `updated_at`, `cart_status`) VALUES
(1, 1, '2026-05-07 12:32:20', '2026-05-07 13:14:27', 'active'),
(2, 2, '2026-05-07 13:14:52', '2026-05-07 13:16:40', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart_items`
--

CREATE TABLE `shopping_cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `added_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shopping_cart_items`
--

INSERT INTO `shopping_cart_items` (`cart_item_id`, `cart_id`, `product_id`, `unit_price`, `added_at`) VALUES
(1, 1, 4, 310.00, '2026-05-07 12:32:20'),
(2, 1, 4, 155.00, '2026-05-07 12:42:45'),
(3, 1, 3, 155.00, '2026-05-07 12:45:20'),
(4, 1, 5, 310.00, '2026-05-07 12:45:48'),
(5, 1, 6, 152.50, '2026-05-07 12:46:28'),
(6, 1, 7, 155.00, '2026-05-07 12:48:09'),
(7, 1, 8, 135.00, '2026-05-07 12:48:32'),
(8, 1, 6, 135.00, '2026-05-07 12:51:19'),
(9, 1, 3, 152.50, '2026-05-07 12:59:08'),
(10, 1, 3, 155.00, '2026-05-07 13:01:22'),
(11, 1, 3, 132.50, '2026-05-07 13:05:57'),
(12, 1, 5, 135.00, '2026-05-07 13:06:14'),
(13, 1, 2, 150.50, '2026-05-07 13:13:26'),
(14, 1, 1, 152.50, '2026-05-07 13:13:44'),
(15, 1, 1, 135.00, '2026-05-07 13:14:27'),
(16, 2, 2, 128.00, '2026-05-07 13:14:52'),
(17, 2, 1, 130.00, '2026-05-07 13:15:24'),
(18, 2, 3, 155.00, '2026-05-07 13:15:40'),
(19, 2, 4, 115.00, '2026-05-07 13:15:51'),
(20, 2, 5, 135.00, '2026-05-07 13:16:00'),
(21, 2, 6, 152.50, '2026-05-07 13:16:19'),
(22, 2, 7, 152.50, '2026-05-07 13:16:30'),
(23, 2, 8, 130.00, '2026-05-07 13:16:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_initial` varchar(10) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `occupation` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `middle_initial`, `last_name`, `username`, `email`, `contact_number`, `user_password`, `occupation`) VALUES
(1, 'John', 'P', 'Pork', 'johnpork', 'johnporkjr@gmail.com', '0912345678', '$2y$10$THBJOSkKwx7MvkvhucbzdeLujncXUTVghoTEpP5OpEonv3gNBTpv2', 'freelancer'),
(2, 'Miguel', 'D', 'Dantes', 'migueldantes', 'migueldantes@gmail.com', '0912345678', '$2y$10$4WJ788SF6JbBzQXaDbK4R.Unu8QNaaQ1nNtpNaJJQMM124qXAx1VG', 'employee'),
(3, 'Roland', 'B.', 'Donald', 'rolanddonald', 'ronaldB2@gmail.com', '0912345678', '$2y$10$aG78A6jannXZ0jaeikK4OeCUI5xWwJMhWKGDj2NJV65DO6wiuPmke', 'Part-Timer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `customization`
--
ALTER TABLE `customization`
  ADD PRIMARY KEY (`customization_id`),
  ADD KEY `fk_file_customization` (`file_id`);

--
-- Indexes for table `file_upload`
--
ALTER TABLE `file_upload`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `fk_user_file` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_user_order` (`user_id`);

--
-- Indexes for table `order_check_out`
--
ALTER TABLE `order_check_out`
  ADD PRIMARY KEY (`checkout_id`),
  ADD KEY `fk_order_checkout` (`order_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `fk_order_detail` (`order_id`),
  ADD KEY `fk_product_detail` (`product_id`),
  ADD KEY `fk_customization_detail` (`customization_id`);

--
-- Indexes for table `order_summary`
--
ALTER TABLE `order_summary`
  ADD PRIMARY KEY (`summary_id`),
  ADD KEY `fk_order_summary` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_product_category` (`category_id`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`receipt_id`),
  ADD KEY `fk_order_receipt` (`order_id`);

--
-- Indexes for table `report_and_analysis`
--
ALTER TABLE `report_and_analysis`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `fk_admin_report` (`admin_id`);

--
-- Indexes for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `fk_user_cart` (`user_id`);

--
-- Indexes for table `shopping_cart_items`
--
ALTER TABLE `shopping_cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `fk_cart_item` (`cart_id`),
  ADD KEY `fk_cart_product` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customization`
--
ALTER TABLE `customization`
  MODIFY `customization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `file_upload`
--
ALTER TABLE `file_upload`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_check_out`
--
ALTER TABLE `order_check_out`
  MODIFY `checkout_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_summary`
--
ALTER TABLE `order_summary`
  MODIFY `summary_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `receipt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_and_analysis`
--
ALTER TABLE `report_and_analysis`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shopping_cart_items`
--
ALTER TABLE `shopping_cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customization`
--
ALTER TABLE `customization`
  ADD CONSTRAINT `fk_file_customization` FOREIGN KEY (`file_id`) REFERENCES `file_upload` (`file_id`);

--
-- Constraints for table `file_upload`
--
ALTER TABLE `file_upload`
  ADD CONSTRAINT `fk_user_file` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_user_order` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_check_out`
--
ALTER TABLE `order_check_out`
  ADD CONSTRAINT `fk_order_checkout` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_customization_detail` FOREIGN KEY (`customization_id`) REFERENCES `customization` (`customization_id`),
  ADD CONSTRAINT `fk_order_detail` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `fk_product_detail` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `order_summary`
--
ALTER TABLE `order_summary`
  ADD CONSTRAINT `fk_order_summary` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `product_category` (`category_id`);

--
-- Constraints for table `receipt`
--
ALTER TABLE `receipt`
  ADD CONSTRAINT `fk_order_receipt` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `report_and_analysis`
--
ALTER TABLE `report_and_analysis`
  ADD CONSTRAINT `fk_admin_report` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`);

--
-- Constraints for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `fk_user_cart` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `shopping_cart_items`
--
ALTER TABLE `shopping_cart_items`
  ADD CONSTRAINT `fk_cart_item` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart` (`cart_id`),
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
