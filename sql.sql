create database birthday_card_maker;

use birthday_card_maker



CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `register_status` tinyint(3) unsigned NOT NULL,
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fb_id` bigint(20) unsigned DEFAULT NULL,
  `fb_picture` varchar(400) DEFAULT NULL,

  `memo` varchar(200) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_name` (`username`),
  KEY `idx_fb_id` (`fb_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE `plans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_id` int(10) unsigned DEFAULT NULL, -- 発起人情報
  `to_id` int(10) unsigned DEFAULT NULL, -- 参加者情報
  `memo` varchar(200) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `plans_ibfk_1` FOREIGN KEY (`from_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE `collaborators` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plan_id` int(10) unsigned DEFAULT NULL, -- プラン情報
  `uid` int(10) unsigned DEFAULT NULL, -- 協力してくれるユーザー情報
  `comment` text DEFAULT NULL,
  `memo` varchar(200) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `collaborators_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `collaborators_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
