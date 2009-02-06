<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Bookstore extends Controller {

	function Bookstore()
	{
		parent::Controller();
	}
	
	function index()
	{
		$pg_data = array(
			'title' => 'Admin - Bookstore',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/bookstore', '', true ),
			'footer' => $this->load->view('layouts/standard_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}
	
}

