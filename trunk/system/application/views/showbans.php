        <h2>Bans</h2>
        <p>
            [<a href="?p=bans">View Entries</a>]
            {if $canAddEntry == true} - [<a href="?p=bans&action=new">New Entry</a>]{/if}
        </p>

        {if $post.action == 'edit or $post.action == 'new'}
            {$postMessage}
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
        {/if}

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
