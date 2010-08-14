<?php

class Bans extends Controller
{
	function Bans()
	{
		parent::Controller();
	}

	function index()
	{
        $this->load->model('bans_model');

        $data = array(
            'canAddNew' => true,
            'canEdit' => true,
            'entries' => $this->bans_model->getBans()
        );

        $this->layout->render('showbans', $data);
	}

    function show($start=null)
    {
        $this->load->model('bans_model');

        $data = array(
            'canAddEntry' => true,
            'canEdit' => true,
            'entries' => $this->bans_model->getBans(($start == null ? 0 : $start))
        );

        $this->layout->render('showbans', $data);
    }
}

/* End of file bans.php */
/* Location: ./system/application/controllers/bans.php */