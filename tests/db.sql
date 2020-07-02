DROP TABLE IF EXISTS `test`;
CREATE TABLE `test` (
  `sampleId` int(11) NOT NULL AUTO_INCREMENT,
  `sampleString` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`sampleId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `test_test`;
CREATE TABLE `test_test` (
  `sampleId` int(11) NOT NULL AUTO_INCREMENT,
  `sampleString` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`sampleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `error`;
CREATE TABLE `error` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `error(...)` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
