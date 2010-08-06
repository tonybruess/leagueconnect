<?php

/* 
 * MySQL class to work with the MySQL database.
 */

require_once('./config.php');
require_once('./classes/player.php');
require_once('./classes/team.php');
require_once('./include/bbcode.php');

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
            $error = mysql_error() . "\n" . $sql . "\n" . '------' . "\n";

            if(!file_exists(Config::ErrorLogFile))
            {
                file_put_contents(Config::ErrorLogFile, '');
            }

            file_put_contents(Config::ErrorLogFile, $error, FILE_APPEND);
            
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

        return mysql_num_rows(self::Query("SELECT id FROM players WHERE `bzid`='$bzid' LIMIT 1")) != 0;
    }

    /* Player */ public static function GetPlayerInfo($id)
    {
        self::CheckConnection();

        $id = self::Sanitize($id);

        $result = self::Query("SELECT * FROM players WHERE `id` = '$id' LIMIT 1");

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

        if(self::Query("UPDATE players SET $sql WHERE `id`='$id' LIMIT 1"))
        {
            self::$PlayerInfoCache[$id] = $row; // Update cache
        }
    }

    /* array of Players */ public static function GetPlayersByTeam($team)
    {
        self::CheckConnection();

        $team = self::Sanitize($team);

        $result = self::Query("SELECT * FROM players WHERE `team`='$team'");
        $players = array();

        while($row = mysql_fetch_assoc($result))
        {
            $player = new Player();
            $player->FromSQLRow($row);
            $players[] = $player;

            self::$PlayerInfoCache[$row['id']] = $row;
        }

        return $players;
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
    
    /* bool */ public static function IsTeamLeader($player, $team=null)
    {
        self::CheckConnection();

        $player = self::Sanitize($player);
        $team = self::Sanitize($team);

        if($team == '')
        {
            return mysql_num_rows(self::Query("SELECT id FROM teams WHERE `leader`='$player' LIMIT 1")) != 0;
        }
        else
        {
            return mysql_num_rows(self::Query("SELECT id FROM teams WHERE `leader`='$player' && `id`='$team' LIMIT 1")) != 0;
        }
    }

    /* bool */ public static function IsTeamMember($player, $team=null)
    {
        self::CheckConnection();

        $player = self::Sanitize($player);
        $team = self::Sanitize($team);

        if($team == '')
        {
            return mysql_num_rows(self::Query("SELECT id FROM players WHERE `id`='$player' && `team`!=NULL")) != 0;
        }
        else
        {
            return mysql_num_rows(self::Query("SELECT id FROM players WHERE `id`='$player' && `team`='$team'")) != 0;
        }
    }

    /* array of Teams */ public static function GetTeamInfoList()
    {
        self::CheckConnection();

        $result = self::Query('SELECT * FROM teams');
        $teams = array();

        while($row = mysql_fetch_assoc($result))
        {
            $team = new Team();
            $team->FromSQLRow($row);
            $teams[] = $team;

            self::$TeamInfoCache[$row['id']] = $row;
        }

        return $teams;
    }

   #endregion
   
   #region pages
  /* list of items */ public static function GetPage($pageid)
  {
        self::CheckConnection();
 
         $pageid = self::Sanitize($pageid);
        $id = -1;

        $lookup = array(
            // news
            "SELECT * FROM news ORDER BY created DESC" => 1,
            // help
            "SELECT * FROM pages WHERE `id`='2'" => 2,
            // contact
            "SELECT * FROM pages WHERE `id`='3'" => 3,
            // bans
            "SELECT * FROM bans ORDER BY created DESC" => 4
        );
        
        $page = array_keys($lookup, $pageid);
        $result = self::Query($page[0]);        
        $data = mysql_fetch_assoc($result);
        
        if($data['type'] == '2'){
            return $data['text'];
        } else {
            $result = self::Query($page[0]);
            while($row = mysql_fetch_assoc($result))
            {
            ?>
        <div id="item">
            <div id="header">
                <div id="author">By: <?php echo $row['author'] ?></div>
                <div id="time"><?php echo $row['created'] ?></div>
            </div>
            <div id="data"><?php echo FormatToBBCode($row['message']) ?></div>
        </div>
        <br><br>
            <?php
            }
        }
  }

  /* list of items */ public static function GetPageName($idea)
  {
        self::CheckConnection();
 
         $idea = self::Sanitize($idea);
        $id = -1;

        $lookup = array(
            'News' => 1,
            'Help' => 2,
            'Contact' => 3,
            'Bans' => 4
        );

        $result = self::Query("SELECT name FROM pages WHERE id='{$lookup[$idea]}' LIMIT 1");

        if(mysql_num_rows($result) == 0)
        {
            return 'Unknown';
        }
        else
        {
            $row = mysql_fetch_assoc($result);
            return $row['name'];
        }
    }
    
    /* bool */ public static function AddEntry($author, $message, $date, $page)
    {
        self::CheckConnection();
        
        $author = self::Sanitize($author);
        $message = self::Sanitize($message);
        $date = self::Sanitize($date);
        $id = -1;

        $lookup = array(
            // news
            "INSERT INTO news SET `author`='$author', `message`='$message', `created`='$date'" => 1,
            // help
            "DO 0" => 2,
            // contact
            "DO 0" => 3,
            // bans
            "INSERT INTO bans SET `author`='$author', `message`='$message', `created`='$date'" => 4,
        );
        
        $query = array_keys($lookup, $page);
        
        
        if(self::Query($query[0]))
        {
            return true;
        }
        else
        {
            return false;
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