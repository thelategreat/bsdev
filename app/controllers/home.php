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
		$this->load->model('event_model');
		$this->load->model('groups_model');
	}
	
	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{
		$this->build_page(0);
	}

	function section()
	{
		$this->build_page((int)$this->uri->segment(4) ? (int)$this->uri->segment(4) : (int)$this->uri->segment(3));
	}
	
	private function build_page( $section )
	{
		/*
		if( (int)$section ) {
			echo $section;
			echo '<pre>' . var_export($this->groups_model->get_parents( $section ), true) . '</pre>';
			exit;
		}
		*/
		$parents = $this->groups_model->get_parents( $section );
				
		$events = NULL;
		$articles = array();

		$res = $this->articles_model->get_published_article_list($section);
		if( $res->num_rows() > 0 ) {
			$articles = $res->result();
		} else {
			$tree = $this->groups_model->get_tree( 'id', $section, false );
			foreach( $tree as $tree_item ) {
				$res = $this->articles_model->get_published_article_list($tree_item->id, 1 );
				if( $res->num_rows() ) {
					$articles[] = $res->row();
				}			
			}			
		}

		if( $section == 0) {
			$events = $this->event_model->get_next_events( 7 );
		}
		
		/*
		if( $section == 0) {
			$tree = $this->groups_model->get_tree( 'id', 1, false );
			foreach( $tree as $tree_item ) {
				$res = $this->articles_model->get_published_article_list($tree_item->id, 1 );
				if( $res->num_rows() ) {
					$articles[] = $res->row();
				}			
			}
			$events = $this->event_model->get_next_events( 7 );
		} else {
			$articles = $this->articles_model->get_published_article_list($section)->result();			
		}
		*/
		
		//echo '<pre>' . var_export($articles, true) . '</pre>';
		$parents = array_reverse($parents);
		array_shift($parents);
		$view_data = array(
			'parents' => $parents,
			'articles' => $articles,
			'events' => $events
			);
		
		$pg_data = $this->get_page_data('Bookshelf', 'home', $section );
		$pg_data['content'] = $this->load->view('home/home_page', $view_data, true);
		$pg_data['section'] = $this->uri->segment(3);
		$this->load->view('layouts/standard_page', $pg_data );		
	}
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */