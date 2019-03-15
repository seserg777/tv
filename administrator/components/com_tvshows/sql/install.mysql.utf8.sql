CREATE TABLE IF NOT EXISTS `#__tvshows_film` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`catid` int(11) unsigned NOT NULL DEFAULT '0',
	`season` int(10) unsigned NOT NULL DEFAULT '0',
	`rate_imdb` VARCHAR(5) NOT NULL,
	`votes` VARCHAR(25) NOT NULL,
	`title` VARCHAR(255) NOT NULL,
	`awards` VARCHAR(255) NOT NULL,
	`creators` VARCHAR(255) NOT NULL,
	`directors` VARCHAR(255) NOT NULL,
	`release_date` DATETIME NOT NULL,
	`language` VARCHAR(15) NOT NULL,
	`cast` VARCHAR(255) NOT NULL,
	`channel` VARCHAR(255) NOT NULL,
	`main_image` VARCHAR(255) NOT NULL,
	`description` VARCHAR(255) NOT NULL,
	`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
	`ordering` int(11) NOT NULL DEFAULT '0',
	`published` tinyint(3) NOT NULL DEFAULT '0',
	`checked_out` int(11) unsigned NOT NULL DEFAULT '0',
	`checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`images` text NOT NULL,
	`hits` int(11) NOT NULL DEFAULT '0',
	`metadata` text NOT NULL,
	`metakey` text NOT NULL,
	`metadesc` text NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__tvshows_season` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`asset_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
	`film` int(10) unsigned NOT NULL DEFAULT '0',
	`title` VARCHAR(255) NOT NULL,
	`custom_btn1` VARCHAR(255) NOT NULL,
	`bage` VARCHAR(25) NOT NULL,
	`links` VARCHAR(255) NOT NULL,
	`next_season_time` DATETIME NOT NULL,
	`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
	`ordering` int(11) NOT NULL DEFAULT '0',
	`published` tinyint(3) NOT NULL DEFAULT '0',
	`checked_out` int(11) unsigned NOT NULL DEFAULT '0',
	`checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`images` text NOT NULL,
	`hits` int(11) NOT NULL DEFAULT '0',
	`params` text NOT NULL,
	`metadata` text NOT NULL,
	`metakey` text NOT NULL,
	`metadesc` text NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
