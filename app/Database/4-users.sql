/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : 127.0.0.1:3306
Source Database       : clinicfa_clinic

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-01-29 18:24:53
*/

SET FOREIGN_KEY_CHECKS=0;

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
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', '$2a$07$3F4J3k84JKdlwdmkor983uQYo6GpX8SjF2RIy9SXbYoBoq1SGq1Oi', 'مهرافروز', 'عطوفی', '3', '1', '1');
