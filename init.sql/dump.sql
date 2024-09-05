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
(1,	'images',	NULL,	'images'),
(2,	'myfolder',	NULL,	'myfolder'),
(3,	'animals',	1,	'images/animals'),
(4,	'photos',	2,	'myfolder/photos');

CREATE TABLE `files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `directory_id` int NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `files` (`id`, `filename`, `directory_id`, `path`) VALUES
(2,	'cat.jpeg',	3,	'images/animals/cat.jpeg'),
(3,	'myphoto.jpeg',	4,	'myfolder/photos/myphoto.jpeg'),
(4,	'dock.docx',	4,	'myfolder/photos/dock.docx'),
(18,	'dog.jpeg',	3,	'uploads/dog.jpeg');

-- 2024-09-04 09:52:31
