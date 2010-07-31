<h2>Teams</h2>
<?php
/* VARIABLES BEING READ FROM THE QUERY STRING ON OCCASION
 ******************************************************************
 * $i	representing an ID (for any purposes)
 * $a	representing an action that's being performed or prepared by the user
 */

@ $a = $_REQUEST['a'];
@ $i = $_GET['i'];

if ($a == 'add')
{
	// New Team
    $team = $_POST['newteam'];

    if(MySQL::TeamExists($team))
    {
        echo 'Team Name Unavailable';
    }
    elseif(MySQL::isTeamLeader(CurrentPlayer::$ID))
    {
		echo 'You must abandon your team before creating a new one';
    }
    else
    {
        MySQL::AddTeam($team, CurrentPlayer::$ID);
 		echo 'Team Created';
    }
}
elseif ($a == "edit")
{
// Edit Team
} elseif ($a == "delete")
{
// Delete
} elseif ($a == "abandon")
{
// Abandon
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
/*

MySQL::ListTeams();

*/
	$q = mysql_query("SELECT * FROM teams ORDER BY Rank DESC");
	$i = 0;
	while ($r = mysql_fetch_array($q, MYSQL_ASSOC))
	{
		$membercount = mysql_num_rows(mysql_query("SELECT * FROM players WHERE `team`=".$r['id']));
		echo "<tr class='".rowClass($i)."'><td>".$r['name']."</td><td>".getPlayerName($r['leader'])."</td><td>".$membercount."</td><td>".$r['rank']."</td><td>";
		if($r['closed'] == 0 && $r['leader'] !== CurrentPlayer::$ID)
			echo '<form method="POST"><input type="hidden" name="jointeam" value="'.$r['id'].'"><input type="submit" value="Join"></form>';
		elseif($r['leader'] == CurrentPlayer::$ID)
			echo '<form method="POST"><input type="hidden" name="jointeam" value="'.$r['id'].'"><input type="submit" value="Edit"></form>';
		else
			echo '<form><input type="submit" value="Closed" disabled></form>';
		echo "</td><td>&nbsp;</td></tr>"; // have activity estimated in realtime from the matches table
		$i++;
	}
?>
</table>
<br>
<h2>Add a Team</h2>
<?php
if(MySQL::isTeamLeader(CurrentPlayer::$ID)){
	echo 'You must abandon your team before creating a new one';
} else {
?>
<form method="POST">
<input type="hidden" name="a" value="add">
Name: <input type="text" name="newteam">
<input type="submit">
</form>
<?php
}
?>