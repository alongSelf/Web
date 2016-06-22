/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50539
Source Host           : localhost:3306
Source Database       : shop

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2016-06-22 21:15:06
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

-- ----------------------------
-- Table structure for `config`
-- ----------------------------
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of config
-- ----------------------------
INSERT INTO `config` VALUES ('1', 'myApp');

-- ----------------------------
-- Table structure for `shopitem`
-- ----------------------------
DROP TABLE IF EXISTS `shopitem`;
CREATE TABLE `shopitem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '' COMMENT '名称',
  `category` int(11) DEFAULT '0' COMMENT '类别ID',
  `content` text COMMENT '详情',
  `describe` varchar(255) DEFAULT NULL COMMENT '描述',
  `prime_price` int(11) DEFAULT '0' COMMENT '原价',
  `cur_price` int(11) DEFAULT '0' COMMENT '现价',
  `buynum` int(11) DEFAULT NULL COMMENT '购买人数',
  `indeximg` varchar(255) DEFAULT '' COMMENT '主图片',
  `showimg` text COMMENT '展示图片',
  `display` tinyint(4) DEFAULT '0' COMMENT '是否显示',
  `activity` tinyint(4) DEFAULT '0' COMMENT '活动',
  `showindex` tinyint(4) DEFAULT '0' COMMENT '是否在主页显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shopitem
-- ----------------------------
INSERT INTO `shopitem` VALUES ('1', '3本组合装 | 云中歌 桐华签名版 电视剧原版小说 古代言情小说', '1', '云中歌（套装全3册）', '云中歌（套装全3册）', '124', '158', '10', '56974ea4579b2.png', '56974ea4579b2.png;56974f536b240.jpg;56974c014e6c8.jpg', '1', '1', '1');
INSERT INTO `shopitem` VALUES ('2', 'SNAP! PRO 台湾 正品bitplay iPhone6 4.7 照相机手机壳 还原传统拍照的顺畅感！', '2', 'SNAP! PRO 台湾 正品bitplay iPhone6 4.7 照相机手机壳 还原传统拍照的顺畅感！', 'SNAP! PRO 台湾 正品bitplay iPhone6 4.7 照相机手机壳 还原传统拍照的顺畅感！', '5000', '5200', '50', '56a1f2d2219f6.jpg', '56a1f2d2219f6.jpg;56a1f55e6a6a2.jpg', '1', '1', '1');
INSERT INTO `shopitem` VALUES ('3', '北鼎养生壶 K106 2L及以下', '3', '北鼎养生壶 K106 2L及以下', '北鼎养生壶 K106 2L及以下', '410', '500', '15', '56974c014e6c8.jpg', '56974c014e6c8.jpg', '1', '1', '1');
