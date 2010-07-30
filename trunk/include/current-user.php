<?php
/* 
 * CurrentUser class stores information related to the user browsing this page.
 */

/* static */ class CurrentUser
{
    public static $UserID = 0;
    public static $Callsign = 'guest';
    public static $BZID = 0;
    public static $Team = '';
    public static $Comment = '';
    public static $Country = '';
    public static $State = '';
    public static $Email = '';
    public static $AIM = '';
    public static $MSN = '';
    public static $Jabber = '';
    public static $AltNicks = array();
    public static $IRCNick = '';
    public static $Banned = false;
    public static $Deleted = false;
}
?>
