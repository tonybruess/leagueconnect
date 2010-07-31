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
    public static $Errors = array();
    public static $ErrorSQL = array();
    
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

    /* resource or false */ public static function Query($sql)
    {
        $result = mysql_query($sql);

        if($result)
        {
            return $result;
        }
        else
        {
            // Log the error and return false
            $error = mysql_error() . '\n' . $sql . '\n' . '------' . '\n';
            return false;
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
            die('Please upgrade your version of PHP to at least 4.3.0');
        }
        
        return $str;
    }

    /*
     * The following function are interfaces for accessing the MySQL database
     * without having SQL scattered all throughout the code.
     */

    #region players table

    private static /* array */ $PlayerInfoCache = array();
    
    /* void */ public static function AddPlayer($name, $bzid)
    {
        self::CheckConnection();

        $name = self::Sanitize($name);
        $bzid = self::Sanitize($bzid);

        self::Query("INSERT INTO players (`name`, `bzid`, `firstlogin`, `lastlogin`) VALUES ('$name', '$bzid', NOW(), NOW())");
    }

    /* void */ public static function PlayerLogin($name, $bzid)
    {
        self::CheckConnection();

        $name = self::Sanitize($name);
        $bzid = self::Sanitize($bzid);

        self::Query("UPDATE players SET `name`='$name', `lastlogin`=NOW() WHERE `bzid`='$bzid'");
    }

    /* unsigned int */ public static function GetPlayerIDByBZID($bzid)
    {
        self::CheckConnection();

        $bzid = self::Sanitize($bzid);

        $result = self::Query("SELECT id FROM players WHERE `bzid`='$bzid'");

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

        return mysql_num_rows(self::Query("SELECT id FROM players WHERE `bzid`='$bzid'")) != 0;
    }

    /* Player */ public static function GetPlayerInfo($id)
    {
        self::CheckConnection();

        $id = self::Sanitize($id);

        $result = self::Query("SELECT * FROM `players` WHERE `id` = '$id' LIMIT 1");

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
        $cached = (isset(self::$PlayerInfoCache[$id]) ? self::$PlayerInfoCache[$id] : null);
        $sqlParts = array();

        foreach($row as $key=>$val)
        {
            if(($cached == null && $val != null) || ($cached != null && $val != $cached[$key]))
            {
                $sqlParts[] = "$key='".self::Sanitize($val)."'";
            }
        }

        if(count($sqlParts) == 0)
            return;

        $sql = implode(',', $sqlParts);

        if(self::Query("UPDATE `players` SET $sql WHERE `id`='$id' LIMIT 1"))
        {
            self::$PlayerInfoCache[$id] = $row; // Update cache
        }
    }
    #endregion

    #region teams table
    private static $TeamInfoCache = array();

    /* void */ public static function AddTeam($name, $leader)
    {
        self::CheckConnection();

        $name = self::Sanitize($name);
        $leader = self::Sanitize($leader);

        self::Query("INSERT INTO teams (`name`, `created`, `leader`) VALUES ('$name', NOW(), '$leader')");
        $team_id = mysql_fetch_assoc(self::Query("SELECT id FROM teams WHERE `name`='$name'"));
        self::Query("UPDATE players SET `team`='".$team_id['id']."' WHERE `id`='$leader'");
    }

    /* bool */ public static function TeamExists($name)
    {
        self::CheckConnection();

        $name = self::Sanitize($name);

        return mysql_num_rows(self::Query("SELECT id FROM teams WHERE `name`='$name' LIMIT 1")) != 0;
    }

    /* Team */ public static function GetTeamInfo($id)
    {
        self::CheckConnection();

        $id = self::Sanitize($id);

        $result = self::Query("SELECT * FROM teams WHERE `id`='$id' LIMIT 1");
        $row = mysql_fetch_assoc($result);

        self::$TeamInfoCache[$id] = $row;

        $team = new Team();
        $team->FromSQLRow($row);

        return $team;
    }

    /* void */ public static function SetTeamInfo($id, $team)
    {
        self::CheckConnection();

        $id = self::Sanitize($id);
        $row = $team->ToSQLRow();
        $cached = self::$TeamInfoCache[$id];
        $sqlParts = array();

        foreach($row as $key=>$val)
        {
            if($val == $cached[$key]) // Skip fields that have the same value as before
                continue;

            $sqlParts[] = "$key='".self::Sanitize($val)."'";
        }

        if(count($sqlParts) == 0) // Nothing to update
            return;

        $sql = implode(',', $sqlParts);

        if(self::Query("UPDATE teams SET $sql WHERE `id`='$id' LIMIT 1"))
        {
            self::$TeamInfoCache[$id] = $row; // Update cache
        }
    }
    
    /* bool */ public static function isTeamLeader($playerid,$team)
    {
    	self::CheckConnection();
    	
    	if(!$team)
    	{
			return mysql_num_rows(self::Query("SELECT id FROM teams WHERE `leader`='$playerid' LIMIT 1")) != 0;	
    	} else {
			return mysql_num_rows(self::Query("SELECT id FROM teams WHERE `leader`='$playerid' && `id`='$team' LIMIT 1")) != 0;	
    	}
     	
    }

    /* bool */ public static function isTeamMember($playerid,$team)
    {
    	self::CheckConnection();
    	
    	if(!$team)
    	{
 			return mysql_num_rows(self::Query("SELECT id FROM teams WHERE `leader`='$playerid' LIMIT 1")) != 0;	
   		} else {
			return mysql_num_rows(self::Query("SELECT id FROM teams WHERE `leader`='$playerid' && `id`='$team' LIMIT 1")) != 0;	
    	}
    	
    }

   #endregion
}

// FIXME: Only call Connect when needed
if(!MySQL::Connect())
{
    die(MySQL::$ConnectionError);
}

?>