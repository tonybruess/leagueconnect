<h2>Teams</h2>
<?php
/* VARIABLES BEING READ FROM TEH QUERY STRING ON OCCASION
 ******************************************************************
 * $i	representing an ID (for any purposes)
 * $a	representing an action that's being performed or prepared by the user
 */

@ $a = $_GET['a'];
@ $i = $_GET['i'];

// this is going to happen if the player hit an apply-button
if ($a == "apply")
{
	echo "there's code missing here.";
} 
// now that we go all special cases let's do the general 
else
{
?>
<table border="0" cellspacing="2" cellpadding="1">
<th>Name</th>
<th>Score</th>
<th>Leader</th>
<?php
	$q = mysql_query("SELECT * FROM teams ORDER BY Rank DESC"); $i = 0;
	while ($r = mysql_fetch_array($q, MYSQL_ASSOC))
	{
		echo "<tr class='".rowClass($i)."'><td>".$r['Name']."</td><td>".$r['Rank']."</td><td>".getPlayerName($r['LeaderID'])."</td></tr>";
		$i++;
	}
?>
</table>
<?php
}
?>