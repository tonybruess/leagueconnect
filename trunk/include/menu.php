	<div id="menu">
		<ul>
			<li><a href="index.php"<?php if($page == 'index') echo ' class="active"';?>>Home</a></li>
<?php if(hasPerm(2)){ ?>			<li><a href="?p=mail"<?php if($page == 'mail'){ echo ' class="active"'; } elseif (hasMail()) { echo ' class="new"'; }?>>Mail</a></li><?php } ?>
<?php if(!isset($_SESSION['callsign'])){ ?>			<li><a href="http://my.bzflag.org/weblogin.php?url=http://static.bzextreme.com/webleague/leagueconnect/authenticate.php%3Ftoken%3D%25TOKEN%25%26username%3D%25USERNAME%25">Login</a></li><?php } ?>

			<li><a href="?p=news"<?php if($page == 'news') echo ' class="active"';?>>News</a></li>
			<li><a href="?p=matches"<?php if($page == 'matches') echo ' class="active"';?>>Matches</a></li>
			<li><a href="?p=teams"<?php if($page == 'teams') echo ' class="active"';?>>Teams</a></li>
			<li><a href="?p=players"<?php if($page == 'players') echo ' class="active"';?>>Players</a></li>
			<li><a href="?p=help"<?php if($page == 'help') echo ' class="active"';?>>Help</a></li>
			<li><a href="?p=contact"<?php if($page == 'contact') echo ' class="active"';?>>Contact</a></li>
			<li><a href="?p=bans"<?php if($page == 'bans') echo ' class="active"';?>>Bans</a></li>
			<?php if(isset($_SESSION['callsign'])){ ?><li><a href="?p=logout">Logout</a></li><?php } ?>	
		</ul>
	</div>
	<div id="admin">
		<ul>
<?php if(hasPerm(7)){ ?>			<li><a href="?p=editpages"<?php if($page == 'editpages') echo ' class="active"';?>>Edit Pages</a></li><?php } ?>

<?php if(hasPerm(4)){ ?>			<li><a href="?p=entermatch"<?php if($page == 'entermatch') echo ' class="active"';?>>Match Manager</a></li><?php } ?>

<?php if(hasPerm(8)){ ?>			<li><a href="?p=usermanager"<?php if($page == 'usermanager') echo ' class="active"';?>>User Manager</a></li><?php } ?>

<?php if(hasPerm(5)){ ?>			<li><a href="?p=teammanager"<?php if($page == 'teammanager') echo ' class="active"';?>>Team Manager</a></li><?php } ?>

<?php if(hasPerm(9)){ ?>			<li><a href="?p=logs"<?php if($page == 'logs') echo ' class="active"';?>>Logs</a></li><?php } ?>	
		</ul>
	</div>
	<div id="body">
