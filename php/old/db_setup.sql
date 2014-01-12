USE adamfake_images;

DROP TABLE IF EXISTS `color`;
CREATE TABLE `color` (
  `filename` varchar(500) DEFAULT NULL,
  `rgb` varchar(10) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `Index_num` (`rgb`),
  KEY `Index_filename` (`filename`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `histogram`;
CREATE TABLE `histogram` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` varchar(100) DEFAULT NULL,
  `category` varchar(45) DEFAULT NULL,
  `rgb` double DEFAULT NULL,
  `rgb_key` double DEFAULT NULL,
  `red` int(10) unsigned DEFAULT NULL,
  `green` int(10) unsigned DEFAULT NULL,
  `blue` int(10) unsigned DEFAULT NULL,
  `constrained` int(10) unsigned DEFAULT NULL,
  `rgb_count` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `interesting_tags`;
CREATE TABLE `interesting_tags` (
  `filename` varchar(500) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `value` varchar(500) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `Index_name` (`name`),
  KEY `Index_value` (`value`),
  KEY `Index_filename` (`filename`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `metadata`;
CREATE TABLE `metadata` (
  `filename` varchar(500) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `value` varchar(500) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `Index_name` (`name`),
  KEY `Index_value` (`value`),
  KEY `Index_filename` (`filename`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 PACK_KEYS=1;

DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `filename` varchar(500) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `Index_name` (`name`),
  KEY `Index_filename` (`filename`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



