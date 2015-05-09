-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 09, 2015 at 05:50 PM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `isit_v3-duhovka`
--

-- --------------------------------------------------------

--
-- Table structure for table `backup_schedules`
--

DROP TABLE IF EXISTS `backup_schedules`;
CREATE TABLE IF NOT EXISTS `backup_schedules` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `computer_id` int(5) NOT NULL,
  `nas_id` int(5) NOT NULL,
  `_day` int(2) NOT NULL,
  `_hour` int(2) NOT NULL,
  `_min` int(2) NOT NULL,
  PRIMARY KEY (`dbid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `device_id` int(5) NOT NULL,
  `device_folder` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `poznamka` text COLLATE utf8_czech_ci NOT NULL,
  `aktivni` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`dbid`,`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=777 ;

-- --------------------------------------------------------

--
-- Table structure for table `computers`
--

DROP TABLE IF EXISTS `computers`;
CREATE TABLE IF NOT EXISTS `computers` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `seriove_cislo` varchar(15) COLLATE utf8_czech_ci NOT NULL,
  `evidencni_cislo` varchar(15) COLLATE utf8_czech_ci NOT NULL,
  `model` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `datum_porizeni` varchar(10) COLLATE utf8_czech_ci DEFAULT NULL,
  `aktivni` int(1) NOT NULL DEFAULT '1',
  `pc_name` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `teamviewer` int(12) NOT NULL,
  `location` int(11) NOT NULL,
  PRIMARY KEY (`dbid`,`id`),
  UNIQUE KEY `seriove_cislo` (`seriove_cislo`,`evidencni_cislo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=382 ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `nadpis` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `zobrazit_od` varchar(10) COLLATE utf8_czech_ci DEFAULT NULL,
  `zobrazit_do` varchar(10) COLLATE utf8_czech_ci DEFAULT NULL,
  `aktivni` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`dbid`,`id`),
  UNIQUE KEY `nadpis` (`nadpis`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
CREATE TABLE IF NOT EXISTS `links` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `addr` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `popis` text COLLATE utf8_czech_ci NOT NULL,
  `aktivni` int(1) NOT NULL DEFAULT '1',
  `_name` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`dbid`,`id`),
  UNIQUE KEY `addr` (`addr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `persons`
--

DROP TABLE IF EXISTS `persons`;
CREATE TABLE IF NOT EXISTS `persons` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `osobni_cislo` varchar(15) COLLATE utf8_czech_ci NOT NULL,
  `pobocka` int(3) NOT NULL,
  `full_name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `login` varchar(25) COLLATE utf8_czech_ci NOT NULL,
  `aktivni` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`dbid`,`id`),
  UNIQUE KEY `osobni_cislo` (`osobni_cislo`,`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=200 ;

-- --------------------------------------------------------

--
-- Table structure for table `printers`
--

DROP TABLE IF EXISTS `printers`;
CREATE TABLE IF NOT EXISTS `printers` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `seriove_cislo` varchar(15) COLLATE utf8_czech_ci NOT NULL,
  `evidencni_cislo` varchar(15) COLLATE utf8_czech_ci NOT NULL,
  `model` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `datum_porizeni` varchar(10) COLLATE utf8_czech_ci DEFAULT NULL,
  `aktivni` int(1) NOT NULL DEFAULT '1',
  `ip` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `_mac` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`dbid`,`id`),
  UNIQUE KEY `seriove_cislo` (`seriove_cislo`,`evidencni_cislo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=184 ;

-- --------------------------------------------------------

--
-- Table structure for table `printer_uses`
--

DROP TABLE IF EXISTS `printer_uses`;
CREATE TABLE IF NOT EXISTS `printer_uses` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `printer_id` int(5) NOT NULL,
  `person_id` int(5) NOT NULL,
  `poznamka` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`dbid`,`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=301 ;

-- --------------------------------------------------------

--
-- Table structure for table `requirements`
--

DROP TABLE IF EXISTS `requirements`;
CREATE TABLE IF NOT EXISTS `requirements` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `obj_id` int(5) NOT NULL,
  `obj_folder` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `poznamka` text COLLATE utf8_czech_ci NOT NULL,
  `aktivni` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`dbid`,`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=22 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
