use progress;

CREATE TABLE progress.main (
  `code` bigint(20) unsigned NOT NULL DEFAULT '0',
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `section` tinyint(4) NOT NULL DEFAULT '0',
  `saptextdk` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`code`),
  KEY `parent` (`parent`),
  KEY `section` (`section`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

insert into progress.main
select code,parent,section,saptextdk
from lyreco.main
order by code;

SET character_set_results = latin1;

select * from progress.main;

/**
	Export data to tab-delimited file 
**/

truncate table progress.main;

LOAD DATA LOCAL INFILE 'H:/PhpstormProjects/Progress/sql/progress-main.txt'
INTO TABLE progress.main
CHARACTER SET latin1
FIELDS
	TERMINATED BY '\t'
	OPTIONALLY ENCLOSED BY '"'
LINES
	TERMINATED BY '\n'
IGNORE 1 LINES;
