-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 22. Nov 2022 um 19:29
-- Server-Version: 10.4.17-MariaDB
-- PHP-Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `webshop`
--
CREATE DATABASE IF NOT EXISTS `webshop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `webshop`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `w_artikel`
--

CREATE TABLE IF NOT EXISTS `w_artikel` (
  `artikelnummer` int(11) NOT NULL AUTO_INCREMENT,
  `artikelbezeichnung` varchar(255) NOT NULL,
  `farbe` varchar(50) NOT NULL,
  `groesse` varchar(10) NOT NULL,
  `preis` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `lieferbar_ab` date DEFAULT NULL,
  `bestellbar_ab` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`artikelnummer`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `w_artikel`
--

INSERT INTO `w_artikel` (`artikelnummer`, `artikelbezeichnung`, `farbe`, `groesse`, `preis`, `image`, `lieferbar_ab`, `bestellbar_ab`) VALUES
(1, 'Handy', 'schwarz', 'L', '499.00', 'uploadedFiles/handy.jpg', '2022-10-01', '2022-09-21 14:36:51'),
(3, 'Tisch', 'buche', '150', '1000.00', 'uploadedFiles/tisch.jpg', '2022-10-31', '2022-10-01 14:36:34'),
(4, 'Spiegel', 'gold', '200', '150.00', 'uploadedFiles/spiegel.jpg', NULL, NULL),
(5, 'Bild', 'bunt', '30', '30.00', 'uploadedFiles/bild.jpg', NULL, NULL),
(6, 'Teppich', 'blau', '250', '1670.00', 'uploadedFiles/teppich.jpg', NULL, NULL),
(7, 'Kühlschrank', 'silber', '300', '3420.00', 'uploadedFiles/kuelschrank.jpg', NULL, NULL),
(20, 'Kopfhörer', 'schwarz', 'L', '45.00', 'uploadedFiles/kopfhoerer.jpg', NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
