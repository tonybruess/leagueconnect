use leagueconnect;

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
    `comment` TEXT, -- Comment?
    `firstlogin` TIMESTAMP, -- Time when the player first logged in
    `lastlogin` TIMESTAMP,  -- Time when the player last logged in
    `country` VARCHAR(50), -- Country of residence
    `location` VARCHAR(100),   -- Location of residence
    `email` VARCHAR(320), -- Email address
    `aim` VARCHAR(320), -- AOL Instant Messenger address
    `msn` VARCHAR(320), -- MSN Instant Messenger address
    `jabber` VARCHAR(320), -- Jabber address
    `altNicks` TEXT, -- Alternative nicknames
    `ircnick` VARCHAR(30), -- IRC nickname
    `pubemail` VARCHAR(320), -- Public Email?
    `banned` BOOLEAN, -- TRUE if banned, FALSE if not
    `deleted` BOOLEAN -- TRUE if deleted, FALSE if not
);

create table roles (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for this role
    `name` VARCHAR(256) NOT NULL, -- Name of the role
    `permissions` VARCHAR(100) NOT NULL -- Permissions granted to this role
);

create table settings (
    `setting` VARCHAR(100) NOT NULL PRIMARY KEY,
    `value` VARCHAR(100)
);

create table teams (
    `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, -- Unique identifier for this team
    `name` VARCHAR(128) NOT NULL, -- Name of the team
    `created` TIMESTAMP NOT NULL, -- Date the team was created
    `leader` INT UNSIGNED NOT NULL, -- Player ID of the leader of the team
    `coleaders` TEXT DEFAULT "", -- List of player IDs for the co-leaders
    `activity` INT DEFAULT 0, -- Activity?
    `rank` INT UNSIGNED DEFAULT 0, -- Rank of this team
    `logo` VARCHAR(128) DEFAULT "", -- URL for the team's logo
    `description` TEXT DEAFULT "", -- Description of the team
    `closed` BOOLEAN DEFAULT FALSE, -- TRUE if this is closed and no one may join, FALSE otherwise
    `inactive` BOOLEAN DEFAULT FALSE, -- TRUE if this team is inactive, FALSE otherwise
    `deleted` BOOLEAN DEFAULT FALSE -- TRUE if this team is deleted, FALSE otherwise
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

create table pages (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for the page
	`name` VARCHAR ( 255 ), -- Name of the page
	`description` TEXT -- Short description
);

create table entrys (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for the entry
	`author` VARCHAR ( 255 ), -- Name of the page
	`message` TEXT, -- Message to be displayed
	`created` TIMESTAMP NOT NULL, -- When the message was posted
	`page` INT -- Which page to put the entry on
);

-- basic setup
insert into roles (`name`,`permissions`) VALUES ('Site Admin','11111111111111111111111');
insert into groups (`name`,`role`,`enabled`) VALUES ('GU.LEAGUE', '1', TRUE);
insert into pages (`name`,`description`) VALUES ('News','News Page');
insert into pages (`name`,`description`) VALUES ('Help','Help Page');
insert into pages (`name`,`description`) VALUES ('Contact','Contact Page');
insert into pages (`name`,`description`) VALUES ('Bans','Bans Page');