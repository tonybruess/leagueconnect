<?php
/* 
 * CurrentUser class stores information related to the user browsing this page.
 */

require_once('./classes/player.php');
require_once('./include/mysql.php');

/* static */ class CurrentPlayer
{
    public static /* unsigned int */ $ID = 0;
    public static /* string */ $Name = 'guest';
    public static /* unsigned int */ $BZID = 0;
    public static /* ??? */ $Team = '';
    public static /* ??? */ $RecordMatch = '';
    public static /* bool or int */ $NewMail = false;
    public static /* bool or int */ $NewNews = false;
    public static /* bool or int */ $NewMatch = false;
    public static /* string */ $Comment = '';
    public static /* datetime */ $FirstLogin = 0;
    public static /* datetime */ $LastLogin = 0;
    public static /* string */ $Country = '';
    public static /* string */ $State = '';
    public static /* string */ $Email = '';
    public static /* string */ $AIM = '';
    public static /* string */ $MSN = '';
    public static /* string */ $Jabber = '';
    public static /* array of strings */ $AltNicks = array();
    public static /* string */ $IRCNick = '';
    public static /* string */ $PublicEmail = '';
    public static /* bool */ $Banned = false;
    public static /* bool */ $Deleted = false;

    private static $initialized = false;
    private static $found = false;

    // Get data from MySQL database
    /* bool */ private static function Initialize($userid = null)
    {
        if($initialized)
        {
            return $found;
        }

        $initialized = true;

        $id = ($userid != null ? $userid : self::$UserID);

        if($id == 0) // Null player
        {
            $found = false;
        }
        else
        {
            try
            {
                // Copy the MySQL player to the current player
                $player = MySQL::GetPlayerInfo($id);

                self::$ID = $player->ID;
                self::$Name = $player->Name;
                self::$BZID = $player->BZID;
                self::$Team = $player->Team;
                self::$RecordMatch = $player->RecordMatch;
                self::$NewMail = $player->NewMail;
                self::$NewNews = $player->NewNews;
                self::$NewMatch = $player->NewMatch;
                self::$Comment = $player->Comment;
                self::$FirstLogin = $player->FirstLogin;
                self::$LastLogin = $player->LastLogin;
                self::$Country = $player->Country;
                self::$State = $player->State;
                self::$Email = $player->Email;
                self::$AIM = $player->AIM;
                self::$MSN = $player->MSN;
                self::$Jabber = $player->Jabber;
                self::$AltNicks = $player->AltNicks;
                self::$IRCNick = $player->IRCNick;
                self::$PublicEmail = $player->PublicEmail;
                self::$Banned = $player->Banned;
                self::$Deleted = $player->Deleted;

                $found = true;
            }
            catch(Exception $e)
            {
                $found = false;
            }
        }

        return $found;
    }
}
?>
