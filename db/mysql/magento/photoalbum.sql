-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 24, 2012 at 04:30 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-----------------------------------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `sohyper_album` (
  `album_id` int(4) NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL,
  `album_name` varchar(255) NOT NULL,
  `photo_description` varchar(255) NOT NULL,
  `album_photo` varchar(255) NOT NULL,
  `insert_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`album_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

SELECT count(*) INTO @exist
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'magento' 
AND TABLE_NAME = 'sohyper_album' 
AND COLUMN_NAME = 'identity_id';

set @query = IF(@exist <= 0, 'ALTER TABLE `sohyper_album` ADD `identity_id` INT( 4 ) NOT NULL AFTER `user_id`', 
'select \'Column Exists\' status');

prepare stmt from @query;

EXECUTE stmt;

DROP TABLE IF EXISTS `sohyper_picture_albums`;

CREATE TABLE IF NOT EXISTS `sohyper_picture_albums` (
  `picture_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `operater_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`picture_id`),
  KEY `album_id` (`album_id`,`user_id`,`operater_id`),
  KEY `sohyper_picture_albums_ibfk_2` (`user_id`),
  CONSTRAINT `sohyper_picture_albums_ibfk_3` FOREIGN KEY (`album_id`) REFERENCES `sohyper_album` (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

SELECT count(*) INTO @exist1
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'magento' 
AND TABLE_NAME = 'sohyper_picture_albums' 
AND COLUMN_NAME = 'identity_id';

set @query1 = IF(@exist1 <= 0, 'ALTER TABLE `sohyper_picture_albums` ADD `identity_id` INT( 4 ) NOT NULL AFTER `user_id`', 
'select \'Column Exists\' status');

prepare stmt1 from @query1;

EXECUTE stmt1;