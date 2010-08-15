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
        $num = $this->db->get('players')->where('bzid', $bzid)->count_all_results();

        return $num != 0;
    }

    function login($callsign, $bzid)
    {
        if($this->playerExists($bzid))
        {
            $this->db->update('lastLogin', time());
        }
        else
        {
            $this->add($callsign, $bzid);
        }
    }
}

?>
