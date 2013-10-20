<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Section extends MY_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		$this->load->model('groups_model');
		$this->load->model('lists_model');
		$this->load->model('list_positions_model');
		$this->load->model('groups_list_positions_model');		
		$this->load->model('articles_model');
	}

  function index()
  {
    $this->view();
  }

	function view( $id = 0 )
	{
    	$nav		= array();
		$data 		= array();

    	$id = (int)$id;
		$page = $this->groups_model->get_group_by_id( $id );

		$nav = $this->groups_model->get_group_tree( $page->parent_id );	
		$nav = $nav[0]->children;

		$data = array('title' => 'Page Not Found', 'body' => '');
		if( $page ) {
			$data['title'] = $page->name;
			$data['name'] = $page->name;
			//$data['body'] = $page->body;
			$data['nav'] = $nav;
			$data['page'] = $page;
		}
		
		/* The available positions (top, left, etc) */
		$data['list_positions'] = $this->list_positions_model->get_list_positions()->result();
		
		/* The lists assigned to this group with their positions */
		$data['lists'] = $this->groups_list_positions_model->get_group_named_lists($id);

		$articles = $this->articles_model->get_published_articles( $id, 10 );
		$data['articles'] = $articles;	
	
		//$pg_data = $this->get_page_data('Bookshelf - ' . $data['title'], 'home' );
		$pg_data = $this->get_page_data('Bookshelf', 'section', false, array('books','bookstore') );		
  		$pg_data['content'] = $this->load->view('page/section', $data, true);


		$this->load->view('layouts/standard_page', $pg_data );
	}

}
