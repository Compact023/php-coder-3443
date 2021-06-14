-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2021 at 04:21 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tankstelle`
--
CREATE DATABASE IF NOT EXISTS `tankstelle` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `tankstelle`;

-- --------------------------------------------------------

--
-- Table structure for table `kunde`
--

CREATE TABLE IF NOT EXISTS `kunde` (
  `kunde_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vorname` varchar(255) COLLATE utf8_bin NOT NULL,
  `nachname` varchar(255) COLLATE utf8_bin NOT NULL,
  `geburtsdatum` date NOT NULL,
  `strasse` varchar(255) COLLATE utf8_bin NOT NULL,
  `plz` int(11) NOT NULL,
  `ort` varchar(45) COLLATE utf8_bin NOT NULL,
  `telefonummer` varchar(55) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`kunde_id`),
  UNIQUE KEY `kunde_id_UNIQUE` (`kunde_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62126 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `kunde`
--

INSERT INTO `kunde` (`kunde_id`, `vorname`, `nachname`, `geburtsdatum`, `strasse`, `plz`, `ort`, `telefonummer`) VALUES
(62123, 'Alexander', 'Maier ', '1980-08-03', 'Waldeggstraße 45', 4020, 'Linz', '05-1111-2222'),
(62124, 'Susanne', 'Huber ', '1990-02-14', 'Altenbergerstraße 1', 4040, 'Linz', '05-1111-3333'),
(62125, 'Eva', 'Strasser', '1972-11-23', 'Herrenstraße 2', 4320, 'Perg', '07320-2222-33');

-- --------------------------------------------------------

--
-- Table structure for table `treibstoff`
--

CREATE TABLE IF NOT EXISTS `treibstoff` (
  `treibstoff_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`treibstoff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `treibstoff`
--

INSERT INTO `treibstoff` (`treibstoff_id`, `bezeichnung`) VALUES
(1, 'Diesel'),
(2, 'Super'),
(3, 'Super Plus');

-- --------------------------------------------------------

--
-- Table structure for table `verbrauch`
--

CREATE TABLE IF NOT EXISTS `verbrauch` (
  `buchungs_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `buchungsdatum` date NOT NULL,
  `menge` float NOT NULL,
  `preis` float NOT NULL,
  `treibstoff_id` int(10) UNSIGNED NOT NULL,
  `kunde_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`buchungs_id`),
  UNIQUE KEY `verbrauch_id_UNIQUE` (`buchungs_id`),
  KEY `fk_vebrauch_treibstoff_idx` (`treibstoff_id`),
  KEY `fk_vebrauch_kunde1_idx` (`kunde_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47621 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `verbrauch`
--

INSERT INTO `verbrauch` (`buchungs_id`, `buchungsdatum`, `menge`, `preis`, `treibstoff_id`, `kunde_id`) VALUES
(47612, '2062-12-03', 40.5, 55.22, 1, 62123),
(47613, '2018-08-04', 52.4, 61.13, 2, 62125),
(47614, '2018-08-12', 59.3, 68.48, 2, 62125),
(47615, '2018-08-14', 37.2, 44.71, 1, 62124),
(47616, '2018-08-15', 42.9, 57.89, 1, 62123),
(47617, '2018-08-22', 32.7, 39.95, 2, 62125),
(47618, '2018-08-23', 40.8, 47.23, 1, 62124),
(47619, '2018-08-28', 38.4, 45.12, 1, 62123),
(47620, '2018-08-31', 60.2, 75.47, 2, 62125);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `verbrauch`
--
ALTER TABLE `verbrauch`
  ADD CONSTRAINT `fk_vebrauch_kunde1` FOREIGN KEY (`kunde_id`) REFERENCES `kunde` (`kunde_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vebrauch_treibstoff` FOREIGN KEY (`treibstoff_id`) REFERENCES `treibstoff` (`treibstoff_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
