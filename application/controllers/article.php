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
		$this->load->model('tag_model');
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
		$this->load->model('venues_model');
		$this->load->model('media_model');
		$this->load->model('events_times_model');
		$this->db->db_select();
		$data = array();
		$layout = 'article';
		$nav = $this->groups_model->get_group_tree();

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
		
		$item = $this->articles_model->get_article($id);

		if (!$item) {
			$this->load->view('errors/page_not_found', array('nav'=>$nav[0]->children));
			return;
		}

		preg_match_all('/{{(\d{13}).*}}/', $item->body, $matches, false);

		foreach ($matches[1] as $key=>$match) {				
			$items[$key] = $this->products_model->get_product_by_ean($match);				
		}
		foreach ($matches[0] as $key=>$match) {
			if ($items[$key]->row()) {
				$row->body = str_replace($match, $this->render_product_detail($items[$key]), $item->body);	
			} else {
				$row->body = str_replace($match, '', $item->body);	
			}
		}
		
		$item->media = $this->media_model->get_media_for_path("/articles/$item->id", 'general', 1);
    	$item->tags = $this->tag_model->get_tags( 'articles', $id );

		$lists['_section'] = $item;

		$data['lists'] = $lists;


		$view_data = array(
			'nav' => $nav,
			'lists' => $lists,
			'item' => $item,
			'nav' => $nav[0]->children
		);
			
			
		/* Associated products and events */
    	$associated_events 	= $this->articles_model->get_events( $id );
    	$item->associated_events = false;
    	if ($associated_events) {
	    	$item->associated_events = array();
    		foreach ($associated_events as $it) {
    			$ev = new stdClass();
    			$ev = $it;
    			$ev->venue = $this->venues_model->get_venue( $it->venues_id );
    			$ev->times = $this->events_times_model->get_event_times( $it->id );


    			$item->associated_events[] = $ev;
    		}
    	}

    	$associated_films		= $this->articles_model->get_films( $id );	
    	$item->associated_films = false;
    	if ($associated_films) {
	    	$item->associated_films = array();
    		foreach ($associated_films as $it) {
    			$ev = new stdClass();
    			$ev = $it;
    			$ev->times = $this->events_times_model->get_film_times( $it->id );

    			$item->associated_films[] = $ev;
    		}
    	}

	   	$item->associated_products 	= $this->articles_model->get_products( $id );
    	
		$pg_data = $this->get_page_data('Bookshelf', 'article_view', false, array('books','bookstore') );		
		

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
