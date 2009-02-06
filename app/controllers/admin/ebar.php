<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Ebar extends Controller {

	function Ebar()
	{
		parent::Controller();
	}
	
	function index()
	{
		$pg_data = array(
			'title' => 'Admin - eBar',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/ebar', '', true ),
			'footer' => $this->load->view('layouts/standard_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}
	
}

