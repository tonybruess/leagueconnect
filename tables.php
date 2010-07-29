<?php
/*
create table groups (`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,`name` VARCHAR(100),`role` BIGINT,`enabled` INT);
create table players (`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT, `name` VARCHAR(100), `bzid` INT, `recordmatch` INT, `newmail` INT, `newnews` INT, `newmatch` INT, `banned` INT, `deleted` INT, `comment` TEXT, `role` INT, `firstlogin` INT, `lastlogin` INT, `country` INT, `state` INT, `email` VARCHAR(100), `aim` VARCHAR(100), `msn` VARCHAR(100), `jabber` VARCHAR(100), altnik1 VARCHAR(100), altnik2 VARCHAR(100), ircnik VARCHAR(100), emailpub INT);
create table roles (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,`name` TEXT,`permissions` VARCHAR(100));
create table settings (`setting` VARCHAR(100),`value` VARCHAR(100));
*/
?>