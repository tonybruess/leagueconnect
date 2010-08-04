<?php

define ('CODE_VERSION', "0.01");

require_once("include/session.php");
require_once("include/common.php");
require_once("include/database.php");
require_once("include/header.php");

$page = (isset($_GET['p']) ? $_GET['p'] : 'index');
$page = preg_replace('/[^a-zA-Z0-9]/', '', $page); // Prevent people from going somewhere they shouldn't be

require_once("include/menu.php");
require_once("pages/$page.php");

require_once("include/footer.php");

?>