<?php
/* 
 * Log errors and other details.
 */

require_once('config.php');

class Logging
{
    /* void */ public static function LogError()
    {
        $error = implode("\n", func_get_args());

        $msg = date('l F jS, Y at g:i:sA') . "\n"
             . $error . "\n"
             . "----------\n";

        file_put_contents(Config::ErrorLogFile, $msg, FILE_APPEND);
    }
}

file_put_contents(Config::ErrorLogFile, '', FILE_APPEND);

?>
