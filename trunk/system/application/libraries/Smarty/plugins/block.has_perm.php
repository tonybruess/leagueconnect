<?php



function smarty_block_has_perm($params, $content)
{
    $permissions = array(
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

    if(@$_SESSION['perm'][$permissions[$params['perm']]])
    {
        return $content;
    }
    else
    {
        return '';
    }
}

?>
