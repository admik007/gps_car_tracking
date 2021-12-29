-- phpMyAdmin SQL Dump
-- version 4.6.6deb4+deb9u2
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost:3306
-- Čas generovania: St 29.Dec 2021, 11:22
-- Verzia serveru: 10.3.31-MariaDB-0+deb10u1
-- Verzia PHP: 5.6.40-0+deb8u12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `ztk_comp_sk`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `gps_miesto`
--

CREATE TABLE `gps_miesto` (
  `lat` varchar(14) NOT NULL,
  `lon` varchar(14) NOT NULL,
  `miesto` text DEFAULT NULL,
  `status` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `gps_provider`
--

CREATE TABLE `gps_provider` (
  `ip` varchar(15) NOT NULL,
  `provider` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `gps_tracking`
--

CREATE TABLE `gps_tracking` (
  `id` int(8) NOT NULL,
  `lat` varchar(14) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `lon` varchar(14) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `alt` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `acc` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `spd` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `sat` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `time` varchar(26) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `bat` varchar(5) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `ip` varchar(15) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `year` varchar(4) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `month` char(2) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `day` char(2) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `hour` char(2) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `minute` char(2) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `second` char(2) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `device` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `provider` char(10) CHARACTER SET latin1 NOT NULL,
  `direction` char(10) CHARACTER SET latin1 NOT NULL,
  `devicerpi` char(16) CHARACTER SET latin1 NOT NULL,
  `temprpi` char(10) CHARACTER SET latin1 NOT NULL,
  `loadrpi` char(11) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `gps_tracking_archive`
--

CREATE TABLE `gps_tracking_archive` (
  `id` int(8) NOT NULL DEFAULT 0,
  `lat` varchar(14) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `lon` varchar(14) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `alt` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `acc` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `spd` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `sat` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `time` varchar(26) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `bat` varchar(5) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `ip` varchar(15) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `year` varchar(4) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `month` char(2) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `day` char(2) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `hour` char(2) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `minute` char(2) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `second` char(2) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `device` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `provider` char(10) CHARACTER SET latin1 NOT NULL,
  `direction` char(10) CHARACTER SET latin1 NOT NULL,
  `devicerpi` char(16) CHARACTER SET latin1 NOT NULL,
  `temprpi` char(10) CHARACTER SET latin1 NOT NULL,
  `loadrpi` char(11) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `gps_tracking`
--
ALTER TABLE `gps_tracking`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `gps_tracking`
--
ALTER TABLE `gps_tracking`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22550627;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
