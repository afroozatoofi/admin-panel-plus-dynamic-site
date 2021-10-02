/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : 127.0.0.1:3306
Source Database       : clinicfa_clinic

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-01-29 18:25:04
*/

SET FOREIGN_KEY_CHECKS=0;

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
-- Records of resources
-- ----------------------------
INSERT INTO `resources` VALUES ('1', null, 'مدیریت کاربران', null, '1', '1', 'p3', '2', null);
INSERT INTO `resources` VALUES ('2', null, 'اطلاعات پایه', null, '2', '1', 'p4', '3', null);
INSERT INTO `resources` VALUES ('3', 'users/manageusers', 'کاربران', 'userslist', '3', '2', 'm10', '1', '1');
INSERT INTO `resources` VALUES ('4', 'groups/crud', 'گروه های کاربری', 'groups', '4', '2', 'm11', '2', '1');
INSERT INTO `resources` VALUES ('5', 'resources/crud', 'منابع', 'resources', '5', '2', 'm7', '1', '2');
INSERT INTO `resources` VALUES ('6', 'resource_policies/crud', 'دسترسی ها', 'policy', '6', '2', 'm12', '3', '1');
INSERT INTO `resources` VALUES ('7', 'clinics/crud', 'درمانگاه ها', 'clinic', '7', '2', 'm8', '4', '1');
INSERT INTO `resources` VALUES ('8', null, 'داشبورد من', null, '8', '1', 'p5', '1', null);
INSERT INTO `resources` VALUES ('9', 'messages/send', 'پیام رسان', 'message', '9', '2', 'm18', '1', '8');
INSERT INTO `resources` VALUES ('10', 'queue/grid', 'نوبت دهی', 'queue', '10', '2', 'm24', '2', '8');
INSERT INTO `resources` VALUES ('11', 'gallery/crud', 'گالری تصاویر', 'gallery', '11', '2', 'm19', '5', '8');
INSERT INTO `resources` VALUES ('12', 'news/crud', 'اخبار', 'news', '12', '2', 'm20', '4', '8');
INSERT INTO `resources` VALUES ('13', 'about_us/form', 'درباره ما', 'aboutus', '13', '2', 'm21', '8', '8');
INSERT INTO `resources` VALUES ('14', 'contact_us/form', 'تماس با ما', 'contactus', '14', '2', 'm22', '9', '8');
INSERT INTO `resources` VALUES ('15', 'slider/crud', 'اسلایدر', 'slider', '15', '2', 'm23', '6', '8');
INSERT INTO `resources` VALUES ('16', 'section/crud', 'بخش', 'section', '16', '2', 'm25', '7', '8');
INSERT INTO `resources` VALUES ('17', 'biography/crud', 'پزشکان', 'doctors', '17', '2', 'm26', '3', '8');
