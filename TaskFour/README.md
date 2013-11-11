## Task 4
Given the following database table, optimize the table and provide queries to select
a) A single member by username.
a) All usernames ordered by the date joined ascending.
b) All ids and usernames ordered by login count descending.

```sql
CREATE TABLE `members` (
   `id` int(10) unsigned not null auto_increment,
   `username` varchar(20) not null,
   `password` char(35) not null,
   `email` varchar(255) not null,
   `date_joined` datetime not null,
   `login_count` int(10) unsigned default 0,
   PRIMARY KEY(`id`)
) ENGINE=InnoDB;
```