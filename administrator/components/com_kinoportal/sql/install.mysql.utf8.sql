DROP TABLE IF EXISTS `#__filme`;

CREATE TABLE `#__filme` (
	`id` INT(11) NOT NULL AUTO_INCREMENT, 
	`name` VARCHAR(100) NOT NULL,
	`kosten` INT NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `#__filme` (`name`, `kosten`) VALUES ('Spider-Man 3','237'),
('Titanic', '290'),
('Rapunzel – Neu verföhnt', '277'),
('Fluch der Karibik 3', '336');
