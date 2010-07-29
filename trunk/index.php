<?php
define (CODE_VERSION, "0.01");
require_once("include/session.php");
$name = $_SESSION['callsign'];
require_once("include/mysql.php");
require_once("include/header.php");
require_once("include/menu.php");
if(!isset($_SESSION['callsign']) && $_GET['p'] != 'error')
{	
?>
<h2>Welcome</h2>
You are not logged in
<?php
} else {
	$page = $_GET['p'];
	if(!isset($_GET['p']) || !file_exists("pages/$page.php"))
		$page = 'index';
	else
		$page = $_GET['p'];
	if($page)
		require_once("pages/$page.php");
}
require_once("include/footer.php");
?>