<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('system/application/libraries/Smarty/Smarty.class.php');

class CI_Smarty extends Smarty
{
    function Smarty()
    {
        $systemPath = $_SERVER['DOCUMENT_ROOT'].'/system';
        
        $this->template_dir = $systemPath.'/application/views/';
        $this->compile_dir = $systemPath.'/cache/';
    }

    public function display($template, $params=array())
    {
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


        print $this->template_dir . $template;
        if(!is_file($this->template_dir . $template))
        {
            show_error("Template [$template] cannot be found.");
        }
        return parent::display($template);
    }
}

?>