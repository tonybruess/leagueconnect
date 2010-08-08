        <h2><?php echo Database::GetPageName("Help") ?></h2>
        <?php
        echo FormatToBBCode(Database::GetPageContents(1));
        if(CurrentPlayer::HasPerm(Permissions::EditPages))
            echo '<br><br>[<a href="?p=editpages&i=1">Edit</a>]';
        ?>
