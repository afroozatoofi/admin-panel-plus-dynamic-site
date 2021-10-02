/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : 127.0.0.1:3306
Source Database       : clinicfa_clinic

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-01-29 18:24:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for about_us
-- ----------------------------
DROP TABLE IF EXISTS `about_us`;
CREATE TABLE `about_us` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `summary` mediumtext NOT NULL,
  `description` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clinic_fk_4` (`clinic_id`) USING BTREE,
  CONSTRAINT `clinic_fk_4` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for biographies
-- ----------------------------
DROP TABLE IF EXISTS `biographies`;
CREATE TABLE `biographies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `detail` text NOT NULL,
  `image` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `clinic_fk` (`clinic_id`),
  CONSTRAINT `clinic_fk` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for clinics
-- ----------------------------
DROP TABLE IF EXISTS `clinics`;
CREATE TABLE `clinics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `englishName` varchar(64) NOT NULL,
  `image` varchar(64) DEFAULT NULL,
  `activity` text,
  `isEnabled` smallint(1) NOT NULL DEFAULT '1',
  `footerImage` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for contact_tels
-- ----------------------------
DROP TABLE IF EXISTS `contact_tels`;
CREATE TABLE `contact_tels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `telephone` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contact_fk` (`contact_id`,`telephone`) USING BTREE,
  CONSTRAINT `contact_fk` FOREIGN KEY (`contact_id`) REFERENCES `contact_us` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for contact_us
-- ----------------------------
DROP TABLE IF EXISTS `contact_us`;
CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `address` varchar(1024) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `longitude` double(18,15) DEFAULT NULL,
  `latitude` double(18,15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clinic_fk_5` (`clinic_id`) USING BTREE,
  CONSTRAINT `clinic_fk_5` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for gallery
-- ----------------------------
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `title` varchar(512) DEFAULT NULL,
  `image` varchar(64) NOT NULL,
  `isEnabled` smallint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `clinik_fk_5` (`clinic_id`),
  CONSTRAINT `clinik_fk_5` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for messages
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `text` mediumtext NOT NULL,
  `date` datetime NOT NULL,
  `jalaliDate` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `clinic_id` (`sender_id`),
  CONSTRAINT `clinic_id_3` FOREIGN KEY (`sender_id`) REFERENCES `clinics` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for message_clinics
-- ----------------------------
DROP TABLE IF EXISTS `message_clinics`;
CREATE TABLE `message_clinics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `message_clinic` (`message_id`,`receiver_id`) USING BTREE,
  KEY `clinic_fk_2` (`receiver_id`),
  CONSTRAINT `clinic_fk_2` FOREIGN KEY (`receiver_id`) REFERENCES `clinics` (`id`),
  CONSTRAINT `message_fk_2` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `summary` varchar(1024) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` mediumtext NOT NULL,
  `image` varchar(64) NOT NULL,
  `date` datetime NOT NULL,
  `jalaliDate` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `clinic_fk_6` (`clinic_id`),
  CONSTRAINT `clinic_fk_6` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for queue
-- ----------------------------
DROP TABLE IF EXISTS `queue`;
CREATE TABLE `queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `age` smallint(3) DEFAULT NULL,
  `sex` smallint(1) DEFAULT NULL,
  `desc` varchar(1024) DEFAULT NULL,
  `registerDate` varchar(20) DEFAULT NULL,
  `visitDate` varchar(20) DEFAULT NULL,
  `tracking` varchar(20) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `payment` smallint(1) NOT NULL DEFAULT '0',
  `section_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clinic_fk_7` (`clinic_id`),
  KEY `section_fk_1` (`section_id`),
  CONSTRAINT `clinic_fk_7` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`),
  CONSTRAINT `section_fk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for resources
-- ----------------------------
DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(50) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `englishName` varchar(50) DEFAULT NULL,
  `code` int(10) NOT NULL,
  `type` smallint(6) NOT NULL,
  `class` varchar(50) DEFAULT NULL,
  `index` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `englishName` (`englishName`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `resources` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for resource_policies
-- ----------------------------
DROP TABLE IF EXISTS `resource_policies`;
CREATE TABLE `resource_policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`),
  KEY `user_policies_ibfk_1` (`group_id`),
  CONSTRAINT `resource_policies_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resource_policies_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sections
-- ----------------------------
DROP TABLE IF EXISTS `sections`;
CREATE TABLE `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `index` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `clinic_id_8` (`clinic_id`),
  CONSTRAINT `clinic_id_8` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sliders
-- ----------------------------
DROP TABLE IF EXISTS `sliders`;
CREATE TABLE `sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL,
  `title` varchar(512) DEFAULT NULL,
  `image` varchar(64) NOT NULL,
  `isEnabled` smallint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `clinic_fk_8` (`clinic_id`),
  CONSTRAINT `clinic_fk_8` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `family` varchar(128) DEFAULT NULL,
  `clinic_id` int(11) NOT NULL,
  `isEnabled` smallint(1) NOT NULL DEFAULT '1',
  `isAdmin` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `company` (`clinic_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_groups
-- ----------------------------
DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `user_groups_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_groups_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
