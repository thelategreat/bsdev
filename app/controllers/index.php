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
		$pg_data = array(
			'title' => 'Welcome',
			'content' => $this->load->view('home_page', '', true)
		);
		$this->load->view('layouts/standard_page', $pg_data );
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */