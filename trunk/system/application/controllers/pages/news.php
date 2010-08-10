<?php

class News extends Controller {

	function News()
	{
		parent::Controller();	
	}
	
	function index()
	{
		$this->load->view('pages/news');
	}
}

/* End of file news.php */
/* Location: ./system/application/controllers/pages/news.php */