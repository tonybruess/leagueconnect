<?php
if(!hasPerm(8)){
	require_once("include/noperm.php");
	require_once("include/footer.php");
	die();
}
if(!$_GET['i']){
?>
		<h2>User Manager</h2>
		<p>Users:</p>
		<table cellpadding="5">
		<tr><th>Name</th><th>BZID</th><th>Last Login</th><th>Manage</th></tr>
		<?php
		$q = mysql_query("SELECT * FROM players");
		while($row = mysql_fetch_assoc($q)){
			echo '<tr>';
			echo '<td>'.$row['name'].'</td>';
			echo '<td>'.$row['bzid'].'</td>';
			echo '<td>'.date("m-d-Y g:i:s",$row['lastlogin']).'</td>';
			echo '<td><a href="?p=usermanager&i='.$row['id'].'">Manage</a></td>';
			echo '</tr>';
			echo "\n";
		}
		?>
		</table>
<?php } else {
	$id = sanitize($_GET['i']);
	$user = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE `id`='$id'"));
	?>
	<h2>Managing <?php echo $user['name']; ?></h2>
	<form>
	Name: <input type="text" name="name" value="<?php echo $user['name']; ?>"><br>
	BZID: <input type="text" name="name" value="<?php echo $user['bzid']; ?>"><br>
	</form>
<?php } ?>
