<?php
if(!CurrentPlayer::HasPerm(Permissions::EnterMatch)){
	require_once("include/noperm.php");
	require_once("include/footer.php");
	die();
}		
?>
		<h2>Enter Match</h2>
		<p>Under Development</p>
