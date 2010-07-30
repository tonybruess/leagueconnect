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
        $PlayerInfoCache[$id] = $row;

        $player = new Player();
        $player->FromSQLRow($row);

        return $player;
    }

    /* void */ public static function SetPlayerInfo($id, $player)
    {
        self::CheckConnection();

        $id = self::Sanitize($id);
        $row = $player->ToSQLRow();
        $cached = $PlayerInfoCache[$id];
        $sqlParts = array();

        foreach($row as $key=>$val)
        {
            if($val == $cached[$key]) // Don't update things that haven't changed
                continue;

            $sqlParts[] = "$key='".self::Sanitize($val)."'";
        }

        $sql = implode(', ', $sqlParts);

        mysql_query("UPDATE `players` SET $sql WHERE `id`=$id LIMIT 1");

        $PlayerInfoCache[$id] = $row;
    }
}

// FIXME: Only call Connect when needed
if(!MySQL::Connect())
{
    die(MySQL::$ConnectionError);
}

?>