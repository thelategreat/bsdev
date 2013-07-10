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
		$this->load->model('tag_model');
	}
	
	function index()
	{
		$this->results();
	}
	
	/**
	 * JSON Search
	 *
	 * Callback for search results in JSON format
	 * @param required POST q Query to search for
	 * @param optional POST size Result size, default 25 results
	 * @param optional POST type Result type fiter flags 'b'=book 'f'=film
	 */
	function json() {
		$this->load->library('httpstatus');
		$query = $this->input->post('q');
		$size = $this->input->post('size');
		$type = $this->input->post('type');
		
		$out = new stdClass();
		
		if (!$query || trim($query) == '') {
			$out->status = $this->httpstatus->status(400, 'No query specified');
			echo json_encode($out);
			exit;			
		}
		
		if (!$size) $size = 250;
		if (!$type) $type = 'all'; // Book & Film
		
		$results = array();
		try {
			if( strlen(trim($query)) == 13 && (int)$query > 0 && substr($query,0,3) == '978' ) {
				$this->load->model('products_model');
				$res = $this->products_model->get_product_by_ean( trim($query) );
				foreach ($res->result() as $row) {
					$results[] = $row;
				}
			} elseif( strlen(trim($query)) > 0 ) {
				$results = $this->search_model->search_callback($query, 'books, articles, events');
			} 
		
			$out->status = $this->httpstatus->status(200, 'OK');
			$out->data = $results;
		} catch (Error $e) {
			$out->status = $this->httpstatus->status(400, $e->getMessage());
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($out));
	}
		
		
	/*
	 * Search results page driven by user query in search box
	 */
	function results() {
		$query = $this->input->post('q');
		$type  = $this->input->post('type');
		$group = $this->input->post('group');
		
		$params = $this->uri->uri_to_assoc(3);
		if ( isset($params['q']) ) $query = $params['q'];
		if ( isset( $params['page'] )
			&& is_int( $params['page'] ) ) {
				$page = $params['page'];
			} else {
				$page = 1;
			}
		if ( isset( $params['size'] )
			&& is_int( $params['size'] ) ) {
				$page_size = $params['size'];
			} else {
				$page_size = 25;
			}
		
		$query = trim($query);
		
		// see if this looks like an ISBN 
		// FIXME dunno if we should keep this ability
		if( strlen($query) == 13 && is_int($query) && substr($query,0,3) == '978' ) {
			$this->load->model('products_model');
			$res = $this->products_model->get_product_by_ean( $query );
			if( $res->num_rows > 0 ) {
				$row = $res->row();
				redirect('/product/view/' . $row->id );
			}
		} elseif( strlen( $query ) > 0 ) {
			$res = $this->search_model->search($query, $type, $page, $page_size );
			$results = $res->result();
		} else {
			$results = array();
		}
		
		
		$prev = $next = false;
		if( $page > 1 ) {
			$prev_page = $page - 1;
			$params['page'] = $prev_page;
			$prev = '/search/results/' . $this->assoc_to_uri($params);
		}
		if ( count($results) > $page * $page_size ) {
			$next_page = $page + 1;
			$params['page'] = $next_page;
			$next = '/searc/results/' . $this->assoc_to_uri($params);
		}


		$nav['main'] = 'Home';
		$nav['sub'] = '';
		$data = array(
			'results' => $results,
			'page' => $page,
			'page_size' => $page_size,
			'query_string' => $query,
			'prev' => $prev,
			'next' => $next,
			'nav'  => $nav
			);		
				
		$this->load->view('search/results', $data);
				
	}
	
	
	/** 
	 * Search by tags
	 */
	 function tags($tag) {
		 $results = $this->tag_model->search( 'articles', $tag);
		 
	 }
	
	/**
	 * Search
	 *
	 * @return void
	 **/
	function old_results()
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
