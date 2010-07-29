<?php
function hasPerm($i){
	if(@$_SESSION['perm'][$i])
		return true;
}

function rowClass($i){
	if (($i%2) != 0) return "rowOdd";
	else return "rowEven";
}

function getPlayerName($id)
{
	if (is_numeric($id)) {
		$q = mysql_query("SELECT name FROM players WHERE id = ".$id);
		while ($r = mysql_fetch_array($q, MYSQL_ASSOC)) return $r['name'];
	}
}
?>