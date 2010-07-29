<?php
function hasPerm($i){
	if(@$_SESSION['perm'][$i])
		return true;
}

function rowClass($i){
	if (($i%2) != 0) return "rowOdd";
	else return "rowEven";
}
?>