<?php

/* 
 * MySQL class to work with the MySQL database.
 */

require_once('./config.php');

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
}

if(!MySQL::Connect())
{
    die(MySQL::$ConnectionError);
}

?>