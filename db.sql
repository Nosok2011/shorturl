CREATE DATABASE `shorturl` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `shorturl`;
CREATE TABLE `urls` (
  `orig` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  UNIQUE KEY `short` (`short`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;