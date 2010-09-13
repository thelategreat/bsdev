<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Page extends MY_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
		$this->load->model('pages_model');
	}
	
	function _remap( $method )
	{		
		$page = $this->pages_model->get_page( $method );
		$data = array('title' => 'Page Not Found', 'body' => '');
		if( $page ) {
			$data['title'] = $page->title;
			$data['body'] = $page->body;
		}
		$pg_data = $this->get_page_data('Bookshelf - ' . $data['title'], 'home' );
  	$pg_data['content'] = $this->load->view('page/page', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
	}
}
