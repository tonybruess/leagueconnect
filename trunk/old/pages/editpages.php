<?php
if(!CurrentPlayer::HasPerm(Permissions::EditPages)){
    require_once("include/noperm.php");
    require_once("include/footer.php");
    die();
}
?>
<?php
if($_GET['i'])
{
    if($_POST)
    {
         if(Database::UpdatePage($name, $text, $id))
            echo "Updated Successfully";
    }

    $page = Database::GetPageInfo($_GET['i']);
?>
        <h2>Editing <?php echo $page->Name; ?></h2>
        <form method="POST">
        Name: <input type="text" name="name" value="<?php echo $page->Name; ?>">
        <br><br>
        Text:
        <br>
        <script type="text/javascript" src="global/bbeditor/ed.js"></script>
        <script>AddBBCodeToolbar('text'); </script>
        <textarea name="text" cols=50 rows=10 id="text"><?php echo $page->Content; ?></textarea>
        <br><br>
        <input type="hidden" name="id" value="<?php echo $page->ID; ?>">
        <input type="submit" value="Save">
        </form>
<?php
} else {        
?>
        <h2>Edit Pages</h2>
        <p>Current Pages</p>
        <table border="0" cellspacing="2" cellpadding="5">
        <tr>
            <td>Page</td>
            <td>Edit</td>
        </tr>
        <?php
        $pages = Database::GetPages();

        foreach($pages as $page)
        {
        ?>
        <tr>
            <td><?php echo $page->Name; ?></td>
            <td><a href="?p=editpages&i=<?php echo $page->ID; ?>">Edit</a></td>
        </tr>
        <?php    
        }
        ?>
        </table>
<?php
}
?>
    