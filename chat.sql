-- MySQL dump 10.13  Distrib 5.7.26, for Win64 (x86_64)
--
-- Host: localhost    Database: chat
-- ------------------------------------------------------
-- Server version	5.7.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `chat_communication`
--

DROP TABLE IF EXISTS `chat_communication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat_communication` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `fromid` int(5) NOT NULL,
  `fromname` varchar(50) NOT NULL,
  `toid` int(5) NOT NULL,
  `toname` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `time` int(10) NOT NULL,
  `shopid` int(5) DEFAULT NULL,
  `isread` tinyint(2) DEFAULT '0',
  `type` tinyint(2) DEFAULT '1' COMMENT '1是普通文本，2是图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_communication`
--

LOCK TABLES `chat_communication` WRITE;
/*!40000 ALTER TABLE `chat_communication` DISABLE KEYS */;
INSERT INTO `chat_communication` VALUES (1,89,'雨薇',87,'大金','Hello',1640757367,NULL,1,1),(2,87,'大金',89,'雨薇','Hi, what\'s up',1640757380,NULL,1,1),(3,89,'雨薇',87,'大金','[em_49][em_49][em_49]',1640757416,NULL,1,1),(4,87,'大金',89,'雨薇','[em_39][em_39]',1640757423,NULL,1,1),(5,89,'雨薇',87,'大金','http://local.chat.com/uploads/202112291357178737.jpg',1640757437,NULL,1,2),(6,87,'大金',89,'雨薇','[em_58]',1640757473,NULL,1,1),(7,89,'雨薇',87,'大金','oh, buck you',1640757503,NULL,1,1),(8,89,'雨薇',87,'大金','야야야',1640759605,NULL,1,1),(9,87,'大金',89,'雨薇','ㅇㅇㅇ',1640759610,NULL,1,1),(10,89,'雨薇',87,'大金','[em_4]',1640759648,NULL,0,1);
/*!40000 ALTER TABLE `chat_communication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_user`
--

DROP TABLE IF EXISTS `chat_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `mpid` int(10) NOT NULL COMMENT '公众号标识',
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `nickname` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '昵称',
  `headimgurl` varchar(255) DEFAULT NULL COMMENT '头像',
  `sex` tinyint(1) DEFAULT NULL COMMENT '性别',
  `subscribe` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否关注',
  `subscribe_time` int(10) DEFAULT NULL COMMENT '关注时间',
  `unsubscribe_time` int(10) DEFAULT NULL COMMENT '取消关注时间',
  `relname` varchar(50) DEFAULT NULL COMMENT '真实姓名',
  `signature` text COMMENT '个性签名',
  `mobile` varchar(15) DEFAULT NULL COMMENT '手机号',
  `is_bind` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否绑定',
  `language` varchar(50) DEFAULT NULL COMMENT '使用语言',
  `country` varchar(50) DEFAULT NULL COMMENT '国家',
  `province` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '省',
  `city` varchar(50) DEFAULT NULL COMMENT '城市',
  `remark` varchar(50) DEFAULT NULL COMMENT '备注',
  `group_id` int(10) DEFAULT '0' COMMENT '分组ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '公众号分组标识',
  `tagid_list` varchar(255) DEFAULT NULL COMMENT '标签',
  `score` int(10) DEFAULT '0' COMMENT '积分',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '金钱',
  `latitude` varchar(50) DEFAULT NULL COMMENT '纬度',
  `longitude` varchar(50) DEFAULT NULL COMMENT '经度',
  `location_precision` varchar(50) DEFAULT NULL COMMENT '精度',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0:公众号粉丝1：注册会员',
  `unionid` varchar(160) DEFAULT NULL COMMENT 'unionid字段',
  `password` varchar(64) DEFAULT NULL COMMENT '密码',
  `last_time` int(10) DEFAULT '586969200' COMMENT '最后交互时间',
  `parentid` int(10) DEFAULT '1' COMMENT '非扫码用户默认都是1',
  `isfenxiao` int(8) DEFAULT '0' COMMENT '是否为分销，默认为0，1,2,3，分别为1,2,3级分销',
  `totle_earn` decimal(8,2) DEFAULT '0.00' COMMENT '挣钱总额',
  `balance` decimal(8,2) DEFAULT '0.00' COMMENT '分销挣的剩余未提现额',
  `fenxiao_leavel` int(8) DEFAULT '2' COMMENT '分销等级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8 COMMENT='公众号粉丝表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_user`
--

LOCK TABLES `chat_user` WRITE;
/*!40000 ALTER TABLE `chat_user` DISABLE KEYS */;
INSERT INTO `chat_user` VALUES (85,1,'oYxpK0bPptICGQd3YP_1s7jfDTmE','Love violet life','http://local.chat.com/img/85.jpg',1,1,1517280919,1517280912,NULL,NULL,NULL,0,'zh_CN','中国','江西','赣州','',0,0,'[]',0,0.00,NULL,NULL,NULL,0,NULL,NULL,1517478028,1,0,26.00,26.00,2),(86,1,'oYxpK0W2u3Sbbp-wevdQtCuviDVM','大美如斯','http://local.chat.com/img/86.jpg',2,1,1507261446,NULL,NULL,NULL,NULL,0,'zh_CN','中国','河南','焦作','',0,0,'[]',0,0.00,NULL,NULL,NULL,0,NULL,NULL,586969200,1,0,0.00,0.00,2),(87,1,'oYxpK0RsvcwgS9DtmIOuyb_BgJbo','大金','http://local.chat.com/img/87.jpg',1,1,1508920878,NULL,NULL,NULL,NULL,0,'zh_CN','中国','河南','商丘','',0,0,'[]',0,0.00,NULL,NULL,NULL,0,NULL,NULL,586969200,1,0,0.00,0.00,2),(88,1,'oYxpK0VnHjESafUHzRpstS8mMwlE','悦悦','http://local.chat.com/img/88.jpg',2,1,1512281210,NULL,NULL,NULL,NULL,0,'zh_CN','中国','福建','福州','',0,0,'[]',0,0.00,NULL,NULL,NULL,0,NULL,NULL,586969200,1,0,0.00,0.00,2),(89,1,'oYxpK0fJVYveWC_nAd7CBwcvYZ3Q','雨薇','http://local.chat.com/img/89.jpg',2,1,1506320564,NULL,NULL,NULL,NULL,0,'zh_CN','','','','',0,0,'[]',0,0.00,NULL,NULL,NULL,0,NULL,NULL,586969200,1,0,0.00,0.00,2);
/*!40000 ALTER TABLE `chat_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-12-29 14:43:54
