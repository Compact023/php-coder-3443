-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 06. Apr 2021 um 17:03
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
-- Datenbank: `buecherei`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `autor`
--

CREATE TABLE `autor` (
  `autor_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `autor`
--

INSERT INTO `autor` (`autor_id`, `name`) VALUES
(1, 'J.R.R Tolkien'),
(2, 'Musterautor');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `buch`
--

CREATE TABLE `buch` (
  `buch_id` int(10) UNSIGNED NOT NULL,
  `isbn` varchar(25) NOT NULL,
  `buch_titel` varchar(255) NOT NULL,
  `kurzbeschreibung` text DEFAULT NULL,
  `erscheinungsdatum` date DEFAULT NULL,
  `verlag_id` int(10) UNSIGNED DEFAULT NULL,
  `kategorie_id` int(10) UNSIGNED DEFAULT NULL,
  `autor_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `buch`
--

INSERT INTO `buch` (`buch_id`, `isbn`, `buch_titel`, `kurzbeschreibung`, `erscheinungsdatum`, `verlag_id`, `kategorie_id`, `autor_id`) VALUES
(1, '11111111111', 'Der Herr der Ringe - Die Gefährten', NULL, '2000-01-01', 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kategorie`
--

CREATE TABLE `kategorie` (
  `kategorie_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `kategorie`
--

INSERT INTO `kategorie` (`kategorie_id`, `name`) VALUES
(1, 'Fantasy'),
(2, 'Krimi'),
(3, 'Sachbuch'),
(4, 'Roman');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schueler`
--

CREATE TABLE `schueler` (
  `schueler_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `schueler`
--

INSERT INTO `schueler` (`schueler_id`, `name`) VALUES
(1, 'Max Mustermann'),
(2, 'Susi Musterfrau');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `verlag`
--

CREATE TABLE `verlag` (
  `verlag_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `verlag`
--

INSERT INTO `verlag` (`verlag_id`, `name`) VALUES
(1, 'XY'),
(2, 'ZZ');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `verleih`
--

CREATE TABLE `verleih` (
  `verleih_id` int(10) UNSIGNED NOT NULL,
  `buch_id` int(10) UNSIGNED NOT NULL,
  `schueler_id` int(10) UNSIGNED NOT NULL,
  `ausleihdatum` datetime NOT NULL DEFAULT current_timestamp(),
  `rueckgabedatum` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `verleih`
--

INSERT INTO `verleih` (`verleih_id`, `buch_id`, `schueler_id`, `ausleihdatum`, `rueckgabedatum`) VALUES
(1, 1, 1, '2021-01-01 18:00:00', '2021-01-31 18:39:58'),
(2, 1, 2, '2021-02-02 18:39:58', NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`autor_id`);

--
-- Indizes für die Tabelle `buch`
--
ALTER TABLE `buch`
  ADD PRIMARY KEY (`buch_id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `verlag_id` (`verlag_id`),
  ADD KEY `kategorie_id` (`kategorie_id`,`autor_id`),
  ADD KEY `autor_id` (`autor_id`);

--
-- Indizes für die Tabelle `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`kategorie_id`);

--
-- Indizes für die Tabelle `schueler`
--
ALTER TABLE `schueler`
  ADD PRIMARY KEY (`schueler_id`);

--
-- Indizes für die Tabelle `verlag`
--
ALTER TABLE `verlag`
  ADD PRIMARY KEY (`verlag_id`);

--
-- Indizes für die Tabelle `verleih`
--
ALTER TABLE `verleih`
  ADD PRIMARY KEY (`verleih_id`),
  ADD KEY `buch_id` (`buch_id`,`schueler_id`),
  ADD KEY `schueler_id` (`schueler_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `autor`
--
ALTER TABLE `autor`
  MODIFY `autor_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `buch`
--
ALTER TABLE `buch`
  MODIFY `buch_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `kategorie_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `schueler`
--
ALTER TABLE `schueler`
  MODIFY `schueler_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `verlag`
--
ALTER TABLE `verlag`
  MODIFY `verlag_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `verleih`
--
ALTER TABLE `verleih`
  MODIFY `verleih_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `buch`
--
ALTER TABLE `buch`
  ADD CONSTRAINT `buch_ibfk_1` FOREIGN KEY (`verlag_id`) REFERENCES `verlag` (`verlag_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `buch_ibfk_2` FOREIGN KEY (`kategorie_id`) REFERENCES `kategorie` (`kategorie_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `buch_ibfk_3` FOREIGN KEY (`autor_id`) REFERENCES `autor` (`autor_id`) ON DELETE SET NULL;

--
-- Constraints der Tabelle `verleih`
--
ALTER TABLE `verleih`
  ADD CONSTRAINT `verleih_ibfk_1` FOREIGN KEY (`buch_id`) REFERENCES `buch` (`buch_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `verleih_ibfk_2` FOREIGN KEY (`schueler_id`) REFERENCES `schueler` (`schueler_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
