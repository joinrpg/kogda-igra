-- phpMyAdmin SQL Dump
-- version 2.11.6-rc1
-- http://www.phpmyadmin.net
--
-- Host: 188.130.180.22
-- Generation Time: Feb 08, 2012 at 03:44 PM
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
-- Table structure for table `ki_updates`
--

CREATE TABLE IF NOT EXISTS `ki_updates` (
  `ki_update_id` int(11) NOT NULL AUTO_INCREMENT,
  `ki_update_type_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `game_id` int(11) DEFAULT NULL,
  `polygon_id` int(11) DEFAULT NULL,
  `photo_id` int(11) DEFAULT NULL,
  `updated_user_id` int(11) DEFAULT NULL,
  `msg` varchar(300) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ki_update_id`),
  KEY `update_date` (`update_date`,`game_id`),
  KEY `photo_id` (`photo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
