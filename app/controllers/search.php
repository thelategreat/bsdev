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

		$results = array();
		$results['query'] = $query;
		$results['results'] = $this->event_model->search_events($query);
		$results['count'] = $results['results']->num_rows();
		
		$main_content_nav = '<ul id="main_content_nav">
  		<li class="search_results selected"><a class="cufon" href="#">Search Results</a></li>
  		<li class="results_meta">Showing 1-6 of '.$results['count'].' results found. Click arrows to view additional results.</li>
  	</ul>';
		
		$pg_data = $this->get_page_data('Bookshelf - Search', 'search-results');
		$pg_data['content'] = $this->load->view('search/results', array('results'=>$results), true);
		$pg_data['main_content_nav'] = $main_content_nav;
		$pg_data['main_nav_arrows'] = $this->main_nav_arrows();
		$pg_data['sidebar_nav'] = $this->load->view('search/sidebar_nav', array('results'=>$results), true );
		$pg_data['sidebar'] = $this->load->view('search/sidebar', array('results'=>$results), true );
		$this->load->view('layouts/standard_page', $pg_data );	  
	}
}