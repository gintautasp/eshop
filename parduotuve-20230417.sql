-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2023 at 08:56 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parduotuve`
--

-- --------------------------------------------------------

--
-- Table structure for table `padaliniai`
--

CREATE TABLE `padaliniai` (
  `id` int(10) UNSIGNED NOT NULL,
  `pav` varchar(256) NOT NULL,
  `adresas` varchar(256) NOT NULL,
  `direktorius` varchar(256) NOT NULL,
  `skaicius_dar` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prekes`
--

CREATE TABLE `prekes` (
  `id` int(10) UNSIGNED NOT NULL,
  `pav` varchar(256) NOT NULL,
  `kaina` decimal(12,2) NOT NULL,
  `paveiksl` varchar(256) NOT NULL,
  `kiekis` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prekes`
--

INSERT INTO `prekes` (`id`, `pav`, `kaina`, `paveiksl`, `kiekis`) VALUES
(1, 'Gvazdikai', '10.00', 'Gėlių-puokštė-Gėlių-pristatymas-Vilniuje-Flower-bouquet-Flower-delivery-service-in-Vilnius-Beatričės-Gėlių-Namai-18-450x450.jpg', 0),
(2, 'Rožės', '15.00', 'klasika-raudonos-rozes-324-1.jpg', 0),
(3, 'Puokštė', '8.00', 'puokste.jpg', 0),
(4, 'Skirtingų rušių gėlės', '7.00', 'skirtingos.jpg', 0),
(5, 'Tulpės', '12.00', 'tulpes.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `uzsakovai`
--

CREATE TABLE `uzsakovai` (
  `id` int(10) UNSIGNED NOT NULL,
  `pav` varchar(255) NOT NULL,
  `adresas` varchar(255) NOT NULL,
  `telefonai` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uzsakovai`
--

INSERT INTO `uzsakovai` (`id`, `pav`, `adresas`, `telefonai`, `email`) VALUES
(1, 'Jonas Jonaitis', 'Pamiškes 8', '86342897', 'spokas40@gmail.com'),
(2, 'Petras Petraitis', 'Šiaurės 22b', '86342899', 'shplintas@gmail.com'),
(3, 'Super kuprinė', 'Selenu 18, Vilnius', '855555555', 'aldas.maciulis@gmail.com'),
(4, 'Gintautas', 'Plento 10c-12', '8456511255', 'test@gmail.com'),
(5, 'Gintautas', 'Namai 1', '8456511255', 'g.petkevicius@inbox.lt');

-- --------------------------------------------------------

--
-- Table structure for table `uzsakymai`
--

CREATE TABLE `uzsakymai` (
  `id` int(10) UNSIGNED NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_uzsakovo` int(10) UNSIGNED NOT NULL,
  `adresas_pristatymo` varchar(255) NOT NULL,
  `busena` enum('priimtas','renkamas','surinktas','pristatomas','pristatytas') NOT NULL DEFAULT 'priimtas',
  `kaina_pristatymo` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uzsakymai`
--

INSERT INTO `uzsakymai` (`id`, `data`, `id_uzsakovo`, `adresas_pristatymo`, `busena`, `kaina_pristatymo`) VALUES
(1, '2022-04-05 07:28:02', 1, 'Pamiškes 8', 'pristatytas', '0.00'),
(2, '2022-04-05 08:01:52', 2, 'Šiaurės 22b', 'priimtas', '0.00'),
(3, '2022-04-05 08:17:33', 3, 'Selenu 18, Vilnius', 'priimtas', '0.00'),
(4, '2023-02-21 09:26:31', 4, 'Plento 10c-12', 'priimtas', '0.00'),
(5, '2023-04-17 06:23:25', 5, 'Namai 1', 'priimtas', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `uzsakymai_prekes`
--

CREATE TABLE `uzsakymai_prekes` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_uzsakymo` int(10) UNSIGNED NOT NULL,
  `id_prekes` int(10) UNSIGNED NOT NULL,
  `kaina_prekes` decimal(12,2) NOT NULL,
  `kiekis` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uzsakymai_prekes`
--

INSERT INTO `uzsakymai_prekes` (`id`, `id_uzsakymo`, `id_prekes`, `kaina_prekes`, `kiekis`) VALUES
(1, 1, 3, '8.00', 2),
(2, 1, 4, '7.00', 3),
(3, 1, 5, '12.00', 1),
(4, 2, 1, '10.00', 4),
(5, 2, 2, '15.00', 2),
(6, 2, 4, '7.00', 3),
(7, 2, 5, '12.00', 3),
(8, 3, 2, '15.00', 2),
(9, 3, 3, '8.00', 3),
(10, 4, 2, '15.00', 5),
(11, 4, 3, '8.00', 3),
(12, 5, 2, '15.00', 3),
(13, 5, 4, '7.00', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `padaliniai`
--
ALTER TABLE `padaliniai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prekes`
--
ALTER TABLE `prekes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uzsakovai`
--
ALTER TABLE `uzsakovai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uzsakymai`
--
ALTER TABLE `uzsakymai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_uzsakovo` (`id_uzsakovo`);

--
-- Indexes for table `uzsakymai_prekes`
--
ALTER TABLE `uzsakymai_prekes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uzsakymai_prekes_ibfk_1` (`id_prekes`),
  ADD KEY `uzsakymai_prekes_ibfk_2` (`id_uzsakymo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `padaliniai`
--
ALTER TABLE `padaliniai`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prekes`
--
ALTER TABLE `prekes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `uzsakovai`
--
ALTER TABLE `uzsakovai`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `uzsakymai`
--
ALTER TABLE `uzsakymai`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `uzsakymai_prekes`
--
ALTER TABLE `uzsakymai_prekes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `uzsakymai`
--
ALTER TABLE `uzsakymai`
  ADD CONSTRAINT `uzsakymai_ibfk_1` FOREIGN KEY (`id_uzsakovo`) REFERENCES `uzsakovai` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `uzsakymai_prekes`
--
ALTER TABLE `uzsakymai_prekes`
  ADD CONSTRAINT `uzsakymai_prekes_ibfk_1` FOREIGN KEY (`id_prekes`) REFERENCES `prekes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `uzsakymai_prekes_ibfk_2` FOREIGN KEY (`id_uzsakymo`) REFERENCES `uzsakymai` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
