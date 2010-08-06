<?php

require_once('./include/current-player.php');
$result = MySQL::Query("SELECT id FROM messages WHERE `read`=FALSE AND `to`='".CurrentPlayer::$ID."'");
$messages = mysql_num_rows($result);
?>

<h2>Welcome</h2>
Welcome to the beta version of league connect.
<br><br>
This is currently a work in progress. Please report all bugs <a href="http://code.google.com/p/leagueconnect/issues/entry">here</a>
<?php if(CurrentPlayer::$ID) { ?>
<br><br>
You are logged in as <?php echo CurrentPlayer::$Name; ?>
<br><br>
You have <?php echo $messages; ?> new message<?php if($messages !== 1) echo 's' ?>
<?php } ?>