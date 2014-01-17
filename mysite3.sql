-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- 主機: 127.0.0.1
-- 產生日期: 2014 年 01 月 17 日 11:35
-- 伺服器版本: 5.5.32
-- PHP 版本: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 資料庫: `mysite3`
--

-- --------------------------------------------------------

--
-- 表的結構 `ci_backend_menu`
--

CREATE TABLE IF NOT EXISTS `ci_backend_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned NOT NULL,
  `name__1` varchar(50) NOT NULL,
  `name__2` varchar(50) NOT NULL,
  `name__3` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 轉存資料表中的資料 `ci_backend_menu`
--

INSERT INTO `ci_backend_menu` (`id`, `sort`, `deleted`, `parent_id`, `name__1`, `name__2`, `name__3`, `url`) VALUES
(1, 1, 0, 0, '網站管理', 'backend', '网站管理', ''),
(2, 2, 0, 1, 'Metadata', 'Metadata', 'Metadata', ''),
(3, 3, 0, 2, '型別', 'Type', '型別', 'type'),
(4, 4, 0, 2, 'Entity', 'Entity', 'Entity', 'entity'),
(5, 5, 0, 2, '語系', 'Language', '语系', 'language'),
(6, 6, 0, 2, 'Option', 'Option', 'Option', 'option'),
(7, 7, 0, 0, '資料管理', 'Data Management', '资料管理', ''),
(8, 8, 0, 7, '後台選單', 'Backend Menu', '后台选单', 'backend_menu');

-- --------------------------------------------------------

--
-- 表的結構 `ci_base_language`
--

CREATE TABLE IF NOT EXISTS `ci_base_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `map` varchar(10) NOT NULL,
  `datepicker` varchar(10) NOT NULL,
  `editor` varchar(10) NOT NULL,
  `browser` varchar(10) NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `jqgrid` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 轉存資料表中的資料 `ci_base_language`
--

INSERT INTO `ci_base_language` (`id`, `name`, `map`, `datepicker`, `editor`, `browser`, `sort`, `deleted`, `jqgrid`) VALUES
(1, '繁', 'zh-TW', 'zh-TW', 'zh', 'zh-tw', 1, 0, 'tw'),
(2, 'EN', 'en', '', 'en', 'en-us', 2, 0, 'en'),
(3, '簡', 'zh-CN', 'zh-CN', 'zh-cn', 'zh-cn', 3, 0, 'cn');

-- --------------------------------------------------------

--
-- 表的結構 `ci_meta_entity`
--

CREATE TABLE IF NOT EXISTS `ci_meta_entity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 轉存資料表中的資料 `ci_meta_entity`
--

INSERT INTO `ci_meta_entity` (`id`, `name`, `table_name`, `sort`, `deleted`) VALUES
(1, 'entity', 'meta_entity', 1, 0),
(2, 'property', 'meta_property', 2, 0),
(3, 'type', 'meta_type', 3, 0),
(4, 'language', 'base_language', 4, 0),
(5, 'backend_menu', 'backend_menu', 5, 0);

-- --------------------------------------------------------

--
-- 表的結構 `ci_meta_property`
--

CREATE TABLE IF NOT EXISTS `ci_meta_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `column_name` varchar(50) DEFAULT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `length` int(10) DEFAULT NULL,
  `nullable` tinyint(1) NOT NULL,
  `updatable` tinyint(1) NOT NULL,
  `multilingual` tinyint(1) NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- 轉存資料表中的資料 `ci_meta_property`
--

INSERT INTO `ci_meta_property` (`id`, `parent_id`, `name`, `column_name`, `type_id`, `length`, `nullable`, `updatable`, `multilingual`, `sort`, `deleted`) VALUES
(1, 1, 'id', '', 1, 10, 0, 0, 0, 1, 0),
(2, 1, 'name', '', 2, 30, 0, 1, 0, 2, 0),
(3, 1, 'table_name', '', 2, 30, 0, 1, 0, 3, 0),
(4, 1, 'delete', '', 1, 1, 0, 0, 0, 4, 0),
(5, 1, 'sort', '', 1, 10, 0, 0, 0, 5, 0),
(6, 2, 'id', '', 1, 10, 0, 0, 0, 6, 0),
(7, 2, 'parent_id', '', 1, 10, 0, 0, 0, 7, 0),
(8, 2, 'name', '', 2, 50, 0, 1, 0, 8, 0),
(9, 2, 'column_name', '', 2, 50, 1, 1, 0, 9, 0),
(10, 2, 'type_id', '', 1, 10, 0, 1, 0, 10, 0),
(11, 2, 'length', '', 1, 10, 0, 1, 0, 11, 0),
(12, 2, 'nullable', '', 5, 1, 0, 1, 0, 12, 0),
(13, 2, 'updatable', '', 5, 1, 0, 1, 0, 13, 0),
(14, 2, 'multilingual', '', 5, 1, 0, 1, 0, 14, 0),
(15, 2, 'sort', '', 1, 10, 0, 0, 0, 15, 0),
(16, 2, 'deleted', '', 5, 1, 0, 0, 0, 16, 0),
(17, 3, 'id', '', 1, 10, 0, 0, 0, 17, 0),
(18, 3, 'name', '', 2, 30, 0, 1, 0, 18, 0),
(19, 3, 'length', '', 1, 10, 0, 1, 0, 19, 0),
(20, 3, 'class_name', '', 2, 50, 0, 1, 0, 20, 0),
(21, 3, 'formatter', '', 2, 50, 0, 1, 0, 21, 0),
(22, 3, 'sort', '', 1, 10, 0, 0, 0, 22, 0),
(23, 3, 'deleted', '', 5, 1, 0, 0, 0, 23, 0),
(24, 3, 'column_type', '', 2, 30, 0, 1, 0, 24, 0),
(25, 4, 'id', '', 1, 10, 0, 0, 0, 25, 0),
(26, 4, 'name', '', 2, 30, 0, 1, 0, 26, 0),
(27, 4, 'map', '', 2, 30, 0, 1, 0, 27, 0),
(28, 4, 'datepicker', '', 2, 30, 0, 1, 0, 28, 0),
(29, 4, 'editor', '', 2, 30, 0, 1, 0, 29, 0),
(30, 4, 'browser', '', 2, 30, 0, 1, 0, 30, 0),
(31, 4, 'sort', '', 1, 10, 0, 0, 0, 31, 0),
(32, 4, 'deleted', '', 5, 1, 0, 0, 0, 32, 0),
(33, 4, 'jqgrid', '', 2, 30, 0, 1, 0, 33, 0),
(34, 5, 'id', NULL, 1, 10, 0, 0, 0, 34, 0),
(35, 5, 'sort', NULL, 1, 10, 0, 0, 0, 35, 0),
(36, 5, 'deleted', NULL, 5, 10, 0, 0, 0, 36, 0),
(37, 5, 'parent_id', '', 1, 10, 1, 0, 0, 37, 0),
(38, 5, 'name', '', 2, 50, 0, 1, 1, 38, 0),
(39, 5, 'url', '', 6, 255, 1, 1, 0, 39, 0);

-- --------------------------------------------------------

--
-- 表的結構 `ci_meta_type`
--

CREATE TABLE IF NOT EXISTS `ci_meta_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `length` int(10) unsigned DEFAULT NULL,
  `class_name` varchar(80) NOT NULL,
  `formatter` varchar(1000) NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `column_type` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 轉存資料表中的資料 `ci_meta_type`
--

INSERT INTO `ci_meta_type` (`id`, `name`, `length`, `class_name`, `formatter`, `sort`, `deleted`, `column_type`) VALUES
(1, 'int', 10, 'int', '', 1, 0, 'INT'),
(2, 'string', 255, 'string', '', 2, 0, 'VARCHAR'),
(3, 'text', 0, 'string', '', 3, 0, 'TEXT'),
(4, 'date', 0, 'date', 'Y-m-d', 4, 0, 'DATE'),
(5, 'boolean', 1, 'boolean', '', 5, 0, 'TINYINT'),
(6, 'url', 255, 'string', '', 6, 0, 'VARCHAR');

-- --------------------------------------------------------

--
-- 表的結構 `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 轉存資料表中的資料 `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('2793260245adacbc3c305fe350457ff5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:26.0) Gecko/20100101 Firefox/26.0 FirePHP/0.7.4', 1389954718, 'a:2:{s:9:"user_data";s:0:"";s:7:"lang_id";s:1:"1";}'),
('bd7716af357d931b230880c682bc6009', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:26.0) Gecko/20100101 Firefox/26.0 FirePHP/0.7.4', 1389953443, 'a:2:{s:9:"user_data";s:0:"";s:7:"lang_id";s:1:"1";}');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
