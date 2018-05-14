CREATE TABLE `companies` (
	`id` INT(100),
	`name` TEXT,
	`location` TEXT,
	`car_time` INT(100),
	`train_time` INT(100),
	`fields` TEXT,
	`physicist` TEXT,
	`contact` TEXT,
	`HRname` TEXT,
	`people` INT(100),
	`peopleCH` INT(100),
	`peopleRD` INT(100),
	`competitors` TEXT,
	`website` TEXT,
	`ranking` TINYINT(2),-- 9 is dream-company, 1 is default, 0 is never
	`comment` TEXT
);

-- UPDATE `companies` SET `fields2` = `fields`, `physicist2` = `physicist`;

CREATE TABLE `comco` (
	`id` INT(100),
	`company` INT(100),
	`timestamp` DATETIME,
	`who` TEXT,
	`media` TEXT,
	`way` TEXT,
	`kind` TEXT,
	`content` TEXT
);

-- UPDATE `comco` SET `media2` = `media`, `way2` = `way`, `kind2` = `kind`;
