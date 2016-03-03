CREATE TABLE `team_results` (
  `year` smallint(6) NOT NULL,
  `week` smallint(6) NOT NULL,
  `team_id` smallint(6) NOT NULL,
  `score` float DEFAULT NULL,
  `rank` float DEFAULT NULL,
  PRIMARY KEY (`year`,`week`,`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
