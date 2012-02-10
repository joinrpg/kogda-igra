-- phpMyAdmin SQL Dump
-- version 2.11.6-rc1
-- http://www.phpmyadmin.net
--
-- Host: 188.130.180.22
-- Generation Time: Feb 10, 2012 at 05:33 PM
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
-- Table structure for table `ki_update_types`
--

CREATE TABLE IF NOT EXISTS `ki_update_types` (
  `ki_update_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `ki_update_type_name` varchar(50) NOT NULL DEFAULT '',
  `update_type_polygon_flag` tinyint(4) NOT NULL DEFAULT '0',
  `update_type_game_flag` tinyint(4) NOT NULL DEFAULT '0',
  `update_type_photo_flag` tinyint(4) NOT NULL DEFAULT '0',
  `update_type_review_flag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ki_update_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `ki_update_types`
--

INSERT INTO `ki_update_types` (`ki_update_type_id`, `ki_update_type_name`, `update_type_polygon_flag`, `update_type_game_flag`, `update_type_photo_flag`, `update_type_review_flag`) VALUES
(1, 'Добавлена новая игра', 0, 1, 0, 0),
(2, 'Изменено описание игры', 0, 1, 0, 0),
(3, 'Игра удалена из календаря', 0, 1, 0, 0),
(5, 'Изменена дата игры', 0, 1, 0, 0),
(6, 'Добавлена новость', 0, 0, 0, 0),
(7, 'Игра восстановлена в календаре', 0, 1, 0, 0),
(8, 'У игры изменился статус', 0, 1, 0, 0),
(9, 'Удален полигон', 1, 0, 0, 0),
(10, 'Добавлен новый полигон', 1, 0, 0, 0),
(11, 'Полигон переименован', 1, 0, 0, 0),
(12, 'Полигон восстановлен', 1, 0, 0, 0),
(13, 'Добавлена новая рецензия', 0, 1, 0, 1),
(14, 'Удалена рецензия', 0, 1, 0, 1),
(15, 'Добавлен фотоотчет', 0, 1, 1, 0),
(16, 'Изменен фотоотчет', 0, 1, 1, 0),
(17, 'Удален фотоотчет', 0, 1, 1, 0),
(18, 'Автор ассоциирован с ЖЖ', 0, 0, 0, 0),
(19, 'Игра добавлена пользователем', 0, 1, 0, 0),
(20, 'Игра проверена редактором', 0, 1, 0, 0),
(21, 'Рецензия восстановлена', 0, 1, 0, 1);
