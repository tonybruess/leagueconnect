<h2>Teams</h2>
<?php
/* VARIABLES BEING READ FROM THE QUERY STRING ON OCCASION
 ******************************************************************
 * $i	representing an ID (for any purposes)
 * $a	representing an action that's being performed or prepared by the user
 */

@ $a = $_GET['a'];
@ $i = $_GET['i'];

if (@$_POST['newteam']){
	$newteam = sanitize($_POST['newteam']);
	$teamexists = mysql_fetch_array(mysql_query("SELECT * FROM teams WHERE `name`='$newteam'"));
	if($teamexists){
		echo "Team Name Unavailable";
	} elseif(mysql_query("INSERT INTO teams (`name`,`created`,`leader`,`rank`,`description`,`closed`,`inactive`,`deleted`) VALUES ('$newteam', NOW(),'$userid','1200','My New Team!', FALSE, FALSE, FALSE)")) {
			$newteamid = mysql_fetch_array(mysql_query("SELECT id FROM teams WHERE `name`='$newteam'"));
			mysql_query("UPDATE players SET `team`=".$newteamid[0]);
			echo "Team ".$_POST['newteam']." created successfully!";
	}
} elseif ($a == "edit") {
	// Edit Team
} elseif ($a == "delete") {
	// Delete
} elseif ($a == "abandon") {
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
	$q = mysql_query("SELECT * FROM teams ORDER BY Rank DESC");
	$i = 0;
	while ($r = mysql_fetch_array($q, MYSQL_ASSOC))
	{
		$membercount = mysql_num_rows(mysql_query("SELECT * FROM players WHERE `team`=".$r['id']));
		echo "<tr class='".rowClass($i)."'><td>".$r['name']."</td><td>".getPlayerName($r['leader'])."</td><td>".$membercount."</td><td>".$r['rank']."</td><td>";
		if($r['closed'] == 0 && $r['leader'] !== $userid)
			echo '<form><input type="hidden" name="jointeam" value="'.$r['id'].'"><input type="submit" value="Join"></form>';
		elseif($r['leader'] == $userid)
			echo '[ Edit ]';
		else
			echo '[ Closed ]';
		echo "</td><td>&nbsp;</td></tr>"; // have activity estimated in realtime from the matches table
		$i++;
	}
?>
</table>
<br>
<h2>Add a Team</h2>
<form method="POST" action="<?php print $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="a" value="add">
Name: <input type="text" name="newteam">
<input type="submit">
</form>