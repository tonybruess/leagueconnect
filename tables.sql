
create table groups (
    `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, -- Unique identifier for this group
    `name` VARCHAR(30), -- Name of the group
    `role` INT UNSIGNED, -- Role ID of the permission from the roles table
    `enabled` BOOLEAN NOT NULL DEFAULT FALSE -- TRUE if enabled, FALSE otherwise
);

create table players (
    `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, -- Unique identifier for each player
    `name` VARCHAR(30), -- Callsign
    `bzid` INT UNSIGNED, -- BZFlag callsign unique identifier
    `team` INT UNSIGNED, -- ID of the team this player is a member of
    `recordmatch` INT, -- ???
    `newmail` BOOLEAN, -- TRUE if mail awaits, FALSE otherwise
    `newnews` BOOLEAN, -- TRUE if news awaits, FALSE otherwise
    `newmatch` BOOLEAN, -- TRUE if a match request awaits, FALSE otherwise
    `comment` TEXT, -- Comment?
    `firstlogin` TIMESTAMP, -- Time when the player first logged in
    `lastlogin` TIMESTAMP,  -- Time when the player last logged in
    `country` VARCHAR(100), -- Country of residence
    `state` VARCHAR(100),   -- State of residence
    `email` VARCHAR(100), -- Email address
    `aim` VARCHAR(100), -- AOL Instant Messenger address
    `msn` VARCHAR(100), -- MSN Instant Messenger address
    `jabber` VARCHAR(100), -- Jabber address
    `altNicks` TEXT, -- Alternative nicknames
    `ircnick` VARCHAR(100), -- IRC nickname
    `pubemail` VARCHAR(100), -- Public Email?
    `banned` BOOLEAN, -- TRUE if banned, FALSE if not
    `deleted` BOOLEAN -- TRUE if deleted, FALSE if not
);

create table roles (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for this role
    `name` TEXT, -- Name of the role
    `permissions` VARCHAR(100) -- Permissions granted to this role
);

create table settings (
    `setting` VARCHAR(100) PRIMARY KEY,
    `value` VARCHAR(100)
);

create table teams (
    `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, -- Unique identifier for this team
    `name` VARCHAR(128), -- Name of the team
    `created` TIMESTAMP, -- Date the team was created
    `leader` UNSIGNED INT, -- Player ID of the leader of the team
    `coleaders` TEXT, -- List of player IDs for the co-leaders
    `activity` INT, -- Activity?
    `rank` UNSIGNED INT, -- Rank of this team
    `logo` VARCHAR(128), -- URL for the team's logo
    `description` TEXT, -- Description of the team
    `closed` BOOLEAN, -- TRUE if this is closed and no one may join, FALSE otherwise
    `inactive` BOOLEAN, -- TRUE if this team is inactive, FALSE otherwise
    `deleted` BOOLEAN -- TRUE if this team is deleted, FALSE otherwise
);

create table messages (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for this message
    `subject` VARCHAR( 255 ) NULL , -- Subject of the message
    `message` TEXT NOT NULL , -- Contents of the message
    `from` INT( 11 ) NOT NULL , -- Player ID who sent the message
    `to` INT( 11 ) NOT NULL , -- Player ID of the recepient
    `read` BOOLEAN NOT NULL DEFAULT FALSE, -- TRUE if read, FALSE otherwise
    `from_deleted` BOOLEAN NOT NULL DEFAULT FALSE, -- TRUE if the player who sent the message deleted it, FALSE otherwise
    `to_deleted` BOOLEAN NOT NULL DEFAULT FALSE, -- TRUE if the player who received the message deleted it, FALSE otherwise
    `created` TIMESTAMP NOT NULL -- Time the message was created
 );

-- basic setup
insert into roles (`name`,`permissions`) VALUES ('Site Admin','11111111111111111111111');
insert into groups (`name`,`role`,`enabled`) VALUES ('GU.LEAGUE', '1', TRUE);