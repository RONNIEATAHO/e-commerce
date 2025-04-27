-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2025 at 04:03 PM
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
-- Database: `order_placement`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 4, '2025-04-21 18:44:24', '2025-04-21 18:44:24');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`) VALUES
(1, 'Appetizers', 'Starters and light bites served before main courses.'),
(2, 'Main Courses', 'Hearty and filling dishes served as main meals.'),
(3, 'Beverages', 'Hot and cold drinks including juices, sodas, and alcohol.'),
(4, 'Desserts', 'Sweet treats including cakes, ice cream, and pastries.'),
(5, 'Breakfast', 'Morning meals including eggs, bread, tea, and coffee.'),
(6, 'Lunch', 'Midday meals offered in the hotel restaurant.'),
(7, 'Dinner', 'Evening meals, typically more formal.'),
(8, 'Snacks', 'Quick bites available throughout the day.'),
(9, 'Room Service', 'Dishes and items delivered to guest rooms.'),
(10, 'Bar', 'Alcoholic beverages and cocktails from the hotel bar.'),
(11, 'Spa Services', 'Wellness treatments like massage, facials, and saunas.'),
(12, 'Laundry', 'Laundry and dry-cleaning services.'),
(13, 'Housekeeping', 'Extra amenities or cleaning services.'),
(14, 'Minibar', 'Items from the in-room minibar.'),
(15, 'Special Offers', 'Discounted combos or seasonal items.');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `subject` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--
-- Error reading structure for table order_placement.login: #1932 - Table 'order_placement.login' doesn't exist in engine
-- Error reading data for table order_placement.login: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `order_placement`.`login`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--
-- Error reading structure for table order_placement.orders: #1932 - Table 'order_placement.orders' doesn't exist in engine
-- Error reading data for table order_placement.orders: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `order_placement`.`orders`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 13, 6, 1, 2.99),
(2, 13, 9, 1, 6.99),
(3, 13, 33, 1, 1.71),
(4, 14, 14, 1, 25.00),
(5, 14, 33, 1, 1.71);

-- --------------------------------------------------------

--
-- Table structure for table `order_processing_metrics`
--
-- Error reading structure for table order_placement.order_processing_metrics: #1932 - Table 'order_placement.order_processing_metrics' doesn't exist in engine
-- Error reading data for table order_placement.order_processing_metrics: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `order_placement`.`order_processing_metrics`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `performance_logs`
--
-- Error reading structure for table order_placement.performance_logs: #1932 - Table 'order_placement.performance_logs' doesn't exist in engine
-- Error reading data for table order_placement.performance_logs: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `order_placement`.`performance_logs`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `description`, `price`, `image_url`, `category`) VALUES
(32, NULL, 'pads', NULL, 1.00, NULL, 'Personal Care'),
(2, 6, 'Whole Grain Bread', 'Freshly baked whole grain bread, perfect for healthy meals.', 4.49, 'https://example.com/images/whole_grain_bread.jpg', ''),
(3, 6, 'Almond Butter', 'Smooth almond butter made from 100% roasted almonds.', 8.99, 'https://example.com/images/almond_butter.jpg', ''),
(4, 6, 'Granola Bars', 'Pack of 12 granola bars with nuts, seeds, and dried fruits.', 5.99, 'https://example.com/images/granola_bars.jpg', ''),
(5, 6, 'Organic Green Tea', 'Pure organic green tea leaves for a refreshing and healthy beverage.', 9.99, 'https://example.com/images/green_tea.jpg', ''),
(6, 6, 'Dark Chocolate', '70% cocoa dark chocolate with a rich, intense flavor.', 2.99, 'https://example.com/images/dark_chocolate.jpg', ''),
(7, 6, 'Greek Yogurt', 'Creamy Greek yogurt, high in protein and probiotics.', 4.49, 'https://example.com/images/greek_yogurt.jpg', ''),
(8, 6, 'Avocado Oil', 'Cold-pressed avocado oil, ideal for cooking and salads.', 10.99, 'https://example.com/images/avocado_oil.jpg', ''),
(9, 6, 'Chia Seeds', 'Nutritious chia seeds, rich in omega-3 fatty acids and fiber.', 6.99, 'https://example.com/images/chia_seeds.jpg', ''),
(10, 6, 'Coconut Water', 'Refreshing coconut water, perfect for hydration.', 1.99, 'https://example.com/images/coconut_water.jpg', ''),
(11, 6, 'WINE', 'High-quality wine', 10.00, 'images/wine.webp', ''),
(12, 6, 'RED WINE', 'Premium red wine', 15.00, 'images/red wine.webp', ''),
(13, 6, 'LIQUOR', 'Fine liquor', 25.00, 'images/liqor.webp', ''),
(14, 6, 'SODA', 'Refreshing soda', 25.00, 'images/soda.jpg', ''),
(15, 6, 'YOGHURT', 'Fresh yoghurt', 25.00, 'images/yoghurt.jpg', ''),
(16, 6, 'CLASSIC MILK', 'Classic milk for every day', 25.00, 'images/CLASSIC MILK.jpg', ''),
(17, 6, 'JUICE', 'Natural fruit juice', 25.00, 'images/JUICE.jpg', ''),
(18, 6, 'ENERGY DRINK', 'Energizing drink', 25.00, 'images/ENERGY DRINK.webp', ''),
(19, 6, 'MINUTE MAID', 'Minute Maid fruit juice', 25.00, 'images/MINUTE MAID.jpg', ''),
(20, 3, 'LOTION', NULL, 10.00, 'images/VASELINE lotion.jpg', ''),
(21, 3, 'VASELINE', NULL, 15.00, 'images/vaseline1.jpg', ''),
(22, 3, 'BODY MIST', NULL, 25.00, 'images/body mist.webp', ''),
(23, 3, 'Product 10', NULL, 25.00, 'images/PERFUME.jpg', ''),
(24, 3, 'TOILET PAPER', NULL, 25.00, 'images/toilet paper.jpg', ''),
(25, 3, 'TOOTH BRUSH', NULL, 25.00, 'images/tooth brush.jpg', ''),
(26, 3, 'TOOTHPASTE', NULL, 25.00, 'images/TOOTHPASTE.jpg', ''),
(27, 3, 'LIQUID SOAP', NULL, 25.00, 'images/LIQUID SOAP.jpg', ''),
(28, 3, 'WASHING SOAP', NULL, 25.00, 'images/WASHING SOAP.jpg', ''),
(29, 3, 'BATHING SOAP', NULL, 25.00, 'images/BATHING SOAP.jpg', ''),
(30, NULL, 'matchbox', NULL, 14.00, NULL, ''),
(31, NULL, 'pads', NULL, 3.00, NULL, ''),
(33, 5, 'morning tea', 'milk', 1.71, 'uploads/morning-tea-pictures.jpg', 'Breakfast'),
(34, 6, 'chicken', 'drum sticks', 7.98, 'uploads/SMOKED-CHICKEN-DRUMSTICKS-MAPLE-SRIRACHA-GLAZE-630x407.jpg', 'Lunch');

-- --------------------------------------------------------

--
-- Table structure for table `system_metrics`
--
-- Error reading structure for table order_placement.system_metrics: #1932 - Table 'order_placement.system_metrics' doesn't exist in engine
-- Error reading data for table order_placement.system_metrics: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `order_placement`.`system_metrics`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT 'default.png',
  `user_type` enum('customer','admin') NOT NULL DEFAULT 'customer'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `created_at`, `profile_picture`, `user_type`) VALUES
(1, 'mucureezi oliviah', 'mucureezioliviah@gmail.com', '123', '2024-10-26 09:29:31', 'yoghurt.jpg', 'customer'),
(13, 'ayebare tricia', 'ayebaretricia@gmail.com', '$2y$10$pj84UXP6QcXVfKK/WIOZFeLfxcX7RP0TFzSmHilUcUKtVaPKxNLvS', '2024-11-06 08:16:04', 'yellow bananas.jpg', 'customer'),
(2, 'kekitinisa proviah', 'kekitinisaproviah@gmail', '$2y$10$EsNMOoNNSfbc6Gj1ZZz8fuQzLxDcVou6zSQHoZnIf9SryawNOkxiu', '2024-10-26 10:06:08', 'CURRY POWDER.jpg', 'customer'),
(3, 'amanya', 'amanya@gmail.com', '$2y$10$UhEtviGfbaVMDjQoweYhh./BBg/8883olPrxLi7F8gZYIGlIGWPoy', '2024-11-04 07:05:27', 'default.png', 'customer'),
(15, 'me', 'me@gmail.com', '$2y$10$5hOYblY/9AneUC/t/ogsVuy7EGrdYGSmpp79heFEe4H7co7xRhGIu', '2024-11-07 07:43:59', 'default.png', 'customer'),
(5, 'oliviah', 'oliviah@gmail.com.com', 'hashed_admin_password', '2024-11-05 10:53:18', 'default.png', 'admin'),
(6, 'gloriah', 'gloriah@gmail.com', 'hashed_password1', '2024-11-05 10:53:18', 'default.png', 'customer'),
(7, 'proviah', 'proviah@gmail.com', 'hashed_password2', '2024-11-05 10:53:18', 'default.png', 'customer'),
(8, 'chris', 'chris.com', 'hashed_password3', '2024-11-05 10:53:18', 'default.png', 'customer'),
(9, 'Bob White', 'bob@gmail.com', 'hashed_password4', '2024-11-05 10:53:18', 'bobprofile.png', 'customer'),
(10, 'Charlie Black', 'charlie@gmail.com', 'hashed_password5', '2024-11-05 10:53:18', 'charlieprofile.png', 'customer'),
(11, 'Super Admin', 'superadmin@gmail.com', '222', '2024-11-05 10:53:18', 'superadminprofile.png', 'admin'),
(12, 'joel', 'joel@gmail.com', '$2y$10$DbUYBpITDs2mVzYqfUcgeOt9P/MRh13rAQle7IdAMQMRiY/Mcm6be', '2024-11-05 11:34:12', 'WhatsApp Image 2024-10-30 at 12.12.35 AM.jpeg', 'customer'),
(16, 'jonathan', 'jonathan@gmail.com', '$2y$10$0xXIZBftimbDQy3/LTxf3.WmekbvMXsdzOuf3M/GPT5STX/dROsTG', '2024-11-08 09:48:25', 'default.png', 'customer'),
(17, 'EricN', 'mabindaeric@gmail.com', '$2y$10$DP1IL8WIYgLm.Z3.e4dj.e6rBj1Xasx6KZRQSjRYeLky8JDcQJCRC', '2024-11-08 17:01:56', 'WIN_20241011_02_57_57_Pro.jpg', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
