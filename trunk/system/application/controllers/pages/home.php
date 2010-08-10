<?php

class Home extends Controller {

	function Home()
	{
		parent::Controller();	
	}
	
	function index()
	{
		$this->load->view('pages/home');
	}
}

/* End of file home.php */
/* Location: ./system/application/controllers/pages/home.php */