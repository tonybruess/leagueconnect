<?php

class Welcome extends Controller
{
    function Welcome()
    {
        parent::Controller();
    }

    function index()
    {
        $data = array(
            'title' => 'League Connect',
            'username' => 'username',
            'page_title' => 'Welcome',
            'page_navigation' => array(array('Menu Item','Link')),
            'template' => 'pages/index'
        );
        
        $this->load->view('layout', $data);
    }
}

/* End of file index.php */
/* Location: ./system/application/controllers/index.php */