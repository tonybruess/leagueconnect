<?php

global $userid;

function rowClass($i){
    if (($i%2) != 0) return "rowOdd";
    else return "rowEven";
}

function getPlayerName($id)
{
    if (is_numeric($id)) {
        $q = Database_query("SELECT name FROM players WHERE id = ".$id);
        while ($r = Database_fetch_array($q, Database_ASSOC)) $name = $r['name'];
        if($name)
            return $name;
        else
            return "CTF League System";
    }
}

function getUserId($username) {
        $sql = "SELECT id FROM players WHERE `name` = '".$username."' LIMIT 1";
        $result = Database_query($sql);
        if(Database_num_rows($result)) {
            $row = Database_fetch_row($result);
            return $row[0];
        } else {
            return false;
        }
}

function hasMail() {
    $sql = "SELECT * FROM messages WHERE `to`=".CurrentPlayer::$ID." AND `read`='0' AND `to_deleted`='0'";
    $result = Database_query($sql);
    $result = Database_fetch_array($result);
    if($result)
        return true;
    else
        return false;
}
?>