/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : 127.0.0.1:3306
Source Database       : clinicfa_clinic

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-01-29 18:26:40
*/

SET FOREIGN_KEY_CHECKS=0;

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
-- Records of clinics
-- ----------------------------
INSERT INTO `clinics` VALUES ('1', 'داروطب', 'darooteb', '', '', '1', '');
INSERT INTO `clinics` VALUES ('2', 'بهار', 'bahar', '', '', '1', '');
INSERT INTO `clinics` VALUES ('3', 'اقبال', 'eghbal', '', '', '1', '');
