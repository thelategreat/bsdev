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
		// uri = /home/section/###/page##
    $section = (int)$this->uri->segment(3);
		$page = (int)$this->uri->segment(4);
		if( $page <= 0 ) {
			$page = 1;
		}
		$this->build_page($section, $page);
	}
	
	private function build_page( $section, $page = 1 )
	{
		$page_size = 10;
		
		$parents = $this->groups_model->get_parents( $section );
				
		$events = NULL;
		$articles = array();

		$res = $this->articles_model->get_published_article_list($section, $page_size, $page );
		if( $res->num_rows() > 0 ) {
			foreach( $res->result() as $row ) {
				$row->media = $this->media_model->get_media_for_path("/articles/$row->id", 'general', 1);
				$articles[] = $row;				
			}
		} else {
			$tree = $this->groups_model->get_tree( 'id', $section, false );
			foreach( $tree as $tree_item ) {
				$res = $this->articles_model->get_published_article_list($tree_item->id, 1 );
				if( $res->num_rows() > 0 ) {
					$row = $res->row();
					$row->media = $this->media_model->get_media_for_path("/articles/$row->id", 'general', 1);
					$articles[] = $row;				
				}			
			}			
		}

		// row across top of page only on home
		if( $section == 0) {
			$events = $this->event_model->get_next_events( 7 );
		}
		
		$pagination = '<table style="width: 100%;"><tr>';
		if( $page > 1 ) {
			$prev_page = $page - 1;
			$pagination .= "<td><a href='/home/section/$section/$prev_page' title='newer stuff...'><img src='/img/big_feature_left_arrow.png'/></a></td>";
		} else {
			$pagination .= '<td/>';
		}
		if( count($articles) == $page_size ) {
			$next_page = $page + 1;
			$pagination .= "<td align='right'><a href='/home/section/$section/$next_page' title='...older stuff'><img src='/img/big_feature_right_arrow.png'/></a></td>";			
		} else {
			$pagination .= '<td/>';			
		}
		$pagination .= '</tr></table>';
		
				
		$parents = array_reverse($parents);
		array_shift($parents);
		$view_data = array(
			'parents' => $parents,
			'articles' => $articles,
			'events' => $events,
			'pagination' => $pagination
			);
		
		$pg_data = $this->get_page_data('Bookshelf', 'home', $section );
		$pg_data['content'] = $this->load->view('home/home_page', $view_data, true);
		$pg_data['section'] = $this->uri->segment(3);
		$this->load->view('layouts/standard_page', $pg_data );		
	}
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */