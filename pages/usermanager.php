<?php
if(!hasPerm(8)){
	require_once("include/noperm.php");
	require_once("include/footer.php");
	die();
}		
?>
		<h2>User Manager</h2>
		<p>Current Users:</p>
		<table>
		<tr>
		<td>Name</td>
