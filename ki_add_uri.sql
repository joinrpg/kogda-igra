-- phpMyAdmin SQL Dump
-- version 2.11.6-rc1
-- http://www.phpmyadmin.net
--
-- Host: 188.130.180.22
-- Generation Time: Feb 16, 2012 at 02:08 PM
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
-- Table structure for table `ki_add_uri`
--

CREATE TABLE IF NOT EXISTS `ki_add_uri` (
  `add_uri_id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(300) DEFAULT NULL,
  `allrpg_info_id` int(11) DEFAULT NULL,
  `resolved` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`add_uri_id`),
  KEY `resolved` (`resolved`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
