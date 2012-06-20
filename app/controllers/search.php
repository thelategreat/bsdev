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
		parent::__construct();
		$this->load->model('search_model');
		$this->load->model('lists_model');
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
			$type = $this->input->post('type');
		} else  {
			$query = $this->uri->segment(4);
			$type = $this->uri->segment(5);
			if( !$type ) {
				$type = 'books';
			}
		}
				
		// search type
		
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
		
		$results = array('results' => null, 'count' => 0);
		
		// see if this looks like an ISBN 
		// FIXME dunno if we should keep this ability
		if( strlen(trim($query)) == 13 && (int)$query > 0 && substr($query,0,3) == '978' ) {
			$this->load->model('products_model');
			$res = $this->products_model->get_product_by_ean( trim($query) );
			if( $res->num_rows > 0 ) {
				$row = $res->row();
				redirect('/product/view/' . $row->id );
			}
		} elseif( strlen(trim($query)) > 0 ) {
			$results['results'] = $this->search_model->search($query, $type, $page, $page_size );
			$results['count'] = $results['results']->num_rows();
		} else {
			$results['count'] = 0;
		}		
		
		$pagination = '<table style="width: 100%;"><tr>';
		if( $page > 1 ) {
			$prev_page = $page - 1;
			$pagination .= "<td><a href='/search/results/$prev_page/" . urlencode($query) . "/$type' title='...prev page'><img src='/img/big_feature_left_arrow.png'/></a></td>";
		} else {
			$pagination .= '<td/>';
		}
		if( $results['count'] == $page_size ) {
			$next_page = $page + 1;
			$pagination .= "<td align='right'><a href='/search/results/$next_page/" . urlencode($query) . "/$type' title='next page...'><img src='/img/big_feature_right_arrow.png'/></a></td>";			
		} else {
			$pagination .= '<td/>';			
		}
		$pagination .= '</tr></table>';
		
		$list_meta = array('Serendipity');
    $lists = array();
    foreach( $list_meta as $list_name ) {
      $lists[$list_name] = $this->lists_model->get_list_items_by_name( $list_name );
    }


		$view_data = array(
			'results' => $results,
			'page' => $page,
			'page_size' => $page_size,
			'query_string' => $query,
      'pagination' => $pagination,
      'lists' => $lists
			);		
				
		$pg_data = $this->get_page_data('Bookshelf - Search', 'search-results', 0);
		$pg_data['content'] = $this->load->view('search/results', $view_data, true);
		$this->load->view('layouts/standard_page', $pg_data );	  
	}
}
