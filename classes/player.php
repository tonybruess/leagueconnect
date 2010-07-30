<?php
/* 
 * This class defines data stored for each player.
 */

class Player
{
    public /* unsigned int */ $ID;
    public /* string */ $Name;
    public /* unsigned int */ $BZID;
    public /* ??? */ $Team;
    public /* ??? */ $RecordMatch;
    public /* bool or int */ $NewMail;
    public /* bool or int */ $NewNews;
    public /* bool or int */ $NewMatch;
    public /* string */ $Comment;
    public /* datetime */ $FirstLogin;
    public /* datetime */ $LastLogin;
    public /* string */ $Country;
    public /* string */ $State;
    public /* string */ $Email;
    public /* string */ $AIM;
    public /* string */ $MSN;
    public /* string */ $Jabber;
    public /* array of strings */ $AltNicks;
    public /* string */ $IRCNick;
    public /* string */ $PublicEmail;
    public /* bool */ $Banned;
    public /* bool */ $Deleted;
}
?>
