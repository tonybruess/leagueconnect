<?php
/* 
 * This class defines data stored for each player.
 */

class Player
{
    public /* unsigned int */ $ID = null;
    public /* string */ $Name = null;
    public /* unsigned int */ $BZID = null;
    public /* unsigned int */ $Team = null;
    public /* bool */ $NewMail = null;
    public /* bool */ $NewNews = null;
    public /* bool */ $NewMatch = null;
    public /* string */ $Comment = null;
    public /* datetime */ $FirstLogin = null;
    public /* datetime */ $LastLogin = null;
    public /* string */ $Country = null;
    public /* string */ $State = null;
    public /* string */ $Email = null;
    public /* string */ $AIM = null;
    public /* string */ $MSN = null;
    public /* string */ $Jabber = null;
    public /* array of strings */ $AltNicks = null;
    public /* string */ $IRCNick = null;
    public /* string */ $PublicEmail = null;
    public /* bool */ $Banned = null;
    public /* bool */ $Deleted = null;

    /* Player */ public function FromSQLRow($row)
    {
        self::$AIM = $row['aim'];
        self::$AltNicks = (strlen($row['altNicks']) == 0 ? array() : explode(',', $row['altNicks']));
        self::$BZID = (int)$row['bzid'];
        self::$Banned = ($row['banned'] != 0);
        self::$Comment = $row['comment'];
        self::$Country = $row['country'];
        self::$Deleted = ($row['deleted'] != 0);
        self::$Email = $row['email'];
        self::$FirstLogin = strtotime($row['firstlogin']);
        self::$ID = (int)$row['id'];
        self::$IRCNick = $row['ircnick'];
        self::$Jabber = $row['jabber'];
        self::$LastLogin = strtotime($row['lastlogin']);
        self::$Location = $row['location'];
        self::$MSN = $row['msn'];
        self::$Name = $row['name'];
        self::$NewMail = $row['newmail'];
        self::$NewMatch = $row['newmatch'];
        self::$NewNews = $row['newnews'];
        self::$PublicEmail = $row['pubemail'];
        self::$Team = (int)$row['team'];
    }

    /* array of strings */ public function ToSQLRow()
    {
        $row = array();
        $row['aim'] = self::$AIM;
        $row['altNicks'] = implode(',', self::$AltNicks);
        $row['bzid'] = self::$BZID;
        $row['banned'] = (self::$Banned ? 1 : 0);
        $row['comment'] = self::$Comment;
        $row['country'] = self::$Country;
        $row['deleted'] = (self::$Deleted ? 1 : 0);
        $row['email'] = self::$Email;
        $row['firstlogin'] = self::$FirstLogin;
        $row['id'] = self::$ID;
        $row['ircnick'] = self::$IRCNick;
        $row['jabber'] = self::$Jabber;
        $row['lastlogin'] = self::$LastLogin;
        $row['location'] = self::$Location;
        $row['msn'] = self::$MSN;
        $row['name'] = self::$Name;
        $row['newmail'] = (self::$NewMail ? 1 : 0);
        $row['newnews'] = (self::$NewNews ? 1 : 0);
        $row['newmatch'] = (self::$NewMatch ? 1 : 0);
        $row['pubemail'] = self::$PublicEmail;
        $row['team'] = self::$Team;

        return $row;
    }
}
?>
