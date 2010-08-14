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
        $this->Bans = $this->db->get('bans')->result();//, $start, $start + $step)->result();
        return $this->Bans;
    }

    function getBansArray($start=0, $step=20)
    {
        return ObjectToArray($this->getBans($start, $step));
    }

    function getBanByID($id)
    {
        $result = $this->db->where('id', $id)->limit(1)->get('bans')->result();
        return $result[0];
    }

    function getNumBans()
    {
        return $this->db->count_all('bans');
    }

    function update($id, $message)
    {
        $this->db->where('id', $id)->update('bans', array('message' => $message));
    }

    function insert($author, $message)
    {
        $this->db->insert('bans', array('author' => $author, 'message' => $message));
    }
}

?>
