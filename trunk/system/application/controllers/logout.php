<?php

class Logout extends Controller
{
    function Logout()
    {
        parent::Controller();
    }

    function index()
    {
        $this->session->unset_userdata('callsign');
        $this->session->unset_userdata('token');
        $this->session->unset_userdata('bzid');
        $this->session->unset_userdata('groups');
        $this->session->unset_userdata('perms');
        $this->session->unset_userdata('player');

        header('Location: '.site_url('home'));
    }
}

?>
