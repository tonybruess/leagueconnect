    <div id="menu">
        <ul>
<?php

require_once('include/database.php');
require_once('classes/permissions.php');
require_once('include/bbcode.php');

function PrintMenuEntry($name, $title, $perm = null)
{
    global $page;
    
    if($perm != null && !CurrentPlayer::HasPerm($perm)) // No permission
    {
        return;
    }

    echo "            <li><a href='?p=$name'".($page == $name ? ' class="active"' : '').">".$title.'</a></li>'."\n";
}

PrintMenuEntry('index', 'Home');

if(CurrentPlayer::HasPerm(Permissions::ViewMail))
{
    echo "            <li><a href='?p=mail'";

    if($page == 'mail')
    {
        echo ' class="active"';
    }
    else if(hasMail())
    {
        echo ' class="new"';
    }

    echo '>Mail</a></li>'."\n";
}

if(!isset($_SESSION['callsign']))
{
    $authPage = urlencode('http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']).'/authenticate.php?token=%TOKEN%&username=%USERNAME%');
    echo "            <li><a href='http://my.bzflag.org/weblogin.php?url=$authPage'>Login</a></li>\n";
}

PrintMenuEntry('news', 'News');
PrintMenuEntry('matches', 'Matches');
PrintMenuEntry('teams', 'Teams');
PrintMenuEntry('players', 'Players');
PrintMenuEntry('help', 'Help');
PrintMenuEntry('contact', 'Contact');
PrintMenuEntry('bans', 'Bans');

if(isset($_SESSION['callsign']))
{
    echo '            <li><a href="?p=logout">Logout</a></li>'."\n";
}

PrintMenuEntry('editpages', 'Edit Pages', Permissions::EditPages);
PrintMenuEntry('entermatch', 'Enter Match', Permissions::EnterMatch);
PrintMenuEntry('usermanager', 'User Manager', Permissions::ViewPlayers);
PrintMenuEntry('teammanager', 'Team Manager', Permissions::ViewTeams);
PrintMenuEntry('logs', 'Logs', Permissions::ViewLogs);

/*
$names = MySQL::GetPageNames();

foreach($names as $name)
{
    PrintMenuEntry(urlencode($name), $name);
}
*/

?>
        </ul>
    </div>
    <div id="body">
