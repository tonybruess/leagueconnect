<?php
function hasPerm($i){
	if($_SESSION['perm'][$i])
		return true;
}
?>