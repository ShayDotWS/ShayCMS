/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : habbo

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2023-11-22 05:31:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `real_name` varchar(25) NOT NULL DEFAULT 'KREWS DEV',
  `password` varchar(64) NOT NULL,
  `mail` varchar(500) DEFAULT NULL,
  `mail_verified` enum('0','1') NOT NULL DEFAULT '0',
  `account_created` int(11) NOT NULL,
  `account_day_of_birth` int(11) NOT NULL DEFAULT 0,
  `last_login` int(11) NOT NULL DEFAULT 0,
  `last_online` int(11) NOT NULL DEFAULT 0,
  `motto` varchar(127) NOT NULL DEFAULT '',
  `look` varchar(256) NOT NULL DEFAULT 'hr-115-42.hd-195-19.ch-3030-82.lg-275-1408.fa-1201.ca-1804-64',
  `gender` enum('M','F') NOT NULL DEFAULT 'M',
  `rank` int(11) NOT NULL DEFAULT 1,
  `credits` int(11) NOT NULL DEFAULT 2500,
  `pixels` int(11) NOT NULL DEFAULT 500,
  `points` int(11) NOT NULL DEFAULT 10,
  `online` enum('0','1','2') NOT NULL DEFAULT '0',
  `auth_ticket` varchar(256) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `ip_register` varchar(45) NOT NULL,
  `ip_current` varchar(45) NOT NULL COMMENT 'Have your CMS update this IP. If you do not do this IP banning won''t work!',
  `machine_id` varchar(64) NOT NULL DEFAULT '',
  `home_room` int(11) NOT NULL DEFAULT 0,
  `secret_key` varchar(40) DEFAULT NULL,
  `pincode` varchar(11) DEFAULT NULL,
  `extra_rank` int(11) DEFAULT NULL,
  `passwort` varchar(25) DEFAULT NULL,
  `ls_experience` int(11) NOT NULL DEFAULT 0,
  `ls_prefix_id` int(11) NOT NULL DEFAULT 0,
  `ls_name_color_id` int(11) NOT NULL DEFAULT 0,
  `ls_show_level` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `username` (`username`) USING BTREE,
  UNIQUE KEY `id` (`id`) USING BTREE,
  UNIQUE KEY `id_2` (`id`) USING BTREE,
  UNIQUE KEY `id_3` (`id`) USING BTREE,
  KEY `account_created` (`account_created`) USING BTREE,
  KEY `last_login` (`last_login`) USING BTREE,
  KEY `last_online` (`last_online`) USING BTREE,
  KEY `figure` (`motto`,`look`,`gender`) USING BTREE,
  KEY `auth_ticket` (`auth_ticket`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'Shaymi', '123', '', 'mail@hoster.de', '1', '1', '1', '1700626757', '1700626751', '', 'hd-209-1015.lg-280-1261.ch-215-1198.cp-3317-1408-1412.hr-841-45.sh-906-62.ea-3107-62-62.fa-1205-1325', 'F', '1', '0', '0', '0', '0', 'ShayCMS-2MW3ADTwe9fFjwLa', '127.0.0.1', '0:0:0:0:0:0:0:1', '', '0', null, null, null, '123', '0', '0', '0', '1');
INSERT INTO `users` VALUES ('2', 'yanik', 'KREWS DEV', '$2y$10$EJrE7TWjaruOjOcivH7SY.HU3pJ.lL6jmhmtQWMCAt2rNeyEzuv7a', null, '0', '0', '0', '0', '0', '', 'hr-115-42.hd-195-19.ch-3030-82.lg-275-1408.fa-1201.ca-1804-64', 'M', '1', '2500', '500', '10', '0', '', '', '', '', '0', null, null, null, null, '0', '0', '0', '1');
