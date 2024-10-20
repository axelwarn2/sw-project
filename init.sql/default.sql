-- Adminer 4.8.1 MySQL 9.0.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `directories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parent_id` int DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `directories` (`id`, `name`, `parent_id`, `path`) VALUES
(6,	'images',	NULL,	'/images'),
(8,	'images2',	6,	'/images/images2'),
(9,	'myfolder',	NULL,	'/myfolder');

CREATE TABLE `files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `directory_id` int NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `files` (`id`, `filename`, `directory_id`, `path`) VALUES
(21,	'cat.jpeg',	8,	'uploads/images/images2/cat.jpeg'),
(22,	'dog.jpeg',	8,	'uploads/images/images2/dog.jpeg'),
(23,	'myphoto.jpeg',	9,	'uploads/myfolder/myphoto.jpeg'),
(24,	'Ночь.jpeg',	9,	'uploads/myfolder/Ночь.jpeg');

-- 2024-09-17 05:17:41
