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
	}
	
	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{
		$this->load->model('media_model');
		$res = $this->media_model->files_for_path('pages/1');		
		$images = array();
		foreach( $res->result() as $row ) {
			$images[] = '/pubmedia/front_page/' . $row->filepath;
		}
		
		$pg_data = $this->get_page_data('Bookshelf - Home', 'home' );
		$pg_data['content'] = $this->load->view('home/home_page', array('images' => $images), true);
		$this->load->view('layouts/standard_page', $pg_data );
	}
	
	function books()
	{
		$this->load->model('media_model');
		$res = $this->media_model->files_for_path('pages/1');		
		$images = array();
		foreach( $res->result() as $row ) {
			$images[] = '/pubmedia/front_page/' . $row->filepath;
		}
		
		$pg_data = $this->get_page_data('Bookshelf - Home', 'home' );
		$pg_data['section'] = 'books';
		$pg_data['style'] = '/css/green.css';
		$pg_data['content'] = $this->load->view('home/home_page', array('images' => $images), true);
		$this->load->view('layouts/standard_page', $pg_data );
		
	}

	function ebar()
	{
		$this->load->model('media_model');
		$res = $this->media_model->files_for_path('pages/1');		
		$images = array();
		foreach( $res->result() as $row ) {
			$images[] = '/pubmedia/front_page/' . $row->filepath;
		}
		
		$pg_data = $this->get_page_data('Bookshelf - Home', 'home' );
		$pg_data['section'] = 'ebar';
		$pg_data['style'] = '/css/blue.css';
		$pg_data['content'] = $this->load->view('home/home_page', array('images' => $images), true);
		$this->load->view('layouts/standard_page', $pg_data );
		
	}
	
	function cinema()
	{
		$this->load->model('media_model');
		$res = $this->media_model->files_for_path('pages/1');		
		$images = array();
		foreach( $res->result() as $row ) {
			$images[] = '/pubmedia/front_page/' . $row->filepath;
		}
		
		$pg_data = $this->get_page_data('Bookshelf - Home', 'home' );
		$pg_data['section'] = 'cinema';
		$pg_data['style'] = '/css/purple.css';
		$pg_data['content'] = $this->load->view('home/home_page', array('images' => $images), true);
		$this->load->view('layouts/standard_page', $pg_data );		
	}
		
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */