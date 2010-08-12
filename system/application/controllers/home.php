<?php

class Home extends Controller {

	function Home()
	{
		parent::Controller();	
	}
	
	function index()
	{
        $this->layout->add('home');
        $this->layout->render();
	}
}

/* End of file home.php */
/* Location: ./system/application/controllers/home.php */