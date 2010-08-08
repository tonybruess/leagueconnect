<?php

/* 
 * Database class to work with the Database database.
 */

require_once('config.php');
require_once('include/session.php');
require_once('include/logging.php');
require_once('classes/player.php');
require_once('classes/team.php');
require_once('classes/page.php');
require_once('classes/ban.php');
require_once('classes/news-entry.php');
require_once('classes/message.php');

class Database
{
    private static $Connection = null;
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

        try
        {
            $server = DatabaseSettings::Server;
            $user = DatabaseSettings::User;
            $password = DatabaseSettings::Password;
            $database = DatabaseSettings::Database;
            $databasePath = DatabaseSettings::DatabasePath;

            switch(DatabaseSettings::Type)
            {
                case DatabaseType::MySQL:
                {
                    self::$Connection = new PDO("mysql:host=$server;dbname=$database", $user, $password);
                }
                break;

                case DatabaseType::SQLite:
                {
                    self::$Connection = new PDO("sqlite:$databasePath");
                }
                break;

                case DatabaseType::PostgreSQL:
                {
                    self::$Connection = new PDO("pgsql:host=$server;dbname=$database", $user, $password);
                }
                break;

                case DatabaseType::Oracle:
                {
                    self::$Connection = new PDO("OCI:dbname=$database", $user, $password);
                }

                case DatabaseType::Informix:
                {
                    self::$Connection = new PDO("informix:DSN=$database", $user, $password);
                }
                break;

                case DatabaseType::MSAccess:
                {
                    self::$Connection = new PDO("obdc:Driver={Microsoft Access Driver (*.mdb)};Dbq=$database;Uid=Admin");
                }
                break;

                default:
                {
                    die('Unknown database type '.DatabaseSettings::Type);
                }
            }
        }
        catch(PDOException $e)
        {
            self::$ConnectionError = $e->getMessage();
            Logging::LogError(self::$ConnectionError);

            die('Error connecting to database, see your error logfile for more information.');
        }

/*
        if(!self::Query('set time_zone = utc;'))
        {
            die('Server Owner: Run "mysql_tzinfo_to_sql /usr/share/zoneinfo|Database -u root -p" on your server to enable setting the timezone to UTC.');
        }
*/        
        return true;
    }

    /* PDOStatement or null */ private static function Query($sql) // After $sql should be a list of params
    {
        $numArgs = func_num_args();
        $params = array();

        for($i = 1; $i < $numArgs; $i++)
        {
            $arg = func_get_arg($i);

            if(is_array($arg))
            {
                foreach($arg as $item)
                {
                    $params[] = $item;
                }
            }
            else
            {
                $params[] = func_get_arg($i);
            }
        }

        $result = self::$Connection->prepare($sql);

        if($result->execute($params))
        {
            return $result;
        }
        else
        {
            // Log the error and return null
            $error = self::$Connection->errorInfo();

            Logging::LogError("SQL: $sql", 'Error:', $error[0], $error[1], $error[2]);

            echo 'There was a database error, see the error log for more information.';
            return null;
        }
    }

    /* unsigned int */ private static function NumRows($result)
    {
        if($result == null)
        {
            return 0;
        }
        else
        {
            return $result->rowCount();
        }
    }

    /* array or false */ private static function GetRow($result)
    {
        if($result == null)
        {
            return false;
        }
        else
        {
            return $result->fetch();
        }
    }

    /* array or false */ private static function GetRows($result)
    {
        if($result == null)
        {
            return false;
        }
        else
        {
            return $result->fetchAll();
        }
    }

    /*
     * The following function are interfaces for accessing the Database database
     * without having SQL scattered all throughout the code.
     */

    #region global
    private static /* array */ $Cache = array();

    /* cls or null */ private static function GetInfo($table, $id, &$cls) // cls is the class that corresponds to each row
    {
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
            $result = self::Query("SELECT * FROM ? WHERE `id`='?' LIMIT 1", $table, $id);

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
        if(!isset(self::$Cache[$table]))
        {
            self::$Cache[$table] = array();
        }

        $row = $cls->ToSQLRow();
        $cached = (isset(self::$Cache[$table][$id]) ? self::$Cache[$table][$id] : array());
        $sqlParts = array();
        $sqlArgs = array();

        foreach($row as $key=>$val)
        {
            if($val == null) // Skip null values
                continue;

            if(!isset($cached[$key]) || $val != $cached[$key]) // Only set if it is different
            {
                $sqlParts[] = "?='?'";
                $sqlArgs[] = $key;
                $sqlArgs[] = $val;
            }
        }

        if(count($sqlParts) == 0) // Nothing to update
        {
            return true;
        }

        $sql = implode(',', $sqlParts);

        if($id == '') // null id, insert it
        {
            if(self::Query("INSERT INTO ? SET $sql", $table, $sqlArgs))
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
            if(self::Query("UPDATE ? SET $sql WHERE `id`='?' LIMIT 1", $table, $sqlArgs, $id))
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
    
    /* void */ public static function GetGroupNames()
    {
        $groups = array();
        $result = self::Query("SELECT name FROM groups");
        
        while ($group = self::GetRow($result))
        {
            $groups[] = $group['name'];
        }
        
        return $groups;
    }
    
    /* void */ public static function CheckGroups($groups)
    {
        foreach($groups as $group)
        {
            $role = self::GetRow(self::Query("SELECT role FROM groups WHERE `name`='?'", $group));
            $permdata = self::GetRow(self::Query("SELECT permissions FROM roles WHERE `id`='?'", $role['role']));
            $perm = str_split($permdata['permissions']);
            
            if($perm[1] == '0')
            {
                session_destroy();
                header('Location: ?p=error&error=3');
            }
            else
            {
                $i = 0;
                foreach($perm as $p)
                {
                    if (!isset($_SESSION['perm'][$i]))
                    {
                        $_SESSION['perm'][$i] = $perm[$i];
                    }

                    $i++;
                }
            }
        }
    }

    #endregion

    #region players
    /* Player or null */ public static function GetPlayerInfo($id)
    {
        return self::GetInfo('players', $id, new Player());
    }

    /* bool */ public static function SetPlayerInfo($id, $player)
    {
        return self::SetInfo('players', $id, $player);
    }
    
    /* void */ public static function AddPlayer($name, $bzid)
    {
        self::Query("INSERT INTO players (`name`, `bzid`, `firstlogin`, `lastlogin`) VALUES ('?', '?', NOW(), NOW())", $name, $bzid);
    }

    /* void */ public static function PlayerLogin($name, $bzid)
    {
        self::Query("UPDATE players SET `name`='?', `lastlogin`=NOW() WHERE `bzid`='?'", $name, $bzid);
    }

    /* unsigned int */ public static function GetPlayerIDByBZID($bzid)
    {
        $result = self::Query("SELECT id FROM players WHERE `bzid`='?'", $bzid);

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
        return self::NumRows(self::Query("SELECT id FROM players WHERE `bzid`='?' LIMIT 1", $bzid)) != 0;
    }

    /* array of ints */ public static function GetPlayersByTeam($team)
    {
        $result = self::Query("SELECT id FROM players WHERE `team`='?'", $team);
        $ids = array();

        while($row = self::GetRow($result))
        {
            $ids[] = (int)$row['id'];
        }

        return $ids;
    }

    /* Player */ public static function GetPlayerByName($name)
    {
        $result = self::Query("SELECT id FROM players WHERE `name`='?' LIMIT 1", $name);

        if(self::NumRows($result) == 0)
        {
            return null;
        }
        else
        {
            return GetPlayerInfo($result['id']);
        }
    }

    #endregion

    #region teams

    /* Team or null */ public static function GetTeamInfo($id)
    {
        return self::GetInfo('teams', $id, new Team());
    }

    /* bool */ public static function SetTeamInfo($id, $team)
    {
        return self::SetInfo('teams', $id, $team);
    }

    /* void */ public static function AddTeam($name, $leader)
    {
        self::Query("INSERT INTO teams SET `name`='?', `leader`='?'", $name, $leader);

        $result = self::GetRow(self::Query("SELECT id FROM teams WHERE `name`='?'", $name));
        $teamID = $result['id'];

        self::Query("UPDATE players SET `team`='?' WHERE `id`='?'", $teamID, $leader);
    }

    /* bool */ public static function TeamExists($name)
    {
        return self::NumRows(self::Query("SELECT id FROM teams WHERE `name`='?' LIMIT 1", $name)) != 0;
    }
    
    /* bool */ public static function IsTeamLeader($player, $team=null)
    {
        if($team == null)
        {
            return self::NumRows(self::Query("SELECT id FROM teams WHERE `leader`='?' LIMIT 1", $player)) != 0;
        }
        else
        {
            return self::NumRows(self::Query("SELECT id FROM teams WHERE `leader`='?' AND `id`='?' LIMIT 1", $player, $team)) != 0;
        }
    }

    /* bool */ public static function IsTeamMember($player, $team=null)
    {
        if($team == null)
        {
            return self::NumRows(self::Query("SELECT id FROM players WHERE `id`='?' AND `team`!=NULL", $player)) != 0;
        }
        else
        {
            return self::NumRows(self::Query("SELECT id FROM players WHERE `id`='?' && `team`='?'", $player, $team)) != 0;
        }
    }

    /* array of Teams */ public static function GetTeamInfoList()
    {
        $result = self::Query('SELECT id FROM teams');
        $teams = array();

        while($row = self::GetRow($result))
        {
            $id = (int)$row['id'];
            $teams[] = self::GetInfo('teams', $id, new Team());
        }

        return $teams;
    }
    
    /* void */ public static function GenerateTeamButton($teamid, $leaderid)
    {
        if (!self::IsTeamMember(CurrentPlayer::$ID) && !self::IsTeamLeader(CurrentPlayer::$ID))
        {
            $action = 'join"';
            $action_value = $teamid;
            $button_value = 'Join';
        }
        elseif (self::IsTeamMember(CurrentPlayer::$ID, $teamid) || self::IsTeamLeader(CurrentPlayer::$ID, $teamid))
        {
            $action = 'abandon';
            $action_value = $teamid;
            $button_value = 'Abandon';   
        }

        return '<form method="GET"><input type="hidden" name="p" value="teams"><input type="hidden" name="' . $action . '" value="' . $action_value . '"><input type="submit" value="' . $button_value . '"></form>';
    
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
        if(!is_numeric($start) || !is_numeric($count))
            return null;

        $result = self::Query("SELECT id FROM ? ORDER BY created DESC LIMIT ?,?", $page, $start, $count);
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
        return self::NumRows(self::Query("SELECT id FROM ?", $page));
    }

    /* bool */ public static function AddEntry($author, $message, $page)
    {
        if(self::Query("INSERT INTO ? SET `author`='?', `message`='?'", $page, $author, $message))
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
        if(self::Query("UPDATE ? SET `author`='?', `message`='?' WHERE `id` ='?'", $page, $author, $message, $messageid))
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
        return self::GetRow(self::Query("SELECT * FROM ? WHERE `id`='?'", $page, $id));
    }
    
    #endregion
    
    #region pagecontent
    
    /* text */ public static function GetPageContents($pageid)
    {
        $id = -1;
        
        $lookup = array(
            // help
            "SELECT content FROM pages WHERE `id`='1'" => 1,
            // contact
            "SELECT content FROM pages WHERE `id`='2'" => 2,
            );
        
        $page = array_keys($lookup, $pageid);
        $result = self::Query($page[0]);        
        $data = self::GetRow($result);
        
        return $data['content'];
    }

    /* list of items */ public static function GetPageName($idea)
    {
        $id = -1;
        
        $lookup = array(
            1 => 'Help',
            2 => 'Contact'
        );
        
        $result = self::Query("SELECT name FROM pages WHERE id='?' LIMIT 1", $lookup[idea]);
        
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
        return self::Query("UPDATE pages SET `name`='?', `content`='?' WHERE `id`='?'", $name, $text, $id) ? true : false;
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

    /* unsigned int */ public static function NumberOfNewMessages()
    {
        return self::NumRows(self::Query("SELECT id FROM messages WHERE `read`=FALSE AND `to`='?' AND `to_deleted`=FALSE", CurrentPlayer::$ID));
    }
    
    /* bool */ public static function MarkMessageRead($message)
    {
        return self::Query("UPDATE messages SET `read`=TRUE WHERE `id` = '?' LIMIT 1", $message) ? true : false;
    }
    
    /* bool */ public static function Delete($id)
    {
        $message = self::GetMessageInfo($id);

        if($message->From == CurrentPlayer::$ID)
        {
            // Sender deleted it
            $message->SenderDeleted = true;
        }
        
        else
        {
            // Recipient deleted it
            $message->RecipientDeleted = true;
        }

        return self::SetMessageInfo($id, $message)
    }
    
    
    /* bool */ public static function SendMessage($to, $subject, $contents)
    {
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

    /* array of Messages */ public static function GetMessages($type=MessageType::All)
    {
        $requirements = array();

        $ToMe = "`to`='".CurrentPlayer::$ID."'";
        $FromMe = "`from`=".CurrentPlayer::$ID."'";
        $Read = "`read`=TRUE";
        $UnRead = "`read`=FALSE";

        if($type & MessageType::Read)
        {
            $requirements[] = "($ToMe || $Read)";
        }
        if($type & MessageType::UnRead)
        {
            $requirements[] = "($ToMe || $UnRead)";
        }
        if($type & MessageType::FromMe)
        {
            $requirements[] = $FromMe;
        }
        if($type & MessageType::ToMe || $type & MessageType::All)
        {
            $requirements[] = $ToMe;
        }

        $sqlRequirements = implode(' || ', $requirements);

        $result = self::Query("SELECT id FROM messages WHERE $sqlRequirements AND recipient_deleted=FALSE");
        $messages = array();

        while($row = self::GetRow($result))
        {
            $messages[] = self::GetMessageInfo($row['id']);
        }

        return $messages;
    }

    /* bool */ public static function HasUnreadMessages()
    {
        return count(self::GetMessages(MessageType::UnRead)) != 0;
    }
    
    #endregion
}

// Connect to the database
if(!Database::Connect())
{
    die(Database::$ConnectionError);
}

?>
