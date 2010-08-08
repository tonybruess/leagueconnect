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
        $this->AIM = $row['aim'];
        $this->AltNicks = (strlen($row['altNicks']) == 0 ? array() : explode(',', $row['altNicks']));
        $this->BZID = (int)$row['bzid'];
        $this->Banned = ($row['banned'] != 0);
        $this->Comment = $row['comment'];
        $this->Country = $row['country'];
        $this->Deleted = ($row['deleted'] != 0);
        $this->Email = $row['email'];
        $this->FirstLogin = strtotime($row['firstlogin']);
        $this->ID = (int)$row['id'];
        $this->IRCNick = $row['ircnick'];
        $this->Jabber = $row['jabber'];
        $this->LastLogin = strtotime($row['lastlogin']);
        $this->Location = $row['location'];
        $this->MSN = $row['msn'];
        $this->Name = $row['name'];
        $this->PublicEmail = $row['pubemail'];
        $this->Team = (int)$row['team'];

        return $this;
    }

    /* array of strings */ public function ToSQLRow()
    {
        $row = array();
        $row['aim'] = $this->AIM;
        $row['altNicks'] = implode(',', $this->AltNicks);
        $row['bzid'] = $this->BZID;
        $row['banned'] = ($this->Banned ? 1 : 0);
        $row['comment'] = $this->Comment;
        $row['country'] = $this->Country;
        $row['deleted'] = ($this->Deleted ? 1 : 0);
        $row['email'] = $this->Email;
        $row['firstlogin'] = $this->FirstLogin;
        $row['id'] = $this->ID;
        $row['ircnick'] = $this->IRCNick;
        $row['jabber'] = $this->Jabber;
        $row['lastlogin'] = $this->LastLogin;
        $row['location'] = $this->Location;
        $row['msn'] = $this->MSN;
        $row['name'] = $this->Name;
        $row['pubemail'] = $this->PublicEmail;
        $row['team'] = $this->Team;

        return $row;
    }
}
?>
