#
# Tabelle `#_shbesuche`
# Kapitel 3
# Vorbereitung und Erstinstallation
#

DROP TABLE IF EXISTS `lqstn_shbesuche`;
CREATE TABLE IF NOT EXISTS `lqstn_shbesuche` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `nachname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `description` text,
  `tel` varchar(50) DEFAULT NULL,
  `date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

