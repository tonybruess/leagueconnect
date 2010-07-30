<?php

/* 
 * MySQL class to work with the MySQL database.
 */

require_once('./config.php');
require_once('./classes/player.php');

class MySQL
{
    const Server = MySQLSettings::Server;
    const User = MySQLSettings::User;
    const Password = MySQLSettings::Password;
    const Database = MySQLSettings::Database;
    
    public static $Connected = false;
    public static $ConnectionError = '';
    
    /* bool */ public static function Connect()
    {
        if(self::$Connected)
        {
            return true;
        }

        if(!mysql_connect(self::Server, self::User, self::Password))
        {
            self::$ConnectionError = mysql_error();
            return false;
        }
        
        if(!mysql_select_db(self::Database))
        {
            self::$ConnectionError = mysql_error();
            return false;
        }

/*
        if(!mysql_query('set time_zone = utc;'))
        {
            die('Server Owner: Run "mysql_tzinfo_to_sql /usr/share/zoneinfo|mysql -u root -p" on your server to enable setting the timezone to UTC.');
        }
*/        
        return true;
    }

    /* bool */ public static function CheckConnection()
    {
        if(!self::Connect())
        {
            die(self::$ConnectionError);
            return false;
        }
        else
        {
            return true;
        }
    }
    
    /* string */ public static function Sanitize($str)
    {
        if(function_exists('mysql_real_escape_string'))
        {
            $str = mysql_real_escape_string($str);
        }
        else
        {
            $str = addslashes($str);
        }
        
        return $str;
    }

    /*
     * The following function are interfaces for accessing the MySQL database
     * without having SQL scattered all throughout the code.
     */

    /* Player */ public static function GetPlayerInfo($id)
    {
        self::CheckConnection();

        $result = mysql_query("SELECT * FROM `players` WHERE `ID` = '$id' LIMIT 1");

        if(mysql_num_rows($result) == 0)
        {
            throw new Exception('Player not found.');
        }

        $row = mysql_fetch_array($result);

        $player = new Player();
        $player->AIM = $row['aim'];
        $player->AltNicks = (strlen($row['altNicks']) == 0 ? array() : explode('|', $row['altNicks']));
        $player->BZID = (int)$row['bzid'];
        $player->Banned = ($row['banned'] != 0);
        $player->Comment = $row['comment'];
        $player->Country = $row['country'];
        $player->Deleted = ($row['deleted'] != 0);
        $player->Email = $row['email'];
        $player->FirstLogin = strtotime($row['firstlogin']);
        $player->ID = (int)$row['id'];
        $player->IRCNick = $row['ircnick'];
        $player->Jabber = $row['jabber'];
        $player->LastLogin = strtotime($row['lastlogin']);
        $player->MSN = $row['msn'];
        $player->Name = $row['name'];
        $player->NewMail = $row['newmail'];
        $player->NewMatch = $row['newmatch'];
        $player->NewNews = $row['newnews'];
        $player->PublicEmail = $row['pubemail'];
        $player->RecordMatch = $row['recordmatch'];
        $player->State = $row['state'];
        $player->Team = (int)$row['team'];

        return $player;
    }
}

// FIXME: Only call Connect when needed
if(!MySQL::Connect())
{
    die(MySQL::$ConnectionError);
}

?>