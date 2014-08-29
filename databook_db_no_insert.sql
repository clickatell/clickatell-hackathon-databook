-- Adminer 3.7.1 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '+02:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `firstname` text NOT NULL,
  `surname` text NOT NULL,
  `title` text NOT NULL,
  `birthdate` text NOT NULL,
  `mobile` text NOT NULL,
  `email` text NOT NULL,
  `gender` text NOT NULL,
  `profession` text NOT NULL,
  `location` text NOT NULL,
  `tags` text NOT NULL,
  `custom1` text NOT NULL,
  `custom2` text NOT NULL,
  `custom3` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `API_key` char(40) NOT NULL,
  `API_hits` int(11) unsigned NOT NULL,
  `API_hit_date` varchar(30) NOT NULL,
  `user` varchar(40) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
