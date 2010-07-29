	<div id="menu">
		<ul>
			<?php if($_SESSION['callsign']){ ?>
			<li><a href="index.php" class="active">Home</a></li>
			<li><a href="?p=mail">Mail</a></li>
			<?php } else { ?>
			<li><a href="http://my.bzflag.org/weblogin.php?url=http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>authenticate.php%3Ftoken%3D%25TOKEN%25%26username%3D%25USERNAME%25">Login</a></li>
			<?php } ?>
			<li><a href="?p=news">News</a></li>
			<li><a href="?p=matches">Matches</a></li>
			<li><a href="?p=teams">Teams</a></li>
			<li><a href="?p=players">Players</a></li>
			<li><a href="?p=help">Help</a></li>
			<li><a href="?p=contact">Contact</a></li>
			<li><a href="?p=bans">Bans</a></li>
			<?php if($_SESSION['callsign']){ ?>
			<li><a href="?p=logout">Logout</a></li>
			<?php } ?>	
		</ul>
	</div>
	<?php if($_SESSION['perm'][1]){ ?>
	<div id="admin">
		<ul>
			<li><a href="#">Enter Match</a></li>
			<li><a href="#">Edit Pages</a></li>
			<li><a href="#">User Manager</a></li>
			<li><a href="#">Team Manager</a></li>
			<li><a href="#">Logs</a></li>
			<?php } ?>	
		</ul>
	</div>
	<div id="body">
