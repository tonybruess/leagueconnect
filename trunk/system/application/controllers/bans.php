<?php

class Bans extends Controller
{
	function Bans()
	{
		parent::Controller();
        $this->load->scaffolding('bans');
	}

	function index()
	{
        $this->show();
	}

    function show($start=null)
    {
        $this->load->model('bans_model');

        $entries = $this->bans_model->getBans(($start == null ? 0 : $start));

        $data = array(
            'canAddEntry' => true,
            'canEdit' => true
        );

        $this->layout->add('bans/header.php', array('canAddEntry' => true));
        $this->layout->add('bans/show.php', array('entries' => ObjectToArray($entries)));
        $this->layout->render();
    }
}

/* End of file bans.php */
/* Location: ./system/application/controllers/bans.php */