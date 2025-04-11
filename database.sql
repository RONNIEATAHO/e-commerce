-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: e-commerce
-- ------------------------------------------------------
-- Server version	8.2.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `cart_item_id` int NOT NULL AUTO_INCREMENT,
  `cart_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_item_id`),
  KEY `cart_id` (`cart_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `message_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `subject` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Pending','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `total_amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,'2024-11-05 14:05:59','Pending',9.99),(2,1,'2024-11-06 06:53:19','Pending',2.99),(3,1,'2024-11-06 07:25:06','Pending',10.00),(4,1,'2024-11-06 15:34:19','Pending',25.00),(5,1,'2024-11-06 15:34:43','Pending',25.00),(6,1,'2024-11-06 15:34:55','Pending',15.00),(7,1,'2024-11-06 15:45:21','Pending',10.00),(8,1,'2024-11-07 08:12:57','Pending',25.00),(9,1,'2024-11-08 09:47:06','Pending',25.00),(10,1,'2024-11-08 16:56:18','Pending',5.99);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  PRIMARY KEY (`product_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (32,NULL,'pads',NULL,1.00,NULL,'Personal Care'),(2,6,'Whole Grain Bread','Freshly baked whole grain bread, perfect for healthy meals.',4.49,'https://example.com/images/whole_grain_bread.jpg',''),(3,6,'Almond Butter','Smooth almond butter made from 100% roasted almonds.',8.99,'https://example.com/images/almond_butter.jpg',''),(4,6,'Granola Bars','Pack of 12 granola bars with nuts, seeds, and dried fruits.',5.99,'https://example.com/images/granola_bars.jpg',''),(5,6,'Organic Green Tea','Pure organic green tea leaves for a refreshing and healthy beverage.',9.99,'https://example.com/images/green_tea.jpg',''),(6,6,'Dark Chocolate','70% cocoa dark chocolate with a rich, intense flavor.',2.99,'https://example.com/images/dark_chocolate.jpg',''),(7,6,'Greek Yogurt','Creamy Greek yogurt, high in protein and probiotics.',4.49,'https://example.com/images/greek_yogurt.jpg',''),(8,6,'Avocado Oil','Cold-pressed avocado oil, ideal for cooking and salads.',10.99,'https://example.com/images/avocado_oil.jpg',''),(9,6,'Chia Seeds','Nutritious chia seeds, rich in omega-3 fatty acids and fiber.',6.99,'https://example.com/images/chia_seeds.jpg',''),(10,6,'Coconut Water','Refreshing coconut water, perfect for hydration.',1.99,'https://example.com/images/coconut_water.jpg',''),(11,6,'WINE','High-quality wine',10.00,'images/wine.webp',''),(12,6,'RED WINE','Premium red wine',15.00,'images/red wine.webp',''),(13,6,'LIQUOR','Fine liquor',25.00,'images/liqor.webp',''),(14,6,'SODA','Refreshing soda',25.00,'images/soda.jpg',''),(15,6,'YOGHURT','Fresh yoghurt',25.00,'images/yoghurt.jpg',''),(16,6,'CLASSIC MILK','Classic milk for every day',25.00,'images/CLASSIC MILK.jpg',''),(17,6,'JUICE','Natural fruit juice',25.00,'images/JUICE.jpg',''),(18,6,'ENERGY DRINK','Energizing drink',25.00,'images/ENERGY DRINK.webp',''),(19,6,'MINUTE MAID','Minute Maid fruit juice',25.00,'images/MINUTE MAID.jpg',''),(20,3,'LOTION',NULL,10.00,'images/VASELINE lotion.jpg',''),(21,3,'VASELINE',NULL,15.00,'images/vaseline1.jpg',''),(22,3,'BODY MIST',NULL,25.00,'images/body mist.webp',''),(23,3,'Product 10',NULL,25.00,'images/PERFUME.jpg',''),(24,3,'TOILET PAPER',NULL,25.00,'images/toilet paper.jpg',''),(25,3,'TOOTH BRUSH',NULL,25.00,'images/tooth brush.jpg',''),(26,3,'TOOTHPASTE',NULL,25.00,'images/TOOTHPASTE.jpg',''),(27,3,'LIQUID SOAP',NULL,25.00,'images/LIQUID SOAP.jpg',''),(28,3,'WASHING SOAP',NULL,25.00,'images/WASHING SOAP.jpg',''),(29,3,'BATHING SOAP',NULL,25.00,'images/BATHING SOAP.jpg',''),(30,NULL,'matchbox',NULL,14.00,NULL,''),(31,NULL,'pads',NULL,3.00,NULL,'');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_picture` varchar(255) DEFAULT 'default.png',
  `user_type` enum('customer','admin') NOT NULL DEFAULT 'customer',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'mucureezi oliviah','mucureezioliviah@gmail.com','123','2024-10-26 09:29:31','yoghurt.jpg','customer'),(13,'ayebare tricia','ayebaretricia@gmail.com','$2y$10$pj84UXP6QcXVfKK/WIOZFeLfxcX7RP0TFzSmHilUcUKtVaPKxNLvS','2024-11-06 08:16:04','yellow bananas.jpg','customer'),(2,'kekitinisa proviah','kekitinisaproviah@gmail','$2y$10$EsNMOoNNSfbc6Gj1ZZz8fuQzLxDcVou6zSQHoZnIf9SryawNOkxiu','2024-10-26 10:06:08','CURRY POWDER.jpg','customer'),(3,'amanya','amanya@gmail.com','$2y$10$UhEtviGfbaVMDjQoweYhh./BBg/8883olPrxLi7F8gZYIGlIGWPoy','2024-11-04 07:05:27','default.png','customer'),(15,'me','me@gmail.com','$2y$10$5hOYblY/9AneUC/t/ogsVuy7EGrdYGSmpp79heFEe4H7co7xRhGIu','2024-11-07 07:43:59','default.png','customer'),(5,'oliviah','oliviah@gmail.com.com','hashed_admin_password','2024-11-05 10:53:18','default.png','admin'),(6,'gloriah','gloriah@gmail.com','hashed_password1','2024-11-05 10:53:18','default.png','customer'),(7,'proviah','proviah@gmail.com','hashed_password2','2024-11-05 10:53:18','default.png','customer'),(8,'chris','chris.com','hashed_password3','2024-11-05 10:53:18','default.png','customer'),(9,'Bob White','bob@gmail.com','hashed_password4','2024-11-05 10:53:18','bobprofile.png','customer'),(10,'Charlie Black','charlie@gmail.com','hashed_password5','2024-11-05 10:53:18','charlieprofile.png','customer'),(11,'Super Admin','superadmin@gmail.com','222','2024-11-05 10:53:18','superadminprofile.png','admin'),(12,'joel','joel@gmail.com','$2y$10$DbUYBpITDs2mVzYqfUcgeOt9P/MRh13rAQle7IdAMQMRiY/Mcm6be','2024-11-05 11:34:12','WhatsApp Image 2024-10-30 at 12.12.35 AM.jpeg','customer'),(16,'jonathan','jonathan@gmail.com','$2y$10$0xXIZBftimbDQy3/LTxf3.WmekbvMXsdzOuf3M/GPT5STX/dROsTG','2024-11-08 09:48:25','default.png','customer'),(17,'EricN','mabindaeric@gmail.com','$2y$10$DP1IL8WIYgLm.Z3.e4dj.e6rBj1Xasx6KZRQSjRYeLky8JDcQJCRC','2024-11-08 17:01:56','WIN_20241011_02_57_57_Pro.jpg','customer');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-09  0:06:04
