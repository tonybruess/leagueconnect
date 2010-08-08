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
if(isset($_POST['newmessage']))
{

    if($_POST['to'] && $_POST['subject'] && $_POST['message'])
    {

        if(MySQL::sendmessage($toclean,$subclean,$mesclean))
        {
            echo 'Message successfully sent to ' . getPlayerName($_POST['to']) . '<br>';
        }
        else
        {
            echo 'Error, couldn\'t send PM<br>';
            $fail = true;
        }
    }
    else
    {
        echo 'Please fill out all fields<br>';
        $fail = true;
    }
}
    
if(isset($_POST['delete']))
{
    if(deleted($_POST['did']))
    {
        echo 'Message deleted successfully<br>';
    }
    else
    {
        echo 'Error, couldn\'t delete PM<br>';
    }
}
    
?>
<?php
switch($_GET['op'])
{
    case 'view':
        
        $message = MySQL::GetMessage($_GET['mid']);
        ?>
        <table border="0" cellspacing="2" cellpadding="3">
        <tr><td>From:</td><td><?php echo getPlayerName($message['from']); ?></td></tr>
        <tr><td>Date:</td><td><?php echo strftime("%B %e, %G at %I:%M %p", strtotime($message['created'])); ?></td></tr>
        <tr><td>Subject:</td><td><?php echo $message['subject']; ?></td></tr>
        <tr><td>Message:</td><td><?php echo FormatToBBCode($message['message']); ?></td></tr>
        </table>
        <br>
        <?php if($message['from'] > 0){ ?>
        <form name='reply' method='post' action='<?php echo $_SERVER['PHP_SELF']."?p=mail&op=compose"; ?>'><input type='hidden' name='id' value='<?php echo $message['id']; ?>'><input type='submit' name='reply' value='Reply'></form>
        <br>
        <?php } ?>
        <form name='delete' method='post' action='<?php echo $_SERVER['PHP_SELF']."?p=mail"; ?>'><input type='hidden' name='did' value='<?php echo $message['id']; ?>'><input type='submit' name='delete' value='Delete'></form>
        <?php
        
    break;
        
    case 'compose':
        
        if($_POST['reply'])
        {
            $reply = true;
            $message = MySQL::GetMessage($_POST['id']);
        }
        ?>
        <form name="new" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?p=mail&op=compose">
        <br><strong>To:</strong>
        <select name="to">
        <option value="" disabled>Select a User</option><?php
        $result = MySQL::Query("SELECT * FROM players WHERE `id` != '".CurrentPlayer::$ID."' ORDER BY name asc");
        while($row = mysql_fetch_array($result)){
            echo '<option value="'.$row['id'].'"';
            if($row['id'] == $message['from'] || $row['id'] == $message['to']) echo " selected";
            echo '>'.$row['name'].'</option>';
        }
        ?>
        </select><br><br>
        <strong>Subject:</strong>
        <input type='text' name='subject' value='<?php if($fail || $reply) echo 'Re: ' . $message['subject'];?>'><br><br>
        <strong>Message:</strong><br>
        <script>AddBBCodeToolbar('message'); </script>
        <textarea cols="60" rows="20" name='message' id='message'><?php if($fail || $reply) echo '[quote]' . $message['message'] . '[/quote]'; ?></textarea><br><br>
        <input type='submit' name='newmessage' value='Send'>
        </form>
        <?php

    break;
        
    case 'sent':
            
        ?>
        <table border="0" cellspacing="2" cellpadding="3">
        <tr><td>To</td><td>Subject</td><td>Date</td></tr>
        <?php
        $messages = MySQL::FetchMessages('sent');
        foreach($messages as $message)
        {
            $unread = false;
            if(!$message['read'])
                $unread = true;
        ?>
        <tr><td><?php echo getPlayerName($message['to']); ?></td><td><a href='?p=mail&op=view&mid=<?php echo $message['id']; ?>'<?php if($unread) echo 'style="color: red;"'; ?>><?php echo $message['subject'] ?></a></td><td><?php echo strftime("%B %e, %G at %I:%M %p", strtotime($message['created'])); ?></td></tr>
        <?php
        }
        if(!$messages)
            echo '<tr><td colspan='3'><strong>No messages to display</strong></td></tr>';
        ?>
        </table>
        <?php
    
    break; 
        
    default:

        ?>
        <table border="0" cellspacing="2" cellpadding="3">
        <tr><td>To</td><td>Subject</td><td>Date</td></tr>
        <?php
        $messages = MySQL::FetchMessages();
        foreach($messages as $message)
        {
            $unread = false;
            if(!$message['read'])
                    $unread = true;
        ?>
        <tr><td><?php echo getPlayerName($message['to']); ?></td><td><a href='?p=mail&op=view&mid=<?php echo $message['id']; ?>'<?php if($unread) echo 'style="color: red;"'; ?>><?php echo $message['subject'] ?></a></td><td><?php echo strftime("%B %e, %G at %I:%M %p", strtotime($message['created'])); ?></td></tr>
        <?php
        }
        if(!$messages)
            echo '<tr><td colspan='3'><strong>No messages to display</strong></td></tr>';
        ?>
        </table>
        <?php
    
    break;
}
?>