-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 10. Jun 2021 um 19:14
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
-- Datenbank: `agrar_webshop`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellpositionen`
--

CREATE TABLE `bestellpositionen` (
  `produkt_id` int(10) UNSIGNED NOT NULL,
  `bestellung_id` int(10) UNSIGNED NOT NULL,
  `menge` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `bestellpositionen`
--

INSERT INTO `bestellpositionen` (`produkt_id`, `bestellung_id`, `menge`) VALUES
(1, 1, 2),
(1, 5, 2),
(2, 1, 1),
(2, 3, 4),
(3, 1, 3),
(3, 4, 1),
(3, 5, 2),
(4, 2, 2),
(4, 4, 2),
(4, 6, 10),
(5, 2, 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellungen`
--

CREATE TABLE `bestellungen` (
  `bestellung_id` int(10) UNSIGNED NOT NULL,
  `kunde_id` int(10) UNSIGNED NOT NULL,
  `bestelldatum` datetime NOT NULL,
  `bestellstatus` set('eingegangen','geliefert','bezahlt') NOT NULL DEFAULT 'eingegangen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `bestellungen`
--

INSERT INTO `bestellungen` (`bestellung_id`, `kunde_id`, `bestelldatum`, `bestellstatus`) VALUES
(1, 1, '2021-02-12 12:00:00', 'bezahlt'),
(2, 1, '2021-03-01 14:00:00', 'bezahlt'),
(3, 1, '2021-06-08 19:00:00', 'eingegangen'),
(4, 2, '2021-04-02 21:00:00', 'bezahlt'),
(5, 2, '2021-06-05 11:00:00', 'geliefert'),
(6, 3, '2021-05-22 15:00:00', 'bezahlt');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kunden`
--

CREATE TABLE `kunden` (
  `kunde_id` int(10) UNSIGNED NOT NULL,
  `vorname` varchar(20) NOT NULL,
  `nachname` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefonnummer` varchar(20) NOT NULL,
  `strasse` varchar(50) NOT NULL,
  `ort_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `kunden`
--

INSERT INTO `kunden` (`kunde_id`, `vorname`, `nachname`, `email`, `telefonnummer`, `strasse`, `ort_id`) VALUES
(1, 'Max', 'Mustermann', 'max.mustermann@test.at', '1234', 'Musterstraße 1', 1),
(2, 'Susi', 'Musterfrau', 'susi.musterfrau@test.at', '2222', 'Teststraße 2', 2),
(3, 'Jane', 'Doe', 'jane.doe@test.at', '3333', 'Doe Street 3', 3),
(4, 'John', 'Doe', 'john.doe@test.at', '3333', 'Doe Street 3', 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `orte`
--

CREATE TABLE `orte` (
  `ort_id` int(10) UNSIGNED NOT NULL,
  `ort` varchar(45) NOT NULL,
  `plz` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `orte`
--

INSERT INTO `orte` (`ort_id`, `ort`, `plz`) VALUES
(1, 'Linz', '4020'),
(2, 'Wien', '1010'),
(3, 'Tirol', '6010'),
(4, 'Graz', '8010'),
(5, 'Klagenfurt', '9010');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `produkte`
--

CREATE TABLE `produkte` (
  `produkt_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `beschreibung` text DEFAULT NULL,
  `lagermenge` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `produktbild` varchar(255) DEFAULT NULL,
  `einkaufspreis` decimal(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `verkaufspreis` decimal(8,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `produkte`
--

INSERT INTO `produkte` (`produkt_id`, `name`, `beschreibung`, `lagermenge`, `produktbild`, `einkaufspreis`, `verkaufspreis`) VALUES
(1, '10 Stück Eier', NULL, 30, NULL, '0.00', '3.00'),
(2, 'Nudeln', NULL, 50, NULL, '1.50', '2.50'),
(3, '1L Milch', NULL, 20, NULL, '0.00', '1.50'),
(4, 'Speck', NULL, 25, NULL, '0.00', '7.50'),
(5, 'Honig', NULL, 15, NULL, '2.00', '3.00');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bestellpositionen`
--
ALTER TABLE `bestellpositionen`
  ADD PRIMARY KEY (`produkt_id`,`bestellung_id`),
  ADD KEY `fk_produkte_has_bestellungen_bestellungen1_idx` (`bestellung_id`),
  ADD KEY `fk_produkte_has_bestellungen_produkte1_idx` (`produkt_id`);

--
-- Indizes für die Tabelle `bestellungen`
--
ALTER TABLE `bestellungen`
  ADD PRIMARY KEY (`bestellung_id`),
  ADD KEY `fk_produkte_has_kunden_kunden1_idx` (`kunde_id`);

--
-- Indizes für die Tabelle `kunden`
--
ALTER TABLE `kunden`
  ADD PRIMARY KEY (`kunde_id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD KEY `fk_kunden_orte_idx` (`ort_id`);

--
-- Indizes für die Tabelle `orte`
--
ALTER TABLE `orte`
  ADD PRIMARY KEY (`ort_id`);

--
-- Indizes für die Tabelle `produkte`
--
ALTER TABLE `produkte`
  ADD PRIMARY KEY (`produkt_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `bestellungen`
--
ALTER TABLE `bestellungen`
  MODIFY `bestellung_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `kunden`
--
ALTER TABLE `kunden`
  MODIFY `kunde_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `orte`
--
ALTER TABLE `orte`
  MODIFY `ort_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `produkte`
--
ALTER TABLE `produkte`
  MODIFY `produkt_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bestellpositionen`
--
ALTER TABLE `bestellpositionen`
  ADD CONSTRAINT `fk_produkte_has_bestellungen_bestellungen1` FOREIGN KEY (`bestellung_id`) REFERENCES `bestellungen` (`bestellung_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produkte_has_bestellungen_produkte1` FOREIGN KEY (`produkt_id`) REFERENCES `produkte` (`produkt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `bestellungen`
--
ALTER TABLE `bestellungen`
  ADD CONSTRAINT `fk_produkte_has_kunden_kunden1` FOREIGN KEY (`kunde_id`) REFERENCES `kunden` (`kunde_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `kunden`
--
ALTER TABLE `kunden`
  ADD CONSTRAINT `fk_kunden_orte` FOREIGN KEY (`ort_id`) REFERENCES `orte` (`ort_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
