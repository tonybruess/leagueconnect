<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('system/application/libraries/Smarty/Smarty.class.php');

class CI_Smarty extends Smarty
{
    function CI_Smarty()
    {
    }

    public function display($template, $params=array())
    {
        $systemDir = dirname($_SERVER['SCRIPT_FILENAME']).'/system';
        $this->setTemplateDir($systemDir.'/application/views/');
        $this->compile_dir = $systemDir.'/cache/';
        $this->cache_dir = $systemDir.'/cache/';

        if(strpos($template, '.') === false)
        {
            $template .= '.php';
        }

        if(is_array($params))
        {
            foreach($params as $key => $val)
            {
                $this->assign($key, $val);
            }
        }

        if(!is_file($this->template_dir[0] . $template))
        {
            show_error("Template [$template] cannot be found.");
        }

        $result = '';
        
        try
        {
            $result = parent::display($template);
        }
        catch(Exception $e)
        {
            show_error($e->getMessage());
        }

        return $result;
    }
}

?>