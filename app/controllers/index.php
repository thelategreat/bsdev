<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Index extends MY_Controller 
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
		$this->load->model('media_model');
		$res = $this->media_model->files_for_section('front_page');		
		$images = array();
		foreach( $res->result() as $row ) {
			$images[] = './pubmedia/front_page/' . $row->filepath;
		}
		
		$pg_data = $this->get_page_data('Bookshelf - Home', 'home' );
		$pg_data['content'] = $this->load->view('home/home_page', array('images' => $images), true);
		$this->load->view('layouts/standard_page', $pg_data );
	}
		
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */