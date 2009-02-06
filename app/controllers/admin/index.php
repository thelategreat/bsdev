<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Index extends Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
	}
	
	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{
		$this->load->view('layouts/admin_nav', '', true);
		
		$pg_data = array(
			'title' => 'Admin',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'footer' => $this->load->view('layouts/standard_footer', '', true),
			'content' => $this->load->view('admin/index', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */