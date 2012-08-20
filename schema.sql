CREATE TABLE IF NOT EXISTS `accounts` (
  `id_account` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) CHARACTER SET latin1 NOT NULL,
  `password` varchar(64) CHARACTER SET latin1 NOT NULL,
  `first_name` varchar(250) CHARACTER SET latin1 NOT NULL,
  `last_name` varchar(250) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL,
  `display_name` varchar(250) CHARACTER SET latin1 NOT NULL,
  `activation_key` varchar(64) CHARACTER SET latin1 NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `ip_address` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `posts` (
  `id_post` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_author` bigint(20) unsigned NOT NULL,
  `status` varchar(20) CHARACTER SET latin1 NOT NULL DEFAULT 'draft',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` tinytext CHARACTER SET latin1 NOT NULL,
  `title` text CHARACTER SET latin1 NOT NULL,
  `slug` varchar(200) NOT NULL,
  `excerpt` text CHARACTER SET latin1,
  `content` longtext CHARACTER SET latin1 NOT NULL,
  `original_path` longtext,
  `width` smallint(6) DEFAULT NULL,
  `height` smallint(6) DEFAULT NULL,
  `width_thumbnail` smallint(6) DEFAULT NULL,
  `height_thumbnail` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id_post`),
  KEY `author` (`id_author`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


ALTER TABLE `posts`
  ADD CONSTRAINT `author` FOREIGN KEY (`id_author`) REFERENCES `accounts` (`id_account`);
