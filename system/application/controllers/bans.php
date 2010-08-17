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

        $entries = $this->bans_model->getBansArray(($start == null ? 0 : $start));

        $this->layout->add('bans/header.php', array('canAddEntry' => true));
        $this->layout->add('bans/show.php', array('entries' => $entries, 'canEdit' => true));
        $this->layout->render();
    }

    function edit($id)
    {
        if(!is_numeric($id))
        {
            $this->show();
        }

        $this->load->model('bans_model');

        $message = $this->input->post('message');

        if($message != false)
        {
            $this->bans_model->update($id, $message);

            $entries = $this->bans_model->getBansArray();
            $this->layout->add('bans/header.php');
            $this->layout->add('bans/show.php', array('entries' => $entries, 'message' => 'Successfully updated data.'));
            $this->layout->render();
        }
        else
        {
            $ban = $this->bans_model->getBanByID($id);
            $this->layout->add('bans/header.php');
            $this->layout->add('bans/edit.php', array('message' => $ban->message));
            $this->layout->render();
        }
    }

    function add()
    {
        $message = $this->input->post('message');

        if($message == false)
        {
            $this->layout->add('bans/header.php');
            $this->layout->add('bans/edit.php');
            $this->layout->render();
        }
        else
        {
            $author = $this->session->userdata('callsign');
            
            // Add the ban
            $this->load->model('bans_model');
            $this->bans_model->insert($author, $message);
            $entries = $this->bans_model->getBansArray();
            
            $this->layout->add('bans/header.php');
            $this->layout->add('bans/show.php', array('entries' => $entries, 'message' => 'Successfully added the ban.'));
            $this->layout->render();
        }
    }
}

/* End of file bans.php */
/* Location: ./system/application/controllers/bans.php */