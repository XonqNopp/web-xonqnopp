-- Creating tables to store games
-- G. Induni
-- Sep 2015
--

CREATE TABLE `db304358_106`.`games` (
	`id` INT(100) NOT NULL AUTO_INCREMENT,
	`name` TEXT NOT NULL,
	`minP` INT(3),
	`maxP` INT(3),
	`age` INT(3),
	`borrowed` TINYINT(1) NOT NULL DEFAULT 0,
	`comment` TEXT,
	PRIMARY KEY (`id`)
);
