<?php
/*
 * Information related to a ban.
 */

class Ban
{
    public /* unsigned int */ $ID = null;
    public /* string */ $Player = null;
    public /* string */ $Banner = null;
    public /* unsigned int */ $BZID = null;
    public /* unsigned int */ $Duration = null;
    public /* string */ $IPAddress = null;
    public /* string */ $Hostmask = null;
    public /* string */ $Reason = null;
    public /* unsigned int */ $Created = null;

    /* Ban */ public function FromSQLRow($row)
    {
        $this->ID = (int)$row['id'];
        $this->Player = $row['player'];
        $this->Banner = $row['banner'];
        $this->BZID = (int)$row['bzid'];
        $this->Duration = (int)$row['duration'];
        $this->IPAddress = $row['ipaddress'];
        $this->Hostmask = $row['hostmask'];
        $this->Reason = $row['reason'];
        $this->Created = strtotime($row['created']);

        return $this;
    }

    /* array */ public function ToSQLRow()
    {
        $row = array();

        $row['id'] = $this->ID;
        $row['player'] = $this->Player;
        $row['banner'] = $this->Banner;
        $row['bzid'] = $this->BZID;
        $row['duration'] = $this->Duration;
        $row['ipaddress'] = $this->IPAddress;
        $row['hostmask'] = $this->Hostmask;
        $row['reason'] = $this->Reason;
        $row['created'] = $this->Created;

        return $row;
    }
}

?>
