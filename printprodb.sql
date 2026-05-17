-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2026 at 07:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customization`
--

INSERT INTO `customization` (`customization_id`, `file_id`, `paper_size`, `gsm`, `paper_texture`, `copies`, `total_price`) VALUES
(1, 1, 'A4 (8.27 x 11.69 in)', '70', 'Cast-coated', 5, 160.00);

-- --------------------------------------------------------

--
-- Table structure for table `file_upload`
--

CREATE TABLE `file_upload` (
  `file_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image1` varchar(255) NOT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `upload_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file_upload`
--

INSERT INTO `file_upload` (`file_id`, `user_id`, `image1`, `image2`, `upload_date`) VALUES
(1, 1, 'uploaded_files/img_front_6a09f9554f7a19.95319110.png', '', '2026-05-18 01:22:29');

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

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `order_status`, `total_amount`, `payment_status`) VALUES
(1, 1, '2026-05-18 01:22:36', 'pending', 160.00, 'unpaid');

-- --------------------------------------------------------

--
-- Table structure for table `order_check_out`
--

CREATE TABLE `order_check_out` (
  `checkout_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` enum('cash','credit card') NOT NULL,
  `delivery_method` varchar(255) NOT NULL,
  `checkout_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_check_out`
--

INSERT INTO `order_check_out` (`checkout_id`, `order_id`, `payment_method`, `delivery_method`, `checkout_date`) VALUES
(1, 1, 'cash', 'Pickup Only', '2026-05-18 01:22:36');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `cart_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_detail_id`, `order_id`, `cart_item_id`, `quantity`, `unit_price`, `subtotal`, `total_price`) VALUES
(3, 1, 1, 5, 32.00, 160.00, 160.00);

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

--
-- Dumping data for table `order_summary`
--

INSERT INTO `order_summary` (`summary_id`, `order_id`, `quantity`, `subtotal`, `discount`, `grand_total`) VALUES
(1, 1, 1, 160.00, 0.00, 160.00);

-- --------------------------------------------------------

--
-- Table structure for table `paper_gsm_inventory`
--

CREATE TABLE `paper_gsm_inventory` (
  `paper_gsm_id` int(11) NOT NULL,
  `paper_gsm` int(11) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paper_gsm_inventory`
--

INSERT INTO `paper_gsm_inventory` (`paper_gsm_id`, `paper_gsm`, `stock_quantity`, `is_available`) VALUES
(1, 70, 95, 1),
(2, 80, 100, 1),
(3, 90, 100, 1),
(4, 100, 100, 1),
(5, 110, 100, 1),
(6, 120, 100, 1),
(7, 150, 100, 1),
(8, 180, 100, 1),
(9, 220, 100, 1),
(10, 250, 100, 1),
(11, 300, 100, 1);

-- --------------------------------------------------------

--
-- Table structure for table `paper_size_inventory`
--

CREATE TABLE `paper_size_inventory` (
  `paper_size_id` int(11) NOT NULL,
  `paper_size` varchar(30) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paper_size_inventory`
--

INSERT INTO `paper_size_inventory` (`paper_size_id`, `paper_size`, `stock_quantity`, `is_available`) VALUES
(1, 'A0 (33.11 x 46.81 in)', 100, 1),
(2, 'A1 (29.7 x 42 in)', 100, 1),
(3, 'A2 (21 x 29.7 in)', 100, 1),
(4, 'A3 (14.8 x 21 in)', 100, 1),
(5, 'A4 (8.27 x 11.69 in)', 95, 1),
(6, 'A5 (5.83 x 8.27 in)', 100, 1),
(7, 'A6 (4.13 x 5.83 in)', 100, 1),
(8, 'A7 (2.83 x 4.13 in)', 100, 1),
(9, 'A8 (2.05 x 2.83 in)', 100, 1),
(10, 'A9 (1.42 x 2.05 in)', 100, 1),
(11, 'A10 (1.02 x 1.42 in)', 100, 1),
(12, 'B0 (39.37 x 55.91 in)', 100, 1),
(13, 'B1 (27.95 x 39.37 in)', 100, 1),
(14, 'B2 (19.69 x 27.95 in)', 100, 1),
(15, 'B3 (13.78 x 19.69 in)', 100, 1),
(16, 'B4 (9.84 x 13.78 in)', 100, 1),
(17, 'B5 (6.89 x 9.84 in)', 100, 1),
(18, 'B6 (4.92 x 6.89 in)', 100, 1),
(19, 'B7 (3.46 x 4.92 in)', 100, 1),
(20, 'B8 (2.44 x 3.46 in)', 100, 1),
(21, 'B9 (1.77 x 2.44 in)', 100, 1),
(22, 'B10 (1.22 x 1.77 in)', 100, 1),
(23, 'Tabloid (11 x 17 in)', 100, 1),
(24, 'Letter (8.5 x 11 in)', 100, 1),
(25, 'Legal (8.5 x 14 in)', 100, 1);

-- --------------------------------------------------------

--
-- Table structure for table `paper_texture_inventory`
--

CREATE TABLE `paper_texture_inventory` (
  `paper_texture_id` int(11) NOT NULL,
  `paper_texture` varchar(20) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paper_texture_inventory`
--

INSERT INTO `paper_texture_inventory` (`paper_texture_id`, `paper_texture`, `stock_quantity`, `is_available`) VALUES
(1, 'Glossy', 100, 1),
(2, 'Matte Finish', 100, 1),
(3, 'Semi-gloss', 100, 1),
(4, 'Cast-coated', 95, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`) VALUES
(1, 1, 'Flyers'),
(2, 2, 'Postcards'),
(3, 3, 'Posters'),
(4, 4, 'Business Cards'),
(5, 5, 'Brochures'),
(6, 6, 'Invitations'),
(7, 7, 'Magazine Covers'),
(8, 8, 'Resumes');

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
(1, 1, '2026-05-18 01:22:29', '2026-05-18 01:22:29', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart_items`
--

CREATE TABLE `shopping_cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customization_id` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `added_at` datetime NOT NULL,
  `is_selected` tinyint(1) NOT NULL DEFAULT 0,
  `cart_status` varchar(20) DEFAULT 'active',
  `paper_size` varchar(100) DEFAULT NULL,
  `paper_gsm` varchar(100) DEFAULT NULL,
  `paper_texture` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shopping_cart_items`
--

INSERT INTO `shopping_cart_items` (`cart_item_id`, `cart_id`, `product_id`, `customization_id`, `unit_price`, `added_at`, `is_selected`, `cart_status`, `paper_size`, `paper_gsm`, `paper_texture`) VALUES
(1, 1, 2, 1, 160.00, '2026-05-18 01:22:29', 1, 'checked_out', 'A4 (8.27 x 11.69 in)', '70', 'Cast-coated');

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
(1, 'John', 'C.', 'Doe', 'johndoe', 'johndoe@outlook.com', '92134567891', '$2y$10$9JszDCf01V3dfjxjNp6AqO/QEGlmI.PdKcN6szIdZ4ZCboQxhzbtS', 'Student'),
(2, 'Juan Dela', 'C.', 'Cruz', 'juan delacruz', 'juandelacruz@yahoo.com', '91234567899', '$2y$10$yONgbC/Nubvu45jnrRkS2.4UCygkocrObkF4M9rmhhfd6k9so02/6', 'Business Owner');

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
  ADD KEY `fk_order_details` (`order_id`),
  ADD KEY `fk_order_items` (`cart_item_id`);

--
-- Indexes for table `order_summary`
--
ALTER TABLE `order_summary`
  ADD PRIMARY KEY (`summary_id`),
  ADD KEY `fk_order_summary` (`order_id`);

--
-- Indexes for table `paper_gsm_inventory`
--
ALTER TABLE `paper_gsm_inventory`
  ADD PRIMARY KEY (`paper_gsm_id`);

--
-- Indexes for table `paper_size_inventory`
--
ALTER TABLE `paper_size_inventory`
  ADD PRIMARY KEY (`paper_size_id`);

--
-- Indexes for table `paper_texture_inventory`
--
ALTER TABLE `paper_texture_inventory`
  ADD PRIMARY KEY (`paper_texture_id`);

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
  ADD KEY `fk_cart_product` (`product_id`),
  ADD KEY `fk_cart_customization` (`customization_id`);

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
  MODIFY `customization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `file_upload`
--
ALTER TABLE `file_upload`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_check_out`
--
ALTER TABLE `order_check_out`
  MODIFY `checkout_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_summary`
--
ALTER TABLE `order_summary`
  MODIFY `summary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `paper_gsm_inventory`
--
ALTER TABLE `paper_gsm_inventory`
  MODIFY `paper_gsm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `paper_size_inventory`
--
ALTER TABLE `paper_size_inventory`
  MODIFY `paper_size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `paper_texture_inventory`
--
ALTER TABLE `paper_texture_inventory`
  MODIFY `paper_texture_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- AUTO_INCREMENT for table `report_and_analysis`
--
ALTER TABLE `report_and_analysis`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shopping_cart_items`
--
ALTER TABLE `shopping_cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `fk_order_details` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `fk_order_items` FOREIGN KEY (`cart_item_id`) REFERENCES `shopping_cart_items` (`cart_item_id`);

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
  ADD CONSTRAINT `fk_cart_customization` FOREIGN KEY (`customization_id`) REFERENCES `customization` (`customization_id`),
  ADD CONSTRAINT `fk_cart_item` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart` (`cart_id`),
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
