<?php

class Groups_Model extends Model
{
    function Groups_Model()
    {
        parent::Model();
    }

    function getGroupNames()
    {
        return $this->db->select('name')->from('groups')->get()->result();
    }

    function getGroup($id)
    {
        return $this->db->get('groups')->where('id', $id)->row();
    }
}

?>
