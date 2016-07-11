/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50539
Source Host           : localhost:3306
Source Database       : shop

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2016-07-12 01:37:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `adminuser`
-- ----------------------------
DROP TABLE IF EXISTS `adminuser`;
CREATE TABLE `adminuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(64) DEFAULT '' COMMENT '用户名',
  `user_pass` varchar(256) DEFAULT '' COMMENT '密码',
  `errortime` int(11) DEFAULT '0' COMMENT '登录验证错误时间',
  `errorcount` int(11) DEFAULT '0' COMMENT '登录验证错误次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of adminuser
-- ----------------------------
INSERT INTO `adminuser` VALUES ('1', 'admin', 'eyJpdiI6ImVEQUtQNXd5eEZSeEo1Q1VpYllTV2c9PSIsInZhbHVlIjoidzI0Q1FEWmlEQmlYSk9PMFlCdXJVdz09IiwibWFjIjoiNGY0YzFhMTM4ZjVjNTZkYjBhNTFmMzI4ZTJlODdiMWNmZGYyNzkyYzE3NmUzNGI4NGNlNDliOWI4NzE1MTE0ZCJ9', '0', '0');

-- ----------------------------
-- Table structure for `category`
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(50) DEFAULT '' COMMENT '//名称',
  `img` varchar(255) DEFAULT '' COMMENT '图标',
  `describe` varchar(255) DEFAULT '',
  `sort` tinyint(4) DEFAULT '0' COMMENT '排序规则',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('17', '工口小学生', '577fa4544e9c9.jpg', '工口小学生图片', '1');

-- ----------------------------
-- Table structure for `config`
-- ----------------------------
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of config
-- ----------------------------
INSERT INTO `config` VALUES ('2', '微信商城');

-- ----------------------------
-- Table structure for `evaluates`
-- ----------------------------
DROP TABLE IF EXISTS `evaluates`;
CREATE TABLE `evaluates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` int(11) DEFAULT '0' COMMENT '物品ID',
  `star` int(11) DEFAULT '5' COMMENT '评分',
  `evaluate` varchar(255) DEFAULT '' COMMENT '评价',
  `userid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0' COMMENT '订单ID',
  `createtime` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of evaluates
-- ----------------------------
INSERT INTO `evaluates` VALUES ('1', '23', '5', '点评1111111', '2', '0', '1138618081');
INSERT INTO `evaluates` VALUES ('2', '23', '5', 'xzxzxzx', '0', '0', '1138628081');
INSERT INTO `evaluates` VALUES ('3', '23', '5', 'casdfasdf', '0', '0', '1138638081');
INSERT INTO `evaluates` VALUES ('4', '23', '5', 'zcvzczxczbhjh', '0', '0', '1138648081');
INSERT INTO `evaluates` VALUES ('5', '23', '5', 'fffgggg', '0', '0', '1138658081');

-- ----------------------------
-- Table structure for `notice`
-- ----------------------------
DROP TABLE IF EXISTS `notice`;
CREATE TABLE `notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notice` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notice
-- ----------------------------
INSERT INTO `notice` VALUES ('1', '公告滚出来');

-- ----------------------------
-- Table structure for `orders`
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of orders
-- ----------------------------

-- ----------------------------
-- Table structure for `shopitem`
-- ----------------------------
DROP TABLE IF EXISTS `shopitem`;
CREATE TABLE `shopitem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '' COMMENT '名称',
  `category` int(11) DEFAULT '0' COMMENT '类别ID',
  `content` text COMMENT '商品图集',
  `describe` varchar(255) DEFAULT NULL COMMENT '描述',
  `prime_price` float(11,0) DEFAULT '0' COMMENT '原价',
  `cur_price` float(11,0) DEFAULT '0' COMMENT '现价',
  `stock` int(11) DEFAULT '-1' COMMENT '库存 -1 无限制',
  `buynum` int(11) DEFAULT NULL COMMENT '购买人数',
  `indeximg` varchar(255) DEFAULT '' COMMENT '主图片',
  `showimg` text COMMENT '展示图片',
  `spec` text COMMENT '规格',
  `activity` tinyint(4) DEFAULT '0' COMMENT '活动',
  `showindex` tinyint(4) DEFAULT '0' COMMENT '是否在主页显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shopitem
-- ----------------------------
INSERT INTO `shopitem` VALUES ('23', '工口小学生1工口小学生1工口小学生1工口小学生1工口小学生1', '17', '<p style=\"text-align: center;\"><img src=\"/ueditor/php/upload/image/20160708/1467983004119155.jpg\" title=\"1467983004119155.jpg\" alt=\"007.jpg\"/></p>', '工口小学生1', '130', '120', '-1', '13', '577fa476146c8.jpg', '[\"577fa49526870.jpg\",\"577fa49558174.jpg\",\"577fa4958292e.jpg\"]', '{\"颜色\":[\"红\",\"黄\"]}', '1', '1');
INSERT INTO `shopitem` VALUES ('24', '工口小学生2', '17', '<p><img src=\"/ueditor/php/upload/image/20160708/1467983058208704.jpg\" title=\"1467983058208704.jpg\" alt=\"015.jpg\"/></p>', '工口小学生2', '456', '450', '-1', '45', '577fa4ab72a8d.jpg', '[\"577fa4c976003.jpg\",\"577fa4c99e87d.jpg\",\"577fa4c9cf5c8.jpg\"]', '{\"尺寸\":[\"M\",\"L\"]}', '1', '1');
INSERT INTO `shopitem` VALUES ('25', '工口小学生3', '17', '<p><img src=\"/ueditor/php/upload/image/20160708/1467983099289173.jpg\" title=\"1467983099289173.jpg\" alt=\"022.jpg\"/></p>', '工口小学生3', '892', '800', '-1', '1', '577fa4df35a56.jpg', '[\"577fa4f291771.jpg\",\"577fa4f2c0964.jpg\",\"577fa4f2ea17e.jpg\"]', '', '1', '1');
INSERT INTO `shopitem` VALUES ('26', '工口小学生4', '17', '<p><img src=\"/ueditor/php/upload/image/20160708/1467983151346104.jpg\" title=\"1467983151346104.jpg\" alt=\"067.jpg\"/></p>', '工口小学生4', '800', '580', '-1', '678', '577fa50a84370.jpg', '[\"577fa522eea4c.jpg\",\"577fa52322c9d.jpg\",\"577fa52348e06.jpg\"]', '', '1', '1');
INSERT INTO `shopitem` VALUES ('27', '小学生5', '17', '<p><img src=\"/ueditor/php/upload/image/20160709/1468077714106804.jpg\" title=\"1468077714106804.jpg\" alt=\"0149.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20160709/1468077728252295.jpg\" title=\"1468077728252295.jpg\" alt=\"053.jpg\"/></p>', '小学生5', '580', '500', '-1', '1001', '578116687ed5c.jpg', '[\"5781168155a8f.jpg\",\"5781168180e01.jpg\",\"57811681a7a58.jpg\"]', '', '0', '1');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wxid` varchar(128) DEFAULT '' COMMENT '微信ID',
  `name` varchar(255) DEFAULT '' COMMENT '姓名',
  `icon` varchar(255) DEFAULT '' COMMENT '头像',
  `nickname` varchar(255) DEFAULT '' COMMENT '昵称',
  `email` varchar(250) DEFAULT '' COMMENT 'email',
  `phone` varchar(50) DEFAULT '' COMMENT '电话',
  `psw` varchar(255) DEFAULT '' COMMENT '密码',
  `weixnumber` varchar(255) DEFAULT '' COMMENT '微信号',
  `qq` varchar(32) DEFAULT NULL COMMENT 'QQ',
  `consume` int(11) DEFAULT '0' COMMENT '总消费',
  `errorcount` tinyint(4) DEFAULT '0' COMMENT '错误次数',
  `errortime` bigint(20) DEFAULT '0' COMMENT '最后次错误时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('10000', '', '张三', 'spread.jpg', '大毛毛虫', '279133271@qq.com', '15882276717', 'eyJpdiI6ImVEQUtQNXd5eEZSeEo1Q1VpYllTV2c9PSIsInZhbHVlIjoidzI0Q1FEWmlEQmlYSk9PMFlCdXJVdz09IiwibWFjIjoiNGY0YzFhMTM4ZjVjNTZkYjBhNTFmMzI4ZTJlODdiMWNmZGYyNzkyYzE3NmUzNGI4NGNlNDliOWI4NzE1MTE0ZCJ9', '855554', '279133271', '582', '1', '1468256036');
