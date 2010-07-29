<?php
// DEFINE SQL HERE
define('SQL_SERVER','localhost'); 
define('SQL_USER','root'); 
define('SQL_PASS',''); 
define('SQL_DB','WebLeague');      
// Creating the connection using the above configuration
mysql_connect(SQL_SERVER,SQL_USER,SQL_PASS) or die("Error: ".mysql_error()); // Connecting to the server
mysql_select_db(SQL_DB) or die("Error: ".mysql_error()); // Connecting to the database

function sanitize($str) {
	if (function_exists( "mysql_real_escape_string" )) {
		$str = mysql_real_escape_string($str) ;
	} else {
		$str = addslashes($str) ;
	}
	return $str ;
}

if($_SESSION['callsign']){
   	$userdata = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE `name`='$name'"));	 
	$rolesdata = mysql_fetch_array(mysql_query("SELECT * FROM roles WHERE `id`=".$userdata['permissions'].""));
	$perm = str_split($rolesdata['permissions']);
    if($perm[1]=='0'){
    	$_SESSION = array();
    	session_destroy();
    	header('Location: ?p=error&error=3');
    } else {
		$_SESSION['perm'][0] = $perm[0];
		$_SESSION['perm'][1] = $perm[1];
		$_SESSION['perm'][2] = $perm[2];
		$_SESSION['perm'][3] = $perm[3];
		$_SESSION['perm'][4] = $perm[4];
		$_SESSION['perm'][5] = $perm[5];
		$_SESSION['perm'][6] = $perm[6];
		$_SESSION['perm'][7] = $perm[7];
		$_SESSION['perm'][8] = $perm[8];
		$_SESSION['perm'][9] = $perm[9];
		$_SESSION['perm'][10] = $perm[10];
		$_SESSION['perm'][11] = $perm[11];
		$_SESSION['perm'][12] = $perm[12];
		$_SESSION['perm'][13] = $perm[13];
		$_SESSION['perm'][14] = $perm[14];
		$_SESSION['perm'][15] = $perm[15];
		$_SESSION['perm'][16] = $perm[16];
		$_SESSION['perm'][17] = $perm[17];
		$_SESSION['perm'][18] = $perm[18];
		$_SESSION['perm'][19] = $perm[19];
		$_SESSION['perm'][20] = $perm[20];
		$_SESSION['perm'][21] = $perm[21];
		$_SESSION['perm'][22] = $perm[22];
		$_SESSION['perm'][23] = $perm[23];
		$_SESSION['perm'][24] = $perm[24];
		$_SESSION['perm'][25] = $perm[25];
		$_SESSION['perm'][26] = $perm[26];
		$_SESSION['perm'][27] = $perm[27];
		$_SESSION['perm'][28] = $perm[28];
		$_SESSION['perm'][29] = $perm[29];
		$_SESSION['perm'][30] = $perm[30];
		$_SESSION['perm'][31] = $perm[31];
		$_SESSION['perm'][32] = $perm[32];
		$_SESSION['perm'][33] = $perm[33];
		$_SESSION['perm'][34] = $perm[34];
	}
}
?>