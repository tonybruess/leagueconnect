<?php

require_once('include/bbcode.php');
require_once('include/database.php');

?>

        <h2>News</h2>
        <p>
            [<a href="?p=news">View Entries</a>]
            <?php if(CurrentPlayer::HasPerm(Permissions::AddPages)){?> - [<a href="?p=news&op=new">New Entry</a>]<?php } ?>
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
                        if(MySQL::AddNewsEntry(CurrentPlayer::$Name, $_POST['message']))
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
                    if(isset($_GET['id'], $_POST['date'], $_POST['time'], $_POST['author'], $_POST['message']))
                    {
                        $date = $_POST['date'].' '.$_POST['time'];

                        if(MySQL::UpdateEntry($_POST['author'], $_POST['message'], $date, 1, $_POST['id']))
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
            ?>

            <form method="POST">
                Message:
                <br>
                <script type="text/javascript" src="global/bbeditor/ed.js"></script>
                <script type="text/javascript">AddBBCodeToolbar('message'); </script>
                <textarea cols=80 rows=20 name="message" id="message"></textarea>
                <br><br>
                <input type="submit" value="Save">
            </form>

            <?php
        }
        else
        {
            // Display the page
            $start = (isset($_GET['start']) ? $_GET['start'] : 0);

            $entries = MySQL::GetNewsEntries($start, 10);

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
                    <div id="message">
                        <?php echo FormatToBBCode($entry->Message); ?>
                        <?php if(CurrentPlayer::HasPerm(Permissions::EditPages)){ ?>
                        <br><br>
                        [<a href="?p=news&action=edit&id=<?php echo $entry->ID; ?>">Edit</a>]
                        <?php } ?>
                    </div>
                </div>

                <?php
            }
        }
        ?>