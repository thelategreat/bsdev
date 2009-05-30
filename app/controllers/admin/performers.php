<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Performers extends Controller {

	function Performers()
	{
		parent::Controller();
		$this->auth->restrict_role('admin');
	}
	
	function index()
	{
		$pg_data = array(
			'title' => 'Admin - Performers',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/performers', '', true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}
	
}

