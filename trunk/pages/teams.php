<h2>Teams</h2>
<?php

switch(@$_POST['action'])
{
    case 'add':
        // Add a new team
        $team = $_POST['newteam'];

        if(MySQL::TeamExists($team))
        {
            echo 'Team name unavailable.';
        }
        else if(MySQL::IsTeamMember(CurrentPlayer::$ID))
        {
            echo 'You must leave your current team before creating a new one.';
        }
        else
        {
            MySQL::AddTeam($team, CurrentPlayer::$ID);
        }
        break;

    case 'edit':
        break;

    case 'delete':
        break;

    case 'abandon':
        break;

    default:
        break;
}
?>

<table border="0" cellspacing="2" cellpadding="3">
<th>Name</th>
<th>Leader</th>
<th>#</th>
<th>Score</th>
<th>Join</th>
<th>Activity</th>
<?php

$teams = MySQL::GetTeamInfoList();
$i = 0;

foreach($teams as $team)
{
    $players = MySQL::GetPlayersByTeam($team->ID);
    $leader = MySQL::GetPlayerInfo($team->Leader);

?>

<tr class="<?php echo rowClass($i); ?>">
    <td><?php echo $team->Name; ?></td>
    <td><?php echo $leader->Name; ?></td>
    <td><?php echo count($players); ?></td>
    <td><?php echo $team->Rank; ?></td>
    <td>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="jointeam" value="<?php echo $team->ID; ?>">
            <input type="submit" value="<?php if(CurrentPlayer::$ID == $leader->ID){ echo 'Edit'; } else { echo 'Join'; } ?>" <?php if($team->Closed){ echo 'disabled'; } ?> >
        </form>
    </td>
    <td>&nbsp;</td>
</tr>

<?php

    $i++;
}

?>
</table>
<br>
<h2>Add a Team</h2>

<?php

if(MySQL::IsTeamMember(CurrentPlayer::$ID))
{
    echo 'You must leave your current team before creating a new one.';
}
else
{
?>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="action" value="add">
    Name: <input type="text" name="newteam">
    <input type="submit" value="Create">
    </form>

<?php
}

?>