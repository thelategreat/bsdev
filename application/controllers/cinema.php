<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   


class Cinema extends MY_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		
		$this->config->load('site_config');
		
		$this->load->helper('cal');
		
		$this->load->model('maillist_model');
		$this->load->model('event_model');
		$this->load->model('lists_model');
		$this->load->model('tweets_model');
	}


	function index()
	{
		redirect(base_url('cinema/view/month'));	
	}


	/* JSON call to retrieve data about a particular event */
	function details_json() {
		$event_id = $this->input->post('eventId');

		$details = $this->event_model->get_event($event_id)->result();
		if ($details) $details = $details[0];

		echo json_encode($details);
	}

	/* Choose the view to display from a restricted list */
	function view( $period = false )
	{
		$period = strtolower(trim($period));
		if (in_array( $period, array('week', 'month', 'list'))) {
			$this->do_view( $period );
		} else {
			$this->blog();
		}
	}
	
	function do_view( $which )
	{
		$this->load->helper('cal');

		// Get the tweets for the @bookshelfnews account
	    $tweets = $this->load->view('widgets/tweets',
	      array('tweets' => $this->tweets_model->load('bookshelfnews')),
	      true );
		// Navigation links
		$nav = $this->groups_model->get_group_tree();
		// Upcoming events
		$events = $this->event_model->get_upcoming_events( 4, date('Y-m-d', strtotime('+2 weeks')) );
      
		$today 	= getdate(time());
		$month 	= (int)$this->uri->segment(4) ? (int)$this->uri->segment(4) : $today['mon'];
		$year 	= (int)$this->uri->segment(5) ? (int)$this->uri->segment(5) : $today['year'];
		$weeknum = (int)$this->uri->segment(6) ? (int)$this->uri->segment(6) : 0; 
		$month_name = date("F", mktime(0, 0, 0, $month, 10));

		// Adjust date to account for out of bounds values 
		$atd 	= cal_adjust_date( $month, $year );
		$month 	= $atd[0];
		$year 	= $atd[1];
		
		// Get the appropriate calendar array 
		if ( $which == 'week' ) {
			$cal = week_cal_gen( $today['mday'], $month, $year );
		} else {
			// gen the calendar grid		
			$cal = cal_gen( $month, $year );
		}

		$upcoming_films = $this->event_model->get_month_films($month);
		$films_this_month = $this->event_model->get_month_films_by_name($month);

		// Calendar array is associative with keys in YYYY-MM-DD format
		$cal_events = array();
		foreach ($upcoming_films as $it) {
			$date = explode(' ', $it->start_time);
			$date = $date[0];

			if (!isset($cal_events[$date])) $cal_events[$date] = array();
			$cal_events[$date][] = $it;
		}

		$next_year 	= (int)$year;
		$prev_year 	= (int)$year;
		$next_month = (((int)$month)+1);
		$prev_month = (((int)$month)-1);
		
		if( $next_month > 12 ) {
			$next_month = 1;
			$next_year++;
		}
		if( $prev_month < 1 ) {
			$prev_month = 12;
			$prev_year--;
		}
		$next_month_url = "/calendar/view/$which/$next_month/$next_year";
		$prev_month_url = "/calendar/view/$which/$prev_month/$prev_year";


		$lists = array();
    	$nav['main'] = 'Home';
		$nav['sub'] = '';


		$view_data = array(
			'month' 	=> $month,
			'month_name'=> $month_name,
			'year' 		=> $year,
		    'tweets' 	=> $tweets,
		    'lists' 	=> $lists,
		    'events'	=> $events,
		    'cal_events'=> $cal_events,
		    'films_this_month' => $films_this_month,
			'next_month_url' => $next_month_url,
			'prev_month_url' => $prev_month_url,
			'nav' => $nav[0]->children
		);

		$pg_data = $this->get_page_data('Bookshelf - calendar', 'calendar', null, array('cinema', 'films') );		
		
		if( $which == 'list') {
			$pg_data['content'] = $this->load->view('calendar/list_view', $view_data, true);
		} else if( $which == 'month' ) {
			$pg_data['content'] = $this->load->view('calendar/month_view', $view_data, true);
		} else if( $which == 'week' ) {
			$pg_data['content'] = $this->load->view('calendar/week_view', $view_data, true);
		} else {
			$pg_data['content'] = $this->load->view('calendar/poster_view', $view_data, true);			
		}
		$this->load->view('layouts/standard_page', $pg_data );				
	}	

	/**
		The main view that shows reviews and other general cinema stuff 
	*/
	function blog() {
		$this->load->model('articles_model');
		$this->load->model('films_model');

	    $tweets = $this->load->view('widgets/tweets',
	      array('tweets' => $this->tweets_model->load('bookshelfnews')),
	      true );
		// Navigation links
		$nav = $this->groups_model->get_group_tree();
		// Upcoming events
		$events = $this->event_model->get_upcoming_events( 4, date('Y-m-d', strtotime('+2 weeks')) );

		$today 	= getdate(time());
		$month 	= $today['mon'];
		$year 	= $today['year'];
		$month_name = date("F", mktime(0, 0, 0, $month, 10));

		$films_this_month = $this->event_model->get_month_films_by_name($month);

		$articles = $this->articles_model->get_articles_by_group(2,3);

		foreach ($articles as &$it) {
			$it->films = $this->articles_model->get_films( $it->id );	

			if ($it->films) foreach ($it->films as &$film) {
				$this->films_model->get_upcoming_showtimes( $film->id );
			}
		}

		$view_data = array(
			'articles'	=> $articles,
		    'tweets' 	=> $tweets,
		    'events'	=> $events,
		    'films_this_month' => $films_this_month,
			'nav' => $nav[0]->children
		);

		$pg_data = $this->get_page_data('Bookshelf - cinema', 'cinema', null, array('cinema', 'films'));
		
		$pg_data['content'] = $this->load->view('calendar/blog', $view_data, true);			
	
		$this->load->view('layouts/standard_page', $pg_data );				
	}
}
