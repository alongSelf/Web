/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50539
Source Host           : localhost:3306
Source Database       : shop

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2016-07-06 17:55:44
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
  `sort` tinyint(4) DEFAULT '0' COMMENT '排序规则',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('11', '洗发护发', '20160704161431628.jpg', '1');
INSERT INTO `category` VALUES ('12', '家用日化', '20160704161530538.jpg', '2');
INSERT INTO `category` VALUES ('13', '女性护理', '20160704161655704.jpg', '3');
INSERT INTO `category` VALUES ('14', '口腔护理', '20160704161738168.jpg', '4');
INSERT INTO `category` VALUES ('15', '母婴用品', '20160704161956421.png', '5');
INSERT INTO `category` VALUES ('16', '明星单品', '20160704162028225.jpg', '6');

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
  `star` int(11) DEFAULT '10' COMMENT '评分',
  `evaluate` varchar(255) DEFAULT '' COMMENT '评价',
  `userid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0' COMMENT '订单ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of evaluates
-- ----------------------------
INSERT INTO `evaluates` VALUES ('1', '11', '10', '谢谢啊啊撒的', '0', '0');
INSERT INTO `evaluates` VALUES ('2', '11', '10', 'asada的撒发生地方', '0', '0');
INSERT INTO `evaluates` VALUES ('3', '11', '10', '56时代发生地方', '0', '0');
INSERT INTO `evaluates` VALUES ('4', '11', '10', '撒地方快快快', '0', '0');
INSERT INTO `evaluates` VALUES ('5', '11', '10', '永远永远永远永远永远', '0', '0');

-- ----------------------------
-- Table structure for `notice`
-- ----------------------------
DROP TABLE IF EXISTS `notice`;
CREATE TABLE `notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notice` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notice
-- ----------------------------
INSERT INTO `notice` VALUES ('1', '嘻嘻嘻嘻嘻嘻嘻嘻惺惺惜惺惺');
INSERT INTO `notice` VALUES ('2', '2222xxxxxxxxxxxxzcxz是多少');

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shopitem
-- ----------------------------
INSERT INTO `shopitem` VALUES ('11', 'SCALABO天然无硅洗发水护发素套装（改善脱发型）', '11', '[\"20160704162528929.jpg\",\"20160704162544781.jpg\",\"20160704162559506.jpg\",\"20160704162610566.jpg\",\"20160704162623138.jpg\",\"20160704162657449.jpg\",\"20160704162728919.jpg\"]', 'SCALABO天然无硅洗发水护发素套装（改善脱发型）', '68', '58', '-1', '10', '20160704162310431.png', '[\"20160704162412106.png\",\"20160704162412771.png\",\"20160704162412635.png\",\"20160704162412337.png\",\"20160704162413213.png\",\"20160704162413181.png\"]', '', '1', '1');
INSERT INTO `shopitem` VALUES ('12', 'SCALABO天然无硅洗发水护发素套装（养润修复型）KAZE-风系列 日本原装进口', '11', '[\"20160704163142812.jpg\",\"20160704163151204.jpg\",\"20160704163156215.jpg\",\"20160704163159579.jpg\",\"20160704163201569.jpg\",\"20160704163206504.jpg\"]', 'SCALABO天然无硅洗发水护发素套装（养润修复型）KAZE-风系列 日本原装进口', '158', '58', '-1', '105', '20160704162940575.jpg', '[\"20160704163038536.jpg\",\"20160704163039928.jpg\",\"20160704163039974.png\",\"20160704163039817.png\",\"20160704163039909.png\",\"20160704163039306.jpg\"]', '', '1', '1');
INSERT INTO `shopitem` VALUES ('13', '日本 KAO/花王 洗衣粉', '12', '[\"20160704164244363.jpg\"]', '日本 KAO/花王 洗衣粉 含天然柔顺剂玫瑰果香 850g/盒  日本原装进口  不含荧光剂', '38', '27', '-1', '25', '20160704164035465.jpg', '[\"20160704164223672.jpg\",\"20160704164223969.jpg\",\"20160704164223832.jpg\",\"20160704164223329.jpg\",\"20160704164224285.jpg\"]', '', '1', '1');
INSERT INTO `shopitem` VALUES ('14', '日本 KAO/花王 洗衣液', '12', '[\"20160704164722504.jpg\"]', '日本 KAO/花王 洗衣液 含天然柔顺剂玫瑰果香 820g/瓶 日本原装进口 不含荧光剂', '49', '33', '-1', '0', '20160704164641414.jpg', '[\"20160704164717410.jpg\",\"20160704164718989.jpg\",\"20160704164718903.jpg\",\"20160704164718518.jpg\",\"20160704164718515.jpg\"]', '', '1', '1');
INSERT INTO `shopitem` VALUES ('15', '花王F系列 日用有护翼卫生巾', '13', '[\"20160704164948877.jpg\"]', '花王F系列 日用有护翼卫生巾 25cm 18片/包 日本原装进口', '38', '32', '-1', '0', '20160704164856543.jpg', '[\"20160704164936673.jpg\",\"20160704164936525.jpg\",\"20160704164936945.jpg\",\"20160704164936639.jpg\",\"20160704164936736.png\"]', '', '1', '1');
INSERT INTO `shopitem` VALUES ('16', '花王S系列 夜用有护翼卫生巾', '13', '[\"20160704165207697.jpg\",\"20160704165217308.jpg\",\"20160704165225690.jpg\",\"20160704165238922.jpg\",\"20160704165300229.jpg\"]', '花王S系列 夜用有护翼卫生巾  30cm 15片/包  日本原装进口', '39', '33', '-1', '0', '20160704165052396.jpg', '[\"20160704165142584.jpg\",\"20160704165142345.jpg\",\"20160704165142915.jpg\",\"20160704165142671.jpg\"]', '', '0', '1');
INSERT INTO `shopitem` VALUES ('17', 'Propolinse比那氏蜂胶茶复合漱口水', '14', '[\"20160704165448821.jpg\",\"20160704165458809.jpg\"]', 'Propolinse比那氏蜂胶茶复合漱口水 600ml 日本原装进', '60', '48', '-1', '0', '20160704165342669.jpg', '[\"20160704165425719.png\",\"20160704165426307.jpg\",\"20160704165426586.png\",\"20160704165426511.jpg\",\"20160704165426415.jpg\"]', '', '0', '1');
INSERT INTO `shopitem` VALUES ('18', 'Lion狮王 美白牙膏', '14', '[\"20160704165650794.jpg\",\"20160704165659776.jpg\"]', 'Lion狮王 美白牙膏 150g 日本原装进口', '15', '12', '-1', '0', '20160704165557288.png', '[\"20160704165636533.png\",\"20160704165636340.png\",\"20160704165636267.png\",\"20160704165636399.png\",\"20160704165637913.png\"]', '', '0', '1');
INSERT INTO `shopitem` VALUES ('19', '和光堂爽身粉', '15', '[\"20160704170039771.jpg\",\"20160704170042328.jpg\",\"20160704170044960.jpg\"]', '和光堂爽身粉', '68', '49', '-1', '0', '20160704170012983.png', '[\"20160704170032337.png\",\"20160704170032391.png\",\"20160704170033941.png\",\"20160704170033750.png\"]', '', '1', '1');
INSERT INTO `shopitem` VALUES ('20', '花王纸尿裤', '15', '[\"20160704170252750.jpg\"]', '花王纸尿裤   L54片  日本原装进口', '178', '158', '-1', '0', '20160704170218249.png', '[\"20160704170225771.png\",\"20160704170225387.jpg\",\"20160704170225207.png\",\"20160704170225128.png\",\"20160704170225187.png\"]', '', '1', '1');
INSERT INTO `shopitem` VALUES ('21', 'VAPE  KT 驱蚊手环', '16', '[\"20160704170443593.jpg\",\"20160704170453299.jpg\",\"20160704170502709.jpg\",\"20160704170514796.jpg\"]', 'VAPE  KT 驱蚊手环', '158', '139', '-1', '0', '20160704170355838.png', '[\"20160704170428541.png\",\"20160704170428546.png\",\"20160704170428913.png\",\"20160704170428436.png\",\"20160704170428819.png\"]', '', '0', '1');
INSERT INTO `shopitem` VALUES ('22', '安耐晒', '16', '[\"20160704170714400.jpg\",\"20160704170725931.jpg\"]', '安耐晒', '198', '268', '-1', '0', '20160704170605420.png', '[\"20160704170617857.png\",\"20160704170617135.png\",\"20160704170617878.png\"]', '{\"颜色\":[\"红\",\"黄\",\"绿\"],\"大小\":[\"M\",\"X\"]}', '0', '1');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wxid` varchar(128) DEFAULT '' COMMENT '微信ID',
  `email` varchar(250) DEFAULT '' COMMENT 'email',
  `phone` varchar(50) DEFAULT '' COMMENT '电话',
  `consume` int(11) DEFAULT '0' COMMENT '总消费',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
