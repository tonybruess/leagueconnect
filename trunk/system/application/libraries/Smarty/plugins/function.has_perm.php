<?php

function smarty_function_has_perm($params)
{
    return HasPerm($params['perm']);
}

?>
