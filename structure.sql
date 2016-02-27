-- phpMyAdmin SQL Dump
-- version 2.11.6-rc1
-- http://www.phpmyadmin.net
--
-- Host: 188.130.180.22
-- Generation Time: Feb 06, 2012 at 01:11 PM
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

--
-- Table structure for table `ki_game_date`
--

CREATE TABLE IF NOT EXISTS `ki_game_date` (
  `game_date_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `begin` date NOT NULL,
  `time` int(11) NOT NULL,
  `hidden_flag` tinyint(4) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`game_date_id`),
  KEY `game_id` (`game_id`,`order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1790 ;

-- --------------------------------------------------------

--
-- Table structure for table `ki_photo`
--

CREATE TABLE IF NOT EXISTS `ki_photo` (
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `photo_author` varchar(40) DEFAULT NULL,
  `photo_uri` varchar(300) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `photo_comment` varchar(200) DEFAULT NULL,
  `photo_good_flag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`photo_id`),
  KEY `game_id` (`game_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=309 ;

-- --------------------------------------------------------

--
-- Table structure for table `ki_polygons`
--

CREATE TABLE IF NOT EXISTS `ki_polygons` (
  `polygon_id` int(11) NOT NULL AUTO_INCREMENT,
  `polygon_name` varchar(100) NOT NULL DEFAULT '',
  `sub_region_id` int(11) NOT NULL DEFAULT '0',
  `meta_polygon` int(11) NOT NULL DEFAULT '0',
  `deleted_flag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`polygon_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=237 ;

-- --------------------------------------------------------

--
-- Table structure for table `ki_review`
--

CREATE TABLE IF NOT EXISTS `ki_review` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `author_name` varchar(100) DEFAULT NULL,
  `topic_id` int(11) NOT NULL,
  `review_uri` varchar(200) DEFAULT NULL,
  `show_review_flag` tinyint(1) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`review_id`),
  KEY `game_id` (`game_id`),
  KEY `game_review` (`game_id`,`review_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=140 ;

-- --------------------------------------------------------

--
-- Table structure for table `ki_status`
--

CREATE TABLE IF NOT EXISTS `ki_status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(20) NOT NULL,
  `status_style` varchar(20) NOT NULL,
  `problem_status` tinyint(1) NOT NULL,
  `future_only_status` tinyint(1) NOT NULL,
  `cancelled_status` tinyint(4) NOT NULL DEFAULT '0',
  `show_review_flag` tinyint(4) NOT NULL DEFAULT '0',
  `show_date_flag` tinyint(1) NOT NULL DEFAULT '0',
  `good_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`status_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `ki_years_cache`
--

CREATE TABLE IF NOT EXISTS `ki_years_cache` (
  `year` smallint(6) NOT NULL DEFAULT '0',
  `region_id` smallint(6) NOT NULL DEFAULT '0',
  UNIQUE KEY `year` (`year`,`region_id`),
  KEY `sub_region_id` (`region_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `old_games`
--

CREATE TABLE IF NOT EXISTS `old_games` (
  `old_game_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_date` varchar(300) NOT NULL,
  `game_name` varchar(300) NOT NULL,
  `game_region` varchar(300) NOT NULL,
  `game_uri` varchar(300) NOT NULL,
  PRIMARY KEY (`old_game_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=553 ;

-- --------------------------------------------------------

--
-- Table structure for table `privs`
--

CREATE TABLE IF NOT EXISTS `privs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `desc` varchar(150) NOT NULL DEFAULT '',
  `hidden_flag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `lastvisit` datetime DEFAULT '0000-00-00 00:00:00',
  `create_date` date DEFAULT NULL,
  `editor_flag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=235 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_privs`
--

CREATE TABLE IF NOT EXISTS `user_privs` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
