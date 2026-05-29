-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: cars_rental
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

CREATE DATABASE IF NOT EXISTS `cars_rental` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cars_rental`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) unsigned DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `activity_logs_model_type_model_id_index` (`model_type`,`model_id`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,4,'user_login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 20:57:06','2026-05-13 20:57:06'),(2,4,'reservation_created','App\\Models\\Reservation',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 20:59:23','2026-05-13 20:59:23'),(3,4,'mock_payment_completed','App\\Models\\Payment',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 20:59:25','2026-05-13 20:59:25'),(4,4,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:13:01','2026-05-13 21:13:01'),(5,4,'user_login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:15:55','2026-05-13 21:15:55'),(6,4,'reservation_created','App\\Models\\Reservation',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:16:51','2026-05-13 21:16:51'),(7,4,'mock_payment_completed','App\\Models\\Payment',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:17:07','2026-05-13 21:17:07'),(8,4,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:17:56','2026-05-13 21:17:56'),(9,3,'user_login','App\\Models\\User',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:18:01','2026-05-13 21:18:01'),(10,3,'vehicle_updated','App\\Models\\Vehicle',1,'{\"id\":1,\"brand\":\"Toyota\",\"model\":\"Camry\",\"category\":\"sedan\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"65.00\",\"description\":\"Comfortable midsize sedan, perfect for business travel.\",\"is_available\":true,\"year\":2023,\"seats\":5,\"color\":\"Silver\",\"license_plate\":\"ABC-1001\",\"image\":null,\"created_by\":2,\"deleted_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T21:54:04.000000Z\",\"creator\":{\"id\":2,\"name\":\"Admin User\",\"email\":\"admin@carsrental.com\",\"phone\":\"+1234567891\",\"address\":null,\"driver_license\":null,\"avatar\":null,\"is_active\":true,\"email_verified_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T21:54:04.000000Z\",\"deleted_at\":null}}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:18:51','2026-05-13 21:18:51'),(11,3,'vehicle_updated','App\\Models\\Vehicle',1,'{\"id\":1,\"brand\":\"Toyota\",\"model\":\"Camry\",\"category\":\"sedan\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"65.00\",\"description\":\"Comfortable midsize sedan, perfect for business travel.\",\"is_available\":true,\"year\":2023,\"seats\":5,\"color\":\"Silver\",\"license_plate\":\"ABC-1001\",\"image\":null,\"created_by\":2,\"deleted_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T21:54:04.000000Z\",\"creator\":{\"id\":2,\"name\":\"Admin User\",\"email\":\"admin@carsrental.com\",\"phone\":\"+1234567891\",\"address\":null,\"driver_license\":null,\"avatar\":null,\"is_active\":true,\"email_verified_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T21:54:04.000000Z\",\"deleted_at\":null}}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:19:11','2026-05-13 21:19:11'),(12,3,'vehicle_updated','App\\Models\\Vehicle',1,'{\"id\":1,\"brand\":\"Toyota\",\"model\":\"Camry\",\"category\":\"sedan\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"65.00\",\"description\":\"Comfortable midsize sedan, perfect for business travel.\",\"is_available\":true,\"year\":2023,\"seats\":5,\"color\":\"Silver\",\"license_plate\":\"ABC-1001\",\"image\":null,\"created_by\":2,\"deleted_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T21:54:04.000000Z\",\"creator\":{\"id\":2,\"name\":\"Admin User\",\"email\":\"admin@carsrental.com\",\"phone\":\"+1234567891\",\"address\":null,\"driver_license\":null,\"avatar\":null,\"is_active\":true,\"email_verified_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T21:54:04.000000Z\",\"deleted_at\":null}}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:19:32','2026-05-13 21:19:32'),(13,3,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:21:43','2026-05-13 21:21:43'),(14,4,'user_login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:21:48','2026-05-13 21:21:48'),(15,4,'reservation_created','App\\Models\\Reservation',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:22:25','2026-05-13 21:22:25'),(16,4,'mock_payment_completed','App\\Models\\Payment',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:22:46','2026-05-13 21:22:46'),(17,4,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:25:21','2026-05-13 21:25:21'),(18,2,'user_login','App\\Models\\User',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:25:24','2026-05-13 21:25:24'),(19,2,'vehicle_updated','App\\Models\\Vehicle',1,'{\"id\":1,\"brand\":\"Toyota\",\"model\":\"Camry\",\"category\":\"sedan\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"65.00\",\"description\":\"Comfortable midsize sedan, perfect for business travel.\",\"is_available\":true,\"year\":2023,\"seats\":5,\"color\":\"Silver\",\"license_plate\":\"ABC-1001\",\"image\":null,\"created_by\":2,\"deleted_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T21:54:04.000000Z\",\"creator\":{\"id\":2,\"name\":\"Admin User\",\"email\":\"admin@carsrental.com\",\"phone\":\"+1234567891\",\"address\":null,\"driver_license\":null,\"avatar\":null,\"is_active\":true,\"email_verified_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T21:54:04.000000Z\",\"deleted_at\":null}}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:26:04','2026-05-13 21:26:04'),(20,2,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:27:45','2026-05-13 21:27:45'),(21,1,'user_login','App\\Models\\User',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:27:47','2026-05-13 21:27:47'),(22,1,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:29:26','2026-05-13 21:29:26'),(23,3,'user_login','App\\Models\\User',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:29:38','2026-05-13 21:29:38'),(24,3,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:31:20','2026-05-13 21:31:20'),(25,1,'user_login','App\\Models\\User',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:31:25','2026-05-13 21:31:25'),(26,1,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:35:03','2026-05-13 21:35:03'),(27,3,'user_login','App\\Models\\User',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:35:05','2026-05-13 21:35:05'),(28,3,'vehicle_updated','App\\Models\\Vehicle',1,'{\"id\":1,\"brand\":\"Toyota\",\"model\":\"Camry\",\"category\":\"sedan\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"65.00\",\"description\":\"Comfortable midsize sedan, perfect for business travel.\",\"is_available\":true,\"year\":2023,\"seats\":5,\"color\":\"Silver\",\"license_plate\":\"ABC-1001\",\"image\":null,\"created_by\":2,\"deleted_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T21:54:04.000000Z\",\"creator\":{\"id\":2,\"name\":\"Admin User\",\"email\":\"admin@carsrental.com\",\"phone\":\"+1234567891\",\"address\":null,\"driver_license\":null,\"avatar\":null,\"is_active\":true,\"email_verified_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T22:27:54.000000Z\",\"deleted_at\":null}}','{\"brand\":\"Toyota\",\"model\":\"Camry\",\"category\":\"sedan\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"75\",\"description\":\"Comfortable midsize sedan, perfect for business travel.\",\"is_available\":\"1\",\"year\":\"2023\",\"seats\":\"5\",\"color\":\"Silver\",\"license_plate\":\"ABC-1001\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:35:19','2026-05-13 21:35:19'),(29,3,'vehicle_updated','App\\Models\\Vehicle',1,'{\"id\":1,\"brand\":\"Toyota\",\"model\":\"Camry\",\"category\":\"sedan\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"75.00\",\"description\":\"Comfortable midsize sedan, perfect for business travel.\",\"is_available\":true,\"year\":2023,\"seats\":5,\"color\":\"Silver\",\"license_plate\":\"ABC-1001\",\"image\":null,\"created_by\":2,\"deleted_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T22:35:19.000000Z\",\"creator\":{\"id\":2,\"name\":\"Admin User\",\"email\":\"admin@carsrental.com\",\"phone\":\"+1234567891\",\"address\":null,\"driver_license\":null,\"avatar\":null,\"is_active\":true,\"email_verified_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T22:27:54.000000Z\",\"deleted_at\":null}}','{\"brand\":\"Toyota\",\"model\":\"Camry\",\"category\":\"sedan\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"75\",\"description\":\"Comfortable midsize sedan, perfect for business travel.\",\"is_available\":\"0\",\"year\":\"2023\",\"seats\":\"5\",\"color\":\"Silver\",\"license_plate\":\"ABC-1001\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:35:29','2026-05-13 21:35:29'),(30,3,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:35:56','2026-05-13 21:35:56'),(31,4,'user_login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:36:00','2026-05-13 21:36:00'),(32,4,'reservation_created','App\\Models\\Reservation',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:40:33','2026-05-13 21:40:33'),(33,4,'mock_payment_completed','App\\Models\\Payment',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:41:06','2026-05-13 21:41:06'),(34,4,'reservation_created','App\\Models\\Reservation',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:41:47','2026-05-13 21:41:47'),(35,4,'reservation_cancelled','App\\Models\\Reservation',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 21:42:01','2026-05-13 21:42:01'),(36,4,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:04:26','2026-05-13 22:04:26'),(37,2,'user_login','App\\Models\\User',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:04:31','2026-05-13 22:04:31'),(38,2,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:11:23','2026-05-13 22:11:23'),(39,4,'user_login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:11:32','2026-05-13 22:11:32'),(40,4,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:12:33','2026-05-13 22:12:33'),(41,1,'user_login','App\\Models\\User',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:12:35','2026-05-13 22:12:35'),(42,1,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:13:59','2026-05-13 22:13:59'),(43,4,'user_login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:28:38','2026-05-13 22:28:38'),(44,4,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:29:20','2026-05-13 22:29:20'),(45,3,'user_login','App\\Models\\User',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:29:22','2026-05-13 22:29:22'),(46,3,'vehicle_updated','App\\Models\\Vehicle',2,'{\"id\":2,\"brand\":\"Ford\",\"model\":\"Explorer\",\"category\":\"suv\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"95.00\",\"description\":\"Spacious SUV ideal for family road trips.\",\"is_available\":true,\"year\":2023,\"seats\":7,\"color\":\"White\",\"license_plate\":\"ABC-1002\",\"image\":null,\"created_by\":2,\"deleted_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T21:54:04.000000Z\",\"creator\":{\"id\":2,\"name\":\"Admin User\",\"email\":\"admin@carsrental.com\",\"phone\":\"+1234567891\",\"address\":null,\"driver_license\":null,\"avatar\":null,\"is_active\":true,\"email_verified_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T22:27:54.000000Z\",\"deleted_at\":null}}','{\"brand\":\"Ford\",\"model\":\"Explorer\",\"category\":\"suv\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"95\",\"description\":\"Spacious SUV ideal for family road trips.\",\"is_available\":\"0\",\"year\":\"2023\",\"seats\":\"7\",\"color\":\"White\",\"license_plate\":\"ABC-1002\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:29:35','2026-05-13 22:29:35'),(47,3,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:29:41','2026-05-13 22:29:41'),(48,4,'user_login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:29:43','2026-05-13 22:29:43'),(49,4,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:29:55','2026-05-13 22:29:55'),(50,2,'user_login','App\\Models\\User',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:29:59','2026-05-13 22:29:59'),(51,2,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:30:34','2026-05-13 22:30:34'),(52,2,'user_login','App\\Models\\User',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:43:30','2026-05-13 22:43:30'),(53,2,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:46:29','2026-05-13 22:46:29'),(54,4,'user_login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:50:29','2026-05-13 22:50:29'),(55,4,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 22:52:08','2026-05-13 22:52:08'),(56,2,'user_login','App\\Models\\User',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 23:16:31','2026-05-13 23:16:31'),(57,2,'vehicle_updated','App\\Models\\Vehicle',1,'{\"id\":1,\"brand\":\"Toyota\",\"model\":\"Camry\",\"category\":\"sedan\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"75.00\",\"description\":\"Comfortable midsize sedan, perfect for business travel.\",\"is_available\":false,\"year\":2023,\"seats\":5,\"color\":\"Silver\",\"license_plate\":\"ABC-1001\",\"image\":null,\"created_by\":2,\"deleted_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T22:35:29.000000Z\",\"creator\":{\"id\":2,\"name\":\"Admin User\",\"email\":\"admin@carsrental.com\",\"phone\":\"+1234567891\",\"address\":null,\"driver_license\":null,\"avatar\":null,\"is_active\":true,\"email_verified_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T22:27:54.000000Z\",\"deleted_at\":null}}','{\"brand\":\"Toyota\",\"model\":\"Camry\",\"category\":\"sedan\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"75\",\"description\":\"Comfortable midsize sedan, perfect for business travel.\",\"is_available\":\"1\",\"year\":\"2023\",\"seats\":\"5\",\"color\":\"Silver\",\"license_plate\":\"ABC-1001\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 23:22:41','2026-05-13 23:22:41'),(58,2,'vehicle_updated','App\\Models\\Vehicle',2,'{\"id\":2,\"brand\":\"Ford\",\"model\":\"Explorer\",\"category\":\"suv\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"95.00\",\"description\":\"Spacious SUV ideal for family road trips.\",\"is_available\":false,\"year\":2023,\"seats\":7,\"color\":\"White\",\"license_plate\":\"ABC-1002\",\"image\":null,\"created_by\":2,\"deleted_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T23:29:35.000000Z\",\"creator\":{\"id\":2,\"name\":\"Admin User\",\"email\":\"admin@carsrental.com\",\"phone\":\"+1234567891\",\"address\":null,\"driver_license\":null,\"avatar\":null,\"is_active\":true,\"email_verified_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T22:27:54.000000Z\",\"deleted_at\":null}}','{\"brand\":\"Ford\",\"model\":\"Explorer\",\"category\":\"suv\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"95\",\"description\":\"Spacious SUV ideal for family road trips.\",\"is_available\":\"1\",\"year\":\"2023\",\"seats\":\"7\",\"color\":\"White\",\"license_plate\":\"ABC-1002\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 23:22:47','2026-05-13 23:22:47'),(59,2,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 23:23:02','2026-05-13 23:23:02'),(60,4,'user_login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 23:23:08','2026-05-13 23:23:08'),(61,4,'reservation_created','App\\Models\\Reservation',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-13 23:26:32','2026-05-13 23:26:32'),(62,2,'user_login','App\\Models\\User',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-14 00:29:31','2026-05-14 00:29:31'),(63,2,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-14 00:40:07','2026-05-14 00:40:07'),(64,4,'user_login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-14 00:49:27','2026-05-14 00:49:27'),(65,4,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-14 00:59:57','2026-05-14 00:59:57'),(66,1,'user_login','App\\Models\\User',1,NULL,NULL,'127.0.0.1','curl/8.19.0','2026-05-16 13:35:06','2026-05-16 13:35:06'),(67,4,'user_login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-16 13:35:10','2026-05-16 13:35:10'),(68,4,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-16 13:35:15','2026-05-16 13:35:15'),(69,4,'user_login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-16 13:35:51','2026-05-16 13:35:51'),(70,2,'user_login','App\\Models\\User',2,NULL,NULL,'127.0.0.1','curl/8.19.0','2026-05-16 13:36:18','2026-05-16 13:36:18'),(71,3,'user_login','App\\Models\\User',3,NULL,NULL,'127.0.0.1','curl/8.19.0','2026-05-16 13:36:20','2026-05-16 13:36:20'),(72,4,'user_login','App\\Models\\User',4,NULL,NULL,'127.0.0.1','curl/8.19.0','2026-05-16 13:36:24','2026-05-16 13:36:24'),(73,4,'reservation_created','App\\Models\\Reservation',7,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-16 13:37:02','2026-05-16 13:37:02'),(74,4,'reservation_cancelled','App\\Models\\Reservation',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-16 13:37:16','2026-05-16 13:37:16'),(75,1,'user_login','App\\Models\\User',1,NULL,NULL,'127.0.0.1','axios/1.16.1','2026-05-16 13:39:41','2026-05-16 13:39:41'),(76,4,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-16 13:40:09','2026-05-16 13:40:09'),(77,3,'user_login','App\\Models\\User',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-16 13:40:14','2026-05-16 13:40:14'),(78,3,'vehicle_updated','App\\Models\\Vehicle',1,'{\"id\":1,\"brand\":\"Toyota\",\"model\":\"Camry\",\"category\":\"sedan\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"75.00\",\"description\":\"Comfortable midsize sedan, perfect for business travel.\",\"is_available\":true,\"year\":2023,\"seats\":5,\"color\":\"Silver\",\"license_plate\":\"ABC-1001\",\"image\":null,\"created_by\":2,\"deleted_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-14T00:22:41.000000Z\",\"creator\":{\"id\":2,\"name\":\"Admin User\",\"email\":\"admin@carsrental.com\",\"phone\":\"+1234567891\",\"address\":null,\"driver_license\":null,\"avatar\":null,\"is_active\":true,\"email_verified_at\":null,\"created_at\":\"2026-05-13T21:54:04.000000Z\",\"updated_at\":\"2026-05-13T22:27:54.000000Z\",\"deleted_at\":null}}','{\"brand\":\"Toyota\",\"model\":\"Camry\",\"category\":\"sedan\",\"transmission\":\"automatic\",\"fuel_type\":\"petrol\",\"price_per_day\":\"75\",\"description\":\"Comfortable midsize sedan, perfect for business travel.\",\"is_available\":\"0\",\"year\":\"2023\",\"seats\":\"5\",\"color\":\"Silver\",\"license_plate\":\"ABC-1001\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-16 13:41:04','2026-05-16 13:41:04'),(79,1,'user_login','App\\Models\\User',1,NULL,NULL,'127.0.0.1','curl/8.19.0','2026-05-16 13:41:14','2026-05-16 13:41:14'),(80,1,'user_login','App\\Models\\User',1,NULL,NULL,'127.0.0.1','axios/1.16.1','2026-05-16 13:41:28','2026-05-16 13:41:28'),(81,3,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-16 13:41:55','2026-05-16 13:41:55'),(82,2,'user_login','App\\Models\\User',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-16 13:42:00','2026-05-16 13:42:00'),(83,2,'user_logout',NULL,NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-16 13:43:22','2026-05-16 13:43:22'),(84,1,'user_login','App\\Models\\User',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-16 13:43:28','2026-05-16 13:43:28');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('carsrental-cache-0y7i4GKbBlXHHKBN','s:7:\"forever\";',2094071703),('carsrental-cache-1TgNk2MOIcsr17Qm','s:7:\"forever\";',2094071366),('carsrental-cache-3r8Zm66Dgd03g63f','s:7:\"forever\";',2094071757),('carsrental-cache-AeWkVdNmwyPtfjjQ','s:7:\"forever\";',2094302115),('carsrental-cache-as2cHxrTw67sC6D9','s:7:\"forever\";',2094075034),('carsrental-cache-DEi40sBXKrYKp2KE','s:7:\"forever\";',2094070676),('carsrental-cache-E64rOagPN7Ey4WeG','s:7:\"forever\";',2094302602),('carsrental-cache-f0YQrLj6JiVMEkWC','s:7:\"forever\";',2094075989),('carsrental-cache-iEdwoM3Xc7C4AaK6','s:7:\"forever\";',2094082807),('carsrental-cache-KbeYXpHY28ueHWO1','s:7:\"forever\";',2094073953),('carsrental-cache-NryN7JEs9E1utrnZ','s:7:\"forever\";',2094083997),('carsrental-cache-ONR9MglRc65SH23m','s:7:\"forever\";',2094071480),('carsrental-cache-OszVy8Dd4DxAWdH8','s:7:\"forever\";',2094078182),('carsrental-cache-QfzxNvZHLhWC00T5','s:7:\"forever\";',2094071121),('carsrental-cache-RL2o8yaWYS33VoAN','s:7:\"forever\";',2094074981),('carsrental-cache-RuaEduOVVpieD3gG','s:7:\"forever\";',2094070381),('carsrental-cache-tBnbzPJWtk72Zx6q','s:7:\"forever\";',2094074960),('carsrental-cache-THGuCDtdnH2aBGZz','s:7:\"forever\";',2094070903),('carsrental-cache-Tw0uPgyPOxzTUS10','s:7:\"forever\";',2094071265),('carsrental-cache-uZ9efZTh9L59oIc2','s:7:\"forever\";',2094076328),('carsrental-cache-w9v0xRiulvwxNgfc','s:7:\"forever\";',2094073883),('carsrental-cache-wIelrCZ5sBsa7Kho','s:7:\"forever\";',2094074995),('carsrental-cache-wMpDS76HaGabqaMj','s:7:\"forever\";',2094073466),('carsrental-cache-Xb2Q8ARMGP4721OO','s:7:\"forever\";',2094074039),('carsrental-cache-xuEwHcITMmcsnwAC','s:7:\"forever\";',2094302409),('carsrental-cache-xUkaanZdTqglHVF0','s:7:\"forever\";',2094302515);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_05_13_204932_create_personal_access_tokens_table',1),(5,'2026_05_13_204933_create_permission_tables',1),(6,'2026_05_13_210001_update_users_table',1),(7,'2026_05_13_210002_create_vehicles_table',1),(8,'2026_05_13_210003_create_reservations_table',1),(9,'2026_05_13_210004_create_payments_table',1),(10,'2026_05_13_210005_create_activity_logs_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(2,'App\\Models\\User',2),(3,'App\\Models\\User',3),(3,'App\\Models\\User',5),(4,'App\\Models\\User',4);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reservation_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` enum('paypal','mock') NOT NULL DEFAULT 'mock',
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `paypal_order_id` varchar(255) DEFAULT NULL,
  `paypal_payer_id` varchar(255) DEFAULT NULL,
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  KEY `payments_user_id_foreign` (`user_id`),
  KEY `payments_reservation_id_status_index` (`reservation_id`,`status`),
  KEY `payments_transaction_id_index` (`transaction_id`),
  CONSTRAINT `payments_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,4,450.00,'mock','completed','MOCK-WVFHNTWRFYPM1WN9',NULL,NULL,'{\"mock\":true,\"auto_approved\":true}','2026-05-13 20:59:25','2026-05-13 20:59:25','2026-05-13 20:59:25'),(2,2,4,480.00,'mock','completed','MOCK-40BKSP5CI6GYBGW9',NULL,NULL,'{\"mock\":true,\"auto_approved\":true}','2026-05-13 21:17:07','2026-05-13 21:17:07','2026-05-13 21:17:07'),(3,3,4,95.00,'mock','completed','MOCK-9DY6EM781AL5SSUN',NULL,NULL,'{\"mock\":true,\"auto_approved\":true}','2026-05-13 21:22:46','2026-05-13 21:22:46','2026-05-13 21:22:46'),(4,4,4,180.00,'mock','completed','MOCK-PCTH7JMJD6UB85EQ',NULL,NULL,'{\"mock\":true,\"auto_approved\":true}','2026-05-13 21:41:06','2026-05-13 21:41:06','2026-05-13 21:41:06');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'manage-users','api','2026-05-13 20:54:03','2026-05-13 20:54:03'),(2,'manage-vehicles','api','2026-05-13 20:54:03','2026-05-13 20:54:03'),(3,'manage-reservations','api','2026-05-13 20:54:03','2026-05-13 20:54:03'),(4,'view-dashboard','api','2026-05-13 20:54:03','2026-05-13 20:54:03'),(5,'make-reservations','api','2026-05-13 20:54:03','2026-05-13 20:54:03'),(6,'manage-payments','api','2026-05-13 20:54:03','2026-05-13 20:54:03'),(7,'view-reports','api','2026-05-13 20:54:03','2026-05-13 20:54:03');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `vehicle_id` bigint(20) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','paid') NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','refunded') NOT NULL DEFAULT 'unpaid',
  `pickup_location` varchar(255) DEFAULT NULL,
  `return_location` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `confirmed_by` bigint(20) unsigned DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservations_confirmed_by_foreign` (`confirmed_by`),
  KEY `reservations_user_id_status_index` (`user_id`,`status`),
  KEY `reservations_vehicle_id_start_date_end_date_index` (`vehicle_id`,`start_date`,`end_date`),
  KEY `reservations_status_index` (`status`),
  CONSTRAINT `reservations_confirmed_by_foreign` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservations_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
INSERT INTO `reservations` VALUES (1,4,6,'2026-05-18','2026-05-28',10,450.00,'paid','paid',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-05-13 20:59:23','2026-05-13 20:59:25'),(2,4,3,'2026-05-13','2026-05-17',4,480.00,'paid','paid',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-05-13 21:16:51','2026-05-13 21:17:07'),(3,4,2,'2026-05-14','2026-05-15',1,95.00,'paid','paid',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-05-13 21:22:25','2026-05-13 21:22:46'),(4,4,4,'2026-05-13','2026-05-14',1,180.00,'paid','paid',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-05-13 21:40:33','2026-05-13 21:41:06'),(5,4,2,'2026-05-16','2026-05-17',1,95.00,'cancelled','unpaid',NULL,NULL,NULL,NULL,NULL,'2026-05-13 21:42:01','Client requested cancellation',NULL,'2026-05-13 21:41:47','2026-05-13 21:42:01'),(6,4,7,'2026-05-14','2026-05-15',1,130.00,'cancelled','unpaid',NULL,NULL,NULL,NULL,NULL,'2026-05-16 13:37:16','Client requested cancellation',NULL,'2026-05-13 23:26:32','2026-05-16 13:37:16'),(7,4,1,'2026-05-28','2026-05-31',3,225.00,'pending','unpaid',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-05-16 13:37:02','2026-05-16 13:37:02');
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(1,2),(2,1),(2,2),(2,3),(3,1),(3,2),(3,3),(4,1),(4,2),(4,3),(4,4),(5,1),(5,4),(6,1),(6,2),(7,1),(7,2),(7,3);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'super-admin','api','2026-05-13 20:54:03','2026-05-13 20:54:03'),(2,'admin','api','2026-05-13 20:54:03','2026-05-13 20:54:03'),(3,'manager','api','2026-05-13 20:54:03','2026-05-13 20:54:03'),(4,'client','api','2026-05-13 20:54:03','2026-05-13 20:54:03');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `driver_license` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','superadmin@carsrental.com','+1234567890',NULL,NULL,NULL,1,NULL,'$2y$12$zkyj0dEeCXs/hE1f9Yza/ub.1S.ZfH4gTn2eMi0hdeLs2LetRaU5W',NULL,'2026-05-13 20:54:03','2026-05-13 20:54:03',NULL),(2,'Admin User','admin@carsrental.com','+1234567891',NULL,NULL,NULL,1,NULL,'$2y$12$Gd5F8aHms1LjRLLylLPIn.pF.LEpBn9YFVhr84dZHuA4iU2dVoZF.',NULL,'2026-05-13 20:54:04','2026-05-13 21:27:54',NULL),(3,'Fleet Manager','manager@carsrental.com','+1234567892',NULL,NULL,NULL,1,NULL,'$2y$12$wercKOl.sgPr.9igvsJ99.EaEtHcadVVhZRAwZyu9bb1KRMOq1H8e',NULL,'2026-05-13 20:54:04','2026-05-13 20:54:04',NULL),(4,'John Client','client@carsrental.com','+1234567893','123 Main St, New York, NY',NULL,NULL,1,NULL,'$2y$12$sgMxprYpfC3WP/gV6kzsIeQZPHwAM3NzeMzA8hGdkNrnHG7zoKGci',NULL,'2026-05-13 20:54:04','2026-05-13 20:54:04',NULL),(5,'Anas','Anas@gmail.com','0762762887',NULL,NULL,NULL,0,NULL,'$2y$12$PVwBkI9PmiVDBXgqD3KIf.AcBMVrWNSMQHkDnCvp5ynWYalAdJclO',NULL,'2026-05-13 21:28:10','2026-05-16 13:43:09',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brand` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `transmission` enum('automatic','manual') NOT NULL,
  `fuel_type` enum('petrol','diesel','electric','hybrid') NOT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `year` int(11) DEFAULT NULL,
  `seats` int(11) NOT NULL DEFAULT 5,
  `color` varchar(255) DEFAULT NULL,
  `license_plate` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicles_license_plate_unique` (`license_plate`),
  KEY `vehicles_created_by_foreign` (`created_by`),
  KEY `vehicles_is_available_category_index` (`is_available`,`category`),
  KEY `vehicles_brand_index` (`brand`),
  CONSTRAINT `vehicles_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
INSERT INTO `vehicles` VALUES (1,'Toyota','Camry','sedan','automatic','petrol',75.00,'Comfortable midsize sedan, perfect for business travel.',0,2023,5,'Silver','ABC-1001',NULL,2,NULL,'2026-05-13 20:54:04','2026-05-16 13:41:04'),(2,'Ford','Explorer','suv','automatic','petrol',95.00,'Spacious SUV ideal for family road trips.',1,2023,7,'White','ABC-1002',NULL,2,NULL,'2026-05-13 20:54:04','2026-05-13 23:22:47'),(3,'Tesla','Model 3','sedan','automatic','electric',120.00,'Premium electric vehicle with autopilot features.',1,2024,5,'Red','ABC-1003',NULL,2,NULL,'2026-05-13 20:54:04','2026-05-13 20:54:04'),(4,'BMW','5 Series','luxury','automatic','petrol',180.00,'Luxury sedan with premium interior and cutting-edge tech.',1,2024,5,'Black','ABC-1004',NULL,2,NULL,'2026-05-13 20:54:04','2026-05-13 20:54:04'),(5,'Chevrolet','Silverado','truck','automatic','diesel',110.00,'Heavy-duty pickup truck for work and adventure.',1,2022,5,'Blue','ABC-1005',NULL,2,NULL,'2026-05-13 20:54:04','2026-05-13 20:54:04'),(6,'Honda','Civic','economy','manual','petrol',45.00,'Fuel-efficient compact car, great value for money.',1,2022,5,'Gray','ABC-1006',NULL,2,NULL,'2026-05-13 20:54:04','2026-05-13 20:54:04'),(7,'Mercedes-Benz','Sprinter','van','automatic','diesel',130.00,'Large cargo/passenger van with ample space.',1,2023,9,'White','ABC-1007',NULL,2,NULL,'2026-05-13 20:54:04','2026-05-13 20:54:04'),(8,'Porsche','Cayenne','luxury','automatic','hybrid',250.00,'Ultra-luxury sports SUV with hybrid powertrain.',1,2024,5,'Midnight Blue','ABC-1008',NULL,2,NULL,'2026-05-13 20:54:04','2026-05-13 20:54:04');
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'cars_rental'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-22 22:45:20
