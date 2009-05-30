<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Search extends Controller 
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
		$content_nav = '<ul id="main_content_nav">
  		<li class="search_results selected"><a class="cufon" href="page-search-results.html">Search Results</a></li>
  		<li class="results_meta">Showing 1-6 of 53 results found. Click arrows to view additional results.</li>
  	</ul>';
		
		
		$pg_data = array(
			'title' => 'Search',
			'page_title' => 'Bookshelf - Search',
			'page_name' => 'search-results',
			'main_nav_arrows' => '<a id="left_arrow" href="#">&laquo; Previous</a><a id="right_arrow" href="#">Next &raquo;</a>',
			'main_content_nav' => $content_nav,
			'content' => $this->load->view('search/results', '', true ),
			'sidebar_nav' => $this->load->view('search/sidebar_nav', '', true ),
			'sidebar' => $this->load->view('search/sidebar', '', true ),
			'footer' => $this->load->view('layouts/standard_footer', '', true )
		);
		
		$this->load->view('layouts/standard_page', $pg_data );			
	}
}