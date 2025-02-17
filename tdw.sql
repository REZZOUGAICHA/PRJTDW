-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 16, 2025 at 10:17 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tdw`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `admin_type` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_type` (`admin_type`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `profile_picture`, `password`, `admin_type`) VALUES
(1, 'Admin Name', 'https://example.com/profile.jpg', 'securepassword', NULL),
(2, 'admin', NULL, 'admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `admintype`
--

DROP TABLE IF EXISTS `admintype`;
CREATE TABLE IF NOT EXISTS `admintype` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admintype`
--

INSERT INTO `admintype` (`id`, `name`) VALUES
(1, 'superadmin'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `aidrequest`
--

DROP TABLE IF EXISTS `aidrequest`;
CREATE TABLE IF NOT EXISTS `aidrequest` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `aid_type_id` int NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `link` varchar(2083) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `aid_type_id` (`aid_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `aidrequest`
--

INSERT INTO `aidrequest` (`id`, `user_id`, `aid_type_id`, `status`, `created_at`, `updated_at`, `link`) VALUES
(27, 51, 1, 'pending', '2025-01-16 20:59:54', '2025-01-16 20:59:54', '/TDW/uploads/aid/6789734ad5f31_1737061194.pdf'),
(28, 51, 2, 'pending', '2025-01-16 21:00:02', '2025-01-16 21:00:02', '/TDW/uploads/aid/67897352e631d_1737061202.pdf'),
(29, 51, 4, 'pending', '2025-01-16 21:00:10', '2025-01-16 21:00:10', '/TDW/uploads/aid/6789735a9c452_1737061210.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `aidtype`
--

DROP TABLE IF EXISTS `aidtype`;
CREATE TABLE IF NOT EXISTS `aidtype` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `aidtype`
--

INSERT INTO `aidtype` (`id`, `name`, `description`) VALUES
(1, 'Aide financière', 'Soutien financier pour les familles ou les individus en difficulté.'),
(2, 'Aide éducative', 'Soutien pour les étudiants ou les élèves ayant besoin de ressources éducatives.'),
(3, 'Aide médicale', 'Aide pour couvrir les frais médicaux ou fournir des équipements de santé.'),
(4, 'Aide sociale', 'Soutien pour les besoins sociaux tels que le logement ou la nourriture.');

-- --------------------------------------------------------

--
-- Table structure for table `aidtypefiletype`
--

DROP TABLE IF EXISTS `aidtypefiletype`;
CREATE TABLE IF NOT EXISTS `aidtypefiletype` (
  `id` int NOT NULL AUTO_INCREMENT,
  `aid_type_id` int NOT NULL,
  `file_type_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `aid_type_id` (`aid_type_id`),
  KEY `file_type_id` (`file_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `aidtypefiletype`
--

INSERT INTO `aidtypefiletype` (`id`, `aid_type_id`, `file_type_id`) VALUES
(1, 1, 2),
(2, 1, 7),
(3, 1, 4),
(4, 1, 5),
(5, 2, 2),
(6, 2, 3),
(7, 2, 5),
(8, 3, 2),
(9, 3, 6),
(10, 3, 5),
(11, 4, 2),
(12, 4, 1),
(13, 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `annonces`
--

DROP TABLE IF EXISTS `annonces`;
CREATE TABLE IF NOT EXISTS `annonces` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `picture_url` varchar(2083) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `annonces`
--

INSERT INTO `annonces` (`id`, `name`, `description`, `picture_url`) VALUES
(1, 'Collecte de vêtements', 'Nous organisons une collecte de vêtements pour les personnes dans le besoin. Merci de votre générosité.', 'https://images.ctfassets.net/77l22z9el0aa/3hk4Xt2gcurIwwAACqfcxh/52d16b9f41ed4c4e63d6f316dcb0379d/How-to-Announce-your-Engagement.jpg?w=1360&fm=webp&q=50'),
(2, 'Distribution de repas', 'Rejoignez-nous pour distribuer des repas chauds aux sans-abri de notre communauté.', 'https://images.ctfassets.net/77l22z9el0aa/3hk4Xt2gcurIwwAACqfcxh/52d16b9f41ed4c4e63d6f316dcb0379d/How-to-Announce-your-Engagement.jpg?w=1360&fm=webp&q=50'),
(3, 'Collecte de fournitures scolaires', 'Aidez-nous à collecter des fournitures scolaires pour les enfants défavorisés de notre région.', 'https://images.ctfassets.net/77l22z9el0aa/3hk4Xt2gcurIwwAACqfcxh/52d16b9f41ed4c4e63d6f316dcb0379d/How-to-Announce-your-Engagement.jpg?w=1360&fm=webp&q=50'),
(4, 'Campagne de sensibilisation', 'Participez à notre campagne de sensibilisation pour promouvoir la santé mentale et le bien-être.', 'https://images.ctfassets.net/77l22z9el0aa/3hk4Xt2gcurIwwAACqfcxh/52d16b9f41ed4c4e63d6f316dcb0379d/How-to-Announce-your-Engagement.jpg?w=1360&fm=webp&q=50'),
(5, 'Collecte de fonds pour les victimes de catastrophe', 'Aidez-nous à collecter des fonds pour soutenir les victimes de catastrophes naturelles.', '/TDW/uploads/announces/67896ae92a93c_1737059049.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

DROP TABLE IF EXISTS `card`;
CREATE TABLE IF NOT EXISTS `card` (
  `id` int NOT NULL AUTO_INCREMENT,
  `card_type_id` int DEFAULT NULL,
  `card_number` char(16) NOT NULL,
  `issue_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `expiration_date` datetime DEFAULT NULL,
  `status` enum('active','inactive','expired') DEFAULT 'inactive',
  PRIMARY KEY (`id`),
  UNIQUE KEY `card_number` (`card_number`),
  KEY `card_type_id` (`card_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `card`
--

INSERT INTO `card` (`id`, `card_type_id`, `card_number`, `issue_date`, `expiration_date`, `status`) VALUES
(17, 1, '8174839324372089', '2025-01-16 22:05:50', '2026-01-16 21:05:50', 'active'),
(18, 1, '3469869562745513', '2025-01-16 22:11:40', '2026-01-16 21:11:40', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `cardtype`
--

DROP TABLE IF EXISTS `cardtype`;
CREATE TABLE IF NOT EXISTS `cardtype` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `annual_fee` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cardtype`
--

INSERT INTO `cardtype` (`id`, `name`, `description`, `annual_fee`) VALUES
(1, 'Classique', 'Standard card with basic benefits', 20000),
(2, 'Jeunes', 'Card for young individuals with special benefits', 10000),
(3, 'Premium', 'High-tier card offering exclusive perks', 50000),
(4, 'Partenaire', 'Carte pour les partenaires avec des avantages exclusifs', 0);

-- --------------------------------------------------------

--
-- Table structure for table `diaporama`
--

DROP TABLE IF EXISTS `diaporama`;
CREATE TABLE IF NOT EXISTS `diaporama` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lien` varchar(255) NOT NULL,
  `titre` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `diaporama`
--

INSERT INTO `diaporama` (`id`, `lien`, `titre`) VALUES
(1, 'https://images.pexels.com/photos/6994982/pexels-photo-6994982.jpeg?auto=compress&cs=tinysrgb&w=600', 'pic1'),
(2, 'https://images.pexels.com/photos/6994855/pexels-photo-6994855.jpeg?auto=compress&cs=tinysrgb&w=600', 'pic2'),
(3, 'https://images.pexels.com/photos/6994806/pexels-photo-6994806.jpeg?auto=compress&cs=tinysrgb&w=600', 'pic3');

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

DROP TABLE IF EXISTS `discount`;
CREATE TABLE IF NOT EXISTS `discount` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `card_type_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `percentage` decimal(5,2) NOT NULL,
  `discount_type` enum('special','regular') NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `link` varchar(2083) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `card_type_id` (`card_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`id`, `partner_id`, `card_type_id`, `name`, `description`, `percentage`, `discount_type`, `start_date`, `end_date`, `link`) VALUES
(1, 1, 1, 'Tech Savers', 'Get 20% off on all tech gadgets.', 20.00, 'special', '2024-01-01 00:00:00', '2024-03-31 23:59:59', '#link'),
(2, 2, 2, 'Education Boost', '15% discount on tuition fees.', 15.00, 'regular', '2024-01-15 00:00:00', '2024-12-31 23:59:59', '#link'),
(3, 3, 3, 'Healthcare Essentials', '10% off on all medical services.', 10.00, 'special', '2024-02-01 00:00:00', '2024-05-31 23:59:59', '#link'),
(4, 4, 1, 'Stay and Savee', '25% discount on room bookings.', 26.00, 'regular', '2024-01-01 00:00:00', '2024-06-30 00:00:00', '#link'),
(5, 5, 1, 'Wellness Discount', 'HI', 25.00, 'special', '2025-01-16 00:00:00', '2025-01-09 00:00:00', '#link'),
(6, 6, 6, 'Student Advantage', '10% off on all school supplies.', 10.00, 'regular', '2024-01-01 00:00:00', '2024-12-31 23:59:59', '#link'),
(7, 1, 1, 'Tech Accessories Discount', '15% off on all tech accessories.', 15.00, 'regular', '2024-01-01 00:00:00', '2024-12-31 23:59:59', '#link'),
(8, 1, 2, 'Software Suite Deal', '25% off on premium software suites.', 25.00, 'special', '2024-02-01 00:00:00', '2024-05-31 23:59:59', '#link'),
(9, 2, 3, 'Back-to-School Special', '10% off on school supplies.', 10.00, 'regular', '2024-01-01 00:00:00', '2024-09-30 23:59:59', '#link'),
(10, 2, 4, 'Exam Prep Materials', '20% off on exam preparation books.', 20.00, 'special', '2024-03-01 00:00:00', '2024-06-30 23:59:59', '#link'),
(11, 3, 5, 'Health Supplements Discount', '15% off on all health supplements.', 15.00, 'regular', '2024-01-01 00:00:00', '2024-12-31 23:59:59', '#link'),
(12, 3, 6, 'Consultation Package', '30% off on multi-session consultations.', 30.00, 'special', '2024-04-01 00:00:00', '2024-06-30 23:59:59', '#link'),
(13, 4, 1, 'Luxury Stay Discount', '25% off for stays of 3 nights or more.', 25.00, 'special', '2024-02-01 00:00:00', '2024-07-31 23:59:59', '#link'),
(14, 4, 2, 'Room Upgrade Offereee', '10% off on room upgradess', 10.00, 'regular', '2024-01-01 00:00:00', '2024-12-31 00:00:00', '#link'),
(15, 5, 3, 'Fitness Center Deal', '15% off on annual gym memberships.', 15.00, 'special', '2024-03-01 00:00:00', '2024-05-31 23:59:59', '#link'),
(16, 6, 4, 'School Fees Discount', '5% off on tuition fees for early enrollments.', 5.00, 'regular', '2024-01-01 00:00:00', '2024-12-31 23:59:59', '#link'),
(21, 12, 1, 'Académie Avenir Brillantt', '1', 1.00, 'regular', '2025-01-26 00:00:00', '2025-01-31 00:00:00', NULL),
(20, 11, 1, 'new', 'A', 20.00, 'regular', '2025-01-10 00:00:00', '2025-01-23 00:00:00', NULL),
(22, 13, 1, 'azertyu', 'azertyui', 5.00, 'regular', '2025-01-08 00:00:00', '2025-01-24 00:00:00', NULL),
(23, 27, 2, 'discount11', 'une remise', 20.00, 'special', '2025-01-17 00:00:00', '2025-01-19 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) NOT NULL,
  `event_description` text,
  `type` enum('event','activity') DEFAULT 'event',
  `event_date` datetime NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `link` varchar(2083) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `event_name`, `event_description`, `type`, `event_date`, `file_path`, `link`) VALUES
(1, 'Food Donation', 'Help us distribute food to those in need.', 'event', '2024-01-05 10:00:00', 'https://images.pexels.com/photos/6995301/pexels-photo-6995301.jpeg?auto=compress&cs=tinysrgb&w=400', NULL),
(2, 'Breast Cancer Awareness', 'Join us to spread awareness about breast cancer.', 'event', '2024-01-10 14:00:00', 'https://images.pexels.com/photos/3900468/pexels-photo-3900468.jpeg?auto=compress&cs=tinysrgb&w=400', NULL),
(3, 'Charity Drive', 'Support our charity event to help those in need.', 'event', '2024-01-15 16:00:00', 'https://images.pexels.com/photos/6646886/pexels-photo-6646886.jpeg?auto=compress&cs=tinysrgb&w=400', NULL),
(4, 'Food Volunteering', 'Volunteer to prepare and distribute food.', 'activity', '2024-01-20 09:00:00', 'https://images.pexels.com/photos/6646886/pexels-photo-6646886.jpeg?auto=compress&cs=tinysrgb&w=400', NULL),
(5, 'Clothes Volunteering', 'Help us sort and distribute clothes to those in need.', 'activity', '2024-01-25 13:00:00', 'https://images.pexels.com/photos/6591155/pexels-photo-6591155.jpeg?auto=compress&cs=tinysrgb&w=400', NULL),
(6, 'Blood Volunteering', 'Participate in our blood donation drive.', 'activity', '2024-02-01 11:00:00', 'https://images.pexels.com/photos/6565756/pexels-photo-6565756.jpeg?auto=compress&cs=tinysrgb&w=400', NULL),
(7, 'Cleaning Volunteering', 'Join us in cleaning public spaces for the community.', 'activity', '2024-02-05 08:00:00', 'https://images.pexels.com/photos/6647020/pexels-photo-6647020.jpeg?auto=compress&cs=tinysrgb&w=400', NULL),
(8, 'Help for Education', 'Support educational initiatives for underprivileged children.', 'event', '2024-02-10 10:00:00', 'https://images.pexels.com/photos/6348119/pexels-photo-6348119.jpeg?auto=compress&cs=tinysrgb&w=400', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `filetype`
--

DROP TABLE IF EXISTS `filetype`;
CREATE TABLE IF NOT EXISTS `filetype` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `filetype`
--

INSERT INTO `filetype` (`id`, `name`, `description`) VALUES
(1, 'Extrait de naissance', 'Document officiel prouvant la naissance d’une personne.'),
(2, 'Carte d’identité', 'Document officiel prouvant l’identité du demandeur.'),
(3, 'Certificat de scolarité', 'Document prouvant l’inscription d’un étudiant dans un établissement éducatif.'),
(4, 'Reçu de paiement', 'Reçu prouvant un paiement effectué.'),
(5, 'Justificatif de domicile', 'Document prouvant l’adresse actuelle du demandeur.'),
(6, 'Certificat médical', 'Document délivré par un médecin attestant de l’état de santé du demandeur.'),
(7, 'Relevé de compte bancaire', 'Document montrant les transactions et le solde du compte bancaire.');

-- --------------------------------------------------------

--
-- Table structure for table `id_card`
--

DROP TABLE IF EXISTS `id_card`;
CREATE TABLE IF NOT EXISTS `id_card` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `id_card`
--

INSERT INTO `id_card` (`id`, `user_id`, `file_path`, `upload_date`) VALUES
(1, 19, '/TDW/uploads/memberships/67804e4d7127b_1736461901.jpeg', '2025-01-09 22:31:41'),
(2, 7, '/TDW/uploads/memberships/6780503e55c04_1736462398.pdf', '2025-01-09 22:39:58'),
(3, 20, '/TDW/uploads/memberships/6780fee9d7a4f_1736507113.pdf', '2025-01-10 11:05:13'),
(4, 21, '/TDW/uploads/memberships/67817836b9ab3_1736538166.jpeg', '2025-01-10 19:42:46'),
(5, 22, '/TDW/uploads/memberships/67819a3f70795_1736546879.jpg', '2025-01-10 22:07:59'),
(6, 23, '/TDW/uploads/memberships/67819d7309252_1736547699.jpg', '2025-01-10 22:21:39'),
(7, 24, '/TDW/uploads/memberships/6781a0bc5d9b8_1736548540.jpg', '2025-01-10 22:35:40'),
(8, 28, '/TDW/uploads/memberships/6786d60fee69f_1736889871.jpg', '2025-01-14 21:24:32'),
(9, 29, '/TDW/uploads/memberships/6786dbf55bc81_1736891381.png', '2025-01-14 21:49:41'),
(10, 30, '/TDW/uploads/memberships/6786e030b0673_1736892464.jpg', '2025-01-14 22:07:44'),
(11, 36, '/TDW/uploads/memberships/6786f88cb3c46_1736898700.jpg', '2025-01-14 23:51:40'),
(12, 37, '/TDW/uploads/memberships/6787ff661b1a0_1736965990.jpg', '2025-01-15 18:33:10'),
(13, 39, '/TDW/uploads/memberships/678855e6f354e_1736988134.jpg', '2025-01-16 00:42:15'),
(14, 42, '/TDW/uploads/memberships/678857bf7e1c8_1736988607.jpg', '2025-01-16 00:50:07'),
(15, 43, '/TDW/uploads/memberships/6788e05c56315_1737023580.jpg', '2025-01-16 10:33:00'),
(16, 44, '/TDW/uploads/memberships/67892c4bc8b7e_1737043019.jpg', '2025-01-16 15:56:59'),
(17, 48, '/TDW/uploads/memberships/678966ee242bd_1737058030.jpg', '2025-01-16 20:07:10'),
(18, 51, '/TDW/uploads/memberships/67896a27de4bd_1737058855.jpg', '2025-01-16 20:20:55'),
(19, 52, '/TDW/uploads/memberships/6789749f7eee2_1737061535.jpg', '2025-01-16 21:05:35'),
(20, 53, '/TDW/uploads/memberships/6789757b0c565_1737061755.jpg', '2025-01-16 21:09:15');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `card_id` int DEFAULT NULL,
  `membership_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `card_id` (`card_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `user_id`, `card_id`, `membership_date`) VALUES
(1, 11, 1, '2025-01-03 15:10:09'),
(2, 7, 1, '2025-01-10 11:31:47'),
(3, 7, 1, '2025-01-10 11:31:55'),
(4, 7, 1, '2025-01-10 11:39:54'),
(5, 20, 2, '2025-01-10 12:05:31'),
(6, 21, 2, '2025-01-10 20:43:00'),
(7, 22, 1, '2025-01-10 23:08:15'),
(8, 23, 5, '2025-01-10 23:30:11'),
(9, 24, 6, '2025-01-10 23:35:48'),
(10, 28, 7, '2025-01-14 22:24:39'),
(11, 29, 8, '2025-01-14 22:49:47'),
(12, 30, 9, '2025-01-14 23:07:50'),
(13, 36, 10, '2025-01-15 00:51:53'),
(14, 37, 11, '2025-01-15 19:33:15'),
(15, 39, 12, '2025-01-16 01:42:27'),
(16, 43, 14, '2025-01-16 11:33:26'),
(17, 44, 15, '2025-01-16 16:57:30'),
(18, 51, 16, '2025-01-16 21:21:15'),
(19, 52, 17, '2025-01-16 22:05:50'),
(20, 52, 18, '2025-01-16 22:11:40');

-- --------------------------------------------------------

--
-- Table structure for table `membership_application`
--

DROP TABLE IF EXISTS `membership_application`;
CREATE TABLE IF NOT EXISTS `membership_application` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `card_type_id` int NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `application_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `processed_date` timestamp NULL DEFAULT NULL,
  `processed_by` int DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `card_type_id` (`card_type_id`),
  KEY `processed_by` (`processed_by`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `membership_application`
--

INSERT INTO `membership_application` (`id`, `user_id`, `card_type_id`, `status`, `application_date`, `processed_date`, `processed_by`, `notes`) VALUES
(19, 52, 1, 'approved', '2025-01-16 21:05:35', NULL, NULL, 'test'),
(20, 53, 3, 'pending', '2025-01-16 21:09:15', NULL, NULL, 'TEST');

-- --------------------------------------------------------

--
-- Table structure for table `menu_main`
--

DROP TABLE IF EXISTS `menu_main`;
CREATE TABLE IF NOT EXISTS `menu_main` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu_main`
--

INSERT INTO `menu_main` (`id`, `name`, `link`) VALUES
(1, 'Accueil', '/TDW/accueil'),
(2, 'Partenaires', '/TDW/partenaires'),
(3, 'Remises', '/TDW/remises'),
(4, 'News', '/TDW/news'),
(5, 'Don', '/TDW/don'),
(6, 'Demande d\'aide', '/TDW/aide'),
(7, 'Devenir Membre', '/TDW/membership'),
(8, 'Scan Membre', '/TDW/scan');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `notification_type_id` int DEFAULT NULL,
  `content` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `notification_type_id` (`notification_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_type`
--

DROP TABLE IF EXISTS `notification_type`;
CREATE TABLE IF NOT EXISTS `notification_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offer`
--

DROP TABLE IF EXISTS `offer`;
CREATE TABLE IF NOT EXISTS `offer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int NOT NULL,
  `card_type_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `offer_type` enum('limited','regular') NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `link` varchar(2083) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`),
  KEY `card_type_id` (`card_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `offer`
--

INSERT INTO `offer` (`id`, `partner_id`, `card_type_id`, `name`, `description`, `offer_type`, `start_date`, `end_date`, `link`) VALUES
(1, 1, 1, 'Innovator Package', 'Exclusive package for tech enthusiasts.', 'limited', '2024-01-01 00:00:00', '2024-04-30 23:59:59', NULL),
(2, 2, 2, 'Learning Advantage', 'Special offer on enrollment fees.', 'regular', '2024-02-01 00:00:00', '2024-12-31 23:59:59', NULL),
(3, 3, 3, 'Wellness Care', 'Free initial consultation for new patients.', 'limited', '2024-01-15 00:00:00', '2024-06-30 23:59:59', NULL),
(4, 4, 4, 'Holiday Escape', 'Book 3 nights, get 1 free.', 'limited', '2024-03-01 00:00:00', '2024-08-31 23:59:59', NULL),
(5, 5, 1, 'Relaxation Offerr', 'Special rates on spa treatments.', 'regular', '2024-01-01 00:00:00', '2024-07-25 00:00:00', NULL),
(6, 6, 6, 'Academic Starter Pack', 'Discounted rates on school enrollment.', 'regular', '2024-01-01 00:00:00', '2024-12-31 23:59:59', NULL),
(7, 1, 1, 'Tech Workshop', 'Free entry to an exclusive workshop on AI.', 'limited', '2024-02-01 00:00:00', '2024-04-30 23:59:59', NULL),
(8, 1, 2, 'Device Upgrade Deal', 'Special discounts on upgrading to the latest devices.', 'regular', '2024-01-01 00:00:00', '2024-12-31 23:59:59', NULL),
(9, 2, 3, 'E-Learning Special', '50% off on e-learning courses.', 'limited', '2024-03-01 00:00:00', '2024-06-30 23:59:59', NULL),
(10, 2, 4, 'Library Access Pass', 'Free library access for enrolled students.', 'regular', '2024-01-01 00:00:00', '2024-12-31 23:59:59', NULL),
(11, 3, 5, 'Wellness Wednesdays', 'Special rates on wellness consultations every Wednesday.', 'regular', '2024-01-01 00:00:00', '2024-12-31 23:59:59', NULL),
(12, 3, 6, 'Health Checkup Camp', 'Free basic health checkups during the camp.', 'limited', '2024-04-01 00:00:00', '2024-06-30 23:59:59', NULL),
(13, 4, 1, 'Luxury Dining Offer', 'Free breakfast and dinner with your stay.', 'limited', '2024-02-01 00:00:00', '2024-07-31 23:59:59', NULL),
(14, 4, 2, 'Weekend Getaway', '20% off for weekend stays.', 'regular', '2024-01-01 00:00:00', '2024-12-31 23:59:59', NULL),
(15, 5, 3, 'Spa Serenityy', 'Buy one spa treatment, get one free.', 'limited', '2024-03-01 00:00:00', '2024-05-31 00:00:00', NULL),
(16, 6, 4, 'Scholarship Offer', 'Partial scholarship for top-performing students.', 'regular', '2024-01-01 00:00:00', '2024-12-31 23:59:59', NULL),
(17, 4, 1, 'new', 'azer', 'limited', '2025-01-17 00:00:00', '2025-01-18 00:00:00', NULL),
(18, 27, 3, 'offre', 'offre', 'limited', '2025-01-17 00:00:00', '2025-01-19 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `partner`
--

DROP TABLE IF EXISTS `partner`;
CREATE TABLE IF NOT EXISTS `partner` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `description` text,
  `logo_url` varchar(2083) DEFAULT NULL,
  `category_id` int NOT NULL,
  `link` varchar(2083) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `card_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  KEY `card_id` (`card_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `partner`
--

INSERT INTO `partner` (`id`, `user_id`, `name`, `city`, `description`, `logo_url`, `category_id`, `link`, `created_at`, `card_id`) VALUES
(1, 1, 'Innovateurs Tech', 'Alger', 'Une entreprise leader dans la technologie offrant des solutions innovantes.', 'https://img.freepik.com/vecteurs-libre/degrade-illustration-oiseau-colore_343694-1741.jpg?uid=R78717624&ga=GA1.1.1542090150.1735383783&semt=ais_hybrid', 6, '/admin/partner/1', '2024-12-30 16:30:59', NULL),
(3, 2, 'Académie Avenir Brillantt', 'Oran', 'Une institution prestigieuse offrant une éducation de qualité supérieure.', 'https://img.freepik.com/vecteurs-libre/symbole-anarchie-design-plat-dessine-main_23-2149244365.jpg?uid=R78717624&ga=GA1.1.1542090150.1735383783&semt=ais_hybrid', 6, '/TDW/entity/partner/0-dc93ebab6dc0', '2025-01-07 17:47:26', NULL),
(4, 3, 'clinic ', 'Constantine', 'Offrant des services de santé exceptionnels à la communauté.SAS', 'https://img.freepik.com/vecteurs-libre/creation-logo-coeur-saint-valentin-illustration-vectorielle_460848-9807.jpg?uid=R78717624&ga=GA1.1.1542090150.1735383783&semt=ais_hybrid', 5, '/TDW/entity/partner/3-7e3c71240c46', '2025-01-07 17:47:30', NULL),
(5, 4, 'Hôtel Horizon Grand', 'Annaba', 'Un hôtel luxueux offrant une hospitalité de classe mondiale.', 'https://img.freepik.com/vecteurs-libre/modele-conception-logo-vectoriel-hotel-branding-identity-corporate_460848-14015.jpg?uid=R78717624&ga=GA1.1.1542090150.1735383783&semt=ais_hybrid', 4, '/TDW/entity/partner/4-83b0a5d02a32', '2025-01-07 17:47:33', NULL),
(27, 49, 'partenaire', 'Alger', 'azerty', '/TDW/uploads/partners/6789676295841_1737058146.jpg', 5, NULL, '2025-01-16 20:09:06', NULL),
(25, 46, 'partner', 'Algiers', 'azerty', '/TDW/uploads/partners/678927426bfdb_1737041730.jpg', 5, NULL, '2025-01-16 15:35:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `partnercategory`
--

DROP TABLE IF EXISTS `partnercategory`;
CREATE TABLE IF NOT EXISTS `partnercategory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `partnercategory`
--

INSERT INTO `partnercategory` (`id`, `name`, `description`) VALUES
(1, 'Technology', 'Partners in the tech industry'),
(2, 'Education', 'Partners in the education sector'),
(3, 'Healthcare', 'Partners providing healthcare services'),
(4, 'Hotel', 'Hotels and hospitality partners'),
(5, 'Clinic', 'Clinics and healthcare centers'),
(6, 'School', 'Schools and educational institutions');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `validated_by` int DEFAULT NULL,
  `payment_type_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `is_valid` tinyint(1) DEFAULT '0',
  `description` text,
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `payment_type_id` (`payment_type_id`),
  KEY `validated_by` (`validated_by`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `user_id`, `validated_by`, `payment_type_id`, `amount`, `payment_date`, `status`, `is_valid`, `description`, `notes`) VALUES
(1, 17, NULL, 1, 1000.00, '2025-01-07 21:59:23', 'pending', 0, 'azerty', NULL),
(2, 17, NULL, 1, 1000.00, '2025-01-07 22:00:41', 'pending', 0, 'azerty', NULL),
(3, 17, NULL, 1, 1111.00, '2025-01-07 22:06:36', 'pending', 0, 'azertyj', NULL),
(4, 18, NULL, 1, 2000.00, '2025-01-08 13:51:01', 'pending', 0, 'A', NULL),
(5, 19, NULL, 1, 11.00, '2025-01-09 22:21:43', 'approved', 0, 'vb,', NULL),
(6, 19, NULL, 2, 111.00, '2025-01-09 22:31:41', 'pending', 0, 'DFGHJ', NULL),
(7, 19, NULL, 2, 1111.00, '2025-01-09 22:38:50', 'pending', 0, 'dfghjk', NULL),
(8, 7, NULL, 2, 100.00, '2025-01-09 22:39:58', 'pending', 0, 'HGJ', NULL),
(9, 20, NULL, 2, 10000.00, '2025-01-10 11:05:13', 'pending', 0, 'Paiement de la cotisation annuelle - Jeunes', NULL),
(10, 20, NULL, 1, 1000.00, '2025-01-10 11:23:29', 'approved', 0, 'HI', NULL),
(11, 19, NULL, 2, 20000.00, '2025-01-10 19:41:22', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(12, 19, NULL, 2, 20000.00, '2025-01-10 19:41:46', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(13, 21, NULL, 2, 10000.00, '2025-01-10 19:42:46', 'pending', 0, 'Paiement de la cotisation annuelle - Jeunes', NULL),
(14, 21, NULL, 1, 199.00, '2025-01-10 19:43:56', 'approved', 0, 'fghj,', NULL),
(15, 21, NULL, 1, 233.00, '2025-01-10 20:15:25', 'rejected', 0, 'AZERTYU233', NULL),
(16, 22, NULL, 2, 20000.00, '2025-01-10 22:07:59', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(17, 23, NULL, 2, 20000.00, '2025-01-10 22:21:39', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(18, 24, NULL, 2, 20000.00, '2025-01-10 22:35:40', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(19, 24, NULL, 1, 100.00, '2025-01-10 23:36:54', 'approved', 0, 'SDFGHJ', NULL),
(20, 28, NULL, 2, 20000.00, '2025-01-14 21:24:32', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(21, 29, NULL, 2, 20000.00, '2025-01-14 21:49:41', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(22, 30, NULL, 2, 20000.00, '2025-01-14 22:07:44', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(23, 36, NULL, 2, 20000.00, '2025-01-14 23:51:40', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(24, 37, NULL, 2, 20000.00, '2025-01-15 18:33:10', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(25, 39, NULL, 2, 0.00, '2025-01-16 00:42:15', 'pending', 0, 'Paiement de la cotisation annuelle - Partenaire', NULL),
(26, 42, NULL, 2, 0.00, '2025-01-16 00:50:07', 'pending', 0, 'Paiement de la cotisation annuelle - Partenaire', NULL),
(27, 42, NULL, 1, 2000.00, '2025-01-16 10:30:06', 'pending', 0, 'AZERTY', NULL),
(28, 42, NULL, 1, 1000.00, '2025-01-16 10:30:22', 'pending', 0, 'AZERTY', NULL),
(29, 42, NULL, 2, 20000.00, '2025-01-16 10:30:41', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(30, 43, NULL, 2, 50000.00, '2025-01-16 10:33:00', 'pending', 0, 'Paiement de la cotisation annuelle - Premium', NULL),
(31, 43, NULL, 1, 123.00, '2025-01-16 10:33:42', 'approved', 0, 'AZERTY', NULL),
(32, 43, NULL, 1, 100.00, '2025-01-16 12:41:58', 'approved', 0, 'AZERTY', NULL),
(33, 44, NULL, 1, 1000.00, '2025-01-16 15:56:22', 'approved', 0, 'AZErty', NULL),
(34, 44, NULL, 2, 20000.00, '2025-01-16 15:56:59', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(35, 48, NULL, 1, 2000.00, '2025-01-16 20:06:36', 'pending', 0, 'don', NULL),
(36, 48, NULL, 2, 20000.00, '2025-01-16 20:07:10', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(37, 51, NULL, 2, 20000.00, '2025-01-16 20:20:55', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(38, 51, NULL, 1, 1000.00, '2025-01-16 20:22:13', 'pending', 0, 'don', NULL),
(39, 51, NULL, 1, 3000.00, '2025-01-16 20:22:31', 'approved', 0, 'Description', NULL),
(40, 51, NULL, 2, 20000.00, '2025-01-16 21:01:42', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(41, 51, NULL, 2, 20000.00, '2025-01-16 21:04:50', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(42, 52, NULL, 2, 20000.00, '2025-01-16 21:05:35', 'pending', 0, 'Paiement de la cotisation annuelle - Classique', NULL),
(43, 48, NULL, 2, 10000.00, '2025-01-16 21:08:07', 'pending', 0, 'Paiement de la cotisation annuelle - Jeunes', NULL),
(44, 53, NULL, 2, 50000.00, '2025-01-16 21:09:15', 'pending', 0, 'Paiement de la cotisation annuelle - Premium', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_type`
--

DROP TABLE IF EXISTS `payment_type`;
CREATE TABLE IF NOT EXISTS `payment_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment_type`
--

INSERT INTO `payment_type` (`id`, `type_name`, `description`) VALUES
(1, 'Don', 'Paiement pour les dons'),
(2, 'Carte', 'Paiement pour les cartes');

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

DROP TABLE IF EXISTS `receipt`;
CREATE TABLE IF NOT EXISTS `receipt` (
  `id` int NOT NULL AUTO_INCREMENT,
  `payment_id` int NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `payment_id` (`payment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `receipt`
--

INSERT INTO `receipt` (`id`, `payment_id`, `file_path`, `upload_date`) VALUES
(1, 1, '677da3bb04091_1736287163.png', '2025-01-07 21:59:23'),
(2, 2, '677da409b956b_1736287241.png', '2025-01-07 22:00:41'),
(3, 3, '677da56c33f5f_1736287596.png', '2025-01-07 22:06:36'),
(4, 4, '677e82c5b2b19_1736344261.jpg', '2025-01-08 13:51:01'),
(5, 5, '/TDW/uploads/receipts/67804bf7bd1be_1736461303.jpeg', '2025-01-09 22:21:43'),
(6, 6, '/TDW/uploads/memberships/67804e4d70e21_1736461901.pdf', '2025-01-09 22:31:41'),
(7, 7, '/TDW/uploads/memberships/67804ffa4e083_1736462330.pdf', '2025-01-09 22:38:50'),
(8, 8, '/TDW/uploads/memberships/6780503e557c1_1736462398.pdf', '2025-01-09 22:39:58'),
(9, 9, '/TDW/uploads/memberships/6780fee9d7725_1736507113.png', '2025-01-10 11:05:13'),
(10, 10, '/TDW/uploads/receipts/6781033155068_1736508209.jpeg', '2025-01-10 11:23:29'),
(11, 11, '/TDW/uploads/memberships/678177e28e6ab_1736538082.png', '2025-01-10 19:41:22'),
(12, 12, '/TDW/uploads/memberships/678177fa7b18f_1736538106.png', '2025-01-10 19:41:46'),
(13, 13, '/TDW/uploads/memberships/67817836b961c_1736538166.png', '2025-01-10 19:42:46'),
(14, 14, '/TDW/uploads/receipts/6781787ccec67_1736538236.png', '2025-01-10 19:43:56'),
(15, 15, '/TDW/uploads/receipts/67817fdd2debd_1736540125.jpeg', '2025-01-10 20:15:25'),
(16, 16, '/TDW/uploads/memberships/67819a3f7034e_1736546879.jpg', '2025-01-10 22:07:59'),
(17, 17, '/TDW/uploads/memberships/67819d7308d7a_1736547699.jpg', '2025-01-10 22:21:39'),
(18, 18, '/TDW/uploads/memberships/6781a0bc5d137_1736548540.jpg', '2025-01-10 22:35:40'),
(19, 19, '/TDW/uploads/receipts/6781af16e9943_1736552214.jpg', '2025-01-10 23:36:54'),
(20, 20, '/TDW/uploads/memberships/6786d60fee37d_1736889871.jpg', '2025-01-14 21:24:32'),
(21, 21, '/TDW/uploads/memberships/6786dbf55b7ae_1736891381.png', '2025-01-14 21:49:41'),
(22, 22, '/TDW/uploads/memberships/6786e030b01e3_1736892464.jpg', '2025-01-14 22:07:44'),
(23, 23, '/TDW/uploads/memberships/6786f88cb379c_1736898700.jpg', '2025-01-14 23:51:40'),
(24, 24, '/TDW/uploads/memberships/6787ff661acf0_1736965990.jpg', '2025-01-15 18:33:10'),
(25, 25, '/TDW/uploads/memberships/678855e6f2fa7_1736988134.jpg', '2025-01-16 00:42:15'),
(26, 26, '/TDW/uploads/memberships/678857bf7db0a_1736988607.jpg', '2025-01-16 00:50:07'),
(27, 27, '/TDW/uploads/receipts/6788dfadf3d83_1737023405.jpg', '2025-01-16 10:30:06'),
(28, 28, '/TDW/uploads/receipts/6788dfbee9ed2_1737023422.jpg', '2025-01-16 10:30:22'),
(29, 29, '/TDW/uploads/memberships/6788dfd1b9f57_1737023441.jpg', '2025-01-16 10:30:41'),
(30, 30, '/TDW/uploads/memberships/6788e05c55af2_1737023580.jpg', '2025-01-16 10:33:00'),
(31, 31, '/TDW/uploads/receipts/6788e086905f8_1737023622.jpg', '2025-01-16 10:33:42'),
(32, 32, '/TDW/uploads/receipts/6788fe96611b8_1737031318.jpg', '2025-01-16 12:41:58'),
(33, 33, '/TDW/uploads/receipts/67892c267cfe1_1737042982.jpg', '2025-01-16 15:56:22'),
(34, 34, '/TDW/uploads/memberships/67892c4bc8418_1737043019.jpg', '2025-01-16 15:56:59'),
(35, 35, '/TDW/uploads/receipts/678966cce4ec9_1737057996.jpg', '2025-01-16 20:06:36'),
(36, 36, '/TDW/uploads/memberships/678966ee23d39_1737058030.jpg', '2025-01-16 20:07:10'),
(37, 37, '/TDW/uploads/memberships/67896a27de05c_1737058855.jpg', '2025-01-16 20:20:55'),
(38, 38, '/TDW/uploads/receipts/67896a75bff14_1737058933.jpg', '2025-01-16 20:22:13'),
(39, 39, '/TDW/uploads/receipts/67896a87a630b_1737058951.jpg', '2025-01-16 20:22:31'),
(40, 40, '/TDW/uploads/memberships/678973b6428df_1737061302.jpg', '2025-01-16 21:01:42'),
(41, 41, '/TDW/uploads/memberships/678974721804e_1737061490.jpg', '2025-01-16 21:04:50'),
(42, 42, '/TDW/uploads/memberships/6789749f7e921_1737061535.jpg', '2025-01-16 21:05:35'),
(43, 43, '/TDW/uploads/memberships/678975374ef78_1737061687.jpg', '2025-01-16 21:08:07'),
(44, 44, '/TDW/uploads/memberships/6789757b0c00a_1737061755.jpg', '2025-01-16 21:09:15');

-- --------------------------------------------------------

--
-- Table structure for table `sidebar`
--

DROP TABLE IF EXISTS `sidebar`;
CREATE TABLE IF NOT EXISTS `sidebar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `order_index` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sidebar`
--

INSERT INTO `sidebar` (`id`, `name`, `link`, `order_index`) VALUES
(1, 'Partenaire', '/TDW/admin/partenaires', 1),
(2, 'Membre', '/TDW/admin/membre', 2),
(3, 'Dons', '/TDW/admin/dons', 3),
(4, 'Bénévolat', '/TDW/admin/benevolat', 4),
(5, 'news', '/TDW/admin/news', 5),
(6, 'aid', '/TDW/admin/aid', 6),
(7, 'Parametres', '/TDW/admin/parametres', 7),
(9, 'Adhesions', '/TDW/admin/adhesions', 8),
(11, 'Tableau de bord', '/TDW/admin/tableau-de-bord', 0);

-- --------------------------------------------------------

--
-- Table structure for table `social_media`
--

DROP TABLE IF EXISTS `social_media`;
CREATE TABLE IF NOT EXISTS `social_media` (
  `id` int NOT NULL AUTO_INCREMENT,
  `social_media_name` varchar(100) NOT NULL,
  `icon_link` varchar(255) NOT NULL,
  `social_media_link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `social_media`
--

INSERT INTO `social_media` (`id`, `social_media_name`, `icon_link`, `social_media_link`) VALUES
(4, 'Facebook', 'https://cdn-icons-png.flaticon.com/128/1384/1384005.png', '#facebook'),
(5, 'Instagram', 'https://cdn-icons-png.flaticon.com/128/1384/1384015.png', '#instagram'),
(6, 'YouTube', 'https://cdn-icons-png.flaticon.com/128/3669/3669688.png', '#youtube');

-- --------------------------------------------------------

--
-- Table structure for table `submenus`
--

DROP TABLE IF EXISTS `submenus`;
CREATE TABLE IF NOT EXISTS `submenus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `page_identifier` varchar(100) NOT NULL,
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `submenus`
--

INSERT INTO `submenus` (`id`, `name`, `page_identifier`, `active`) VALUES
(1, 'Partner Submenu', 'partner', 1),
(2, 'offer Submenu', 'offer', 1);

-- --------------------------------------------------------

--
-- Table structure for table `submenu_items`
--

DROP TABLE IF EXISTS `submenu_items`;
CREATE TABLE IF NOT EXISTS `submenu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `submenu_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL,
  `order_position` int NOT NULL,
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `submenu_id` (`submenu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `submenu_items`
--

INSERT INTO `submenu_items` (`id`, `submenu_id`, `name`, `link`, `order_position`, `active`) VALUES
(10, 1, 'Education', '#category-education', 2, 1),
(5, 2, 'Remise', '#regular-discounts', 1, 1),
(6, 2, 'Offres', '#LINK', 2, 1),
(7, 2, 'Remises spéciales', '#special-discounts', 3, 1),
(8, 2, 'Offres limitées', '#LINK', 4, 1),
(11, 1, 'Healthcare', '#category-healthcare', 3, 1),
(9, 1, 'Technology', '#category-technology', 1, 1),
(12, 1, 'Hotel', '#category-hotel', 4, 1),
(13, 1, 'Clinic', '#category-clinic', 5, 1),
(14, 1, 'School', '#category-school', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `topbar`
--

DROP TABLE IF EXISTS `topbar`;
CREATE TABLE IF NOT EXISTS `topbar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `logo_link` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `join_us_label` varchar(255) DEFAULT 'Rejoignez-nous',
  `join_us_link` varchar(255) DEFAULT '/join-us',
  `login_label` varchar(255) DEFAULT 'Se connecter',
  `login_link` varchar(255) DEFAULT '/login',
  `asso_name` varchar(255) DEFAULT 'EL Mountada',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `topbar`
--

INSERT INTO `topbar` (`id`, `logo_link`, `is_active`, `join_us_label`, `join_us_link`, `login_label`, `login_link`, `asso_name`) VALUES
(4, '/TDW/uploads/Platform_Logo/logo2.jpg', 1, 'Rejoignez-nous', '/join-us', 'Se connecter', '/login', 'EL Mountada');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registration_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  `profile_picture` mediumblob,
  `user_type` enum('user','member','partner') DEFAULT 'user',
  `membership_status` enum('none','pending','approved','rejected') NOT NULL DEFAULT 'none',
  `QR_LINK` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `password`, `registration_date`, `is_active`, `profile_picture`, `user_type`, `membership_status`, `QR_LINK`) VALUES
(44, 'user', 'user', 'user@gmail.com', '$2y$10$ugUErQDFya0AeY3HjAFR/OJ/aSUAVl/SKZspp5PEBQdaYiCaQYGmC', '2025-01-16 16:12:57', 1, 0x2f5444572f75706c6f6164732f70726f66696c652f363738393231663931666138325f313733373034303337372e6a7067, 'member', 'none', NULL),
(48, 'utilisateur', 'utilisateur', 'utilisateur@gmail.com', '$2y$10$leBGqNGtl4wqw3KohEPYdu6Af9x5rb2VhpobWjVKijALeYJ73Uxlq', '2025-01-16 20:59:06', 1, 0x2f5444572f75706c6f6164732f70726f66696c652f363738393635306135636131325f313733373035373534362e6a7067, 'user', 'none', NULL),
(46, 'partner', 'partner', 'partnertest@gmail.com', '$2y$10$GqZxYP63DlVcPVB/7ODhPOh0s1zKzOpoxKY0gQLb2vN7b/Q1gOMLa', '2025-01-16 16:35:30', 1, 0x2f5444572f75706c6f6164732f706172746e6572732f363738393237343236626664625f313733373034313733302e6a7067, 'partner', 'none', NULL),
(47, 'REZZOUG', 'AICHA', 'partner2@gmail.com', '$2y$10$ICwJUISDDIMfoghQ1zRcjOGFYv37D/KmKg7BIVWHgG.yHnfvOAzHW', '2025-01-16 16:53:43', 1, 0x2f5444572f75706c6f6164732f706172746e6572732f363738393262383735366639625f313733373034323832332e6a7067, 'partner', 'none', NULL),
(49, 'partenaire', 'partenaire', 'partenaire@gmail.com', '$2y$10$cIjM3Do1XRtK4FSutVOj4.cIMUjfOXb2wPqkmIi8czlHNkpnTKpUO', '2025-01-16 21:09:06', 1, 0x2f5444572f75706c6f6164732f706172746e6572732f363738393637363239353834315f313733373035383134362e6a7067, 'partner', 'none', NULL),
(50, 'utilisateur2', 'utilisateur2', 'utilisateur2@gmail.com', '$2y$10$LPYmUAlBACElfc4BE6ZujOSEffLnSPbur/odbkLfwYl5fNm8c3GDm', '2025-01-16 21:19:48', 1, 0x2f5444572f75706c6f6164732f70726f66696c652f363738393639653464663933385f313733373035383738382e6a7067, 'user', 'none', NULL),
(51, 'membre', 'membre', 'membre@gmail.com', '$2y$10$0g6VsIQj7B0VF9hDkSePMeoocLQBlWuTOk6UoNge3rEzMhocCs7ZW', '2025-01-16 21:20:28', 1, 0x2f5444572f75706c6f6164732f70726f66696c652f363738393661306332333035355f313733373035383832382e6a7067, 'member', 'none', '/qr_codes/card_cardnumber5512553971787173membernamemembremembreissuedate2025-01-16212115expirationdate2026-01-16202115cardtypeClassiqueassociationELMountada.png'),
(52, 'testmember', 'testmember', 'testmember@gmail.com', '$2y$10$QS.rCREeL3293DFugYa14u8XgMLEVllqoPWGOktsOV7.OPUhP7rvm', '2025-01-16 22:05:17', 1, 0x2f5444572f75706c6f6164732f70726f66696c652f363738393735363336313037345f313733373036313733312e6a7067, 'member', 'none', '/qr_codes/card_cardnumber8174839324372089membernametestmembertestmemberissuedate2025-01-16220550expirationdate2026-01-16210550cardtypeClassiqueassociationELMountada.png'),
(53, 'utilisateur2test', 'utilisateur2test', 'utilisateur2test@gmail.com', '$2y$10$M0AJdPxH7xxl5Rfj2SOhQuXKQT/tdc38Zj/KpHFtOsxU52ep.k//S', '2025-01-16 22:08:51', 1, 0x2f5444572f75706c6f6164732f70726f66696c652f363738393735363336313037345f313733373036313733312e6a7067, 'user', 'none', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_favorites`
--

DROP TABLE IF EXISTS `user_favorites`;
CREATE TABLE IF NOT EXISTS `user_favorites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `partner_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `partner_id` (`partner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `volunteer`
--

DROP TABLE IF EXISTS `volunteer`;
CREATE TABLE IF NOT EXISTS `volunteer` (
  `volunteer_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL,
  `volunteer_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`volunteer_id`),
  KEY `user_id` (`user_id`),
  KEY `event_id` (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
