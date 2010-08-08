<?php
/* 
 * CurrentUser class stores information related to the user browsing this page.
 */

require_once('./classes/player.php');
require_once('./include/database.php');

/* static */ class CurrentPlayer
{
    public static /* unsigned int */ $ID = 0;
    public static /* string */ $Name = 'guest';
    public static /* unsigned int */ $BZID = 0;
    public static /* unsigned int */ $Team = '';
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

    private static $upToDate = false;
    private static $found = false;

    /* bool */ public static function HasPerm($perm)
    {
        return @$_SESSION['perm'][$perm];
    }

    // Get data from Database database
    /* bool */ public static function UpdateInfo($userid = null)
    {
        if(self::$upToDate)
        {
            return self::$found;
        }

        self::$upToDate = true;

        $id = ($userid != null ? $userid : self::$ID);

        if($id == 0) // Null player
        {
            self::$found = false;
        }
        else
        {
            try
            {
                // Copy the Database player to the current player
                $player = Database::GetPlayerInfo($id);

                self::$ID = $player->ID;
                self::$Name = $player->Name;
                self::$BZID = $player->BZID;
                self::$Team = $player->Team;
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

                self::$found = true;
            }
            catch(Exception $e)
            {
                self::$found = false;
            }
        }

        return self::$found;
    }
}

// FIXME: Only run this when needed
if(isset($_SESSION['player']))
{
    CurrentPlayer::UpdateInfo($_SESSION['player']);
}
?>
