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
	
	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{
	  $this->about();
	}
	
	function about()
	{
		$pg_data = $this->get_page_data('Bookshelf - About', 'home');
		
		$page = $this->pages_model->get_page('About');
		$data = array('title' => 'Page Not Found', 'body' => '');
		if( $page ) {
			$data['title'] = $page->title;
			$data['body'] = $page->body;
		}
  	$pg_data['content'] = $this->load->view('page/page', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
	}


	function contact()
	{
		$pg_data = $this->get_page_data('Bookshelf - Contact', 'home');
		$page = $this->pages_model->get_page('Contact');
		$data = array('title' => 'Page Not Found', 'body' => '');
		if( $page ) {
			$data['title'] = $page->title;
			$data['body'] = $page->body;
		}
  	$pg_data['content'] = $this->load->view('page/page', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
	}
	
	function legal()
	{
		$pg_data = $this->get_page_data('Bookshelf - Legal', 'home');
		$page = $this->pages_model->get_page('Legal');
		$data = array('title' => 'Page Not Found', 'body' => '');
		if( $page ) {
			$data['title'] = $page->title;
			$data['body'] = $page->body;
		}
  	$pg_data['content'] = $this->load->view('page/page', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
	}

	function privacy()
	{
		$pg_data = $this->get_page_data('Bookshelf - Privacy', 'home');
		$page = $this->pages_model->get_page('Privacy');
		$data = array('title' => 'Page Not Found', 'body' => '');
		if( $page ) {
			$data['title'] = $page->title;
			$data['body'] = $page->body;
		}
  	$pg_data['content'] = $this->load->view('page/page', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
	}


	function subscribe()
	{
		$pg_data = $this->get_page_data('Bookshelf - Subscribe', 'home');
	  $pg_data['content'] = '<h2>Subscribe</h2>';
		$this->load->view('layouts/standard_page', $pg_data );		
	}
	
	function help()
	{
		$pg_data = $this->get_page_data('Bookshelf - Help', 'home');
		$page = $this->pages_model->get_page('Help');
		$data = array('title' => 'Page Not Found', 'body' => '');
		if( $page ) {
			$data['title'] = $page->title;
			$data['body'] = $page->body;
		}
  	$pg_data['content'] = $this->load->view('page/page', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );		
	}	
}
