<?php
/*
create table groups (`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,`name` VARCHAR(100),`role` BIGINT,`enabled` INT);
create table players (`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT, `name` VARCHAR(100), `bzid` INT, `recordmatch` INT, `newmail` INT, `newnews` INT, `newmatch` INT, `banned` INT, `deleted` INT, `comment` TEXT, `role` INT, `firstlogin` INT, `lastlogin` INT, `country` INT, `state` INT, `email` VARCHAR(100), `aim` VARCHAR(100), `msn` VARCHAR(100), `jabber` VARCHAR(100), altnik1 VARCHAR(100), altnik2 VARCHAR(100), ircnik VARCHAR(100), emailpub INT);
create table roles (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,`name` TEXT,`permissions` VARCHAR(100));
create table settings (`setting` VARCHAR(100),`value` VARCHAR(100));

TABLE teams
+-----+----------+--------------+------------+------+------------+--------------+------------+----------+
| id  | LeaderID | ColeadersIDs | PlayersIDs | Rank | Name       | CreationDate | LogoURL    | SiteText |
|int11|  int11   |   text256    |   text256  | int8 | varchar128 |    int32     | varchar512 | text1024 |
+-----+----------+--------------+------------+------+------------+--------------+------------+----------+  

* basic setup *
insert into roles (`name`,`permissions`) VALUES ('Site Admin','11111111111111111111111');
insert into groups (`name`,`role`,`enabled`) VALUES ('GU.LEAGUE','1','1');
*/
?>