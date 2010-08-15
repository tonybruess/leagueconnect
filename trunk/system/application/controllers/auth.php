<?php

class Auth extends Controller
{
    function index()
    {
        header('Location: '.site_url('home'));
    }

    function check($token, $username)
    {
        $this->load->model('groups_model');

        $groups = $this->groups_model->getGroupNames();
        print_r($groups);
        $result = $this->validate_token($token, $username, $groups);
        var_dump($result);
        print_r($result);
        if(count($result['groups']) > 0)
        {
            $this->session->set_userdata('callsign', $username);
            $this->session->set_userdata('token', $token);
            $this->session->set_userdata('bzid', $result['bzid']);
            $this->session->set_userdata('groups', $result['groups']);

            $bzid = $result['bzid'];

            $perms = $this->groups_model->getTotalPermissions($result['groups']);
            $this->session->set_userdata('perms', $perms);

            if(!HasPerm(Permissions::Login))
            {
                header('Location: '.site_url('error/show/3'));
            }
            else
            {
                $this->load->model('players_model');
                $this->players_model->login($username, $bzid);

                $this->session->set_userdata('player', $this->players_model->getIDByBZID($bzid));

                header('Location: '.site_url('home'));
            }
        }
        else
        {
            //header('Location: '.site_url('error/show/4'));
        }
    }


    private function validate_token($token, $username, $groups = array(), $checkIP = true)
    {
        if (isset($token, $username) && strlen($token) > 0 && strlen($username) > 0)
        {
            $listserver = Array();
            $listserver['url'] = 'http://my.bzflag.org/db/';
            $listserver['url'].= '?action=CHECKTOKENS&checktokens=' . urlencode($username);
            if ($checkIP) $listserver['url'].= '@' . $_SERVER['REMOTE_ADDR'];
            $listserver['url'].= '%3D' . $token;
            if (is_array($groups) && sizeof($groups) > 0) $listserver['url'].= '&groups=' . implode("%0D%0A", $groups);
            $listserver['reply'] = trim(file_get_contents($listserver['url']));
            $listserver['reply'] = str_replace("\r\n", "\n", $listserver['reply']);
            $listserver['reply'] = str_replace("\r", "\n", $listserver['reply']);
            $listserver['reply'] = explode("\n", $listserver['reply']);
            foreach($listserver['reply'] as $line)
            {
                print $line."\n";
                if (substr($line, 0, strlen('TOKGOOD: ')) == 'TOKGOOD: ')
                {
                    if (strpos($line, ':', strlen('TOKGOOD: ')) == FALSE) continue;
                    $listserver['groups'] = explode(':', substr($line, strpos($line, ':', strlen('TOKGOOD: ')) + 1));
                } else if (substr($line, 0, strlen('BZID: ')) == 'BZID: ')
                {
                    list($listserver['bzid'], $listserver['username']) = explode(' ', substr($line, strlen('BZID: ')), 2);
                }
            }
            if (isset($listserver['bzid']) && is_numeric($listserver['bzid']))
            {
                $return = array();
                $return['username'] = $listserver['username'];
                $return['bzid'] = $listserver['bzid'];
                if (isset($listserver['groups']) && sizeof($listserver['groups']) > 0)
                {
                    $return['groups'] = $listserver['groups'];
                } else
                {
                    $return['groups'] = Array();
                }
                return $return;
            }
        }
    }
}

?>
