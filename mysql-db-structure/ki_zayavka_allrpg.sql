-- phpMyAdmin SQL Dump
-- version 2.11.6-rc1
-- http://www.phpmyadmin.net
--
-- Host: 188.130.180.22
-- Generation Time: Apr 11, 2012 at 10:03 PM
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
-- Table structure for table `ki_zayavka_allrpg`
--

CREATE TABLE IF NOT EXISTS `ki_zayavka_allrpg` (
  `allrpg_zayvka_id` int(11) NOT NULL,
  `game_id` int(11) DEFAULT NULL,
  `name` varchar(500) NOT NULL,
  `opened` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`allrpg_zayvka_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
