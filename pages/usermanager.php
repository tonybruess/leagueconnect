<?php
if(!hasPerm(8)){
	require_once("include/noperm.php");
	require_once("include/footer.php");
	die();
}		
?>
		<h2>User Manager</h2>
		<p>Users:</p>
		<table cellpadding="5">
		<tr>
		<th>Name</td>
		<th>BZID</td>
		<th>Last Login</td>
		</tr>
		<?php
		$q = mysql_query("SELECT * FROM players");
		while($row = mysql_fetch_assoc($q)){
			echo "<tr>\n";
			echo '<td>'.$row['name']."</td>\n";
			echo '<td>'.$row['bzid']."</td>\n";
			echo '<td>'.date("m-d-Y g:i:s",$row['lastlogin'])."</td>\n";
			echo "</tr>\n";
		}
		?>
		</table>
