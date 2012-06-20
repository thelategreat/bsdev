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
	
	
	function view()
	{
		$id = $this->uri->segment(3);
		if( !$id ) {
      redirect("/");
    }
		$this->load->model('media_model');
		$res = $this->media_model->get_media_for_path("/articles/$id", 'general');		
		$images = array();

		foreach( $res as $row ) {
			$images[] = $row;
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
		
		if( $res->num_rows() ) {
			$res = $res->row();
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
		$this->load->view('layouts/standard_page', $pg_data );		
	}
}

?>
