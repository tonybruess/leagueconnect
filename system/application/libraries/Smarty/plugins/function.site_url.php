<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsFunction
 */

/* Only works with CodeIgniter */
/* Interface for the CodeIgniter site_url function */

function smarty_function_site_url($args, &$smarty)
{
    return site_url($args['path']);
}
