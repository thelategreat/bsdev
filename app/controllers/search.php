<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Search extends MY_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
		$this->load->model('search_model');
	}
	
	function index()
	{
		$this->results();
	}
		
	/**
	 * Search
	 *
	 * @return void
	 **/
	function results()
	{
		if( $this->input->post('q') ) {
			$query = $this->input->post('q');
		} else  {
			$query = $this->uri->segment(4);
		}
		
		// search type
		$type = $this->input->post('type');
		
		// FIX ME for urlencode
		$group = $this->input->post('group');
		
		$page_size = 25;
		$page = 1;
		// the page number, maybe
		if( $this->uri->segment(3) && is_numeric($this->uri->segment(3))) {
			$page = $this->uri->segment(3);
			if( $page < 1 ) {
				$page = 1;
			}
		}
		
		$results = array();
		if( strlen(trim($query)) > 0 ) {
			$results['results'] = $this->search_model->search($query, $type, $page, $page_size );
			$results['count'] = $results['results']->num_rows();
		} else {
			$results['count'] = 0;
		}		
		
		$view_data = array(
			'results' => $results,
			'page' => $page,
			'page_size' => $page_size,
			'query_string' => $query
			);		
				
		$pg_data = $this->get_page_data('Bookshelf - Search', 'search-results', 0);
		$pg_data['content'] = $this->load->view('search/results', $view_data, true);
		$this->load->view('layouts/standard_page', $pg_data );	  
	}
}