/* DROP TABLE IF EXISTS `#__vvisitcounter`; */

CREATE TABLE IF NOT EXISTS `#__vvisitcounter` (
	`id` int(11) unsigned NOT NULL auto_increment, 
	`tm` int NOT NULL, 
	`ip` varchar(16) NOT NULL DEFAULT '0.0.0.0', 
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;