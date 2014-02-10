<?php
class error404 extends CI_Controller 
{
    public function __construct()   {
		parent::__construct();  
		$this->load->model('groups_model');
    }

    public function index() 
    {
        $this->output->set_status_header('404');

        // The nav is coming from the group tree / model.  There's a similar call for the pages
		// which works the same way.
		$nav = $this->groups_model->get_group_tree();
		$data['nav'] = $nav[0]->children;

        $this->load->view('errors/page_not_found', $data);
    }
}
?>