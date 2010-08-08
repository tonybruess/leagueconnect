        <h2><?php echo MySQL::GetPageName("Contact") ?></h2>
        <?php
        echo FormatToBBCode(MySQL::GetPageContents(2));
        if(CurrentPlayer::HasPerm(Permissions::EditPages))
            echo '<br><br>[<a href="?p=editpages&i=2">Edit</a>]';
        ?>
