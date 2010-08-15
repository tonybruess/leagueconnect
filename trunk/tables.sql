-- WARNING: This will delete all information stored in your leagueconnect database
-- Be sure to back up your data before running this script.
-- This needs to be run as root or a user that has DROP, CREATE, and INSERT permissions.
-- You can load this into MySQL with the following command: mysql -u root -p -D {{database_name}} < tables.sql

DROP TABLE IF EXISTS groups;
CREATE TABLE groups (
    `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, -- Unique identifier for this group
    `name` VARCHAR(30), -- Name of the group
    `role` INT UNSIGNED, -- Role ID of the permission from the roles table
    `enabled` BOOLEAN NOT NULL DEFAULT FALSE -- TRUE if enabled, FALSE otherwise
);

DROP TABLE IF EXISTS players;
CREATE TABLE players (
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
    `pubemail` VARCHAR(320), -- Public Email
    `banned` BOOLEAN, -- TRUE if banned, FALSE if not
    `deleted` BOOLEAN -- TRUE if deleted, FALSE if not
);

DROP TABLE IF EXISTS roles;
CREATE TABLE roles (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for this role
    `name` VARCHAR(256) NOT NULL, -- Name of the role
    `permissions` VARCHAR(100) NOT NULL -- Permissions granted to this role
);

DROP TABLE IF EXISTS settings;
CREATE TABLE settings (
    `setting` VARCHAR(100) NOT NULL PRIMARY KEY,
    `value` VARCHAR(100)
);

DROP TABLE IF EXISTS teams;
CREATE TABLE teams (
    `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, -- Unique identifier for this team
    `name` VARCHAR(128) NOT NULL, -- Name of the team
    `created` TIMESTAMP NOT NULL DEFAULT NOW(), -- Date the team was created
    `leader` INT UNSIGNED NOT NULL, -- Player ID of the leader of the team
    `coleaders` TEXT DEFAULT "", -- List of player IDs for the co-leaders
    `activity` INT DEFAULT 0, -- Activity?
    `rank` INT UNSIGNED DEFAULT 0, -- Rank of this team
    `logo` VARCHAR(128) DEFAULT "", -- URL for the team's logo
    `description` TEXT DEFAULT "", -- Description of the team
    `closed` BOOLEAN DEFAULT FALSE, -- TRUE if this is closed and no one may join, FALSE otherwise
    `inactive` BOOLEAN DEFAULT FALSE, -- TRUE if this team is inactive, FALSE otherwise
    `deleted` BOOLEAN DEFAULT FALSE -- TRUE if this team is deleted, FALSE otherwise
);

DROP TABLE IF EXISTS messages;
CREATE TABLE messages (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for this message
    `subject` VARCHAR( 255 ) NULL , -- Subject of the message
    `contents` TEXT NOT NULL , -- Contents of the message
    `from` INT( 11 ) UNSIGNED NOT NULL , -- Player ID who sent the message
    `to` INT( 11 ) UNSIGNED NOT NULL , -- Player ID of the recepient
    `read` BOOLEAN NOT NULL DEFAULT FALSE, -- TRUE if read, FALSE otherwise
    `sender_deleted` BOOLEAN NOT NULL DEFAULT FALSE, -- TRUE if the player who sent the message deleted it, FALSE otherwise
    `recipient_deleted` BOOLEAN NOT NULL DEFAULT FALSE, -- TRUE if the player who received the message deleted it, FALSE otherwise
    `created` TIMESTAMP NOT NULL DEFAULT NOW() -- Time the message was created
 );

DROP TABLE IF EXISTS pages;
CREATE TABLE pages (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for the page
    `name` VARCHAR ( 255 ), -- Name of the page
    `content` TEXT -- Page text. Not necessary
);


DROP TABLE IF EXISTS news;
CREATE TABLE news (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for the entry
    `author` VARCHAR ( 255 ), -- Name of the page
    `message` TEXT, -- Message to be displayed
    `created` TIMESTAMP NOT NULL DEFAULT NOW() -- When the message was posted
);

DROP TABLE IF EXISTS bans;
CREATE TABLE bans (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for the entry
    `author` VARCHAR ( 255 ), -- Name of the page
    `message` TEXT, -- Message to be displayed
    `created` TIMESTAMP NOT NULL DEFAULT NOW() -- When the message was posted
);

-- basic setup
insert into roles (`name`,`permissions`) VALUES ('Site Admin','11111111111111111111111');
insert into groups (`name`,`role`,`enabled`) VALUES ('GU.LEAGUE', '1', TRUE);
insert into groups (`name`,`role`,`enabled`) VALUES ('BZBUREAU.WEBADMIN', '1', TRUE);
insert into pages (`name`,`content`) VALUES ('Help','Help');
insert into pages (`name`,`content`) VALUES ('Contact','Contact');