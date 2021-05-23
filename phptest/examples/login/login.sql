-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 15. Apr 2021 um 18:47
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
-- Datenbank: `login`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rolle`
--

CREATE TABLE `rolle` (
  `rolle_id` int(10) UNSIGNED NOT NULL,
  `rolle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `rolle`
--

INSERT INTO `rolle` (`rolle_id`, `rolle`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(75) NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `passwort` varchar(255) NOT NULL,
  `remote_ip` varchar(100) DEFAULT NULL,
  `aktiv` tinyint(4) NOT NULL DEFAULT 1,
  `login_versuche` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `gesperrt_am` timestamp NULL DEFAULT NULL,
  `rolle_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`user_id`, `name`, `email`, `last_login`, `passwort`, `remote_ip`, `aktiv`, `login_versuche`, `gesperrt_am`, `rolle_id`) VALUES
(1, 'Max Mustermann', 'max.mustermann@test.at', '2021-04-15 16:37:56', '$2y$10$NCyLQll6ts8JkgafmdsFnuEiVvUonHEM6/cKy5F85fnMjgJCRu0D.', '::1', 1, 0, NULL, 1),
(2, 'Susi Musterfrau', 'susi.musterfrau@test.at', NULL, '$2y$10$QfYBQpRJ/c5c1a4fG1liR.3wCNdupwGNtZquyn1wrSG3pdcobTDW.', NULL, 1, 0, NULL, 1),
(3, 'John Doe', 'john.doe@test.at', NULL, '$2y$10$q2KiZ0yVPx4aSGuEHrL4FueYt8JfaWZOViJUHlOI8wK0MBLkPd3tO', NULL, 1, 0, NULL, 1),
(4, 'Jane Doe', 'jane.doe@test.at', NULL, '$2y$10$9KbjKfVVr56LYIsxYUcNNeMLoLyPsmmidthluTToE/n89lhUYlUNy', NULL, 1, 0, NULL, 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `rolle`
--
ALTER TABLE `rolle`
  ADD PRIMARY KEY (`rolle_id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD KEY `fk_user_rolle_idx` (`rolle_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `rolle`
--
ALTER TABLE `rolle`
  MODIFY `rolle_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_rolle` FOREIGN KEY (`rolle_id`) REFERENCES `rolle` (`rolle_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
