<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * Helper class for rendering the page.
 */

/**
 * @name PageRenderer
 * @param string
 */
class PageRenderer
{
    private static $views = array();
    private static $CI;

    public static function AddView($name, $params = array())
    {
        self::$views[] = array('Name' => $name, 'Params' => $params);
    }

    public static function Render()
    {
        $CI = &get_instance();

        $headerData = array(
            'Title' => 'League Connect',
            'Username' => 'Guest'
        );

        $CI->smarty->display('header', $headerData);

        $menuData = array(
            'MenuItems' => array(
                array('Name' => 'Sample Menu Item'),
                array('Name' => 'Another Menu Item')
            )
        );

        $CI->smarty->display('menu', $menuData);

        foreach(self::$views as $view)
        {
            $CI->smarty->display($view['Name'], $view['Params']);
        }

        $CI->load->view('footer');
    }
}
?>
