-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2026 at 02:47 PM
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
(1, 1, 'uploaded_files/img_69fb35108246e5.12733899.png', '2026-05-06 20:33:20'),
(2, 3, 'uploaded_files/img_69fb355a9627b2.58512489.png', '2026-05-06 20:34:34'),
(3, 3, 'uploaded_files/img_69fb356e445d66.45260733.png', '2026-05-06 20:34:54');

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
  `payment_method` enum('cash','gcash') NOT NULL,
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
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_summary`
--

CREATE TABLE `order_summary` (
  `summary_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
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
  `base_price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) NOT NULL,
  `product_status` enum('available','unavailable') NOT NULL DEFAULT 'available',
  `is_customizable` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `base_price`, `stock_quantity`, `image`, `product_status`, `is_customizable`) VALUES
(1, 1, 'Flyer-1', 10.00, 10, 'image_resources/flyers/flyers.png', 'available', 1),
(2, 1, 'Flyer-2', 10.00, 10, 'image_resources/flyers/flyers-2.png', 'available', 1),
(3, 1, 'Flyer-3', 10.00, 10, 'image_resources/flyers/flyers-3.png', 'available', 1),
(4, 1, 'Flyer-4', 10.00, 10, 'image_resources/flyers/flyers-4.png', 'available', 1),
(5, 2, 'Postcard-1', 45.00, 10, 'image_resources/postcards/postcards-2.png', 'available', 1),
(6, 2, 'Postcard-2', 45.00, 10, 'image_resources/postcards/postcards-3.png', 'available', 1),
(7, 2, 'Postcard-3', 45.00, 10, 'image_resources/postcards/postcards-4.png', 'available', 1),
(8, 2, 'Postcard-4', 45.00, 10, 'image_resources/postcards/postcards-5.png', 'available', 1),
(9, 3, 'Poster-1', 180.00, 10, 'image_resources/poster/poster-2.webp', 'available', 1),
(10, 3, 'Poster-2', 180.00, 10, 'image_resources/poster/poster-3.webp', 'available', 1),
(11, 3, 'Poster-3', 180.00, 10, 'image_resources/poster/poster-4.webp', 'available', 1),
(12, 3, 'Poster-4', 180.00, 10, 'image_resources/poster/poster-5.png', 'available', 1),
(13, 4, 'Business Card-1', 350.00, 10, 'image_resources/businesscard/businesscard-2.gif', 'available', 1),
(14, 4, 'Business Card-2', 350.00, 10, 'image_resources/businesscard/businesscard-3.gif', 'available', 1),
(15, 4, 'Business Card-3', 350.00, 10, 'image_resources/businesscard/businesscard-4.png', 'available', 1),
(16, 4, 'Business Card-4', 350.00, 10, 'image_resources/businesscard/businesscard-5.webp', 'available', 1),
(17, 5, 'Brochures-1', 15.00, 10, 'image_resources/brochures/brochures-2.png', 'available', 1),
(18, 5, 'Brochures-2', 15.00, 10, 'image_resources/brochures/brochures-3.png', 'available', 1),
(19, 5, 'Brochures-3', 15.00, 10, 'image_resources/brochures/brochures-4.png', 'available', 1),
(20, 5, 'Brochures-4', 15.00, 10, 'image_resources/brochures/brochures-5.png', 'available', 1),
(21, 6, 'Invitations-1', 30.00, 10, 'image_resources/invitation/invite-1.png', 'available', 1),
(22, 6, 'Invitations-2', 30.00, 10, 'image_resources/invitation/invite-2.png', 'available', 1),
(23, 6, 'Invitations-3', 30.00, 10, 'image_resources/invitation/invite-3.png', 'available', 1),
(24, 6, 'Invitations-4', 30.00, 10, 'image_resources/invitation/invite-4.webp', 'available', 1),
(25, 7, 'Magazine Cover-1', 150.00, 10, 'image_resources/magazine/magazine-2.webp', 'available', 1),
(26, 7, 'Magazine Cover-2', 150.00, 10, 'image_resources/magazine/magazine-3.webp', 'available', 1),
(27, 7, 'Magazine Cover-3', 150.00, 10, 'image_resources/magazine/magazine-4.webp', 'available', 1),
(28, 7, 'Magazine Cover-4', 150.00, 10, 'image_resources/magazine/magazine-5.webp', 'available', 1),
(29, 8, 'Resume-1', 90.00, 10, 'image_resources/resume/resume-2.webp', 'available', 1),
(30, 8, 'Resume-2', 90.00, 10, 'image_resources/resume/resume-2.png', 'available', 1),
(31, 8, 'Resume-3', 90.00, 10, 'image_resources/resume/resume-2.avif', 'available', 1),
(32, 8, 'Resume-4', 90.00, 10, 'image_resources/resume/resume-2.png', 'available', 1);

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

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart_items`
--

CREATE TABLE `shopping_cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `added_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `customization_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_upload`
--
ALTER TABLE `file_upload`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

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
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shopping_cart_items`
--
ALTER TABLE `shopping_cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT;

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
