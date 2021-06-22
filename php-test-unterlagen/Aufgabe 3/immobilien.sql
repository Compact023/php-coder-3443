-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 17. Jun 2021 um 18:13
-- Server-Version: 10.4.19-MariaDB
-- PHP-Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `immobilien`
--
CREATE DATABASE IF NOT EXISTS `immobilien` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `immobilien`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bilder`
--

CREATE TABLE IF NOT EXISTS `bilder` (
  `bild_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `beschreibung` text DEFAULT NULL,
  `pfad` varchar(255) NOT NULL,
  `immobilie_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`bild_id`),
  KEY `fk_bilder_immobilien1_idx` (`immobilie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `bilder`
--

INSERT INTO `bilder` (`bild_id`, `name`, `beschreibung`, `pfad`, `immobilie_id`) VALUES
(1, 'Schlafzimmer', 'Bett, Kasten, Schreibtisch inkl.', 'C:/Pics/Schlafzimmer.png', 1),
(2, 'Innblick', 'Schöner Ausblick', 'C:/Pics/Ausblick.png', 2),
(3, 'Feld', 'Bewachsenes Feld', 'C:/Pics/Feld.png', 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `immobilien`
--

CREATE TABLE IF NOT EXISTS `immobilien` (
  `immobilie_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(50) NOT NULL,
  `beschreibung` text NOT NULL,
  `ort` varchar(100) NOT NULL,
  `preis` int(11) NOT NULL,
  `wohnflaeche` int(10) UNSIGNED NOT NULL,
  `erstellungsdatum` datetime NOT NULL,
  `verkaufsart_id` int(10) UNSIGNED NOT NULL,
  `immobilien_art_id` int(10) UNSIGNED NOT NULL,
  `mitarbeiter_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`immobilie_id`),
  KEY `fk_immobilien_verkaufsarten_idx` (`verkaufsart_id`),
  KEY `fk_immobilien_arten1_idx` (`immobilien_art_id`),
  KEY `fk_immobilien_mitarbeiter1_idx` (`mitarbeiter_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `immobilien`
--

INSERT INTO `immobilien` (`immobilie_id`, `bezeichnung`, `beschreibung`, `ort`, `preis`, `wohnflaeche`, `erstellungsdatum`, `verkaufsart_id`, `immobilien_art_id`, `mitarbeiter_id`) VALUES
(1, 'WG in der Altstadt', 'schön, altbau, ....', 'Linzer Straße 420, 84951 Linz', 350, 35, '2020-06-17 12:00:00', 1, 2, 1),
(2, 'Haus mit Blick zum Inn', 'saniert, grün, ...', 'Friedhofstraße 33, 5280 Braunau', 1400, 170, '2019-06-17 12:00:00', 2, 1, 2),
(3, 'Feld', 'Agrar', 'irgendwo, 1234 anders', 2000, 14000, '2021-06-17 12:00:00', 2, 4, 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `immobilien_arten`
--

CREATE TABLE IF NOT EXISTS `immobilien_arten` (
  `immobilien_art_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(45) NOT NULL,
  PRIMARY KEY (`immobilien_art_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `immobilien_arten`
--

INSERT INTO `immobilien_arten` (`immobilien_art_id`, `bezeichnung`) VALUES
(1, 'Haus'),
(2, 'Wohnung'),
(3, 'Gewerbe'),
(4, 'Agrar'),
(5, 'Grundstück');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mitarbeiter`
--

CREATE TABLE IF NOT EXISTS `mitarbeiter` (
  `mitarbeiter_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vorname` varchar(20) NOT NULL,
  `nachname` varchar(20) NOT NULL,
  `email` varchar(60) NOT NULL,
  `telefonnummer` varchar(20) NOT NULL,
  PRIMARY KEY (`mitarbeiter_id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `mitarbeiter`
--

INSERT INTO `mitarbeiter` (`mitarbeiter_id`, `vorname`, `nachname`, `email`, `telefonnummer`) VALUES
(1, 'Christoph', 'Danzer', 'cd@pos.ag', '+431468498465'),
(2, 'Robert', 'Hofmann', 'rb@some4you.at', '+4365498165'),
(3, 'Max', 'Mustermann', 'mm@mustermail.de', '+4964984986519');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `verkaufsarten`
--

CREATE TABLE IF NOT EXISTS `verkaufsarten` (
  `verkaufsart_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`verkaufsart_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `verkaufsarten`
--

INSERT INTO `verkaufsarten` (`verkaufsart_id`, `name`) VALUES
(1, 'Miete'),
(2, 'Kauf');

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bilder`
--
ALTER TABLE `bilder`
  ADD CONSTRAINT `fk_bilder_immobilien1` FOREIGN KEY (`immobilie_id`) REFERENCES `immobilien` (`immobilie_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `immobilien`
--
ALTER TABLE `immobilien`
  ADD CONSTRAINT `fk_immobilien_immobilien_arten1` FOREIGN KEY (`immobilien_art_id`) REFERENCES `immobilien_arten` (`immobilien_art_id`),
  ADD CONSTRAINT `fk_immobilien_mitarbeiter1` FOREIGN KEY (`mitarbeiter_id`) REFERENCES `mitarbeiter` (`mitarbeiter_id`),
  ADD CONSTRAINT `fk_immobilien_verkaufsarten` FOREIGN KEY (`verkaufsart_id`) REFERENCES `verkaufsarten` (`verkaufsart_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
