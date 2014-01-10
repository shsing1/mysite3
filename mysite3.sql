-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- 主機: 127.0.0.1
-- 產生日期: 2014 年 01 月 10 日 11:43
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
-- 表的結構 `ci_meta_entity`
--

CREATE TABLE IF NOT EXISTS `ci_meta_entity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 轉存資料表中的資料 `ci_meta_entity`
--

INSERT INTO `ci_meta_entity` (`id`, `name`, `table_name`, `deleted`) VALUES
(1, 'entity', 'meta_entity', 0),
(2, 'property', 'meta_property', 0);

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
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 轉存資料表中的資料 `ci_meta_property`
--

INSERT INTO `ci_meta_property` (`id`, `parent_id`, `name`, `column_name`, `type_id`, `length`, `nullable`, `updatable`, `multilingual`, `deleted`) VALUES
(1, 1, 'id', '', 1, 10, 0, 0, 0, 0),
(2, 1, 'name', '', 2, 30, 0, 1, 0, 0),
(3, 1, 'table_name', '', 2, 30, 0, 1, 0, 0),
(4, 1, 'delete', '', 1, 1, 0, 0, 0, 0);

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
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `column_type` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 轉存資料表中的資料 `ci_meta_type`
--

INSERT INTO `ci_meta_type` (`id`, `name`, `length`, `class_name`, `formatter`, `deleted`, `column_type`) VALUES
(1, 'int', 10, 'int', '', 0, 'INT'),
(2, 'string', 255, 'string', '', 0, 'VARCHAR'),
(3, 'boolean', 1, 'boolean', '', 0, 'TINYINT'),
(4, 'double', 10, 'double', '', 0, 'DOUBLE'),
(5, 'text', 0, 'string', '', 0, 'TEXT'),
(6, 'url', 255, 'string', '', 0, 'VARCHAR'),
(7, 'email', 255, 'string', '', 0, 'VARCHAR');

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
('9f4b49ede83a31c881f7604acb1848c9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:26.0) Gecko/20100101 Firefox/26.0 FirePHP/0.7.4', 1389350446, ''),
('b28635fb590f6a7701814cb57835c73d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:26.0) Gecko/20100101 Firefox/26.0', 1389350104, ''),
('e83201ae05c5166b788e212222565708', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:26.0) Gecko/20100101 Firefox/26.0 FirePHP/0.7.4', 1389348632, '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
