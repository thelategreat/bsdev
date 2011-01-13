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
		
		$pagination = '<table style="width: 100%;"><tr>';
		if( $page > 1 ) {
			$prev_page = $page - 1;
			$pagination .= "<td><a href='/search/results/$prev_page/" . urlencode($query) . "' title='...prev page'><img src='/img/big_feature_left_arrow.png'/></a></td>";
		} else {
			$pagination .= '<td/>';
		}
		if( $results['count'] == $page_size ) {
			$next_page = $page + 1;
			$pagination .= "<td align='right'><a href='/search/results/$next_page/" . urlencode($query) . "' title='next page...'><img src='/img/big_feature_right_arrow.png'/></a></td>";			
		} else {
			$pagination .= '<td/>';			
		}
		$pagination .= '</tr></table>';
		
		
		$view_data = array(
			'results' => $results,
			'page' => $page,
			'page_size' => $page_size,
			'query_string' => $query,
			'pagination' => $pagination
			);		
				
		$pg_data = $this->get_page_data('Bookshelf - Search', 'search-results', 0);
		$pg_data['content'] = $this->load->view('search/results', $view_data, true);
		$this->load->view('layouts/standard_page', $pg_data );	  
	}
}