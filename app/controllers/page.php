<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Page extends Controller 
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
	  $this->about();
	}
	
	function about()
	{
	  $pg_data = $this->load_page();
	  $pg_data['content'] = '<h2>About</h2>';
		$this->load->view('layouts/standard_page', $pg_data );
	}


	function contact()
	{
	  $pg_data = $this->load_page();
	  $pg_data['content'] = '<h2>Contact</h2>';
		$this->load->view('layouts/standard_page', $pg_data );
	}
	
	function legal()
	{
	  $pg_data = $this->load_page();
	  $pg_data['content'] = '<h2>Legal</h2>';
		$this->load->view('layouts/standard_page', $pg_data );
	}

	function privacy()
	{
	  $pg_data = $this->load_page();
	  $pg_data['content'] = '<h2>Privacy</h2>';
		$this->load->view('layouts/standard_page', $pg_data );
	}


	function subscribe()
	{
	  $pg_data = $this->load_page();
	  $pg_data['content'] = '<h2>Subscribe</h2>';
		$this->load->view('layouts/standard_page', $pg_data );		
	}
	
	function help()
	{
	  $pg_data = $this->load_page();
	  $pg_data['content'] = '<h2>Help</h2>';
		$this->load->view('layouts/standard_page', $pg_data );		
	}
	
	private function load_page()
	{
		$pg_data = array(
			'title' => 'Welcome',
			'page_title' => 'Bookshelf - Home',
			'page_name' => 'home',
			'main_content_nav' => '<ul id="main_content_nav"><li></li></ul>',
			'content' => '',
			'sidebar_nav' => $this->load->view('events/sidebar_nav', '', true ),
			'sidebar' => $this->load->view('home/sidebar', '', true ),
			'footer' => $this->load->view('layouts/standard_footer', '', true )
		);	
		
		return $pg_data;  
	}
	
}
