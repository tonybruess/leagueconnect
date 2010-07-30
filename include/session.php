<?php
require_once('./include/current-user.php');

session_start();
header("Cache-control: private");

if(isset($_SESSION['callsign']))
{
    CurrentUser::$Callsign = $_SESSION['callsign'];
}

if(isset($_SESSION['userid']))
{
    $userid = $_SESSION['userid'];
}
else
{
    $userid = null;
}
?>