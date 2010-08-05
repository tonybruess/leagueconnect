		<h2>News</h2>
		<p>[<a href="?p=news&op=new">New Entry</a>]</p>
		<?php
		if($_GET['op'] == 'new')
		{
			if($_POST){
				$author = MySQL::Sanitize($_POST['author']);
				$message = MySQL::Sanitize($_POST['message']);
				$date = MySQL::Sanitize($_POST['date']) . ' ' . MySQL::Sanitize($_POST['time']);
				$page = '1';
				if(mysql_query("INSERT INTO entries SET `author`='$author', `message`='$message', `created`='$date', `page`='$page'"))
					echo "Posted new entry successfully";
					echo mysql_error();
			}
		?>
		<form method="POST">
		Author:
		<input type="text" name="author" value="<?php echo CurrentPlayer::$Name ?>">
		<br><br>
		Message:
		<br>
		<textarea cols=50 rows=10 name="message"></textarea>
		<br><br>
		Date: <input type="text" name="date" value="<?php echo date("Y-m-d") ?>" maxlength="10" style="width: 70px;">
		<br><br>
		Time: <input type="text" name="time" value="<?php echo date("H:i:s") ?>" maxlength="8" style="width: 50px;">
		<br><br>
		Page: <?php echo MySQL::getPageName("News"); ?>
		<br><br>
		<input type="submit" value="Save">
		</form>
		<?php
		} elseif($_POST['edit']){
		} else {
			MySQL::GetItems(1);
		}
		?>