<?php

class Home extends Controller {

	function Home()
	{
		parent::Controller();	
	}
	
	function index()
	{
            PageRenderer::AddView('home');
            PageRenderer::Render();
	}
}

/* End of file home.php */
/* Location: ./system/application/controllers/home.php */