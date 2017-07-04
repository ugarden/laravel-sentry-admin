/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50713
Source Host           : localhost:3306
Source Database       : laravel_sentry_admin

Target Server Type    : MYSQL
Target Server Version : 50713
File Encoding         : 65001

Date: 2017-07-04 14:21:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of groups
-- ----------------------------
INSERT INTO `groups` VALUES ('1', 'admin', '{\"UserController@getShow\":1,\"UserController@getIndex\":1,\"UserController@deleteIndex\":1,\"GroupController@getShow\":1,\"GroupController@getIndex\":1,\"OperationController@getIndex\":1,\"OperationController@postIndex\":1,\"UserController@getEdit\":1,\"UserController@putEdit\":1,\"OperationController@putIndex\":1,\"GroupController@getUserList\":1,\"UserController@getPassword\":1,\"UserController@postPassword\":1,\"UserController@getStatus\":1,\"GroupController@postIndex\":1,\"GroupController@putIndex\":1,\"GroupController@deleteIndex\":1,\"PlantBasicController@getShow\":1,\"PlantBasicController@getIndex\":1,\"PlantBasicController@getPlantDetail\":1,\"PlantImageController@getIndex\":1,\"PlantImageController@getCreate\":1,\"PlantImageController@postUpload\":1,\"PlantImageController@deleteIndex\":1,\"PlantBasicController@getCreate\":1,\"PlantBasicController@postCreate\":1,\"PlantBasicController@getEdit\":1,\"PlantBasicController@putEdit\":1,\"PlantBasicController@postEdit\":1,\"PlantBasicController@deleteIndex\":1,\"PlantNeedTabooController@getShow\":1,\"PlantNeedTabooController@getIndex\":1,\"PlantNeedTabooController@getCreate\":1,\"PlantNeedTabooController@postCreate\":1,\"PlantNeedTabooController@getEdit\":1,\"PlantNeedTabooController@postEdit\":1,\"PlantNeedTabooController@putEdit\":1,\"PlantNeedTabooController@deleteIndex\":1,\"PlantPointController@getShow\":1,\"PlantPointController@getIndex\":1,\"PlantPointController@getCreate\":1,\"PlantPointController@postCreate\":1,\"PlantPointController@getDetail\":1,\"PlantPointController@getEdit\":1,\"PlantPointController@putEdit\":1,\"PlantPointController@deleteIndex\":1,\"PlantTraitController@getShow\":1,\"PlantTraitController@getIndex\":1,\"PlantTraitController@getCreate\":1,\"PlantTraitController@postCreate\":1,\"PlantTraitController@getDetail\":1,\"PlantTraitController@getEdit\":1,\"PlantTraitController@putEdit\":1,\"PlantTraitController@deleteIndex\":1,\"PlantTypeController@getShow\":1,\"PlantTypeController@getIndex\":1,\"PlantTypeController@getCreate\":1,\"PlantTypeController@postCreate\":1,\"PlantTypeController@getEdit\":1,\"PlantTypeController@putEdit\":1,\"PlantTypeController@deleteIndex\":1,\"PlantSpecieController@getShow\":1,\"PlantSpecieController@getIndex\":1,\"PlantSpecieController@getCreate\":1,\"PlantSpecieController@postCreate\":1,\"PlantSpecieController@getEdit\":1,\"PlantSpecieController@putEdit\":1,\"PlantSpecieController@deleteIndex\":1,\"PlantToolController@getShow\":1,\"PlantToolController@getIndex\":1,\"PlantToolController@getCreate\":1,\"PlantToolController@postIndex\":1,\"PlantToolController@postCreate\":1,\"PlantToolController@getDetail\":1,\"PlantToolController@getEdit\":1,\"PlantToolController@putEdit\":1,\"PlantToolController@deleteIndex\":1,\"QrCustomerController@getShow\":1,\"QrCustomerController@getIndex\":1,\"QrCustomerController@getCreate\":1,\"QrCustomerController@postCreate\":1,\"QrCustomerController@getEdit\":1,\"QrCustomerController@putEdit\":1,\"QrCustomerController@deleteIndex\":1,\"QrCustomerAreaController@getShow\":1,\"QrCustomerAreaController@getIndex\":1,\"QrCustomerAreaController@getCreate\":1,\"QrCustomerAreaController@postCreate\":1,\"QrCustomerController@getBindUser\":1,\"QrCustomerController@postBindUser\":1,\"QrCustomerAreaController@getEdit\":1,\"QrCustomerAreaController@putEdit\":1,\"QrCustomerAreaController@deleteIndex\":1,\"QrTagController@getShow\":1,\"QrTagController@getIndex\":1,\"QrTagController@getDetail\":1,\"QrTagController@getPreview\":1,\"QrScanningController@getShow\":1,\"QrScanningController@getIndex\":1,\"QrScanningController@deleteIndex\":1,\"QrTagController@getCreate\":1,\"QrTagController@postCreate\":1,\"QrTagController@getDownloadDemo\":1,\"QrTagController@getEdit\":1,\"QrTagController@putEdit\":1,\"QrTagController@deleteIndex\":1,\"QrTagController@getDownload\":1,\"QrCommentController@getShow\":1,\"QrCommentController@getIndex\":1,\"QrCommentController@deleteIndex\":1,\"QrCommentController@getCheck\":1,\"PlantKnowledgeController@getShow\":1,\"PlantKnowledgeController@getIndex\":1,\"PlantKnowledgeController@getCreate\":1,\"PlantKnowledgeController@postCreate\":1,\"PlantKnowledgeController@getEdit\":1,\"PlantKnowledgeController@putEdit\":1,\"PlantKnowledgeController@deleteIndex\":1,\"QrLinkController@getShow\":1,\"QrLinkController@getIndex\":1,\"QrLinkController@getCreate\":1,\"QrLinkController@postCreate\":1,\"QrLinkController@getEdit\":1,\"QrLinkController@putIndex\":1,\"QrLinkController@putEdit\":1,\"QrLinkController@deleteIndex\":1,\"QrTraceabilityInfoController@getShow\":1,\"QrTraceabilityInfoController@getIndex\":1,\"QrTraTagController@getShow\":1,\"QrTraTagController@getIndex\":1,\"QrTraTagController@getDetail\":1,\"QrTraTagController@getPreview\":1,\"QrTraTagController@getDownload\":1,\"QrTraTagController@getCreate\":1,\"QrTraTagController@postCreate\":1,\"QrTraTagController@getEdit\":1,\"QrTraTagController@putEdit\":1,\"QrTraTagController@deleteIndex\":1,\"QrTraceabilityInfoController@getCreate\":1,\"QrTraceabilityInfoController@postCreate\":1,\"QrTraceabilityInfoController@getEdit\":1,\"QrTraceabilityInfoController@putEdit\":1,\"QrTraceabilityInfoController@deleteIndex\":1,\"PlantBasicController@getImport\":1,\"PlantBasicController@postImport\":1,\"QrCommentController@getDetail\":1,\"UserController@getCreate\":1,\"UserController@postCreate\":1}', '2017-05-08 08:14:48', '2017-06-22 10:41:59');
INSERT INTO `groups` VALUES ('4', '客户', '{\"QrCustomerAreaController@getIndex\":1,\"QrCustomerAreaController@getShow\":1,\"QrCustomerAreaController@getCreate\":1,\"QrCustomerAreaController@postCreate\":1,\"QrCustomerAreaController@getEdit\":1,\"QrCustomerAreaController@putEdit\":1,\"QrCustomerAreaController@deleteIndex\":1,\"QrTagController@getShow\":1,\"QrTagController@getIndex\":1,\"QrTagController@getDetail\":1,\"QrTagController@getPreview\":1,\"QrScanningController@getShow\":1,\"QrScanningController@getIndex\":1,\"QrScanningController@deleteIndex\":1,\"QrTagController@getCreate\":1,\"QrTagController@postCreate\":1,\"QrTagController@getDownloadDemo\":1,\"QrTagController@getEdit\":1,\"QrTagController@putEdit\":1,\"QrTagController@deleteIndex\":1,\"QrTagController@getDownload\":1,\"QrCommentController@getShow\":1,\"QrCommentController@getIndex\":1,\"QrCommentController@deleteIndex\":1,\"QrCommentController@getCheck\":1,\"QrLinkController@getShow\":1,\"QrLinkController@getIndex\":1,\"QrLinkController@getCreate\":1,\"QrLinkController@postCreate\":1,\"QrLinkController@getEdit\":1,\"QrLinkController@putIndex\":1,\"QrLinkController@putEdit\":1,\"QrLinkController@deleteIndex\":1,\"QrTraceabilityInfoController@getShow\":1,\"QrTraceabilityInfoController@getIndex\":1,\"QrTraTagController@getShow\":1,\"QrTraTagController@getIndex\":1,\"QrTraTagController@getDetail\":1,\"QrTraTagController@getPreview\":1,\"QrTraTagController@getDownload\":1,\"QrTraTagController@getCreate\":1,\"QrTraTagController@postCreate\":1,\"QrTraTagController@getEdit\":1,\"QrTraTagController@putEdit\":1,\"QrTraTagController@deleteIndex\":1,\"QrTraceabilityInfoController@getCreate\":1,\"QrTraceabilityInfoController@postCreate\":1,\"QrTraceabilityInfoController@getEdit\":1,\"QrTraceabilityInfoController@putEdit\":1,\"QrTraceabilityInfoController@deleteIndex\":1,\"QrCommentController@getDetail\":1}', '2017-05-12 21:45:27', '2017-06-19 15:17:27');

-- ----------------------------
-- Table structure for operations
-- ----------------------------
DROP TABLE IF EXISTS `operations`;
CREATE TABLE `operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(50) NOT NULL COMMENT 'controller@index',
  `module_name` varchar(50) NOT NULL COMMENT '模块名',
  `operation_name` varchar(50) NOT NULL COMMENT '操作名',
  `sort` int(11) NOT NULL DEFAULT '1000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `action` (`action`)
) ENGINE=MyISAM AUTO_INCREMENT=183 DEFAULT CHARSET=utf8 COMMENT='操作表';

-- ----------------------------
-- Records of operations
-- ----------------------------
INSERT INTO `operations` VALUES ('1', 'UserController@getShow', '用户管理', '查看', '1');
INSERT INTO `operations` VALUES ('2', 'UserController@getIndex', '用户管理', '查看', '1');
INSERT INTO `operations` VALUES ('3', 'UserController@getCreate', '用户管理', '创建', '1');
INSERT INTO `operations` VALUES ('4', 'UserController@postCreate', '用户管理', '创建', '1');
INSERT INTO `operations` VALUES ('5', 'UserController@deleteIndex', '用户管理', '删除', '1');
INSERT INTO `operations` VALUES ('6', 'UserController@getEdit', '用户管理', '修改', '1');
INSERT INTO `operations` VALUES ('7', 'UserController@putEdit', '用户管理', '修改', '1');
INSERT INTO `operations` VALUES ('8', 'GroupController@getShow', '角色管理', '查看', '2');
INSERT INTO `operations` VALUES ('41', 'GroupController@getIndex', '角色管理', '查看', '2');
INSERT INTO `operations` VALUES ('42', 'GroupController@getUserList', '角色管理', '查看角色对应用户列表', '2');
INSERT INTO `operations` VALUES ('43', 'OperationController@getIndex', '权限管理', '查看', '3');
INSERT INTO `operations` VALUES ('44', 'OperationController@postIndex', '权限管理', '创建', '3');
INSERT INTO `operations` VALUES ('45', 'OperationController@putIndex', '权限管理', '刷新', '3');
INSERT INTO `operations` VALUES ('46', 'UserController@getPassword', '用户管理', '重置密码', '1');
INSERT INTO `operations` VALUES ('47', 'UserController@postPassword', '用户管理', '重置密码', '1');
INSERT INTO `operations` VALUES ('50', 'UserController@getStatus', '用户管理', '用户激活禁用', '1');
INSERT INTO `operations` VALUES ('51', 'GroupController@postIndex', '角色管理', '创建', '2');
INSERT INTO `operations` VALUES ('52', 'GroupController@putIndex', '角色管理', '编辑', '2');
INSERT INTO `operations` VALUES ('53', 'GroupController@deleteIndex', '角色管理', '删除', '2');

-- ----------------------------
-- Table structure for throttle
-- ----------------------------
DROP TABLE IF EXISTS `throttle`;
CREATE TABLE `throttle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT '0',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `last_attempt_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `throttle_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of throttle
-- ----------------------------
INSERT INTO `throttle` VALUES ('9', '2', null, '0', '0', '0', null, null, null);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_code` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `persist_code` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reset_password_code` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_activation_code_index` (`activation_code`),
  KEY `users_reset_password_code_index` (`reset_password_code`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('2', 'admin@qq.com', '$2y$10$E8CVSqILlIqEGtFzjXTfMuSLj3M7070QtXc.PYv2fbNR.j4bOu8Ga', null, '1', null, '2017-05-12 20:17:09', '2017-07-04 11:49:42', '$2y$10$MQc5DMmIw6mr3wEnDy3ik.JQxSk.AfwJj6fqPz1FsM.Wc6unEEJqm', null, '超级', '管理员', '2017-05-08 08:14:48', '2017-07-04 11:49:42');

-- ----------------------------
-- Table structure for users_groups
-- ----------------------------
DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE `users_groups` (
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users_groups
-- ----------------------------
INSERT INTO `users_groups` VALUES ('2', '1');
