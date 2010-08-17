<?php

/* static */ class Permissions
{
    const Login = 1;
    const ViewMail = 2;
    const SendMail = 3;
    const EditMail = 4;
    const ViewPages = 5;
    const AddPages = 6;
    const EditPages = 7;
    const ViewMatches = 8;
    const AddMatches = 9;
    const EditMatches = 10;
    const ViewTeams = 11;
    const AddTems = 12;
    const JoinTeams = 13;
    const EditTeams = 14;
    const ReviveTeams = 15;
    const ViewPlayers = 16;
    const EditPlayers = 17;
    const DeletePlayers = 18;
    const EnterMatch = 19;
    const ViewLogs = 20;

    public static $Array = array(
        'Login' => 1,
        'ViewMail' => 2,
        'SendMail' => 3,
        'EditMail' => 4,
        'ViewPages' => 5,
        'AddPages' => 6,
        'EditPages' => 7,
        'ViewMatches' => 8,
        'AddMatches' => 9,
        'EditMatches' => 10,
        'ViewTeams' => 11,
        'AddTems' => 12,
        'JoinTeams' => 13,
        'EditTeams' => 14,
        'ReviveTeams' => 15,
        'ViewPlayers' => 16,
        'EditPlayers' => 17,
        'DeletePlayers' => 18,
        'EnterMatch' => 19,
        'ViewLogs' => 20
    );
}

function HasPerm($perm)
{
    $CI = &get_instance();

    $perms = $CI->session->userdata('perms');
    $perms = (int)$perms;
    $permID = -1;

    if(is_numeric($perm))
    {
        $permID = $perm;
    }
    else
    {
        if(!isset(Permissions::$Array[$perm]))
        {
            throw new Exception("Unknown permission $perm");
        }
        else
        {
            $permID = Permissions::$Array[$perm];
        }
    }

    if($perms & (1 << $permID) == 1)
        return true;

    else
        return false;
}

?>
