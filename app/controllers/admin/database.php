<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Database extends Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
		$this->auth->restrict_role('admin');
		$this->load->database();
	}
	
	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{		
		$db_data = array(
			'tables' => $this->db->list_tables()
		);
		
		$pg_data = array(
			'title' => 'Admin - Database',
			'content' => $this->load->view('admin/database', $db_data, true),
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		
		$this->load->view('layouts/admin_page', $pg_data );
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */