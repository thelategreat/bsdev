<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Article extends MY_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		$this->load->model('articles_model');
		$this->load->model('comments_model');
		$this->load->model('lists_model');
		$this->load->model('event_model');
		$this->load->model('groups_model');
		$this->load->model('tweets_model');
		$this->load->helper('cal_helper');
	}
	
	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{
		$this->view();
	}
	
	
	function view($id=false)
	{
		$this->load->model('products_model');
		$this->load->model('media_model');
		$data = array();
		$layout = 'article';

		if( !$id ) {
			redirect("/");
	    }
		
		// comment stuff
		// -------------
		// system or per article level
		$allow_comments = $this->config->item('allow_comments');
		// user authorized to comment
		$can_comment = $this->auth->logged_in();
		// empty
		$comments = array();
		
		$res = $this->articles_model->get_article($id);
		if ($res->num_rows() < 1) {
			$this->load->view('errors/page_not_found');
			return;
		}
		foreach( $res->result() as $row ) {
			preg_match_all('/{{(\d{13}).*}}/', $row->body, $matches, false);

			foreach ($matches[1] as $key=>$match) {				
				$items[$key] = $this->products_model->get_product_by_ean($match);				
			}
			foreach ($matches[0] as $key=>$match) {
				if ($items[$key]->row()) {
					$row->body = str_replace($match, $this->render_product_detail($items[$key]), $row->body);	
				} else {
					$row->body = str_replace($match, '', $row->body);	
				}
			}
			
			$row->media = $this->media_model->get_media_for_path("/articles/$row->id", 'general', 1);
			$data[] = $row;
		}
		$lists['_section'] = $data;

		$data['lists'] = $lists;

    	$nav['main'] = 'Home';
		$nav['sub'] = '';

		$view_data = array(
			'nav' => $nav,
			'lists' => $lists
		);
			
			
		/* Associated products and events */
	   	$associated_products = $this->articles_model->get_products( $id );
    	$associated_events 	 = $this->articles_model->get_events( $id );
    	
		$pg_data = $this->get_page_data('Bookshelf', 'article_view' );		
		
		$view_data['associated_products'] = $associated_products;
    	$view_data['associated_events'] = $associated_events;
    	$pg_data['content'] = $this->load->view('layouts/article', $view_data, true);			
		$pg_data['lists'] = $lists;

		$this->load->view('layouts/standard_page', $pg_data );		

		/*
		
		if( $res->num_rows() ) {
			$res = $res->row();

			preg_match_all('/{{(\d{13}).*}}/', $res->body, $matches, false);
			
			foreach ($matches[1] as $key=>$match) {				
				$items[$key] = $this->products_model->get_product_by_ean($match);				
			}
			foreach ($matches[0] as $key=>$match) {
				if ($items[$key]->row()) {
					$res->body = str_replace($match, $this->render_product_detail($items[$key]), $res->body);	
				} else {
					$res->body = str_replace($match, '', $res->body);	
				}
				
			}
			
			if( $allow_comments ) {
				$comments = $this->comments_model->get_comments('articles', $id )->result();
			}
		} else {
			$res = NULL;
		}


    // lists    
    $list_meta = array('Serendipity');
    $lists = array();
    foreach( $list_meta as $list_name ) {
      $lists[$list_name] = $this->lists_model->get_list_items_by_name( $list_name );
    }
    // events
    $events = $this->event_model->get_next_events( 4 );
     // calendar
    $cal = $this->load->view('widgets/calendar', 
      array('cal' => cal_gen( date('n'), date('Y')),
            'data' => array()), 
      true);

    // tweets
    $tweets = $this->load->view('widgets/tweets',
      array('tweets' => $this->tweets_model->load('bookshelfnews')),
      true );

    $view_data = array(
			'article' => $res,
			'images' => $images,
			'comments' => $comments,
			'can_comment' => $can_comment,
      'allow_comments' => $allow_comments,
      'lists' => $lists,
      'events' => $events,
      'cal' => $cal,
      'tweets' => $tweets
			);
		
		$pg_data = $this->get_page_data('Bookshelf - Home', 'home' );
		$pg_data['content'] = $this->load->view('page/article', $view_data, true);
		
		$this->load->view('layouts/article', $pg_data);
		return;
		$this->load->view('layouts/standard_page', $pg_data );		
				*/
	}
	
	function render_product_detail($product) {
		$product = $product->row();
		$str = "<div class='product-detail tooltip' title='Linked Product Detail'><div class='title'>{$product->title}</div><div class='author'>{$product->contributor}</div></div>";
		return $str;
	}
}

?>
