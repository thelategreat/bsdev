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
		$query = $this->input->post('q');
		$group = $this->input->post('group');
		
		$results = array();
		$results['query'] = $query;
		$results['results'] = $this->event_model->search_events($query);
		$results['count'] = $results['results']->num_rows();
				
		$pg_data = $this->get_page_data('Bookshelf - Search', 'search-results');
		$pg_data['content'] = $this->load->view('search/results', array('results'=>$results), true);
		$this->load->view('layouts/standard_page', $pg_data );	  
	}
}