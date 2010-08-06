<?php
if(!CurrentPlayer::HasPerm(8)){
    require_once("include/noperm.php");
    require_once("include/footer.php");
    die();
}
if(!$_GET['i']){
?>
        <h2>User Manager</h2>
        <p>Users:</p>
        <table cellpadding="5">
        <tr><th>Name</th><th>BZID</th><th>Last Login</th><th>Manage</th></tr>
        <?php
        $q = mysql_query("SELECT * FROM players");
        while($row = mysql_fetch_assoc($q)){
            echo '<tr>';
            echo '<td>'.$row['name'].'</td>';
            echo '<td>'.$row['bzid'].'</td>';
            echo '<td>'.date("m-d-Y g:i:s",$row['lastlogin']).'</td>';
            echo '<td><a href="?p=usermanager&i='.$row['id'].'">Manage</a></td>';
            echo '</tr>';
            echo "\n";
        }
        ?>
        </table>
<?php } else {
    $id = MySQL::sanitize($_GET['i']);
    $user = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE `id`='$id'"));
    ?>
    <h2>Managing <?php echo $user['name']; ?></h2>
    <form method="POST">
    Name: <input type="text" name="name" value="<?php echo $user['name']; ?>"><br>
    BZID: <input type="text" name="bzid" value="<?php echo $user['bzid']; ?>"><br>
    Comment: <textarea cols="110" rows="4" style="resize: vertical;"><?php echo $user['comment'] ?></textarea><br>
    Banned: <input type="checkbox" name="banned" value="1"<?php if($user['banned']) echo ' checked';?>"><br>
    Deleted: <input type="checkbox" name="deleted" value="1"<?php if($user['deleted']) echo ' checked';?>"><br>
    BZID: <input type="text" name="name" value="<?php echo $user['bzid']; ?>"><br>
    <input type="submit" name="Save">
    </form>
<?php } ?>
