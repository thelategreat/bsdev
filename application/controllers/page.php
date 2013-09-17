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
		parent::__construct();
		$this->load->model('pages_model');
	}

  function index()
  {
    $this->view();
  }

	function view( $id = 0 )
	{
    	$nav		= array();
		$data 		= array();

    	$id = (int)$id;
		$page = $this->pages_model->get_page_by_id( $id );
		
		$nav = $this->pages_model->get_pages_tree( $page->parent_id );
		
		$data = array('title' => 'Page Not Found', 'body' => '');
		if( $page ) {
			$data['title'] = $page->title;
			$data['name'] = $page->title;
			$data['body'] = $page->body;
			$data['nav'] = $nav;
			$data['page'] = $page;
		}
		$pg_data = $this->get_page_data('Bookshelf - ' . $data['title'], 'home' );
  		$pg_data['content'] = $this->load->view('page/page', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
	}

}
