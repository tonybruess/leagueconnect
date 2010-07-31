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

    private static /* array */ $PlayerInfoCache = array();

    /* void */ public static function AddPlayer($name, $bzid)
    {
        self::CheckConnection();

        $name = self::Sanitize($name);
        $bzid = self::Sanitize($bzid);

        mysql_query("INSERT INTO players (`name`, `bzid`, `firstlogin`, `lastlogin`) VALUES ('$name', '$bzid', NOW(), NOW())");
    }

    /* void */ public static function PlayerLogin($name, $bzid)
    {
        self::CheckConnection();

        $name = self::Sanitize($name);
        $bzid = self::Sanitize($bzid);

        mysql_query("UPDATE players SET `name`='$name', `lastlogin`=NOW() WHERE `bzid`='$bzid'");
    }

    /* unsigned int */ public static function GetPlayerIDByBZID($bzid)
    {
        self::CheckConnection();

        $bzid = self::Sanitize($bzid);

        $result = mysql_query("SELECT id FROM players WHERE `bzid`='$bzid'");

        if(mysql_num_rows($result) == 0)
        {
            return 0;
        }
        else
        {
            $row = mysql_fetch_assoc($result);
            return $row['id'];
        }
    }

    /* bool */ public static function PlayerExists($bzid)
    {
        self::CheckConnection();

        $bzid = self::Sanitize($bzid);

        return mysql_count_rows(mysql_query("SELECT id FROM players WHERE `bzid`='$bzid'")) != 0;
    }

    /* Player */ public static function GetPlayerInfo($id)
    {
        self::CheckConnection();

        $id = self::Sanitize($id);

        $result = mysql_query("SELECT * FROM `players` WHERE `id` = '$id' LIMIT 1");

        if(mysql_num_rows($result) == 0)
        {
            throw new Exception('Player not found.');
        }

        $row = mysql_fetch_array($result);
        self::$PlayerInfoCache[$id] = $row;

        $player = new Player();
        $player->FromSQLRow($row);

        return $player;
    }

    /* void */ public static function SetPlayerInfo($id, $player)
    {
        self::CheckConnection();

        $id = self::Sanitize($id);
        $row = $player->ToSQLRow();
        $cached = self::$PlayerInfoCache[$id];
        $sqlParts = array();

        foreach($row as $key=>$val)
        {
            if($val == $cached[$key]) // Don't update things that haven't changed
                continue;

            $sqlParts[] = "$key='".self::Sanitize($val)."'";
        }

        $sql = implode(', ', $sqlParts);

        mysql_query("UPDATE `players` SET $sql WHERE `id`=$id LIMIT 1");

        self::$PlayerInfoCache[$id] = $row;
    }
}

// FIXME: Only call Connect when needed
if(!MySQL::Connect())
{
    die(MySQL::$ConnectionError);
}

?>