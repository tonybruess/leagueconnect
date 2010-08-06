        <h2><?php echo MySQL::getPageName("Contact") ?></h2>
        <?php
        echo FormatToBBCode(MySQL::GetPage(3));
        if(CurrentPlayer::HasPerm(Permissions::EditPages))
            echo '<br><br>[<a href="?p=editpages&i=3">Edit</a>]';
        ?>
