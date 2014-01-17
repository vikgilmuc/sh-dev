DROP TABLE IF EXISTS `#__wohnungen`;

CREATE TABLE `#__wohnungen` (
	`id` INT(11) NOT NULL AUTO_INCREMENT, 
	`name` VARCHAR(100) NOT NULL,
	`meter` INT NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `#__wohnungen` (`name`, `meter`) VALUES ('Top-1','237'),
('Top-2', '290'),
('Top-3', '277'),
('Top-4', '336');

