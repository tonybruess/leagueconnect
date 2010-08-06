		<h2>News</h2>
		<p>[<a href="?p=news">View Entries</a>] - [<a href="?p=news&op=new">New Entry</a>]</p>
		<?php
		if($_GET['op'] == 'new')
		{
			if($_POST)
			{
				$date = $_POST['date'] . ' ' . $_POST['time'];

				if(MySQL::AddItem($_POST['author'],$_POST['message'],$date,1))
                {
					echo "Posted new entry successfully.";
                }
			}
            ?>

            <form method="POST">
            Author:
            <input type="text" name="author" value="<?php echo CurrentPlayer::$Name ?>">
            <br><br>
            Message:
            <br>
            <script type="text/javascript" src="global/bbeditor/ed.js"></script>
            <script>edToolbar('message'); </script>
            <textarea cols=50 rows=10 name="message" id="message"></textarea>
            <br><br>
            Date: <input type="text" name="date" value="<?php echo date("Y-m-d") ?>" maxlength="10" style="width: 70px;">
            <br><br>
            Time: <input type="text" name="time" value="<?php echo date("H:i:s") ?>" maxlength="8" style="width: 50px;">
            <br><br>
            Page: <?php echo MySQL::GetPageName("News"); ?>
            <br><br>
            <input type="submit" value="Save">
            </form>

            <?php
		}
		elseif($_POST['edit'])
		{
		// TODO: Edit already posted news items
		}
		else
		{
			MySQL::GetItems(1);
		}
		?>