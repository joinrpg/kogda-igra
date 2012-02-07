-- phpMyAdmin SQL Dump
-- version 2.11.6-rc1
-- http://www.phpmyadmin.net
--
-- Host: 188.130.180.22
-- Generation Time: Feb 07, 2012 at 07:38 PM
-- Server version: 5.5.1
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_leotsar_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `ki_regions`
--

CREATE TABLE IF NOT EXISTS `ki_regions` (
  `region_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `region_name` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `region_code` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `region_experimental` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`region_id`),
  UNIQUE KEY `region_name` (`region_name`,`region_code`),
  KEY `region_experimental` (`region_experimental`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `ki_regions`
--

INSERT INTO `ki_regions` (`region_id`, `region_name`, `region_code`, `region_experimental`) VALUES
(1, 'Россия', '', 0),
(2, 'Северо-Запад', 'spb', 0),
(3, 'Москва и Центральный регион', 'msk', 0),
(5, 'Урал', 'ural', 0),
(6, 'Сибирь', 'sibir', 0),
(7, 'Южный федеральный округ', 'south', 0);
