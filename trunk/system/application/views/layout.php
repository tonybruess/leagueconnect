<!DOCTYPE html>
<html>
<head>
<title>League Connect Test</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div id="top">
		<div id="logo"><a href=""><?php echo $title; ?></a></div>
		<div id="statusbar">
			<ul>
				<li><a href='?p=index'>Mail</a></li>
				<li><a href='?p=index'>Logout</a></li>
				<li>Logged in as: <a href="#"><?php echo $username; ?></a></li>
			</ul>
		</div>
	</div>
	<div id="menu">
		<ul>
			<li><a href='?p=index' class="active">Home</a></li>
			<li><a href='?p=news'>News</a></li>
			<li><a href='?p=teams'>Teams</a></li>
			<li><a href='?p=players'>Players</a></li>
			<li><a href='?p=matches'>Matches</a></li>
			<li><a href='?p=help'>Help</a></li>
			<li><a href='?p=contact'>Contact</a></li>
			<li><a href='?p=bans'>Bans</a></li>
			<li><a href='?p=cp'>Control Panel</a></li>
		</ul>
	</div>
	<div id="page_title"><h3><?php echo $page_title ?></h3></div>
	<div id="page_navigation">
<?php
foreach($page_navigation as $item)
{
?>
		<a href="<?php echo $item[1]?>"><?php echo $item[0] ?></a>
<?php
}
?>
	</div>
	<div id="page_body">
<?php $this->load->view($template) ?>

	</div>
	<div id="footer">League Connect v. 0.01 B1 - <a href="http://code.google.com/p/leagueconnect/source/browse/#svn/trunk">Source Code</a></div>
</body>
</html>
