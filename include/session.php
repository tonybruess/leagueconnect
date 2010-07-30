<?php
require_once('./include/current-player.php');

session_start();
header("Cache-control: private");

if(isset($_SESSION['callsign']))
{
    CurrentPlayer::$Name = $_SESSION['callsign'];
}

if(isset($_SESSION['userid']))
{
    CurrentPlayer::$ID = $_SESSION['userid'];
}
?>