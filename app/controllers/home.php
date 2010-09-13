<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Home extends MY_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
		$this->load->model('articles_model');
	}
	
	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{
		$this->build_page('home');
	}

	function section()
	{
		$this->build_page($this->uri->segment(3));
	}
	
	private function build_page( $section )
	{
		$this->load->model('media_model');
		$res = $this->media_model->get_media_for_path('/pages/1', 'general');		
		$images = array();
		foreach( $res as $row ) {
			$images[] = '/media/' . $row['url'];
		}
		
		$view_data = array(
			'images' => $images
			);
		
		$pg_data = $this->get_page_data('Bookshelf - ' . $section, 'home', $section );
		$pg_data['content'] = $this->load->view('home/home_page', $view_data, true);
		$pg_data['section'] = $section;
		$this->load->view('layouts/standard_page', $pg_data );		
	}
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */