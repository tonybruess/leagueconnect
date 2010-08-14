<?php

class BansModel extends Model
{
    public $Bans = array();

    function BansModel()
    {
        parent::Model();
    }

    function &getBans($start=0, $step=20)
    {
        $this->Bans = $this->db->get('bans')->result();
        return $this->Bans;
    }

    function getNumTotal()
    {
        return $this->db->count_all('bans');
    }
}

?>
