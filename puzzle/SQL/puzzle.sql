#----------------------------------------------
# 共同约定：
# 用户命名为：puzzle
# 密码设置为：puzzle
# 数据库命名为：姓名_puzzle
# PHP脚本中必须存在执行创建数据库和表的脚本
#----------------------------------------------

CREATE DATABASE IF NOT EXISTS linech_puzzle default character set utf8 COLLATE utf8_general_ci;

use linech_puzzle;

CREATE TABLE IF NOT EXISTS `ranking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(155) NOT NULL,
  `difficult` tinyint(2) NOT NULL,
  `time` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
