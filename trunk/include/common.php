<?php
/* 
 * Stuff that doesn't belong any where else.
 */


/* void */ function rowClass($i)
{
    if ( ( $i % 2) != 0)
        return 'rowOdd';
    else
        return 'rowEven';
}

?>
