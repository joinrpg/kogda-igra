-- phpMyAdmin SQL Dump
-- version 2.11.6-rc1
-- http://www.phpmyadmin.net
--
-- Host: 188.130.180.22
-- Generation Time: Feb 06, 2012 at 01:12 PM
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
-- Dumping data for table `ki_status`
--

INSERT INTO `ki_status` (`status_id`, `status_name`, `status_style`, `problem_status`, `future_only_status`, `cancelled_status`, `show_review_flag`, `show_date_flag`, `good_status`) VALUES
(0, 'OK', 'status-ok', 0, 1, 0, 1, 1, 1),
(1, 'Прошла', 'status-finish', 0, 0, 0, 1, 1, 1),
(2, '???', 'status-unknown', 1, 0, 0, 0, 1, 0),
(3, 'Отложена', 'status-postponedd', 0, 0, 1, 0, 1, 0),
(4, 'Дата?', 'status-date', 1, 1, 0, 0, 0, 0),
(5, 'Отменена', 'status-canceled', 0, 0, 1, 0, 1, 0);

--
-- Dumping data for table `privs`
--

INSERT INTO `privs` (`id`, `name`, `desc`, `hidden_flag`) VALUES
(1, 'BASTILIA_NEWS', 'Новости на Бастилии', 1),
(2, 'USERS_CONTROL', 'Администратор пользователей', 0),
(3, 'VIEW_SPB_GAMES', 'Приватные игры в СПБ', 1),
(4, 'EDIT_GAMES', 'Редактор календаря', 0),
(5, 'EDIT_POLYGONS', 'Редактор полигонов', 0),
(6, 'PHOTO', 'Фотомодератор', 0),
(7, 'PHOTO_SELF', 'Доверенный фотограф', 0);
