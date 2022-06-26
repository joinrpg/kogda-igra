-- phpMyAdmin SQL Dump
-- version 2.11.6-rc1
-- http://www.phpmyadmin.net
--
-- Host: 188.130.180.22
-- Generation Time: Feb 16, 2012 at 01:12 PM
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
-- Table structure for table `ki_game_types`
--

CREATE TABLE IF NOT EXISTS `ki_game_types` (
  `game_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_type_name` varchar(50) NOT NULL DEFAULT '',
  `show_all_regions` tinyint(4) NOT NULL DEFAULT '0',
  `game_type_style` varchar(50) NOT NULL DEFAULT '',
  `game_type_real_game` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`game_type_id`),
  KEY `show_all_regions` (`show_all_regions`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `ki_game_types`
--

INSERT INTO `ki_game_types` (`game_type_id`, `game_type_name`, `show_all_regions`, `game_type_style`, `game_type_real_game`) VALUES
(1, 'Полевая', 0, '', 1),
(2, 'Городская', 0, '', 1),
(3, 'На&nbsp;турбазе', 0, '', 1),
(4, 'Павильонная', 0, '', 0),
(5, 'Конвент', 1, '', 0),
(6, 'Бал', 0, '', 0),
(7, 'Маневры', 0, '', 0),
(8, 'Городская + Полевая', 0, '', 1),
(9, 'Городская + На турбазе', 0, '', 1),
(10, 'Турнир', 0, '', 0),
(11, 'Подземная', 0, '', 1),
(12, 'страйкбол', 0, '', 0);
