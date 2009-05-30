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
			'page_title' => 'Bookshelf - Home',
			'page_name' => 'home',
			'main_content_nav' => '<ul id="main_content_nav"><li></li></ul>',
			'content' => $this->load->view('home/home_page', '', true),
			'sidebar_nav' => $this->load->view('events/sidebar_nav', '', true ),
			'sidebar' => $this->load->view('home/sidebar', '', true ),
			'footer' => $this->load->view('layouts/standard_footer', '', true )
		);
		$this->load->view('layouts/standard_page', $pg_data );
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */