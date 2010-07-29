<?php
define ('CODE_VERSION', "0.01");
require_once("include/common.php");
require_once("include/session.php");
@ $name = $_SESSION['callsign'];
require_once("include/mysql.php");
require_once("include/header.php");
@ $page = $_GET['p'];
if(!isset($_GET['p']) || !file_exists("pages/$page.php"))
	$page = 'index';
else
	$page = $_GET['p'];
if($page){
	require_once("include/menu.php");
	require_once("pages/$page.php");
}
require_once("include/footer.php");
?>