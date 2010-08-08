<?php

/* 
 * MySQL class to work with the MySQL database.
 */

require_once('config.php');
require_once('classes/player.php');
require_once('classes/team.php');
require_once('classes/page.php');
require_once('classes/ban.php');
require_once('classes/entry.php');

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

    /* resource or false */ private static function Query($sql)
    {
        $result = mysql_query($sql);

        if($result)
        {
            return $result;
        }
        else
        {
            // Log the error and return false
            $error = 'MySQL Error: ' . mysql_error() . "\n" . "SQL: $sql\n" . "-------\n";

            if(!file_exists(Config::ErrorLogFile))
            {
                file_put_contents(Config::ErrorLogFile, '');
            }

            file_put_contents(Config::ErrorLogFile, $error, FILE_APPEND);

            throw new Exception('MySQL Error: '.mysql_error());
            return false;
        }
    }

    /* unsigned int */ private static function NumRows($result)
    {
        if(!$result)
        {
            return 0;
        }
        else
        {
            return mysql_num_rows($result);
        }
    }

    /* array or false */ private static function GetRow($result)
    {
        if(!$result)
        {
            return false;
        }
        else
        {
            return mysql_fetch_assoc($result);
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

    #region global
    private static /* array */ $Cache = array();

    /* cls or null */ private static function GetInfo($table, $id, &$cls) // cls is the class that corresponds to each row
    {
        self::CheckConnection();

        $table = self::Sanitize($table);
        $id = self::Sanitize($id);

        if(!isset(self::$Cache[$table]))
        {
            self::$Cache[$table] = array();
        }

        if(isset(self::$Cache[$table][$id]))
        {
            $row = self::$Cache[$table][$id];
        }
        else
        {
            $result = self::Query("SELECT * FROM $table WHERE `id` = '$id' LIMIT 1");

            if(self::NumRows($result) == 0)
            {
                return null;
            }

            $row = self::GetRow($result);
            self::$Cache[$table][$id] = $row;
        }

        $cls->FromSQLRow($row);

        return $cls;
    }

    /* bool */ private static function SetInfo($table, $id, $cls)
    {
        self::CheckConnection();

        $table = self::Sanitize($table);
        $id = self::Sanitize($id);

        if(!isset(self::$Cache[$table]))
        {
            self::$Cache[$table] = array();
        }

        $row = $cls->ToSQLRow();
        $cached = (isset(self::$Cache[$table][$id]) ? self::$Cache[$table][$id] : array());
        $sqlParts = array();

        foreach($row as $key=>$val)
        {
            if($val == null) // Skip null values
                continue;

            if(!isset($cached[$key]) || $val != $cached[$key]) // Only set if it is different
            {
                $sqlParts[] = "$key='".self::Sanitize($val)."'";
            }
        }

        if(count($sqlParts) == 0) // Nothing to update
        {
            return true;
        }

        $sql = implode(',', $sqlParts);

        if($id == '') // null id, insert it
        {
            if(self::Query("INSERT INTO $table SET $sql"))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else // update
        {
            if(self::Query("UPDATE $table SET $sql WHERE `id`='$id' LIMIT 1"))
            {
                self::$Cache[$table][$id] = $row; // Update the cache
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    #endregion

    #region players
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

        if(self::NumRows($result) == 0)
        {
            return 0;
        }
        else
        {
            $row = self::GetRow($result);
            return $row['id'];
        }
    }

    /* bool */ public static function PlayerExists($bzid)
    {
        self::CheckConnection();

        $bzid = self::Sanitize($bzid);

        return self::NumRows(self::Query("SELECT id FROM players WHERE `bzid`='$bzid' LIMIT 1")) != 0;
    }

    /* Player or null */ public static function GetPlayerInfo($id)
    {
        return self::GetInfo('players', $id, new Player());
    }

    /* bool */ public static function SetPlayerInfo($id, $player)
    {
        return self::SetInfo('players', $id, $player);
    }

    /* array of ints */ public static function GetPlayersByTeam($team)
    {
        self::CheckConnection();

        $team = self::Sanitize($team);

        $result = self::Query("SELECT id FROM players WHERE `team`='$team'");
        $ids = array();

        while($row = self::GetRow($result))
        {
            $ids[] = (int)$row['id'];
        }

        return $ids;
    }
    #endregion

    #region teams table

    /* void */ public static function AddTeam($name, $leader)
    {
        self::CheckConnection();

        $name = self::Sanitize($name);
        $leader = self::Sanitize($leader);

        self::Query("INSERT INTO teams (`name`, `created`, `leader`) VALUES ('$name', NOW(), '$leader')");
        $team_id = self::GetRow(self::Query("SELECT id FROM teams WHERE `name`='$name'"));
        self::Query("UPDATE players SET `team`='".$team_id['id']."' WHERE `id`='$leader'");
    }

    /* bool */ public static function TeamExists($name)
    {
        self::CheckConnection();

        $name = self::Sanitize($name);

        return self::NumRows(self::Query("SELECT id FROM teams WHERE `name`='$name' LIMIT 1")) != 0;
    }

    /* Team or null */ public static function GetTeamInfo($id)
    {
        return self::GetInfo('teams', $id, new Team());
    }

    /* bool */ public static function SetTeamInfo($id, $team)
    {
        return self::SetInfo('teams', $id, $team);
    }
    
    /* bool */ public static function IsTeamLeader($player, $team=null)
    {
        self::CheckConnection();

        $player = self::Sanitize($player);
        $team = self::Sanitize($team);

        if($team == '')
        {
            return self::NumRows(self::Query("SELECT id FROM teams WHERE `leader`='$player' LIMIT 1")) != 0;
        }
        else
        {
            return self::NumRows(self::Query("SELECT id FROM teams WHERE `leader`='$player' && `id`='$team' LIMIT 1")) != 0;
        }
    }

    /* bool */ public static function IsTeamMember($player, $team=null)
    {
        self::CheckConnection();

        $player = self::Sanitize($player);
        $team = self::Sanitize($team);

        if($team == '')
        {
            return self::NumRows(self::Query("SELECT id FROM players WHERE `id`='$player' && `team`!=NULL")) != 0;
        }
        else
        {
            return self::NumRows(self::Query("SELECT id FROM players WHERE `id`='$player' && `team`='$team'")) != 0;
        }
    }

    /* array of Teams */ public static function GetTeamInfoList()
    {
        self::CheckConnection();

        $result = self::Query('SELECT id FROM teams');
        $teams = array();

        while($row = self::GetRow($result))
        {
            $id = (int)$row['id'];
            $teams[] = self::GetInfo('teams', $id, new Team());
        }

        return $teams;
    }
    #endregion
   
    #region pages
    /* Page or null */ public static function GetPageInfo($id)
    {
        return self::GetInfo('pages', $id, new Page());
    }

    /* bool */ public static function SetPageInfo($id, $page)
    {
        return self::SetInfo('pages', $id, $page);
    }

    /* array of strings */ public static function GetPageNames()
    {
        self::CheckConnection();

        $result = self::Query('SELECT id FROM pages');
        $names = array();

        while($row = self::GetRow($result))
        {
            $id = (int)$row['id'];
            $page = self::GetPageInfo($id);
            $names[] = $page->Name;
        }

        return $names;
    }
    #endregion

    #region news and bans
    /* NewsEntry or null */ public static function GetEntryInfo($id, $page)
    {
        return self::GetInfo($page, $id, new Entry());
    }

    /* bool */ public static function SetEntryInfo($id, $newsEntry, $page)
    {
        return self::SetInfo($page, $id, $newsEntry);
    }

    /* array of NewsEntries */ public static function GetEntries($start, $count, $page)
    {
        self::CheckConnection();

        $start = self::Sanitize($start);
        $count = self::Sanitize($count);

        $result = self::Query("SELECT id FROM $page ORDER BY created DESC LIMIT $start,$count");
        $entries = array();

        while($row = self::GetRow($result))
        {
            $id = (int)$row['id'];
            $entries[] = self::GetEntryInfo($id, $page);
        }

        return $entries;
    }

    /* unsigned int */ public static function GetNumEntries($page)
    {
        self::CheckConnection();

        return self::NumRows(self::Query("SELECT id FROM $page"));
    }

    /* bool */ public static function AddEntry($author, $message, $page)
    {
        self::CheckConnection();
        
        $author = self::Sanitize($author);
        $message = self::Sanitize($message);
        $date = self::Sanitize($date);

        if(self::Query("INSERT INTO $page SET `author`='$author', `message`='$message', `created`=NOW()"))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /* bool */ public static function UpdateEntry($author, $message, $page, $messageid)
    {
        self::CheckConnection();
        
        $author = self::Sanitize($author);
        $message = self::Sanitize($message);
        $date = self::Sanitize($date);
        $messageid = self::Sanitize($messageid);
 
        if(self::Query("UPDATE $page SET `author`='$author', `message`='$message' WHERE `id` ='$messageid'"))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /* array */ public static function FetchEntry($id, $page)
    {
        self::CheckConnection();
        
        $id = MySQL::Sanitize($id);
        
        return self::GetRow(self::Query("SELECT * FROM $page WHERE `id`='$id'"));
    }
    
    #endregion
    
    #region pagecontent
    
    /* text */ public static function GetPageContents($pageid)
    {
        self::CheckConnection();
        
        $pageid = self::Sanitize($pageid);
        $id = -1;
        
        $lookup = array(
            // help
            "SELECT content FROM pages WHERE `id`='1'" => 1,
            // contact
            "SELECT content FROM pages WHERE `id`='2'" => 2,
            );
        
        $page = array_keys($lookup, $pageid);
        $result = self::Query($page[0]);        
        $data = mysql_fetch_assoc($result);
        
        return $data['content'];
    }

    /* list of items */ public static function GetPageName($idea)
    {
        self::CheckConnection();
        
        $idea = self::Sanitize($idea);
        $id = -1;
        
        $lookup = array(
            'Help' => 1,
            'Contact' => 2,
        );
        
        $result = self::Query("SELECT name FROM pages WHERE id='{$lookup[$idea]}' LIMIT 1");
        
        if(self::NumRows($result) == 0)
        {
            return 'Unknown';
        }
        else
        {
            $row = self::GetRow($result);
            return $row['name'];
        }
    }
    
    /* bool */ public static function UpdatePage($name, $text, $id)
    {
        self::CheckConnection();
        
        $name = self::Sanitize($name);
        $text = self::Sanitize($text);
        $id = self::Sanitize($id);
        
        return self::Query("UPDATE pages SET `name`='$name', `content`='$text' WHERE `id`='$id'") ? true : false;
    }
    
    #endregion
    
    #region messages

    /* Message or null */ public static function GetMessageInfo($id)
    {
        return self::GetInfo('messages', $id, new Message());
    }

    /* bool */ public static function SetMessageInfo($id, $message)
    {
        return self::GetInfo('messages', $id, $message);
    }
    
    /* void */ public static function MarkMessageRead($message)
    {
        self::CheckConnection();

        return self::Query("UPDATE messages SET `read` = TRUE WHERE `id` = '$message' LIMIT 1") ? true : false;
    }
    
    /* void */ public static function deleted($messageid)
    {
        self::CheckConnection();

        $messageclean = MySQL::Sanitize($messageid);
        $sql = 'UPDATE messages SET ';

        $result = self::Query("SELECT * FROM messages WHERE `id` = '$messageclean' && (`from` = '".CurrentPlayer::$ID."' || `to` = '".CurrentPlayer::$ID."') LIMIT 1");
        $message = self::GetRow($result);

        if($message['from'] == $uid)
        {
            // From Deleted
            $sql .= "`from_deleted` = '1' " ;
        }
        
        else
        {
            // To Deleted
            $sql .= "`to_deleted` = '1' ";
        }
       
        return self::Query($sql . "WHERE `id` = '$messageid' LIMIT 1") ? true : false;
    }
    
    
    /* bool */ public static function SendMessage($to, $subject, $contents)
    {
        self::CheckConnection();

        $message = new Message();
        $message->To = $to;
        $message->From = $from;
        $message->Subject = $subject;
        $message->Contents = $contents;

        if($message->To == $message->From)
        {
            return false;
        }
        else
        {
            return self::SetMessageInfo(null, $message);
        }
    }
    
    /* void */ public static function GetMessage($id)
    {
        self::CheckConnection();   
        
        $id = self::Sanitize($id);
        
        $message = self::GetRow(self::Query("SELECT * FROM messages WHERE `id` = '$id' && (`from` = '".CurrentPlayer::$ID."' || `to` = '".CurrentPlayer::$ID."') LIMIT 1"));
    
        if($message['from'] != CurrentPlayer::$ID)
            self::MarkMessageRead($id);

        return $message;
    
    }

    /* void */ public static function FetchMessages($type)
    {
        self::CheckConnection();   
        
        switch($type)
        {
            case 'sent':
                $q = "SELECT * FROM messages WHERE `from` = '".CurrentPlayer::$ID."' &&  `from_deleted` = FALSE";
                break;
            default:
                $q = "SELECT * FROM messages WHERE `to` = '".CurrentPlayer::$ID."' &&  `to_deleted` = FALSE";
                break;
        }
        
        $id = self::Sanitize($id);
        $result = self::Query($q);
        $messages = array();
        
        while($row = self::GetRow($result))
        {
            $id = (int)$row['id'];
            $messages[] = self::GetRow(self::Query("SELECT * FROM messages WHERE `id`='$id'"));
        }
        
        return $messages ? $messages : false;
    }
    
    #endregion
}

// FIXME: Only call Connect when needed
if(!MySQL::Connect())
{
    die(MySQL::$ConnectionError);
}

?>
