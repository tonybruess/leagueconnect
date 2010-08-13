<?php

class News extends Controller
{
	function News()
	{
		parent::Controller();	
	}
	
	function index()
	{
        $this->layout->render('news');
	}
}

/* End of file news.php */
/* Location: ./system/application/controllers/news.php */