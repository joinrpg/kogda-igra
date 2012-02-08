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

-- --------------------------------------------------------

--
-- Table structure for table `ki_correction`
--

CREATE TABLE IF NOT EXISTS `ki_correction` (
  `correction_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `post_date` datetime NOT NULL,
  `text` varchar(400) NOT NULL,
  `resolution` tinyint(4) NOT NULL,
  `editor_id` int(11) NOT NULL,
  PRIMARY KEY (`correction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ki_games`
--

CREATE TABLE IF NOT EXISTS `ki_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `uri` varchar(100) NOT NULL DEFAULT '',
  `begin_old` date NOT NULL DEFAULT '0000-00-00',
  `time_old` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `polygon` int(4) NOT NULL DEFAULT '0',
  `mg` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `show_flags` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `comment` varchar(100) NOT NULL DEFAULT '',
  `region_old` int(11) NOT NULL DEFAULT '1',
  `sub_region_id` int(11) NOT NULL DEFAULT '0',
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  `year_cache_old` int(11) NOT NULL DEFAULT '0',
  `hide_email` tinyint(4) NOT NULL DEFAULT '0',
  `players_count` int(11) DEFAULT NULL,
  `review_count` int(11) NOT NULL DEFAULT '0',
  `allrpg_info_id` int(11) DEFAULT NULL,
  `photo_count` int(11) NOT NULL DEFAULT '0',
  `redirect_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `polygon` (`polygon`),
  KEY `begin` (`begin_old`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1786 ;

-- --------------------------------------------------------

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

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
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `news_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `news_author` varchar(15) NOT NULL DEFAULT '',
  `news_header` varchar(80) NOT NULL DEFAULT '',
  `news_text` text NOT NULL,
  PRIMARY KEY (`news_id`),
  UNIQUE KEY `news_date` (`news_date`),
  FULLTEXT KEY `news_text` (`news_text`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 COMMENT='News information' AUTO_INCREMENT=317 ;

-- --------------------------------------------------------

--
-- Table structure for table `news_tags`
--

CREATE TABLE IF NOT EXISTS `news_tags` (
  `nid` int(11) NOT NULL DEFAULT '0',
  `tid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nid`,`tid`)
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
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(30) NOT NULL DEFAULT '',
  `tag_name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`uri`),
  UNIQUE KEY `name` (`tag_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=33 ;

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
