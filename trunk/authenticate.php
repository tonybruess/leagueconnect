<?php
session_start();
header("Cache-control: private");
include ("include/database");
include ("include/current-user.php");
function validate_token($token, $username, $groups = array(), $checkIP = true) {
    if (isset($token, $username) && strlen($token) > 0 && strlen($username) > 0) {
        $listserver = Array();
        $listserver['url'] = 'http://my.bzflag.org/db/';
        $listserver['url'].= '?action=CHECKTOKENS&checktokens=' . urlencode($username);
        if ($checkIP) $listserver['url'].= '@' . $_SERVER['REMOTE_ADDR'];
        $listserver['url'].= '%3D' . $token;
        if (is_array($groups) && sizeof($groups) > 0) $listserver['url'].= '&groups=' . implode("%0D%0A", $groups);
        $listserver['reply'] = trim(file_get_contents($listserver['url']));
        $listserver['reply'] = str_replace("\r\n", "\n", $listserver['reply']);
        $listserver['reply'] = str_replace("\r", "\n", $listserver['reply']);
        $listserver['reply'] = explode("\n", $listserver['reply']);
        foreach($listserver['reply'] as $line) {
            if (substr($line, 0, strlen('TOKGOOD: ')) == 'TOKGOOD: ') {
                if (strpos($line, ':', strlen('TOKGOOD: ')) == FALSE) continue;
                $listserver['groups'] = explode(':', substr($line, strpos($line, ':', strlen('TOKGOOD: ')) + 1));
            } else if (substr($line, 0, strlen('BZID: ')) == 'BZID: ') {
                list($listserver['bzid'], $listserver['username']) = explode(' ', substr($line, strlen('BZID: ')), 2);
            }
        }
        if (isset($listserver['bzid']) && is_numeric($listserver['bzid'])) {
            $return['username'] = $listserver['username'];
            $return['bzid'] = $listserver['bzid'];
            if (isset($listserver['groups']) && sizeof($listserver['groups']) > 0) {
                $return['groups'] = $listserver['groups'];
            } else {
                $return['groups'] = Array();
            }
            return $return;
        }
    }
}
if (!$_GET['token'] || !$_GET['username']) {
    die("Incorrect information submitted.");
} else {
    $fuser = $_GET['username'];
    $ftoken = $_GET['token'];
    $sql = mysql_query("SELECT name FROM groups");
    $grouparray = array();
    while ($group = mysql_fetch_assoc($sql)) {
        array_push($grouparray, $group['name']);
    }
    $result = validate_token($_GET['token'], $_GET['username'], $grouparray);
    if (count($result['groups']) > 0) {
        $_SESSION['callsign'] = $fuser;
        $_SESSION['bzid'] = $result['bzid'];
        $_SESSION['pass'] = $ftoken;
        $_SESSION['groups'] = $result['groups'];
        $bzid = $result['bzid'];
        
        foreach($result['groups'] as $group) {
            // Grab the current group
            $roleidtoget = mysql_fetch_array(mysql_query("SELECT role FROM groups WHERE `name`='$group'"));
            $rolesdata = mysql_fetch_array(mysql_query("SELECT permissions FROM roles WHERE `id`=" . $roleidtoget[0]));
            $perm = str_split($rolesdata['permissions']);
            if ($perm[1] == '0') {
                // Our account is locked, log us out
                $_SESSION = array();
                session_destroy();
                header('Location: ?p=error&error=3');
            } else {
                // Loop through permissions
                $i = 0;
                foreach($perm as $p) {
                    if (!$_SESSION['perm'][$i]) $_SESSION['perm'][$i] = $perm[$i];
                    $i++;
                }
            }
        }
        // Do we have this user?
        if(MySQL::PlayerExists($bzid))
        {
            // Update login data
            MySQL::PlayerLogin($fuser, $bzid);
        }
        else
        {
            // Add player to database
            MySQL::AddPlayer($fuser, $bzid);
        }

        // Set Player ID
        $_SESSION['player'] = MySQL::GetPlayerIDByBZID($bzid);
        header("Location: index.php");
    }
    else
    {
        header("Location: index.php?p=error&error=4");
    }
}
?>