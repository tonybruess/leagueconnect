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
		if($this->uri->segment(1) && !$this->uri->segment(2))
			die();
		if(!$this->uri->segment(2))
			$this->load->view('pages/home');
		else
			$this->load->view('pages/' . $this->uri->segment(2));
		$this->parser->parse('footer', $data);
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */