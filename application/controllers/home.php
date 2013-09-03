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
		parent::__construct();
		$this->load->model('articles_model');
		$this->load->model('event_model');
		$this->load->model('groups_model');
		$this->load->model('lists_model');
		$this->load->model('pages_model');
		//$this->load->model('polls_model');
		$this->load->model('tweets_model');
		$this->load->model('groups_list_positions_model');
		$this->load->model('list_positions_model');

		$this->load->helper('cal_helper');

    //$this->output->enable_profiler( TRUE );
    
	}
	
	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{
		/*
		$this->output->enable_profiler(TRUE);
		
		$sections = array(
			'benchmark' => TRUE,
			'controller_info' => TRUE,
		    'config'  => TRUE,
		    'queries' => TRUE
		    );
				
		$this->output->set_profiler_sections($sections);
		*/
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
	
	/** 
	General page builder - all sections get generated here
	@param Section number (0 is the default i.e. home page)
	@param Page # in the section (was used in old generator, maybe not necessary here)
	*/
	private function build_page( $section, $page = 1 )
	{
		$page_size 	= 10; 
		$parents 	= $this->groups_model->get_parents( $section );
		$events 	= NULL;
		$nav		= array();
		$data 		= array();

		$this->benchmark->mark('code_start');
		
		$tweets = $this->tweets_model->load('bookshelfnews');

		$nav = $this->pages_model->get_pages_tree();
		
		// Sidebar events for homepage
		if ($section == 0) {
			$movies = $this->event_model->get_upcoming_films( 2 );
			$events = $this->event_model->get_upcoming_events( 4, date('Y-m-d', strtotime('+2 weeks')) );
			

			

			$lists['serendipity'] = $this->lists_model->get_list_items_by_name( 'serendipity' );
			shuffle($lists['serendipity']);
			
			$articles = $this->articles_model->get_published_articles( $section, 5,  1 );
			if ($articles) foreach( $articles as &$it) {
				$it->media = $this->media_model->get_media_for_path("/articles/$it->id", 'general', 1);
			}			
			$lists['_section'] = $articles;

			$nav['main'] = 'Home';
			$nav['sub'] = '';
			
			$data['tweets'] = $tweets;	
			$data['movies'] = $movies;	
			$data['events'] = $events;
			$data['nav'] 	= $nav;
			$layout = 'main';
		} else {
			
			$res = $this->articles_model->get_published_articles( $section, 5,  1 );

			foreach( $res->result() as $row ) {
				$row->media = $this->media_model->get_media_for_path("/articles/$row->id", 'general', 1);
				$data[] = $row;
			}
			$lists['_section'] = $data;
			
			$nav['main'] = 'section';
			$nav['sub'] = isset($data[0]->group) ? $data[0]->group : false;
			
			$lists['serendipity'] = $this->lists_model->get_list_items_by_name( 'serendipity' );
			$layout = 'section';
		}
		
		$group_lists = $this->groups_list_positions_model->get_group_lists($section)->result();
		foreach ($group_lists as $g) {
			if ($g->lists_id > 0) {
				$lists['position_' . strtolower($g->name)] = $this->lists_model->get_list_items_by_name( $this->lists_model->get_list($g->lists_id)->name );
			}
		}

		$data['nav'] = $nav[0]->children;
		$data['lists'] = $lists;
		$data['tweets'] = $tweets;

		$this->load->view('layouts/'.$layout, $data);
		return;
		die;
	    // Poll - DISABLED for the moment
	    // $poll = $this->polls_model->get_current_poll();

	    // list driven stuffs

    $list_meta = array('Features','Serendipity','v3','v4','v5','v6','v7','v8');
    $lists = array();
    foreach( $list_meta as $list_name ) {
      $lists[$list_name] = $this->lists_model->get_list_items_by_name( $list_name );
    }
    
    $list_positions = $this->list_positions_model->get_list_positions()->result();

    if( $section > 0 ) {
      $data = array();
      $res = $this->articles_model->get_published_articles( $section, 10,  1 );
      foreach( $res->result() as $row ) {
        $row->media = $this->media_model->get_media_for_path("/articles/$row->id", 'general', 1);
        $data[] = $row;
      }
      $lists['_section'] = $data;
    }

    // calendar
    $cal = $this->load->view('widgets/calendar', 
      array('cal' => cal_gen( date('n'), date('Y')),
            'data' => array()), 
      true);

    // tweets
    $tweet_data = $this->tweets_model->load('bookshelfnews');

    $tweets = $this->load->view('widgets/tweets',
      array('tweets' => $this->tweets_model->load('bookshelfnews')),
      true );
      
		$parents = array_reverse($parents);
		array_shift($parents);
		$view_data = array(
			'parents' => $parents,
		      'lists' => $lists,
		      'events' => $events,
		      'poll' => $poll,
		      'cal' => $cal,
		      'tweets' => $tweets,
		      'list_positions' => $list_positions
			);

    $pg_data = $this->get_page_data('Bookshelf', 'home', $section );
	$pg_data['section'] = $section;
	//new dBug($lists);die;
    if( $section == 0 ) {
      $pg_data['content'] = $this->load->view('home/home_page', $view_data, true);
    } else {
      $pg_data['content'] = $this->load->view('home/section', $view_data, true);
    }

		$this->load->view('layouts/standard_page', $pg_data );		
	}
	
	private function build_page_old( $section, $page = 1 )
	{
		$page_size = 10;
		
		$parents = $this->groups_model->get_parents( $section );
				
		$events = NULL;

		// row in sidebar of page only on home
		//if( $section == 0 ) {
			$events = $this->event_model->get_next_events( 4 );
		//}
		
    // current poll
    $poll = $this->polls_model->get_current_poll();

    // list driven stuffs

    $list_meta = array('Features','Serendipity','v3','v4','v5','v6','v7','v8');
    $lists = array();
    foreach( $list_meta as $list_name ) {
      $lists[$list_name] = $this->lists_model->get_list_items_by_name( $list_name );
    }

/*    
    $lists = $this->groups_list_positions_model->get_group_named_lists( $section );
    foreach ($lists as &$list) {
    	$list = $this->lists_model->get_list($list->lists_id);
    	if (isset($list->name)) {
	    	$list->data = $this->lists_model->get_list_items_by_name($list->name);	
    	}
	    else 
	    {
		    $list->data = false;
	    }
    }
*/
    $list_positions = $this->list_positions_model->get_list_positions()->result();

    if( $section > 0 ) {
      $data = array();
      $res = $this->articles_model->get_published_articles( $section, 10,  1 );
      foreach( $res->result() as $row ) {
        $row->media = $this->media_model->get_media_for_path("/articles/$row->id", 'general', 1);
        $data[] = $row;
      }
      $lists['_section'] = $data;
    }

    // calendar
    $cal = $this->load->view('widgets/calendar', 
      array('cal' => cal_gen( date('n'), date('Y')),
            'data' => array()), 
      true);

    // tweets
    $tweet_data = $this->tweets_model->load('bookshelfnews');

    $tweets = $this->load->view('widgets/tweets',
      array('tweets' => $this->tweets_model->load('bookshelfnews')),
      true );
      
		$parents = array_reverse($parents);
		array_shift($parents);
		$view_data = array(
			'parents' => $parents,
		      'lists' => $lists,
		      'events' => $events,
		      'poll' => $poll,
		      'cal' => $cal,
		      'tweets' => $tweets,
		      'list_positions' => $list_positions
			);

    $pg_data = $this->get_page_data('Bookshelf', 'home', $section );
	$pg_data['section'] = $section;
	//new dBug($lists);die;
    if( $section == 0 ) {
      $pg_data['content'] = $this->load->view('home/home_page', $view_data, true);
    } else {
      $pg_data['content'] = $this->load->view('home/section', $view_data, true);
    }

		$this->load->view('layouts/standard_page', $pg_data );		
	}
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
