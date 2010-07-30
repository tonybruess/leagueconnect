<?php
require_once('./include/current-player.php');

session_start();
header("Cache-control: private");

if(isset($_SESSION['callsign']))
{
    CurrentUser::$Name = $_SESSION['callsign'];
}

if(isset($_SESSION['userid']))
{
    CurrentUser::$UserID = $_SESSION['userid'];
}
?>