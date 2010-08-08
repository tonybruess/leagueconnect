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
    $i = Database::Sanitize($_GET['i']);
    $query = Database::Query("SELECT * FROM pages WHERE `id`='$i' LIMIT 1");
    $page = Database_fetch_assoc($query);
?>
        <h2>Editing <?php echo $page['name']?></h2>
        <form method="POST">
        Name: <input type="text" name="name" value="<?php echo $page['name'] ?>">
        <br><br>
        Text:
        <br>
        <script type="text/javascript" src="global/bbeditor/ed.js"></script>
        <script>AddBBCodeToolbar('text'); </script>
        <textarea name="text" cols=50 rows=10 id="text"><?php echo $page['content']; ?></textarea>
        <br><br>
        <input type="hidden" name="id" value="<?php echo $page['id']; ?>">
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
        $query = Database::Query("SELECT * FROM pages");
        
        while($row = Database_fetch_assoc($query)){
        ?>
        <tr>
            <td><?php echo $row['name'] ?></td>
            <td><a href="?p=editpages&i=<?php echo $row['id'] ?>">Edit</a></td>
        </tr>
        <?php    
        }
        ?>
        </table>
<?php
}
?>
    