<!DOCTYPE html>
<html>
<head>
<title>League Connect Test</title>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Nobile">
<link rel="stylesheet" type="text/css" href='{site_url path="css/style.css"}'>
</head>
<body>
    <div id="top">
        <div id="logo"><a href="">{$Title}</a></div>
        <div id="statusbar">
            <ul>
                <li><a href='{site_url}'>Mail</a></li>
                {if {has_perm perm='Login'}}
                <li><a href='{site_url path="logout"}'>Logout</a></li>
                {else}
                <li><a href='http://my.bzflag.org/weblogin.php?url={"{site_url path="auth/check"}/%TOKEN%/%USERNAME%"|urlencode}'>Login</a></li>
                {/if}
                <li>Logged in as: <a href="#">{$Username}</a></li>
            </ul>
        </div>
    </div>
