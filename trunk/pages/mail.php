<?php
if(!CurrentPlayer::HasPerm(Permissions::SendMail)){
	require_once("include/noperm.php");
	require_once("include/footer.php");
	die();
}
require_once("include/bbcode.php");
?>
<h2>Mail</h2>
<p>[<a href="?p=mail&op=compose">Compose</a>] - [<a href="?p=mail&op=new">Inbox</a>] - [<a href="?p=mail&op=sent">Outbox</a>]</p>
<script type="text/javascript" src="global/bbeditor/ed.js"></script>  
<?php
	$uid = CurrentPlayer::$ID;
    function read($message) {
        $sql = "UPDATE messages SET `read` = TRUE WHERE `id` = '$message' LIMIT 1";
        return (@mysql_query($sql)) ? true:false;
    }
    
    function deleted($messageid) {
    	$messageclean = MySQL::Sanitize($messageid);
		$uid = CurrentPlayer::$ID;
		$sql = "SELECT * FROM messages WHERE `id` = '$messageclean' && (`from` = '".CurrentPlayer::$ID."' || `to` = '".CurrentPlayer::$ID."') LIMIT 1";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)) {
			$message = mysql_fetch_assoc($result);
		}
    	if($message['from'] == $uid)
    		// From Deleted
    		$sql = "UPDATE messages SET `from_deleted` = '1' WHERE `id` = '$messageid' LIMIT 1";
        else
        	// To Deleted
    		$sql = "UPDATE messages SET `to_deleted` = '1' WHERE `id` = '$messageid' LIMIT 1";
        return (@mysql_query($sql)) ? true:false;
    }
    
        
	function sendmessage($to,$subject,$message) {
		$ts = time();
		$uid = CurrentPlayer::$ID;
		if($to == $uid)
			return false;
		else
        	$sql = "INSERT INTO messages SET `to` = '$to', `from` = '$uid', `subject` = '$subject', `message` = '$message', `created` = '$ts'";
        	return (@mysql_query($sql)) ? true:false;
    }
    	
    // check if a new message had been sent
    if(isset($_POST['newmessage'])) {
        // error while sending message?
        if($_POST['to'] !== "" && $_POST['subject'] !== "" && $_POST['message'] !== ""){
        	$toclean = MySQL::sanitize($_POST['to']);
        	$subclean = MySQL::sanitize($_POST['subject']);
        	$mesclean = MySQL::sanitize($_POST['message']);
        	if(sendmessage($toclean,$subclean,$mesclean)) {
            // Goood
            	echo "Message successfully sent to ".getPlayerName($_POST['to']);
            	echo "<br>";
        	} else {
            	// No Good
            	echo "Error, couldn't send PM";
            	echo "<br>";
            	$fail = 1;
        	}
    	} else {
    		echo "Please fill out all fields";
			echo "<br>";
			$fail = 1;
		}
    }
    
    // Were deleting a message
    if(isset($_POST['delete'])) {
        if(deleted($_POST['did'])) {
            echo 'Message deleted successfully!';
            echo "<br><br>";
        } else {
            echo "Error, couldn't delete PM";
            echo "<br><br>";
        }
    }
    
?>
<?php
// If unspecified/recgonized page, show all messages
if(!isset($_GET['op']) || $_GET['op'] == 'new') {
?>
<table border="0" cellspacing="2" cellpadding="3">
    <tr>
        <td>From</td>
        <td>Subject</td>
        <td>Date</td>
    </tr>
    <?php
        // If there are messages, show them
   		$sql = "SELECT * FROM messages WHERE `to` = '$uid' && `to_deleted`='0'";
		$result = mysql_query($sql) or die (mysql_error());
        // Check if there are any results
        if(mysql_num_rows($result)) {
            // if yes, fetch them!
            while($message = mysql_fetch_assoc($result)) {
            	$unread = false;
            	if($message['read']=='0')
            		$unread = true;
                ?>
                <tr>
                    <td><?php echo getPlayerName($message['from']); ?></td>
                    <td><a href='?p=mail&op=view&mid=<?php echo $message['id']; ?>'<?php if($unread) echo 'style="color: red;"'; ?>><?php echo $message['subject'] ?></a></td>
                    <td><?php echo strftime("%B %e, %G at %I:%M %p", strtotime($message['created'])) ?></td>
                </tr>
                <?php
            }
        } else {
            // No messages!
            echo "<tr><td colspan='3'><strong>You have no new messages</strong></td></tr>";
        }
    ?>
</table>
<?php
} elseif($_GET['op'] == 'view' && isset($_GET['mid'])) {
	$messageid = $_GET['mid'];
	// Set message to viewed
	$sql = "SELECT * FROM messages WHERE `id` = '$messageid' && (`from` = '".CurrentPlayer::$ID."' || `to` = '".CurrentPlayer::$ID."') LIMIT 1";
	$result = mysql_query($sql);
	if(mysql_num_rows($result)) {
	// fetch the data
		$message = mysql_fetch_assoc($result);
	}
	if($message['from'] !== $uid)
 		read($message['id']);
?>
    <table border="0" cellspacing="2" cellpadding="3">
        <tr>
            <td>From:</td>
            <td><?php echo getPlayerName($message['from']); ?></td>
        </tr>
        <tr>
            <td>Date:</td>
            <td><?php echo strftime("%B %e, %G at %I:%M %p", strtotime($message['created'])); ?></td>
        </tr>
        <tr>
            <td>Subject:</td>
            <td><?php echo $message['subject']; ?></td>
        </tr>
        <tr>
		<td>Message:</td>
		<td><?php echo bbcode($message['message']); ?></td>
        </tr>
    </table>
    <br>
    <?php if($message['from'] > 0){ ?>
    <form name='reply' method='post' action='<?php echo $_SERVER['PHP_SELF']."?p=mail&op=compose"; ?>'>
        <input type='hidden' name='from' value='<?php echo $message['from']; ?>'>
        <input type='hidden' name='subject' value='Re: <?php echo $message['subject']; ?>'>
        <input type='hidden' name='message' value='[quote]<?php echo $message['message']; ?>[/quote]'>
        <input type='submit' name='reply' value='Reply'>
    </form>
    <br>
    <?php } ?>
    <form name='delete' method='post' action='<?php echo $_SERVER['PHP_SELF']."?p=mail"; ?>'>
        <input type='hidden' name='did' value='<?php echo $message['id']; ?>'>
        <input type='submit' name='delete' value='Delete'>
    </form>
<?php
} elseif($_GET['op'] == 'compose' ) {
	if($_POST['reply'])
		$reply = 1;
?>
	<form name="new" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?p=mail&op=compose">
		<br><strong>To:</strong>
		<select name=to>
		<option value="" disabled>Select a User</option><?php
		$query = "SELECT * FROM players WHERE `id` != '$uid' ORDER BY name asc"; 
		$result = mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			echo '<option value="'.$row['id'].'"';
			if($row['id'] == $_POST['from'] || $row['id'] == $_POST['to']) echo " selected";
			echo '>'.$row['name'].'</option>';
		}
		?>
		</select><br><br>
		<strong>Subject:</strong>
		<input type='text' name='subject' value='<?php if($fail || $reply) echo $_POST['subject'];?>'><br><br>
		<strong>Message:</strong><br>
		<script>edToolbar('message'); </script>
		<textarea cols="60" rows="20" name='message' id='message'><?php if($fail || $reply) echo $_POST['message']; ?></textarea><br><br>
		<input type='submit' name='newmessage' value='Send'>
	</form>
<?php
} elseif($_GET['op'] == 'sent'){
?>
<table border="0" cellspacing="2" cellpadding="3">
    <tr>
        <td>To</td>
        <td>Subject</td>
        <td>Date</td>
    </tr>
    <?php
        // If there are messages, show them
   		$sql = "SELECT * FROM messages WHERE `from` = '".CurrentPlayer::$ID."' &&  `from_deleted` = '0'";
		$result = mysql_query($sql) or die (mysql_error());
        // Check if there are any results
        if(mysql_num_rows($result)) {
            // if yes, fetch them!
            while($message = mysql_fetch_assoc($result)) {
            	$unread = false;
            	if($message['read']=='0')
            		$unread = true;
                ?>
                <tr>
                    <td><?php echo getPlayerName($message['to']); ?></td>
                    <td><a href='?p=mail&op=view&mid=<?php echo $message['id']; ?>'<?php if($unread) echo 'style="color: red;"'; ?>><?php echo $message['subject'] ?></a></td>
                    <td><?php echo date("m-d-Y",$message['ts']); ?></td>
                </tr>
                <?php
            }
        } else {
            // No messages!
            echo "<tr><td colspan='3'><strong>You have no sent messages</strong></td></tr>";
        }
    ?>
</table>
<?php
}
?>