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
        $this->ID = $row['id'];
        $this->Name = $row['name'];
        $this->Created = strtotime($row['created']);
        $this->Leader = $row['leader'];
        $this->CoLeaders = explode(',', $row['coleaders']);
        $this->Activity = (int)$row['activity'];
        $this->Rank = (int)$row['rank'];
        $this->Logo = $row['logo'];
        $this->Description = $row['description'];
        $this->Closed = ($row['closed'] != 0);
        $this->Inactive = ($row['inactive'] != 0);
        $this->Deleted = ($row['deleted'] != 0);

        return self;
    }

    /* array of strings */ public function ToSQLRow()
    {
        $row = array();

        $row['id'] = $this->ID;
        $row['name'] = $this->Name;
        $row['created'] = $this->Created;
        $row['leader'] = $this->Leader;
        $row['coleaders'] = implode(',', $this->CoLeaders);
        $row['activity'] = $this->Activity;
        $row['rank'] = $this->Rank;
        $row['logo'] = $this->Logo;
        $row['description'] = $this->Description;
        $row['closed'] = ($this->Closed ? 1 : 0);
        $row['inactive'] = ($this->Inactive ? 1 : 0);
        $row['deleted'] = ($this->Deleted ? 1 : 0);

        return $row;
    }
}
?>
