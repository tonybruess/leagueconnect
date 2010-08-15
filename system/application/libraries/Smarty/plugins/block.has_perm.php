<?php

function smarty_block_has_perm($params, $content)
{
    if(HasPerm($params['perm']))
        return $content;

    else
        return '';
}

?>
