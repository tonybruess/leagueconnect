		<h2>News</h2>
		<p>[<a href="?p=news">View Entries</a>] - [<a href="?p=news&op=new">New Entry</a>]</p>
		<?php
		if($_GET['op'] == 'new')
		{
			if($_POST)
			{

				$date = $_POST['date'] . ' ' . $_POST['time'];
				
				if(MySQL::addItem($_POST['author'],$_POST['message'],$date,1))
					echo "Posted new entry successfully";
			}
			
			MySQL::newItemForm();

		} elseif($_POST['edit']){
		} else {
			MySQL::GetItems(1);
		}
		?>