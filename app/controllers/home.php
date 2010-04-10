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
		$this->load->model('media_model');
		$res = $this->media_model->get_media_for_path('/pages/1', 'general');		
		$images = array();
		foreach( $res as $row ) {
			$images[] = '/media/' . $row['url'];
		}
		
		$view_data = array(
			'images' => $images
			);
		
		$pg_data = $this->get_page_data('Bookshelf - Home', 'home' );
		$pg_data['content'] = $this->load->view('home/home_page', $view_data, true);
		$this->load->view('layouts/standard_page', $pg_data );
	}
	
	function books()
	{
		$this->load->model('media_model');
		$res = $this->media_model->get_media_for_path('/pages/1', 'general');		
		$images = array();
		foreach( $res as $row ) {
			$images[] = '/media/' . $row['url'];
		}

		$res = $this->articles_model->get_published_article_list('Books');

		$view_data = array(
			'view_title' => 'Bookstore',
			'articles' => $res
			);

		
		$pg_data = $this->get_page_data('Bookshelf - Home', 'home' );
		$pg_data['section'] = 'books';
		$pg_data['style'] = '/css/green.css';
		$pg_data['content'] = $this->load->view('home/home_articles', $view_data, true);
		$this->load->view('layouts/standard_page', $pg_data );
		
	}

	function ebar()
	{
		$this->load->model('media_model');
		$res = $this->media_model->get_media_for_path('/pages/1', 'general');		
		$images = array();
		foreach( $res as $row ) {
			$images[] = '/media/' . $row['url'];
		}
		
		$res = $this->articles_model->get_published_article_list('eBar');

		$view_data = array(
			'view_title' => 'eBar',
			'articles' => $res
			);
		
		$pg_data = $this->get_page_data('Bookshelf - Home', 'home' );
		$pg_data['section'] = 'ebar';
		$pg_data['style'] = '/css/blue.css';
		$pg_data['content'] = $this->load->view('home/home_articles', $view_data, true);
		$this->load->view('layouts/standard_page', $pg_data );
		
	}
	
	function cinema()
	{
		$this->load->model('media_model');
		$res = $this->media_model->get_media_for_path('/pages/1', 'general');		
		$images = array();
		foreach( $res as $row ) {
			$images[] = '/media/' . $row['url'];
		}
		
		$res = $this->articles_model->get_published_article_list('Cinema');

		$view_data = array(
			'view_title' => 'Cinema',
			'articles' => $res
			);
		
		$pg_data = $this->get_page_data('Bookshelf - Home', 'home' );
		$pg_data['section'] = 'cinema';
		$pg_data['style'] = '/css/purple.css';
		$pg_data['content'] = $this->load->view('home/home_articles', $view_data, true);
		$this->load->view('layouts/standard_page', $pg_data );		
	}
		
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */