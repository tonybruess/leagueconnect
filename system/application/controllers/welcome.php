<?php

class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();	
	}
	
	function index()
	{
		$this->load->library('parser');	
		$data = array(
            'title' => 'League Connect',
            'username' => 'username'
        );
		$this->parser->parse('header', $data);
		$this->parser->parse('menu', $data);
		$this->load->view('pages/home');
		$this->parser->parse('footer', $data);
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */