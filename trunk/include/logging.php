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
        $e = new Exception();

        $msg = date('l F jS, Y g:i:sA') . "\n"
             . $error . "\n"
             . "Backtrace:\n"
             . $e->getTraceAsString()
             . "----------\n";

        file_put_contents(Config::ErrorLogFile, $msg, FILE_APPEND);
    }
}

file_put_contents(Config::ErrorLogFile, '', FILE_APPEND);

?>
