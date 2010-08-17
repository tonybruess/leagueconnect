<?php

class Players_Model extends Model
{
    function Players_Model()
    {
        parent::Model();
    }

    function add($callsign, $bzid)
    {
        $data = array(
            'name' => $callsign,
            'bzid' => $bzid,
            'firstLogin' => time(),
            'lastLogin' => time()
        );

        $this->db->insert('players', $data);
    }

    function playerExists($bzid)
    {
        $num = $this->db->where('bzid', $bzid)->get('players');

        return $this->db->count_all_results() != 0;
    }

    function login($callsign, $bzid)
    {
        if($this->playerExists($bzid))
        {
            $this->db->update('players', array('lastLogin' => time()));
        }
        else
        {
            $this->add($callsign, $bzid);
        }
    }

    function getIDByBZID($bzid)
    {
        $result = $this->db->select('id')->where('bzid', $bzid)->get('players')->result();

        return $result->id;
    }
}

?>
