-- phpMyAdmin SQL Dump
-- version 2.11.6-rc1
-- http://www.phpmyadmin.net
--
-- Host: 188.130.180.22
-- Generation Time: Apr 18, 2012 at 02:32 PM
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
-- Table structure for table `ki_games`
--

CREATE TABLE IF NOT EXISTS `ki_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `uri` varchar(100) NOT NULL DEFAULT '',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `polygon` int(4) NOT NULL DEFAULT '0',
  `mg` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `show_flags` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `comment` varchar(100) NOT NULL DEFAULT '',
  `sub_region_id` int(11) NOT NULL DEFAULT '0',
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  `hide_email` tinyint(4) NOT NULL DEFAULT '0',
  `players_count` int(11) DEFAULT NULL,
  `review_count` int(11) NOT NULL DEFAULT '0',
  `allrpg_info_id` int(11) DEFAULT NULL,
  `photo_count` int(11) NOT NULL DEFAULT '0',
  `redirect_id` int(11) DEFAULT NULL,
  `vk_likes` int(11) NOT NULL DEFAULT '0',
  `vk_club` varchar(40) DEFAULT NULL,
  `lj_comm` varchar(40) DEFAULT NULL,
  `fb_comm` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `polygon` (`polygon`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1914 ;
