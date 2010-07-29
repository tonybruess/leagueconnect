<?php

require("../config.php"); // Connection settings

// Connect to MySQL server
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
?>