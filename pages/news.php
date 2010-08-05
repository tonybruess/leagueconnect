		<h2>News</h2>
		<p>[<a href="?p=news&op=new">New Entry</a>]</p>
		<?php
		if($_GET['op'] == 'new')
		{
			if($_POST['edit']){
			}
		?>
		<form method="POST">
		Author:
		<select name="author">
		<option value="" disabled>Select a User</option><?php
		$query = "SELECT * FROM players ORDER BY name asc"; 
		$result = mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			echo '<option value="'.$row['id'].'"';
			if($row['id'] == CurrentPlayer::$ID) echo " selected";
			echo '>'.$row['name'].'</option>';
		}
		?>
		</select><br><br>
		</form>
		<?php
		} else {
			MySQL::GetItems(1);
		}
		?>