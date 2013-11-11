CREATE TABLE `members` (
   `id` int(10) unsigned not null auto_increment,
   `username` varchar(20) not null,
   `password` char(35) not null,
   `email` varchar(255) not null,
   `date_joined` datetime not null,
   `login_count` int(10) unsigned default 0,
   PRIMARY KEY(`id`)
) ENGINE=InnoDB;

SELECT * FROM `members` 
	WHERE `username` = 'JoeUser' 
	LIMIT 1;
	
SELECT `username` FROM `members`
	ORDER BY `date_joined` ASC;
	
SELECT `id`, `username` FROM `members`
	ORDER BY `login_count`DESC;