<?php

require_once('include/bbcode.php');
require_once('include/database.php');

?>

        <h2>Bans</h2>
        <p>
            [<a href="?p=bans">View Entries</a>]
            <?php if(CurrentPlayer::HasPerm(Permissions::AddPages)){?> - [<a href="?p=bans&action=new">New Entry</a>]<?php } ?>
        </p>

        <?php

        $action = @$_GET['action'];

        switch($action)
        {
            case 'new':
            {
                if(CurrentPlayer::HasPerm(Permissions::AddPages))
                {
                    if(isset($_POST['message']))
                    {
                        if(Database::AddEntry(CurrentPlayer::$Name, $_POST['message'], 'bans'))
                        {
                            echo 'Posted new entry successfully.';
                        }
                    }
                }
            }
            break;

            case 'edit':
            {
                if(CurrentPlayer::HasPerm(Permissions::EditPages))
                {
                    if(isset($_GET['id'], $_POST['author'], $_POST['message']))
                    {
                        $date = $_POST['date'].' '.$_POST['time'];

                        if(Database::UpdateEntry($_POST['author'], $_POST['message'], 'bans', $_POST['id']))
                        {
                            echo 'Updated entry successfully.';
                        }
                    }
                }
            }
            break;

            default:
            {
            }
            break;
        }
        if(($action == 'new' && CurrentPlayer::HasPerm(Permissions::AddPages))
        || ($action == 'edit' && CurrentPlayer::HasPerm(Permissions::EditPages)))
        {
        	if($action == 'edit')
        	{
 		        $entry = Database::FetchEntry($_GET['id'], 'bans');	
         	}
        	?>

            <form method="POST">
                Author: <input type="text" name="author" value="<?php
                if($action == 'edit')
                    echo $entry['author'];
                else
                    echo CurrentPlayer::$Name;
                ?>">
                <br>
                Message:
                <br>
                <script type="text/javascript" src="global/bbeditor/ed.js"></script>
                <script type="text/javascript">AddBBCodeToolbar('message'); </script>
                <textarea cols=80 rows=20 name="message" id="message"><?php if($action == 'edit') echo $entry['message'];?></textarea>
                <input type="hidden" name="id" value="<?php echo $entry['id']; ?>">
                <br><br>
                <input type="submit" value="Save">
            </form>

            <?php
        }
        else
        {
            // Display the page
            $start = (isset($_GET['start']) ? $_GET['start'] : 0);

            $entries = Database::GetEntries($start, 10, 'bans');

            foreach($entries as $entry)
            {
                ?>

                <div id="item">
                    <div id="header">
                        <div id="author">
                            By: <?php echo $entry->Author; ?>
                        </div>
                        <div id="time">
                            <?php echo date('l F jS g:i A', $entry->Created); ?>
                        </div>
                    </div>
                    <div id="data">
                        <?php echo FormatToBBCode($entry->Message); ?>
                        <?php if(CurrentPlayer::HasPerm(Permissions::EditPages)){ ?>
                        <br><br>
                        [<a href="?p=bans&action=edit&id=<?php echo $entry->ID; ?>">Edit</a>]
                        <?php } ?>
                    </div>
                </div>
				<br>
                <?php
            }
        }
        ?>