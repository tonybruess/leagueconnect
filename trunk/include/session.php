<?php
require_once('./include/current-player.php');

session_start();
header("Cache-control: private");

if(isset($_SESSION['player']))
{
    CurrentPlayer::$ID = $_SESSION['player'];
}
?>