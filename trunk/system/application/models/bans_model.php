<?php

class Bans_Model extends Model
{
    public $Bans = array();

    function Bans_Model()
    {
        parent::Model();
    }

    function &getBans($start=0, $step=20)
    {
        $this->Bans = $this->db->get('bans', $start, $start + $step)->result();
        return $this->Bans;
    }

    function getNumBans()
    {
        return $this->db->count_all('bans');
    }
}

?>
