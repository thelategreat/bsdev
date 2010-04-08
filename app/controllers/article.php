<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Article extends MY_Controller 
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
		$this-view();
	}
	
	
	function view()
	{
		$id = $this->uri->segment(3);
		
		$this->load->model('media_model');
		$res = $this->media_model->get_media_for_path('/pages/1', 'general');		
		$images = array();
		foreach( $res as $row ) {
			$images[] = '/media/' . $row['url'];
		}
		
		$res = $this->articles_model->get_article($id);
		if( $res->num_rows() ) {
			$res = $res->row();
		} else {
			$res = NULL;
		}
		
		$view_data = array(
			'article' => $res 
			);
		
		$pg_data = $this->get_page_data('Bookshelf - Home', 'home' );
		$pg_data['content'] = $this->load->view('page/article', $view_data, true);
		$this->load->view('layouts/standard_page', $pg_data );		
	}
}

?>