<?php

require_once('./include/current-player.php');
$result = MySQL::Query("SELECT id FROM messages WHERE `read`=FALSE AND `to`='".CurrentPlayer::$ID."'");
$messages = mysql_num_rows($result);
?>

<h2>Welcome back</h2>
You are logged in as <?php echo CurrentPlayer::$Name; ?>
<br><br>
You have <?php echo $messages; ?> new message<?php if($messages !== 1) echo 's' ?>