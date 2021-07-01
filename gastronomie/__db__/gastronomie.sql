-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 01. Jul 2021 um 19:19
-- Server-Version: 10.4.14-MariaDB
-- PHP-Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `gastronomie`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `registrierungen`
--

CREATE TABLE `registrierungen` (
  `registrierung_id` int(10) UNSIGNED NOT NULL,
  `vorname` varchar(25) NOT NULL,
  `nachname` varchar(25) NOT NULL,
  `email` varchar(75) NOT NULL,
  `telefon` varchar(25) NOT NULL,
  `tischnummer` int(10) UNSIGNED NOT NULL,
  `strasse` varchar(75) DEFAULT NULL,
  `plz` varchar(10) DEFAULT NULL,
  `ort` varchar(75) DEFAULT NULL,
  `registrierungszeitpunkt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `registrierungen`
--
ALTER TABLE `registrierungen`
  ADD PRIMARY KEY (`registrierung_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `registrierungen`
--
ALTER TABLE `registrierungen`
  MODIFY `registrierung_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
