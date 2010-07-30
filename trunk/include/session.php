<?php
session_start();
header("Cache-control: private");

if(isset($_SESSION['callsign']))
{
    $name = $_SESSION['callsign'];
}
else
{
    $name = 'guest';
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