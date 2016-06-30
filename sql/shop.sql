/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50539
Source Host           : localhost:3306
Source Database       : shop

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2016-06-30 12:56:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `category`
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(50) DEFAULT '' COMMENT '//名称',
  `img` varchar(255) DEFAULT '' COMMENT '图标',
  `sort` tinyint(4) DEFAULT '0' COMMENT '排序规则',
  `display` tinyint(4) DEFAULT '0' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('1', '分类1', '20160627220439.png', '1', '1');
INSERT INTO `category` VALUES ('2', '分类2', '20160627220439.png', '2', '1');
INSERT INTO `category` VALUES ('3', '分类3', '20160627220439.png', '3', '1');
INSERT INTO `category` VALUES ('4', '分类4', '20160627220439.png', '4', '1');
INSERT INTO `category` VALUES ('5', '分类5', '20160627220439.png', '5', '1');

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
  `content` varchar(255) DEFAULT NULL COMMENT '图片详情',
  `describe` varchar(255) DEFAULT NULL COMMENT '描述',
  `prime_price` float(11,0) DEFAULT '0' COMMENT '原价',
  `cur_price` float(11,0) DEFAULT '0' COMMENT '现价',
  `stock` int(11) DEFAULT '-1' COMMENT '库存 -1 无限制',
  `buynum` int(11) DEFAULT NULL COMMENT '购买人数',
  `indeximg` varchar(255) DEFAULT '' COMMENT '主图片',
  `showimg` text COMMENT '展示图片',
  `spec` text COMMENT '规格',
  `display` tinyint(4) DEFAULT '0' COMMENT '是否显示',
  `activity` tinyint(4) DEFAULT '0' COMMENT '活动',
  `showindex` tinyint(4) DEFAULT '0' COMMENT '是否在主页显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shopitem
-- ----------------------------
INSERT INTO `shopitem` VALUES ('1', '云中歌1', '1', '56f35f74b0ea4.jpg', '云中歌（套装全3册）', '124', '158', '1', '10', '56974ea4579b2.png', '56a1f2d2219f6.jpg;56a1f55e6a6a2.jpg;56974c014e6c8.jpg', '规格1;规格12', '1', '1', '1');
INSERT INTO `shopitem` VALUES ('2', '台湾 正品bitplay iPhone6 4.7 照相机手机壳 2', '2', '56f35f75b01d4.jpg', 'SNAP! PRO 台湾 正品bitplay iPhone6 4.7 照相机手机壳 还原传统拍照的顺畅感！', '5000', '5200', '-1', '50', '56a1f2d2219f6.jpg', '56974ea4579b2.png;home-img1.png;homeSlide-02.jpg', '规格2;规格22', '1', '1', '1');
INSERT INTO `shopitem` VALUES ('3', '北鼎养生壶 K106 2L222', '3', '56f35f76d5c98.jpg', '北鼎养生壶 K106 2L及以下', '410', '500', '-1', '15', '56974c014e6c8.jpg', '56974f536b240.jpg;homeSlide-01.jpg;produce-01.jpg', '规格3;规格32', '1', '1', '1');
INSERT INTO `shopitem` VALUES ('4', '北鼎养生壶 K106 2L111', '5', '56f35f77adab6.jpg', 'SNAP! PRO 台湾 正品bitplay iPhone6 4.7 照相机手机壳 还原传统拍照的顺畅感！', '4', '7', '-1', '1', '56a1f2d2219f6.jpg', '56974ea4579b2.png;home-img1.png;homeSlide-02.jpg', null, '1', '0', '1');
INSERT INTO `shopitem` VALUES ('5', '台湾 正品bitplay iPhone6 4.7 照相机手机壳 1', '4', '56f35f78a4534.jpg', '台湾 正品bitplay iPhone6 4.7 照相机手机壳 ', '122', '33', '-1', '11', '56974c014e6c8.jpg', '56a1f2d2219f6.jpg;56a1f55e6a6a2.jpg;56974c014e6c8.jpg', null, '1', '0', '1');
INSERT INTO `shopitem` VALUES ('6', '云中歌2', '1', '56f35f79e74cf.jpg', '云中歌2', '11', '112', '-1', '33', '56a1f2d2219f6.jpg', '56974f536b240.jpg;homeSlide-01.jpg;produce-01.jpg', null, '1', '0', '1');
INSERT INTO `shopitem` VALUES ('7', '北鼎养生壶 K106 2L333', '1', '56f35f793a7e0.jpg', '北鼎养生壶 K106 2L111', '12', '23', '-1', '1', '56a1f2d2219f6.jpg', '56974f536b240.jpg;homeSlide-01.jpg;produce-01.jpg', null, '1', '0', '1');
