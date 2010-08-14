<?php

class Bans extends Controller
{
	function Bans()
	{
		parent::Controller();
	}

	function index()
	{
        $this->load->model('bansmodel', '', TRUE);

        $data = array(
            'canAddNew' => true,
            'canEdit' => true,
            'entries' => $this->bansmodel->getBans()
        );

        $this->layout->render('showbans', $data);
	}
}

/* End of file bans.php */
/* Location: ./system/application/controllers/bans.php */