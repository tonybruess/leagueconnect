<?php

class Groups_Model extends Model
{
    function Groups_Model()
    {
        parent::Model();
    }

    function getGroupNames()
    {
        $result = $this->db->select('name')->from('groups')->get()->result();

        $name = array();

        foreach($result as $obj)
        {
            $name[] = $obj->name;
        }
        
        return $name;
    }

    function getGroup($id)
    {
        return $this->db->get('groups')->where('id', $id)->row();
    }

    function getTotalPermissions($groups)
    {
        $totalPerms = 0;

        foreach($groups as $group)
        {
            $role = $this->db->select('role')->where('name', $group)->get('groups')->row();
            $perms = $this->db->select('permissions')->get('roles')->where('id', $role['role']);
            $perms = (int)$perms;

            $totalPerms |= $perms;
        }

        return $totalPerms;
    }
}

?>
