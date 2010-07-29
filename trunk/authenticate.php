<?php
session_start(); 
header("Cache-control: private");
include("include/mysql.php");
if(!$_GET['token'] || !$_GET['username']){
die("Incorrect information submitted.");
} else {
	
	// TODO: Add some error handling/reporting

function validate_token($token, $username, $groups = array(), $checkIP = true)
{
  if (isset($token, $username) && strlen($token) > 0 && strlen($username) > 0)
  {
    $listserver = Array();

    // First off, start with the base URL
    $listserver['url'] = 'http://my.bzflag.org/db/';
    // Add on the action and the username
    $listserver['url'] .= '?action=CHECKTOKENS&checktokens='.urlencode($username);
    // Make sure we match the IP address of the user
    if ($checkIP) $listserver['url'] .= '@'.$_SERVER['REMOTE_ADDR'];
    // Add the token
    $listserver['url'] .= '%3D'.$token;
    // If use have groups to check, add those now
    if (is_array($groups) && sizeof($groups) > 0)
      $listserver['url'] .= '&groups='.implode("%0D%0A", $groups);

    // Run the web query and trim the result
    // An alternative to this method would be to use cURL
    $listserver['reply'] = trim(file_get_contents($listserver['url']));

    // Fix up the line endings just in case
    $listserver['reply'] = str_replace("\r\n", "\n", $listserver['reply']);
    $listserver['reply'] = str_replace("\r", "\n", $listserver['reply']);
    $listserver['reply'] = explode("\n", $listserver['reply']);

    // Grab the groups they are in, and their BZID
    foreach ($listserver['reply'] as $line)
    {
      if (substr($line, 0, strlen('TOKGOOD: ')) == 'TOKGOOD: ')
      {
        if (strpos($line, ':', strlen('TOKGOOD: ')) == FALSE) continue;
        $listserver['groups'] = explode(':', substr($line, strpos($line, ':', strlen('TOKGOOD: '))+1 ));
      }
      else if (substr($line, 0, strlen('BZID: ')) == 'BZID: ')
      {
        list($listserver['bzid'],$listserver['username']) = explode(' ', substr($line, strlen('BZID: ')), 2);
      }
    }

    if (isset($listserver['bzid']) && is_numeric($listserver['bzid']))
    {
      $return['username'] = $listserver['username'];
      $return['bzid'] = $listserver['bzid'];

      if (isset($listserver['groups']) && sizeof($listserver['groups']) > 0)
      {
        $return['groups'] = $listserver['groups'];
      }
      else
      {
        $return['groups'] = Array();
      }

      return $return;
    }
  } 
} 

    $fuser = $_GET['username'];
    $ftoken = $_GET['token']; 
	$q = mysql_query("SELECT name FROM groups");
	$grouparray = array();
	while($group = mysql_fetch_assoc($q)){
		array_push($grouparray,$group['name']);
	}
	$result = validate_token($_GET['token'], $_GET['username'], $grouparray);
	if(count($result['groups']) > 0) { 
		$_SESSION['callsign'] = $fuser;
		$_SESSION['bzid'] = $result['bzid'];
		$_SESSION['pass'] = $ftoken;
		$_SESSION['groups'] = $result['groups'];
		$bzid = $result['bzid'];
		$ts = time();
		foreach($result['groups'] as $group){
			// Grab the current group
   			$roleidtoget = mysql_fetch_array(mysql_query("SELECT role FROM groups WHERE `name`='$group'"));
			$rolesdata = mysql_fetch_array(mysql_query("SELECT permissions FROM roles WHERE `id`=".$roleidtoget[0]));
			$perm = str_split($rolesdata['permissions']);
    		if($perm[1]=='0'){
    			// Our account is locked, log us out
    			$_SESSION = array();
    			session_destroy();
    			header('Location: ?p=error&error=3');
			} else {
				// Loop through permissions
				$i = 0;
				foreach($perm as $p){
					if(!$_SESSION['perm'][$i])
						$_SESSION['perm'][$i] = $perm[$i];
					$i++;
				}
			}
		}
		// Do we have this user?
		$q = mysql_query("SELECT * FROM players WHERE `bzid`='$bzid'");
		$user = mysql_fetch_assoc($q);
		if($user){
			// Update last login because we have them
			mysql_query("UPDATE players SET `lastlogin`='$ts',`name`='$fuser'");
			echo mysql_error();
		} else {
			// Add them as a new user
			mysql_query("INSERT INTO players (`name`,`bzid`,`firstlogin`,`lastlogin`) VALUES ('$fuser','$bzid','$ts','$ts')");
			echo mysql_error();
		}
		header("Location: index.php");
	} else {
		header("Location: index.php?p=error&error=4");
	}
}
?>