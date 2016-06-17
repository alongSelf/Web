/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50539
Source Host           : localhost:3306
Source Database       : shop

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2016-06-17 17:56:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `category`
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(50) DEFAULT '' COMMENT '//名称',
  `sort` tinyint(4) DEFAULT '0' COMMENT '排序规则',
  `display` tinyint(4) DEFAULT '0' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('1', '分类1', '1', '1');
INSERT INTO `category` VALUES ('2', '分类2', '2', '1');
INSERT INTO `category` VALUES ('3', '分类3', '3', '1');
INSERT INTO `category` VALUES ('4', '分类4', '4', '1');
INSERT INTO `category` VALUES ('5', '分类5', '5', '1');
