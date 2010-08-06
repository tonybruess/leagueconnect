        <h2>Bans</h2>
        <p>[<a href="?p=bans">View Entries</a>]<?php if(CurrentPlayer::HasPerm(Permissions::AddPages)){?> - [<a href="?p=bans&op=new">New Entry</a>]<?php } ?></p>
        <?php
        if($_GET['op'] == 'new' && CurrentPlayer::HasPerm(Permissions::AddPages))
        {
            if($_POST)
            {
                $date = $_POST['date'] . ' ' . $_POST['time'];

                if(MySQL::AddEntry($_POST['author'],$_POST['message'],$date,4))
                    echo "Posted new entry successfully.";
            }
            ?>

            <form method="POST">
            Author:
            <input type="text" name="author" value="<?php echo CurrentPlayer::$Name ?>">
            <br><br>
            Message:
            <br>
            <script type="text/javascript" src="global/bbeditor/ed.js"></script>
            <script>AddBBCodeToolbar('message'); </script>
            <textarea cols=50 rows=10 name="message" id="message"></textarea>
            <br><br>
            Date: <input type="text" name="date" value="<?php echo date("Y-m-d") ?>" maxlength="10" style="width: 70px;">
            <br><br>
            Time: <input type="text" name="time" value="<?php echo date("H:i:s") ?>" maxlength="8" style="width: 50px;">
            <br><br>
            Page: <?php echo MySQL::GetPageName("Bans"); ?>
            <br><br>
            <input type="submit" value="Save">
            </form>

            <?php
        }
        elseif($_GET['op'] == 'edit' && $_GET['i'] && CurrentPlayer::HasPerm(Permissions::EditPages))
        {
            $id = MySQL::Sanitize($_GET['i']);
            if($_POST)
            {
                $date = $_POST['date'] . ' ' . $_POST['time'];
                
                if(MySQL::UpdateEntry($_POST['author'],$_POST['message'],$date,4,$_POST['id']))
                    echo 'Updated entry successfully';
            }
            
            $entry = mysql_fetch_assoc(MySQL::Query("SELECT * FROM bans WHERE id='$id' LIMIT 1"));
            $date = explode(' ',$entry['created']);
        ?>
            <form method="POST">
            Author:
            <input type="text" name="author" value="<?php echo $entry['author'] ?>">
            <br><br>
            Message:
            <br>
            <script type="text/javascript" src="global/bbeditor/ed.js"></script>
            <script>AddBBCodeToolbar('message'); </script>
            <textarea cols=50 rows=10 name="message" id="message"><?php echo $entry['message'] ?></textarea>
            <br><br>
            Date: <input type="text" name="date" value="<?php echo $date[0]; ?>" maxlength="10" style="width: 70px;">
            <br><br>
            Time: <input type="text" name="time" value="<?php echo $date[1]; ?>" maxlength="8" style="width: 50px;">
            <br><br>
            Page: <?php echo MySQL::GetPageName("Bans"); ?>
            <br><br>
            <input type="hidden" name="id" value="<?php echo $entry['id'] ?>">
            <input type="submit" value="Save">
            </form>
        <?php
        }
        else
        {
            MySQL::GetPage(4);
        }
        ?>