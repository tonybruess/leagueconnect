<?php
if(!CurrentPlayer::HasPerm(Permissions::ViewLogs)){
    require_once("include/noperm.php");
    require_once("include/footer.php");
    die();
}        
?>
        <h2>Logs</h2>
        <p>Under Development</p>
