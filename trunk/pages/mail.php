<?php
if(!hasPerm(2)){
	require_once("include/noperm.php");
	require_once("include/footer.php");
	die();
}		
?>		<h2>Mail</h2>
<?php
	$userid = $_SESSION['userid'];
	$time = time();
    // Fetch a specific message

    // Flag a message as viewed
    function read($message) {
        $sql = "UPDATE messages SET `read` = '1' WHERE `id` = '".$message."' LIMIT 1";
        return (@mysql_query($sql)) ? true:false;
    }
    
    // Flag a message as deleted
    function deleted($message) {
        $sql = "UPDATE messages SET `to_deleted` = '1' WHERE `id` = '".$message."' LIMIT 1";
        return (@mysql_query($sql)) ? true:false;
    }
        
	function sendmessage($to,$title,$message) {
        $sql = "INSERT INTO messages SET `to` = '".$to."', `from` = '".$_SESSION['userid']."', `title` = '".$title."', `message` = '".$message."', `created` = '$time'";
        return (@mysql_query($sql)) ? true:false;
    }
    
    // BBCode
    function render($text) {
  		$bbcode = array("<", ">",
                "[list]", "[*]", "[/list]", 
                "[img]", "[/img]", 
                "[b]", "[/b]", 
                "[u]", "[/u]", 
                "[i]", "[/i]",
                '[color="', "[/color]",
                "[size=\"", "[/size]",
                '[url="', "[/url]",
                "[mail=\"", "[/mail]",
                "[code]", "[/code]",
                "[quote]", "[/quote]",
                '"]');
  		$htmlcode = array("&lt;", "&gt;",
                "<ul>", "<li>", "</ul>", 
                "<img src=\"", "\">", 
                "<b>", "</b>", 
                "<u>", "</u>", 
                "<i>", "</i>",
                "<span style=\"color:", "</span>",
                "<span style=\"font-size:", "</span>",
                '<a href="', "</a>",
                "<a href=\"mailto:", "</a>",
                "<code>", "</code>",
                "<img src=\"img/quote1.jpg\">", "<img src=\"img/quote2.jpg\">",
                '">');
 		$newtext = str_replace($bbcode, $htmlcode, $text);
  		$newtext = nl2br($newtext);//second pass
  		return $newtext;
	}
    // check if a new message had been sent
    if(isset($_POST['newmessage'])) {
        // error while sending message?
        if($_POST['to'] !== "" && $_POST['subject'] !== "" && $_POST['message'] !== ""){
        	if(sendmessage($_POST['to'],$_POST['subject'],$_POST['message'])) {
            // Goood
            echo "Message successfully sent to ".getPlayerName($_POST['to']);
            echo "<br><br>";
        } else {
            // No Good
            echo "Error, couldn't send PM. Maybe wrong user.";
            echo "<br><br>";
            $fail = 1;
        }
    } else {
    	echo "Missing Data";
		echo "<br><br>";
		$fail = 1;
		}
    }
    // Delted message?
    if(isset($_POST['delete'])) {
        // Error deleting?
        if(deleted($_POST['did'])) {
            echo 'Message deleted successfully!';
            echo "<br><br>";
        } else {
            echo "Error, couldn't delete PM!";
            echo "<br><br>";
        }
    }
    
?>
<script type="text/javascript" src="global/bbeditor/ed.js"></script>  
<a href="?p=mail&op=compose">Compose</a> - 
<a href="?p=mail&op=new">Read</a>
<?php
// If unspecified/recgonized page, show all messages
if(!isset($_GET['op']) || $_GET['op'] == 'new') {
?>
<table border="0" cellspacing="2" cellpadding="3">
    <tr>
        <td>From</td>
        <td>Title</td>
        <td>Date</td>
    </tr>
    <?php
        // If there are messages, show them
   		$sql = "SELECT * FROM messages WHERE `to` = '".$userid."' && `to_deleted`='0'";
		$result = mysql_query($sql) or die (mysql_error());
        // Check if there are any results
        if(mysql_num_rows($result)) {
            // if yes, fetch them!
            while($message = mysql_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo getPlayerName($message['from']); ?></td>
                    <td><a href='<?php echo $_SERVER['PHP_SELF']; ?>?p=mail&op=view&mid=<?php echo $message['id']; ?>'><?php echo $message['title'] ?></a></td>
                    <td><?php echo $message['created']; ?></td>
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
	$sql = "SELECT * FROM messages WHERE `id` = '$messageid' && (`from` = '".$userid."' || `to` = '".$userid."') LIMIT 1";
	$result = mysql_query($sql);
	if(mysql_num_rows($result)) {
	// fetch the data
		$message = mysql_fetch_assoc($result);
	}
 	read($message['id']);
?>
    <table border="0" cellspacing="2" cellpadding="3">
        <tr>
            <td>From:</td>
            <td><?php echo getPlayerName($message['from']); ?></td>
        </tr>
        <tr>
            <td>Date:</td>
            <td><?php echo $message['created']; ?></td>
        </tr>
        <tr>
            <td>Subject:</td>
            <td><?php echo $message['title']; ?></td>
        </tr>
        <tr>
		<td>Message:</td>
		<td><?php echo render($message['message']); ?></td>
        </tr>
    </table>
    <br>
    <?php if($message['from'] > 0){ ?>
    <form name='reply' method='post' action='<?php echo $_SERVER['PHP_SELF']."?p=mail&op=compose"; ?>'>
        <input type='hidden' name='from' value='<?php echo $message['from']; ?>'>
        <input type='hidden' name='subject' value='Re: <?php echo $message['title']; ?>'>
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
<option value="">Select a User</option><?php
$query = "SELECT * FROM players ORDER BY name asc"; 
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)){
	echo '<option value="'.$row['id'].'"';
	if($row['id'] == $_POST['rfrom'] || $row['id'] == $_POST['to']) echo " selected";
	echo '>'.$row['name'].'</option>';
}
?>
</select><br><br>
<strong>Subject:</strong>
<input type='text' name='subject' value='<?php if($fail || $reply) echo $_POST['subject'];?>'><br><br>
<strong>Message:</strong><br>
<script type="text/javascript" src="bbeditor/ed.js"></script>  
<script>edToolbar('message'); </script>
<textarea cols="60" rows="20" name='message' id='message'><?php if($fail || $reply) echo $_POST['message']; ?></textarea><br><br>
<input type='submit' name='newmessage' value='Send'>
</form>
</body>
</html>
<?php
}
//Thats all folks
?>