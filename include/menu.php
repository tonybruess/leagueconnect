	<div id="menu">
		<ul>
			<li><a href="index.php" class="active">Home</a></li>
			<?php if($_SESSION['callsign']){ ?>
			<li><a href="#">Mail</a></li>
			<li><a href="#">News</a></li>
			<li><a href="#">Matches</a></li>
			<li><a href="#">Teams</a></li>
			<li><a href="#">Players</a></li>
			<li><a href="#">Help</a></li>
			<li><a href="#">Contact</a></li>
			<li><a href="#">Bans</a></li>
			<?php } else { ?>
			<li><a href="http://my.bzflag.org/weblogin.php?url=http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>authenticate.php%3Ftoken%3D%25TOKEN%25%26username%3D%25USERNAME%25">Login</a></li>
			<?php } ?>		
		</ul>
	</div>
	<?php if(!$_SESSION['perm'][1]){ ?>
	<div id="admin">
		<ul>
			<li><a href="index.php" class="active">Home</a></li>
			<li><a href="#">Mail</a></li>
			<li><a href="#">News</a></li>
			<li><a href="#">Matches</a></li>
			<li><a href="#">Teams</a></li>
			<li><a href="#">Players</a></li>
			<li><a href="#">Help</a></li>
			<li><a href="#">Contact</a></li>
			<li><a href="#">Bans</a></li>
			<?php } ?>	
		</ul>
	</div>
	<div id="body">
