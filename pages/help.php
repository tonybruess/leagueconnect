        <h2><?php echo MySQL::getPageName("Help") ?></h2>
        <?php
        echo FormatToBBCode(MySQL::GetPage(2));
        if(CurrentPlayer::HasPerm(Permissions::EditPages))
            echo '<br><br>[<a href="?p=editpages&i=2">Edit</a>]';
        ?>
