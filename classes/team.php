<?php
/* 
 * Class storing information relating to a team.
 */

class Team
{
    public /* unsigned int */ $ID = null;
    public /* string */ $Name = null;
    public /* timestamp */ $Created = null;
    public /* unsigned int */ $Leader = null;
    public /* array of unsigned ints */ $CoLeaders = null;
    public /* int */ $Activity = null;
    public /* unsigned int */ $Rank = null;
    public /* string (url) */ $Logo = null;
    public /* string */ $Description = null;
    public /* bool */ $Closed = null;
    public /* bool */ $Inactive = null;
    public /* bool */ $Deleted = null;

    /* Team */ public function FromSQLRow($row)
    {
        self::$ID = $row['id'];
        self::$Name = $row['name'];
        self::$Created = strtotime($row['created']);
        self::$Leader = $row['leader'];
        self::$CoLeaders = explode(',', $row['coleaders']);
        self::$Activity = (int)$row['activity'];
        self::$Rank = (int)$row['rank'];
        self::$Logo = $row['logo'];
        self::$Description = $row['description'];
        self::$Closed = ($row['closed'] != 0);
        self::$Inactive = ($row['inactive'] != 0);
        self::$Deleted = ($row['deleted'] != 0);

        return self;
    }

    /* array of strings */ public function ToSQLRow()
    {
        $row = array();

        $row['id'] = self::$ID;
        $row['name'] = self::$Name;
        $row['created'] = self::$Created;
        $row['leader'] = self::$Leader;
        $row['coleaders'] = implode(',', self::$CoLeaders);
        $row['activity'] = self::$Activity;
        $row['rank'] = self::$Rank;
        $row['logo'] = self::$Logo;
        $row['description'] = self::$Description;
        $row['closed'] = (self::$Closed ? 1 : 0);
        $row['inactive'] = (self::$Inactive ? 1 : 0);
        $row['deleted'] = (self::$Deleted ? 1 : 0);

        return $row;
    }
}
?>
