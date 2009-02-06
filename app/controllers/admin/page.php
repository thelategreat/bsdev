<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Page extends Controller {

	function Page()
	{
		parent::Controller();
		$this->load->database();	
	}
	
	function index()
	{
		$pg = array();
		$pg['page_tree'] = array(
				array( 
					'title' => 'Homepage',
					'children' => NULL
				),
				array( 
					'title' => 'Footer',
					'children' => NULL
				),
			);
		
		
		
		$pg_data = array(
			'title' => 'Admin - Page',
			'content' => $this->load->view('admin/page', $pg, true )
			'footer' => $this->load->view('layouts/standard_footer', '', true),
		);
		$this->load->view('layouts/standard_page', $pg_data );
	}
	
}

